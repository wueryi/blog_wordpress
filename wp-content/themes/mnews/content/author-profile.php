<?php
global $salong,$wp_query,$current_user;
$current_id       = $current_user->ID;//当前用户 ID
$current_name     = $current_user->display_name;
$current_url      = $current_user->user_url;
$current_email    = $current_user->user_email;
$description      = $current_user->description;
$salong_locality  = $current_user->salong_locality;
$salong_qq        = $current_user->salong_qq;
$salong_weibo     = $current_user->salong_weibo;
$salong_wechat    = $current_user->salong_wechat;
$salong_gender    = $current_user->salong_gender;
$salong_open      = $current_user->salong_open;
$salong_phone     = $current_user->salong_phone;
$salong_company   = $current_user->salong_company;
$salong_position  = $current_user->salong_position;
$salong_avatar    = $current_user->salong_avatar;
$salong_alipay    = $current_user->salong_alipay;
$salong_wechatpay = $current_user->salong_wechatpay;
$get_tab          = $_GET['tab'];//获取连接中 tab 后面的参数

//~ 个人资料
if( isset($_POST['update']) && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) {
    $message = __('没有发生变化','salong');	
    $update = sanitize_text_field($_POST['update']);
    if($update=='info'){
        $update_user_id = wp_update_user( array(
            'ID'             => $current_id, 
            'display_name'   => sanitize_text_field($_POST['display_name']),
            'user_url'       => esc_url($_POST['url']),
            'description'    => $_POST['description'],
            'salong_avatar'  => $_POST['salong_avatar'],
         ) );
        if ( !is_wp_error( $update_user_id ) ) $message = __('基本信息已更新','salong');	
    }
    if($update=='extend'){
        $update_user_id = wp_update_user( array(
            'ID' => $current_id, 
            'salong_locality'   => $_POST['salong_locality'],
            'salong_weibo'      => $_POST['salong_weibo'],
            'salong_qq'         => $_POST['salong_qq'],
            'salong_wechat'     => $_POST['salong_wechat'],
            'salong_gender'     => $_POST['salong_gender'],
            'salong_open'       => $_POST['salong_open'],
            'salong_alipay'     => $_POST['salong_alipay'],
            'salong_wechatpay'  => $_POST['salong_wechatpay'],
            'salong_phone'      => $_POST['salong_phone'],
            'salong_company'    => $_POST['salong_company'],
            'salong_position'   => $_POST['salong_position']
         ) );
        if ( !is_wp_error( $update_user_id ) ) $message = __('扩展信息已更新','salong');
    }	
    if($update=='pass'){
        $data = array();
        $data['ID']          = $current_id;
        $data['user_email']  = sanitize_text_field($_POST['email']);
        if( !empty($_POST['pass1']) && !empty($_POST['pass2']) && $_POST['pass1']===$_POST['pass2'] ) $data['user_pass'] = sanitize_text_field($_POST['pass1']);
        $current_id = wp_update_user( $data );
        if ( !is_wp_error( $current_id ) ) $message = __('账号修改已更新','salong');
    }
    $message .= ' <a href="">'.__('点击刷新','salong').'</a>';
}
//~ 个人资料end

