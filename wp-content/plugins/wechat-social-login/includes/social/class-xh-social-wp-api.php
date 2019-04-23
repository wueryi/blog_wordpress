<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * wordpress apis
 * 
 * @author rain
 * @since 1.0.0
 */
class XH_Social_WP_Api{
    /**
     * The single instance of the class.
     *
     * @since 1.0.0
     * @var XH_Social_WP_Api
     */
    private static $_instance = null;
    /**
     * Main Social Instance.
     *
     * Ensures only one instance of Social is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return XH_Social - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    
    private function __construct(){}
    
    /**
     * 判断当前用户是否允许操作
     * @param array $roles
     * @since 1.0.0
     */
    public function capability($roles=array('administrator')){
        global $current_user;
        if(!is_user_logged_in()){
        }
         
        if(!$current_user->roles||!is_array($current_user->roles)){
            $current_user->roles=array();
        }
         
        foreach ($roles as $role){
            if(in_array($role, $current_user->roles)){
                return true;
            }
        }
        return false;
    }
    
    public function get_log_on_backurl($atts = array(),$_get=true,$get_session=true,$set_session = false,$default = null){
        $log_on_callback_uri=$atts&&is_array($atts)&&isset($atts['redirect_to'])?esc_url_raw($atts['redirect_to']):null;
        if(empty($log_on_callback_uri)){
            if(!is_null($_get)&&$_get===true){
                $_get=$_GET;
            }
            if($_get&&is_array($_get)&&isset($_get['redirect_to'])){
                $log_on_callback_uri =esc_url_raw(urldecode($_get['redirect_to']));
            }
        }
        
        if($get_session&&empty($log_on_callback_uri)){
            $log_on_callback_uri = XH_Social::instance()->session->get('social_login_location_uri');
        }
        
        if(empty($default)){
            $default = home_url('/');
        }
        
        if(empty($log_on_callback_uri)){
            $log_on_callback_uri=$default;
        }
        
        if(strcasecmp(XH_Social_Helper_Uri::get_location_uri(), $log_on_callback_uri)===0){
            $log_on_callback_uri = $default;
        }
        
        $log_on_callback_uri =  apply_filters('wsocial_log_on_backurl', $log_on_callback_uri);
        if($set_session){
            XH_Social::instance()->session->set('social_login_location_uri',$log_on_callback_uri);
        }
        
        return $log_on_callback_uri;
    }
    
    /**
     * 根据昵称，创建user_login
     * @param string $nickname
     * @return string
     * @since 1.0.1
     */
    public function generate_user_login($nickname){
        $_nickname = $nickname;
        
        $nickname =  apply_filters('wsocial_user_login_pre', null,$_nickname);
        if(!empty($nickname)){
            return $nickname;
        }
        
        $nickname = sanitize_user(XH_Social_Helper_String::remove_emoji($nickname));
        if(empty($nickname)){
            $nickname = mb_substr(str_shuffle("abcdefghigklmnopqrstuvwxyz123456") ,0,4,'utf-8');
        }
        
        $nickname =  apply_filters('wsocial_user_login_sanitize', $nickname,$_nickname);
        if(mb_strlen($nickname)>32){
            $nickname = mb_substr($nickname, 0,32,'utf-8');
        }
        
        $pre_nickname =$nickname;
    
        $index=0;
        while (username_exists($nickname)){
            $index++;
            if($index==1){
                $nickname=$pre_nickname.'_'.time();//年+一年中的第N天
                continue;
            }
            
            //加随机数
            $nickname.=mt_rand(1000, 9999);
            if(strlen($nickname)>60){
                $nickname = $pre_nickname.'_'.time();
            }
            
            //尝试次数过多
            if($index>5){
                $nickname = XH_Social_Helper_String::guid();
                break;
            }
        }
    
        return apply_filters('wsocial_user_login', $nickname,$_nickname);  
    }

