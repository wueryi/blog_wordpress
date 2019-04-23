<?php global $salong,$wp_query,$current_user;
$curauth            = $wp_query->get_queried_object();//当前用户
$current_id         = $current_user->ID;//登录用户 ID
$curauth_id         = $curauth->ID;//当前用户 ID
$curauth_login      = $curauth->user_login;
$curauth_name       = $curauth->display_name;
$curauth_email      = $curauth->user_email;
$curauth_url        = $curauth->user_url;
$curauth_registered = $curauth->user_registered;
$description        = $curauth->description;
$salong_locality    = $curauth->salong_locality;
$salong_qq          = $curauth->salong_qq;
$salong_weibo       = $curauth->salong_weibo;
$salong_wechat      = $curauth->salong_wechat;
$salong_gender      = $curauth->salong_gender;
$salong_alipay      = $curauth->salong_alipay;
$salong_wechatpay   = $curauth->salong_wechatpay;
$salong_phone       = $curauth->salong_phone;
$salong_company     = $curauth->salong_company;
$salong_position    = $curauth->salong_position;
$salong_open        = $curauth->salong_open;
$salong_certified   = $current_user->salong_certified;

//最后登录时间
$last_login = get_last_login($curauth_id);

// 自己
$oneself = $current_id==$curauth_id;

// 角色
$role = get_userdata($current_id)->roles;

//评论数量
$comments_count = get_comments( array('status' => '1', 'user_id'=>$curauth_id, 'count' => true) );

/*文章类型*/
$post_type_arr = array('post','topic','download','video');

?>

