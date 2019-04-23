<?php

global $salong,$post,$client_ali,$regionId,$current_user;
$post_id = $post->ID;

$youku_id       = get_post_meta($post->ID, "youku_id", true);
$ali_id         = get_post_meta($post->ID, "ali_id", true);
$video_height   = get_post_meta($post->ID, "video_height", true);
$video_auto     = get_post_meta($post->ID, "video_auto", true);
$source         = get_post_meta($post->ID, "source", true);
$poster_html5   = get_post_meta($post->ID, "poster_html5", true);
$video_list     = get_post_meta($post->ID, "video_list", true);
$product_id     = get_post_meta($post->ID, "product_id", true);
$subtitles      = get_post_meta( $post->ID, 'subtitles', true );

$access_level   = $salong['vip_access'];

if( empty($youku_id) && empty($ali_id) && empty($source) )
    return;

if ( salong_is_administrator() || empty($product_id) || current_user_can( $access_level ) || wc_customer_bought_product( $current_user->email, $current_user->ID, $product_id ) || $current_user->roles[0] == 'vip' ) { ?>
<section class="video_player"<?php if($youku_id || $ali_id){ echo ' id="video_width"'; } ?>>
    <div class="video_player_list">
        <?php if($youku_id){ ?>
        <div id="youku_video" style="height:<?php if($video_height){ echo $video_height; }else{ echo '675'; } ?>px"></div>
        <script type="text/javascript" src="//player.youku.com/jsapi"></script>
        <script type="text/javascript">
            var player = new YKU.Player('youku_video', {
                styleid: '0',
                client_id: '<?php echo $salong['client_youku']; ?>',
                vid: '<?php echo $youku_id; ?>',
                newPlayer: true,
                show_related: false,
                <?php if($video_auto){ ?>
                autoplay: true,
                <?php }else{ ?>
                autoplay: false,
                <?php } ?>
                wmode: 'opaque'
            });
        </script>
        <?php }else if($ali_id){ ?>
        <div id="ali_video" class="ali_video" style="height:<?php if($video_height){ echo $video_height; }else{ echo '675'; } ?>px"></div>
        <script type="text/javascript">
            var player = new Aliplayer({
                id: "ali_video",
                isLive: false,
                <?php if($video_auto){ ?>
                autoplay: true,
                <?php }else{ ?>
                autoplay: false,
                <?php } ?>
                width: "100%",
                controlBarVisibility: "hover",
                useH5Prism: true,
                vid : '<?php echo $ali_id; ?>',
                playauth : '<?php echo salong_ali_video('PlayAuth',$ali_id); ?>',
                cover: "<?php echo salong_ali_video('CoverURL',$ali_id); ?>"
            }, function(player) {
                console.log("播放器创建了。");
            });
        </script>
        <?php }else if($source){$ff='License';if ( ! class_exists( 'AM_'.$ff.'_Menu' ) )return; ?>
        <div class="html5_video">
            <video<?php if($video_auto){ ?> autoplay="autoplay"<?php } ?> width="1200" height="<?php if($video_height){ echo $video_height; }else{ echo '675'; } ?>" style="max-width:100%;" poster="<?php echo $poster_html5; ?>">
                <source src="<?php echo $source; ?>" type="video/mp4">
                <?php if($subtitles){ foreach($subtitles as $value){ ?>
                <track kind="subtitles" src="<?php echo $value['value']; ?>" srclang="<?php echo $value['lang']; ?>" />
                <?php } } ?>
            </video>
        </div>
        <?php } if($video_list){$va='License';if ( ! class_exists( 'AM_'.$va.'_Menu' ) )return;
        $taxterms = wp_get_object_terms( $post->ID, 'vcat', array('fields' => 'ids') );
        $desc = category_description($taxterms[0]);
        ?>
        <!--视频文章列表-->
        <section class="videocat_list">
            <section class="videocat_main">
                <h3><?php _e('目录','salong'); ?></h3>
                <?php if($desc){ echo $desc; } ?>
                <ul>
                    <?php
                    $taxterms = wp_get_object_terms( $post->ID, 'vcat', array('fields' => 'ids') );
                    $args=array( 'post_type'=> 'video','ignore_sticky_posts' => 1,'posts_per_page'=>-1,'order'=>'ASC','tax_query' => array( array( 'taxonomy' => 'vcat', 'field' => 'id', 'terms' => $taxterms ) ));$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
                    $time = get_post_meta($post->ID, "time", true);
                    ?>
                    <li<?php if ($post_id == $wp_query->post->ID) { echo ' class="current"'; } ?>>
                        <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
                        <span><?php echo $time; ?></span>
                    </li>
                    <?php endwhile; endif;wp_reset_query(); ?>
                </ul>
            </section>
        </section>
        <?php } ?>
    </div>
    <!--开关灯-->
    <a href="#l" id="light" class="video_btn" rel="nofollow">
        <?php echo svg_light(); ?>
        <span class="off"><?php _e('关灯','salong'); ?></span>
        <span class="on"><?php _e('开灯','salong'); ?></span>
    </a>
    <?php if($video_list){ ?>
    <!--目录列表-->
    <a href="#c" id="catlist" class="video_btn" rel="nofollow">
        <?php echo svg_category(); ?>
        <span class="off"><?php _e('目录','salong'); ?></span>
        <span class="on"><?php _e('收起','salong'); ?></span>
    </a>
    <?php } ?>
</section>
<?php }else{
    if ( is_user_logged_in() ) {
        echo sprintf('<div class="warningbox">'.__('当前视频内容只有购买了&nbsp;【%s】&nbsp;产品的用户才能查看，点击&nbsp;<a href="%s" target="_blank" title="前往购买">前往购买</a>。','salong').'</div>',get_the_title($product_id),get_permalink($product_id));
    }else{
        if ( class_exists( 'XH_Social' ) ){
            $login_url = '#login';
        }else{
            $login_url  = wp_login_url($_SERVER['REQUEST_URI']);//登录
        }
        echo sprintf('<div class="warningbox">'.__('当前视频内容只有购买了&nbsp;【%s】&nbsp;产品的用户才能查看，点击&nbsp;<a href="%s" target="_blank" title="前往购买">前往购买</a>，如果您已经购买，<a href="%s" title="">请登录</a>。','salong').'</div>',get_the_title($product_id),get_permalink($product_id),$login_url);
    }
}
?>