    public function get_plugin_settings_url(){
        return admin_url('admin.php?page=social_page_add_ons');
    }
    
    /**
     * @since 1.0.9
     * @param array $request
     * @param bool $validate_notice
     * @return bool
     */
    public function ajax_validate(array $request,$hash,$validate_notice = true){
        if(XH_Social_Helper::generate_hash($request, XH_Social::instance()->get_hash_key())!=$hash){
            return false;
        }
       
        return true;
    }
    
    /**
     * 设置错误
     * @param string $key
     * @param string $error
     * @since 1.0.5
     */
    public function set_wp_error($key,$error){
        XH_Social::instance()->session->set("error_{$key}", $error);
    }
    
    /**
     * 清除错误
     * @param string $key
     * @param string $error
     * @since 1.0.5
     */
    public function unset_wp_error($key){
        XH_Social::instance()->session->__unset("error_{$key}");
    }
    
    /**
     * 获取错误
     * @param string $key
     * @param string $error
     * @since 1.0.5
     */
    public function get_wp_error($key,$clear=true){
        $cache_key ="error_{$key}";
        $session =XH_Social::instance()->session;
        $error = $session->get($cache_key);
        if($clear){
            $this->unset_wp_error($key);
        }
        return $error;
    }
    
    /**
     * @since 1.0.7
     * @param string $log_on_callback_uri
     * @return string
     */
    public function wp_loggout_html($log_on_callback_uri=null,$include_css=false,$include_header_footer=false,$include_html=false){
        XH_Social_Temp_Helper::set('atts', array(
            'log_on_callback_uri'=>$log_on_callback_uri,
            'include_css'=>$include_css,
            'include_header_footer'=>$include_header_footer,
            'include_html'=>$include_html
        ),'templete');
        
        ob_start();
        require XH_Social::instance()->WP->get_template(XH_SOCIAL_DIR, 'account/logout-content.php');
     
        return ob_get_clean();
    }
    
    /**
     * wp die
     * @param Exception|XH_Social_Error|WP_Error|string|object $err
     * @since 1.0.0
     */
    public function wp_die($err=null,$include_header_footer=true,$exit=true){
        XH_Social_Temp_Helper::set('atts', array(
            'err'=>$err,
            'include_header_footer'=>$include_header_footer
        ),'templete');
        
        ob_start();
        require XH_Social::instance()->WP->get_template(XH_SOCIAL_DIR, 'wp-die.php');
        echo ob_get_clean();
        if($exit){
        exit;
        }
    }
    
    /**
     * 返回登录/注册/找回密码/完善资料等页面
     * 特点：1.不需要登录检查
     *      2.登录成功后的跳转不能回到这些页面
     * 
     * @since 1.2.4
     * @return int[]
     */
    public function get_unsafety_pages(){
        return apply_filters('wsocial_unsafety_pages', array());
    }
    
    public function get_safety_authorize_redirect_page(){
        //在template_redirect之前调用的当前方法
        if(!function_exists('get_the_ID')){
            return home_url('/');
        }
        
        //当前不是页面
        $current_post_id = get_the_ID();
        if(!$current_post_id){
            return home_url('/');
        }
        
        $unsafety_pages = $this->get_unsafety_pages();
        if(in_array($current_post_id, $unsafety_pages)){
            return home_url('/');
        }
        
        return XH_Social_Helper_Uri::get_location_uri();
    }
    
