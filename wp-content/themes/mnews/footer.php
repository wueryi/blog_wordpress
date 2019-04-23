<?php global $salong;

$footer_logo    = $salong['footer_logo']['url'];
$app_android_qr = $salong['app_android_qr']['url'];
$app_apple_qr   = $salong['app_apple_qr']['url'];
$wechat_qr      = $salong['wechat_qr']['url'];
$weibo_url      = $salong['weibo_url'];
$qqzone_url     = $salong['qqzone_url'];


if ($salong['switch_link_go']) {
    $weibo_link  = external_link($weibo_url);
    $qqzone_link = external_link($qqzone_url);
} else {
    $weibo_link  = $weibo_url;
    $qqzone_link = $qqzone_url;
}

$sitemap = get_page_id_from_template('template-sitemap.php');

?>
<footer class="footer">
    <section class="wrapper">
        <div class="left">
            <!--页脚菜单-->
            <?php wp_nav_menu( array( 'container'=>'nav','container_class'=>'footer_menu', 'theme_location' => 'footer-menu','items_wrap'=>'<ul class="menu">%3$s</ul>' , 'fallback_cb'=>'Salong_footer_nav_fallback') ); ?>
            <section class="footer_contact">
                <?php echo $salong['footer_contact']; ?>
            </section>
            <section class="copyright">
                <?php echo $salong['copyright_text']; ?>
                <?php if($salong[ 'tracking_code'] && !current_user_can('level_10')){ echo '&nbsp;'.stripslashes($salong[ 'tracking_code']). ''; } ?>
            </section>
        </div>
        <div class="right">
            <?php if($footer_logo){ ?>
            <a href="<?php echo home_url(); ?>" class="footer_logo" <?php echo new_open_link(); ?>><img src="<?php echo $footer_logo; ?>" alt="<?php bloginfo('name'); ?>-<?php bloginfo('description'); ?>"></a>
            <?php } ?>
            <?php if($app_android_qr || $app_apple_qr || $wechat_qr || $weibo_url || $qqzone_url){ ?>
            <section class="footer_btn">
                <?php if ($app_android_qr){ ?>
                <a href="#android" title="<?php _e('客户端安卓版','salong'); ?>" rel="external nofollow">
                    <?php echo svg_android(); ?>
                </a>
                <?php } if ($app_apple_qr){ ?>
                <a href="#apple" title="<?php _e('客户端苹果版','salong'); ?>" rel="external nofollow">
                    <?php echo svg_apple(); ?>
                </a>
                <?php } if ($wechat_qr){ ?>
                <a href="#wechat" title="<?php _e('微信公众号','salong'); ?>" rel="external nofollow">
                    <?php echo svg_wechat(); ?>
                </a>
                <?php } if ($weibo_url){ ?>
                <a href="<?php echo $weibo_link; ?>" title="<?php _e('微博主页','salong'); ?>" rel="external nofollow"<?php echo new_open_link(); ?>>
                    <?php echo svg_sina(); ?>
                </a>
                <?php } if ($qqzone_url){ ?>
                <a href="<?php echo $qqzone_link; ?>" title="<?php _e('QQ 空间','salong'); ?>" rel="external nofollow"<?php echo new_open_link(); ?>>
                    <?php echo svg_qqzone(); ?>
                </a>
                <?php } if ($sitemap){ ?>
                <a href="<?php echo get_page_link($sitemap); ?>" title="<?php _e('网站地图','salong'); ?>"<?php echo new_open_link(); ?>>
                    <?php echo svg_rss(); ?>
                </a>
                <?php } ?>
            </section>
            <?php } ?>
        </div>
    </section>
    <?php get_template_part( 'content/footer','popup'); ?>
    <?php get_template_part( 'content/side','btn'); ?>
    <!--广告背景-->
    <div class="bg light"></div>
    <!--购物车-->
    <div class="bg cart"></div>
    <?php if (class_exists('woocommerce')){ get_template_part( 'woocommerce/ajax','cart'); } ?>
    <?php get_template_part( 'content/mobile','btn'); ?>
</footer>
<!--禁止复制-->
<?php if($salong['switch_copy']){ ?>
<script type="text/Javascript">
    document.oncontextmenu=function(e){return false;}; document.onselectstart=function(e){return false;};
</script>
<style>
    body {
        -moz-user-select: none;
    }

</style>
<SCRIPT LANGUAGE=javascript>
    if (top.location != self.location) top.location = self.location;

</SCRIPT>
<noscript>
    <iframe src=*.Html></iframe>
</noscript>
<?php } ?>
<?php wp_footer(); ?>
<?php if($salong[ 'switch_loadmore']){ get_template_part( 'includes/loadmore'); } ?>
<?php if(salong_is_weixin() && $salong['switch_wechat_share']){ get_template_part( 'includes/function/wxshare'); } ?>

</body>

</html>