<section class="author_profile">
    <section class="basic_profile">
        <h3>
            <?php _e('基本信息','salong'); ?>
            
            <?php if($oneself && in_array($role[0],array('contributor','subscriber'))){ ?>
                <a href="#certification" class="certification" rel="nofollow">
                    <?php if($salong_certified){ ?>
                    <?php _e('审核中……','salong'); ?>
                    <?php }else{ ?>
                    <?php _e('专栏作者认证','salong'); ?>
                    <?php } ?>
                </a>
            <?php } ?>
        </h3>
        <ul class="layout_ul">
            <li class="layout_li"><span><?php _e('昵称：','salong'); ?></span>
                <?php echo $curauth_name; ?>
            </li>
            <li class="layout_li"><span><?php _e('角色：','salong'); ?></span>
                <?php echo user_role($curauth_id); ?>
            </li>
            <?php if($salong_open == 'on'){ ?>
            <li class="layout_li"><span><?php _e('邮箱：','salong'); ?></span>
                <?php echo $curauth_email; ?>
            </li>
            <?php } if($curauth_url){ ?>
            <li class="layout_li"><span><?php _e('站点：','salong'); ?></span>
                <a href="<?php echo $curauth_url; ?>" title="<?php _e('访问我的站点！','salong'); ?>">
                    <?php echo $curauth_url; ?>
                </a>
            </li>
            <?php } ?>
            <li class="layout_li"><span><?php _e('注册时间：','salong'); ?></span>
                <?php echo $curauth_registered; ?>
            </li>
            <li class="layout_li"><span><?php _e('最后登录：','salong'); ?></span>
                <?php echo $last_login; ?>
            </li>
            <?php if($description){ ?>
            <li class="layout_li desc"><span><?php _e('个人说明：','salong'); ?></span>
                <?php echo $description; ?>
            </li>
            <?php } ?>
        </ul>
        <?php if( class_exists( 'XH_Social' ) && is_user_logged_in() && $oneself ){
        $channels = XH_Social::instance()->channel->get_social_channels(array('login'));
        ?>
        <?php if($channels && $mnews_data['api_key']){ ?>
        <?php echo do_shortcode('[xh_social_accountbind]'); ?>
        <?php } } if( ($salong_locality || $salong_qq || $salong_weibo || $salong_wechat) && ( $salong_open == 'on' || $oneself || salong_is_administrator())){ ?>
        <hr>
        <h3>
            <?php _e('扩展信息','salong'); ?>
        </h3>
        <ul class="layout_ul">
            <?php if($salong_gender){ ?>
            <li class="layout_li"><span><?php _e('性别：','salong'); ?></span>
                <?php if($salong_gender == 'male'){ echo __('男','salong'); }else{ echo __('女','salong'); } ?>
            </li>
            <?php } if($salong_locality){ ?>
            <li class="layout_li"><span><?php _e('坐标：','salong'); ?></span>
                <?php echo $salong_locality; ?>
            </li>
            <?php } if($salong_phone){ ?>
            <li class="layout_li"><span><?php _e('手机：','salong'); ?></span>
                <?php echo $salong_phone; ?>
            </li>
            <?php } if($salong_qq){ ?>
            <li class="layout_li"><span><?php _e('QQ：','salong'); ?></span>
                <?php echo $salong_qq; ?>
            </li>
            <?php } if($salong_wechat){ ?>
            <li class="layout_li"><span><?php _e('微信：','salong'); ?></span>
                <?php echo $salong_wechat; ?>
            </li>
            <?php } if($salong_weibo){ ?>
            <li class="layout_li"><span><?php _e('微博：','salong'); ?></span>
                <a href="<?php echo $salong_weibo; ?>" title="<?php _e('访问我的站点！','salong'); ?>">
                    <?php echo $salong_weibo; ?>
                </a>
            </li>
            <?php } if($salong_company){ ?>
            <li class="layout_li"><span><?php _e('公司：','salong'); ?></span>
                <?php echo $salong_company; ?>
            </li>
            <?php } if($salong_position){ ?>
            <li class="layout_li"><span><?php _e('职位：','salong'); ?></span>
                <?php echo $salong_position; ?>
            </li>
            <?php } if($salong_open){ ?>
            <li class="layout_li"><span><?php _e('资料：','salong'); ?></span>
                <?php if($salong_open == 'on'){ echo __('公开','salong'); }else{ echo __('不公开','salong'); } ?>
            </li>
            <?php } if($salong_alipay || $salong_wechatpay){ ?>
            <li class="layout_li qr">
                <span><?php _e('打赏：','salong'); ?></span>
                <?php if($salong_alipay){ ?>
                <div class="alipay">
                    <img src="<?php echo $salong_alipay; ?>" alt="<?php echo sprintf(__('%s 支付宝收款二维码','salong'),$curauth_name); ?>">
                    <?php echo svg_alipay(); _e('支付宝收款二维码','salong'); ?>
                </div>
                <?php } if($salong_wechatpay){ ?>
                <div class="wechatpay">
                    <img src="<?php echo $salong_wechatpay; ?>" alt="<?php echo sprintf(__('%s 微信收款二维码','salong'),$curauth_name); ?>">
                    <?php echo svg_wechat(); _e('微信收款二维码','salong'); ?>
                </div>
                <?php } ?>
            </li>
            <?php } ?>
        </ul>
        <?php } ?>
    </section>
    <?php if(salong_author_post_count($curauth_id,'post') || salong_author_post_count($curauth_id,'topic') || salong_author_post_count($curauth_id,'download') || salong_author_post_count($curauth_id,'video')){ ?>
    <hr>
    <!-- 网站统计 -->
    <section class="site_stats">
        <h3>
            <?php _e('我的统计','salong'); ?>
        </h3>
        <ul class="layout_ul">
           <?php foreach($post_type_arr as $post_type){
            $type_object  = get_post_type_object( $post_type );
            $type_name    = $type_object->labels->singular_name;
            if(!salong_author_post_count($curauth_id,$post_type)){
                continue;
            }
            ?>
            <li class="layout_li">
                <article class="stats_main">
                    <h4>
                        <?php echo $type_name; ?>
                    </h4>
                    <?php $args=array( 'author'=> $curauth_id,'post_type' => $post_type,'posts_per_page' => 1,'ignore_sticky_posts'=> 1);$newpost_query = new WP_Query($args);if( $newpost_query->have_posts() ) { while ($newpost_query->have_posts()) : $newpost_query->the_post(); ?>
                    <div class="new_post">
                        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"<?php echo new_open_link(); ?>>
                            <?php _e( '最新：', 'salong'); ?><?php the_title(); ?>
                        </a>
                    </div>
                    <?php endwhile;}wp_reset_query(); ?>
                    <span><?php echo sprintf(__('%s数量：','salong'),$type_name); ?><b><?php echo salong_author_post_count($curauth_id,$post_type); ?></b></span>
                    <span><?php _e('浏览数量：','salong'); ?><b><?php echo salong_author_post_field_count($post_type,$curauth_id,'views'); ?></b></span>
                    <span><?php _e('点赞数量：','salong'); ?><b><?php echo salong_author_post_field_count($post_type,$curauth_id,'salong_post_like_count'); ?></b></span>
                </article>
            </li>
            <?php } if($comments_count){ ?>
            <li class="layout_li">
                <article class="stats_main">
                    <h4>
                        <?php _e('评论','salong'); ?>
                    </h4>
                    <span><?php _e('评论数量：','salong'); ?><b><?php echo $comments_count; ?></b></span>
                </article>
            </li>
            <?php } ?>
        </ul>
    </section>
    <?php } get_template_part( 'content/author', 'certification'); ?>
</section>