    /**
     * 执行登录操作
     * @param WP_User $wp_user
     * @return XH_Social_Error
     * @since 1.0.0
     */
    public function do_wp_login($wp_user,$remember=true){
        XH_Social::instance()->session->__unset('social_login_location_uri');
        
        $user = apply_filters( 'authenticate', $wp_user, $wp_user->user_login, null );
        if(is_wp_error($user)){
            return XH_Social_Error::wp_error($user);    
        }
        
        $secure_cookie='';
        if ( get_user_option('use_ssl', $wp_user->ID) ) {
            $secure_cookie = true;
            force_ssl_admin(true);
        }
    
        wp_set_auth_cookie($wp_user->ID, $remember, $secure_cookie);
        /**
         * Fires after the user has successfully logged in.
         *
         * @since 1.5.0
         *
         * @param string  $user_login Username.
         * @param WP_User $user       WP_User object of the logged-in user.
         */
        do_action( 'wp_login', $wp_user->user_login, $wp_user );
        
        return XH_Social_Error::success();
    }
    
    public function clear_captcha(){
        XH_Social::instance()->session->__unset('social_captcha');
    }

    const FIELD_CAPTCHA_NAME ='captcha';
    /**
     * 获取图片验证字段
     * @return array
     * @since 1.0.0
     */
    public function get_captcha_fields($social_key='social_captcha'){
        $fields[self::FIELD_CAPTCHA_NAME]=array(
            'social_key'=>$social_key,
            'type'=>function($form_id,$data_name,$settings){
                   $html_name = $data_name;
                   $html_id =isset($settings['id'])?$settings['id']:  ($form_id."_".$data_name);
                
                    ob_start();
                    ?>
                   <div class="xh-input-group" style="width:100%;">
                        <input name="<?php echo esc_attr($html_name);?>" type="text" id="<?php echo esc_attr($html_id);?>" maxlength="6" class="form-control" placeholder="<?php echo __('image captcha',XH_SOCIAL)?>">
                        <span class="xh-input-group-btn" style="width:96px;"><img style="width:96px;height:35px;border:1px solid #ddd;background:url('<?php echo XH_SOCIAL_URL?>/assets/image/loading.gif') no-repeat center;" id="img-captcha-<?php echo esc_attr($html_id);?>"/></span>
                    </div>
                    
                    <script type="text/javascript">
            			(function($){
            				if(!$){return;}

                            window.captcha_<?php echo esc_attr($html_id);?>_load=function(){
                            	$('#img-captcha-<?php echo esc_attr($html_id);?>').attr('src','<?php echo XH_SOCIAL_URL?>/assets/image/empty.png');
                            	$.ajax({
        				            url: '<?php echo XH_Social::instance()->ajax_url(array(
        				                'action'=>'xh_social_captcha',
        				                'social_key'=>$settings['social_key']
        				            ),true,true)?>',
        				            type: 'post',
        				            timeout: 60 * 1000,
        				            async: true,
        				            cache: false,
        				            data: {},
        				            dataType: 'json',
        				            success: function(m) {
        				            	if(m.errcode==0){
        				            		$('#img-captcha-<?php echo esc_attr($html_id);?>').attr('src',m.data);
        								}
        				            }
        				         });
                            };
                            
            				$('#img-captcha-<?php echo esc_attr($html_id);?>').click(function(){
            					window.captcha_<?php echo esc_attr($html_id);?>_load();
            				});
            				
            				window.captcha_<?php echo esc_attr($html_id);?>_load();
            			})(jQuery);
                    </script>
                <?php 
                XH_Social_Helper_Html_Form::generate_field_scripts($form_id, $html_name,$html_id);
                return ob_get_clean();
            },
            'validate'=>function($name,$datas,$settings){
                //插件未启用，那么不验证图形验证码     
                $code_post =isset($_REQUEST[$name])?trim($_REQUEST[$name]):'';
                if(empty($code_post)){
                    return XH_Social_Error::error_custom(__('image captcha is required!',XH_SOCIAL));
                }
                
                $captcha =XH_Social::instance()->session->get($settings['social_key']);
                if(empty($captcha)){
                    return XH_Social_Error::error_custom(__('Please refresh the image captcha!',XH_SOCIAL));
                }
                
                if(strcasecmp($captcha, $code_post)!==0){
                    return XH_Social_Error::error_custom(__('image captcha is invalid!',XH_SOCIAL));
                }
                
                XH_Social::instance()->session->__unset($settings['social_key']);
               
                return $datas;
            }
        );
    
        return apply_filters('xh_social_captcha_fields', $fields);
    }

    /**
     * 获取插件列表
     * @return NULL|Abstract_XH_Social_Add_Ons[]
     */
    public function get_plugin_list_from_system(){
        $base_dirs = XH_Social::instance()->plugins_dir;
        
        $plugins = array();
        $include_files = array();
        
        foreach ($base_dirs as $base_dir){
            try {
                if(!is_dir($base_dir)){
                    continue;
                }
        
                $handle = opendir($base_dir);
                if(!$handle){
                    continue;
                }
                
                try {
                    while(($file = readdir($handle)) !== false){
                        if(empty($file)||$file=='.'||$file=='..'||$file=='index.php'){
                            continue;
                        }
        
                        if(in_array($file, $include_files)){
                            continue;
                        }
                        //排除多个插件目录相同插件重复includ的错误
                        $include_files[]=$file;
                        
                        try {
                            if(strpos($file, '.')!==false){
                                if(stripos($file, '.php')===strlen($file)-4){
                                    $file=str_replace("\\", "/",$base_dir.$file);
                                }
                            }else{
                                $file=str_replace("\\", "/",$base_dir.$file."/init.php");
                            }
        
                            
                            if(file_exists($file)){
                                $add_on=null;
                                if(isset(XH_Social::instance()->plugins[$file])){
                                    //已安装
                                    $add_on=XH_Social::instance()->plugins[$file];
                                }else{
                                    //未安装
                                    $add_on = require_once $file;
                                   
                                    if($add_on&&$add_on instanceof Abstract_XH_Social_Add_Ons){
                                        $add_on->is_active=false;
                                        XH_Social::instance()->plugins[$file]=$add_on;
                                    }else{
                        	            $add_on=null;
                        	        }
                                } 
                               
                                if($add_on){
                                    $plugins[$file]=$add_on;
                                }
                            }
        
                        } catch (Exception $e) {
                        }
                    }
                } catch (Exception $e) {
                }
        
                closedir($handle);
            } catch (Exception $e) {
                
            }
        }
  
        $results = array();
        $plugin_ids=array();
        foreach ($plugins as $file=>$plugin){
            if(in_array($plugin->id, $plugin_ids)){
                continue;
            }
            
            $results[$file]=$plugin;
        }
        
        return $results;
    }

    /**
     *
     * @param string $page_templete_dir
     * @param string $page_templete
     * @return string
     * @since 1.0.8
     */
    public function get_template($page_templete_dir,$page_templete){
        if(strpos($page_templete, 'social/')===0){
            $ltemplete=substr($page_templete, 7);
        }
        
        if(file_exists(STYLESHEETPATH.'/social/'.$page_templete)){
            return STYLESHEETPATH.'/social/'.$page_templete;
        }
        
        if(file_exists(STYLESHEETPATH.'/wechat-social-login/'.$page_templete)){
            return STYLESHEETPATH.'/wechat-social-login/'.$page_templete;
        }
    
        return apply_filters('wsocial_get_template', $page_templete_dir . '/templates/' . $page_templete,$page_templete_dir, $page_templete);
    }
    
    /**
     *
     * @param string $dir
     * @param string $templete_name
     * @param mixed $params
     * @return string
     */
    public function requires($dir, $templete_name, $params = null,$require=false)
    {
        if (! is_null($params)) {
            XH_Social_Temp_Helper::set('atts', $params, 'templates');
        }
        $dir =apply_filters('wsocial_require_dir', $dir,$templete_name);
        
        if($require){
            return require $this->get_template($dir, $templete_name);
        }else{
            ob_start();
            require $this->get_template($dir, $templete_name);
            return ob_get_clean();
        }
    }
}