?>
<section class="author_subtabs">
    <ul class="tabs">
        <li<?php if($get_tab=='edit-profile' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo add_query_arg('tab', 'edit-profile', get_author_posts_url($current_id)); ?>">
                <?php _e('基本信息','salong'); ?>
            </a>
        </li>
        <li<?php if( $get_tab=='edit-profile-extension' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo add_query_arg('tab', 'edit-profile-extension', get_author_posts_url($current_id)); ?>">
                <?php _e('扩展信息','salong'); ?>
            </a>
        </li>
        <li<?php if( $get_tab=='edit-profile-password' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo add_query_arg('tab', 'edit-profile-password', get_author_posts_url($current_id)); ?>">
                <?php _e('更改密码','salong'); ?>
            </a>
        </li>
    </ul>
    <section class="form_secton">
        <?php if($message) echo '<p class="hint">'.$message.'</p>'; ?>
        <?php if($get_tab == 'edit-profile'){ ?>
        <form id="info-form" class="author_form" role="form" method="POST" action="">
            <input type="hidden" name="update" value="info">
            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
            <h3>
                <?php _e('基本信息','salong');?>
            </h3>
            <p>
                <label for="display_name"><?php _e('昵称 (必填)','salong');?></label>
                <input type="text" id="display_name" name="display_name" value="<?php echo $current_name;?>" required>
            </p>
            <p>
                <label for="url"><?php _e('网站','salong');?></label>
                <input type="text" id="url" name="url" value="<?php echo $current_url;?>">
            </p>
            <p>
                <label for="description"><?php _e('个人说明','salong');?></label>
                <textarea rows="3" name="description" id="description"><?php echo $description;?></textarea>
            </p>
            <div class="salong_field_main">
                <label for="salong_avatar"><?php _e('头像','salong'); ?></label>
                <div class="salong_field_area avatar">
                    <div class="salong_file_button active">
                        <a href="#" class="salong_upload_button"><b>+</b><span><?php _e('更换头像','salong'); ?></span></a>
                        <div class="salong_file_preview"><?php echo salong_get_avatar($current_id,$current_name); ?></div>
                        <div class="bg"></div>
                        <input class="salong_field_upload" type="hidden" value="<?php if($salong_avatar){ echo $salong_avatar; } ?>" id="salong_avatar" name="salong_avatar" />
                    </div>
                    <div class="salong_file_hint">
                        <p>
                            <?php echo sprintf(__('当前为<strong>%s</strong>，建议大小：120*120。获取头像的顺序为：自定义头像、社交头像、默认头像','salong'),salong_avatar_name()); ?>
                        </p>
                    </div>
                </div>
            </div>
            <p>
                <input type="submit" value="保存更改" class="submit" />
            </p>
        </form>
        <?php }else if($get_tab == 'edit-profile-extension'){ ?>
        <form id="extend-form" class="author_form" role="form" method="post">
            <input type="hidden" name="update" value="extend">
            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
            <h3>
                <?php _e('扩展信息','salong');?>
            </h3>
            <p>
                <label><?php _e('公开显示','salong');?></label>
                <select name="salong_open">
                    <option value=""><?php _e('请选择是否公开显示扩展资料','salong');?></option>
                    <option value="on" <?php if($salong_open=='on') echo 'selected = "selected"'; ?>><?php _e('公开','salong');?></option>
                    <option value="off" <?php if($salong_open=='off') echo 'selected = "selected"'; ?>><?php _e('不公开','salong');?></option>
                </select>
            </p>
            <p>
                <label><?php _e('性别','salong');?></label>
                <select name="salong_gender">
                    <option value=""><?php _e('请选择','salong');?></option>
                    <option value="male" <?php if($salong_gender=='male') echo 'selected = "selected"'; ?>><?php _e('男','salong');?></option>
                    <option value="female" <?php if($salong_gender=='female') echo 'selected = "selected"'; ?>><?php _e('女','salong');?></option>
                </select>
            </p>
            <hr>
            <p>
                <label><?php _e('坐标','salong');?></label>
                <input type="text" id="salong_locality" name="salong_locality" readonly="readonly" value="<?php echo $salong_locality;?>">
                <span class="help-block"><?php _e('请选择所在地','salong');?></span>
            </p>
            <p>
                <label><?php _e('公司','salong');?></label>
                <input type="text" id="salong_company" name="salong_company" value="<?php echo $salong_company;?>">
                <span class="help-block"><?php _e('请填写公司名称','salong');?></span>
            </p>
            <p>
                <label><?php _e('职位','salong');?></label>
                <input type="text" id="salong_position" name="salong_position" value="<?php echo $salong_position;?>">
                <span class="help-block"><?php _e('请填写职位','salong');?></span>
            </p>
            <hr>
            <p>
                <label><?php _e('手机','salong');?></label>
                <input type="text" id="salong_phone" name="salong_phone" value="<?php echo $salong_phone;?>">
                <span class="help-block"><?php _e('请填写手机号码','salong');?></span>
            </p>
            <p>
                <label><?php _e('QQ','salong');?></label>
                <input type="text" id="salong_qq" name="salong_qq" value="<?php echo $salong_qq;?>">
                <span class="help-block"><?php _e('请输入QQ账号','salong');?></span>
            </p>
            <p>
                <label><?php _e('微信','salong');?></label>
                <input type="text" id="salong_wechat" name="salong_wechat" value="<?php echo $salong_wechat;?>">
                <span class="help-block"><?php _e('请填写微信账号','salong');?></span>
            </p>
            <p>
                <label><?php _e('新浪微博','salong');?></label>
                <input type="text" id="salong_weibo" name="salong_weibo" value="<?php echo $salong_weibo;?>">
                <span class="help-block"><?php _e('请填写新浪微博地址','salong');?></span>
            </p>
            <hr>
            <div class="salong_field_main">
                <label for="salong_alipay"><?php _e('支付宝二维码','salong'); ?></label>
                <div class="salong_field_area">
                    <div class="salong_file_button<?php if($salong_alipay){ echo ' active'; } ?>">
                        <a href="#" class="salong_upload_button"><b>+</b><span><?php _e('上传二维码','salong'); ?></span></a>
                        <div class="salong_file_preview">
                            <?php if($salong_alipay){ ?>
                            <img src="<?php echo $salong_alipay; ?>" alt="<?php echo sprintf(__('%s 的支付宝二维码','salong'),$current_name); ?>">
                            <?php } ?>
                        </div>
                        <div class="bg"></div>
                        <input class="salong_field_upload" type="hidden" value="<?php if($salong_alipay){ echo $salong_alipay; } ?>" id="salong_alipay" name="salong_alipay" />
                    </div>
                    <div class="salong_file_hint">
                        <p>
                            <?php _e('上传您的支付宝收款二维码，建议大小：258*258，如果有发布文章，用户可以给您打赏。','salong'); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="salong_field_main">
                <label for="salong_wechatpay"><?php _e('微信收款二维码','salong'); ?></label>
                <div class="salong_field_area">
                    <div class="salong_file_button<?php if($salong_wechatpay){ echo ' active'; } ?>">
                        <a href="#" class="salong_upload_button"><b>+</b><span><?php _e('上传二维码','salong'); ?></span></a>
                        <div class="salong_file_preview">
                            <?php if($salong_wechatpay){ ?>
                            <img src="<?php echo $salong_wechatpay; ?>" alt="<?php echo sprintf(__('%s 的支付宝二维码','salong'),$current_name); ?>">
                            <?php } ?>
                        </div>
                        <div class="bg"></div>
                        <input class="salong_field_upload" type="hidden" value="<?php if($salong_wechatpay){ echo $salong_wechatpay; } ?>" id="salong_wechatpay" name="salong_wechatpay" />
                    </div>
                    <div class="salong_file_hint">
                        <p>
                            <?php _e('上传您的微信收款二维码，建议大小：258*258，如果有发布文章，用户可以给您打赏。','salong'); ?>
                        </p>
                    </div>
                </div>
            </div>
            <p>
                <input type="submit" value="保存更改" class="submit" />
            </p>
        </form>
        <script type="text/javascript">
            $(function() {
                init_city_select($("#salong_locality"));
            });

            function getValue() {
                alert($('#salong_locality').val());
            }

        </script>
        <?php }else if($get_tab == 'edit-profile-password'){ ?>
        <form id="pass-form" class="author_form" role="form" method="post">
            <input type="hidden" name="update" value="pass">
            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
            <h3 id="pass">
                <?php _e('账号修改','salong');?>
            </h3>
            <p>
                <label for="email"><?php _e('电子邮件 (必填)','salong');?></label>
                <input type="text" id="email" name="email" value="<?php echo $current_email;?>" required>
            </p>
            <p>
                <label for="pass1"><?php _e('新密码','salong');?></label>
                <input type="password" id="pass1" name="pass1">
                <span class="help-block"><?php _e('如果需要修改密码，请输入新的密码，不改则留空。','salong');?></span>
            </p>
            <p>
                <label for="pass2"><?php _e('重复新密码','salong');?></label>
                <input type="password" id="pass2" name="pass2">
                <span class="help-block"><?php _e('再输入一遍新密码，提示：密码最好至少包含7个字符，为了保证密码强度，使用大小写字母、数字和符号结合。','salong');?></span>
            </p>
            <p>
                <input type="submit" value="保存更改" class="submit" />
            </p>
        </form>
        <?php } ?>
    </section>
</section>
