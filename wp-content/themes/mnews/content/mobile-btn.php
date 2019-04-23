<?php global $salong,$current_user;
$user_id      = $current_user->id;
$topic_url    = get_page_link(get_page_id_from_template('template-topic.php'));
$download_url = get_page_link(get_page_id_from_template('template-download.php'));
$video_url    = get_page_link(get_page_id_from_template('template-video.php'));
$post_url     = get_page_link(get_page_id_from_template('template-post.php'));
$kx_url       = get_page_link(get_page_id_from_template('template-kx.php'));

if (class_exists('woocommerce')){
    $shop_url     = get_page_link(wc_get_page_id('shop'));
    $cart_url     = get_page_link(wc_get_page_id('cart'));
}
$metas        = $salong[ 'mobile_btn_metas'];

if(is_user_logged_in()){
    if (class_exists('woocommerce')){
        $login_url = get_permalink(wc_get_page_id( 'myaccount' ));
    }
}else{
    if ( class_exists( 'XH_Social' ) ){
        $login_url = '#login';
    }else{
        $login_url  = wp_login_url($_SERVER['REQUEST_URI']);//登录
    }
}
?>
<?php if ($metas !=0 ) { ?>
<aside class="mobile_btn">
    <ul>
        <?php if (in_array( 'home', $metas)) { ?>
        <li>
            <a href="<?php echo get_option('home'); ?>">
                <?php echo svg_home();_e('首页','salong'); ?>
            </a>
        </li>
        <?php } if (in_array( 'search', $metas)) { ?>
        <li>
            <a href="#search">
                <?php echo svg_search();_e('搜索','salong'); ?>
            </a>
        </li>
        <?php } if (in_array( 'post', $metas)) { ?>
        <li>
            <a href="<?php echo $post_url; ?>">
                <?php echo svg_post(); _e('文章','salong'); ?>
            </a>
        </li>
        <?php } if (in_array( 'kx', $metas)) { ?>
        <li>
            <a href="<?php echo $kx_url; ?>">
                <?php echo svg_kx(); _e('快讯','salong'); ?>
            </a>
        </li>
        <?php } if (in_array( 'topic', $metas)) { ?>
        <li>
            <a href="<?php echo $topic_url; ?>">
                <?php echo svg_topic();_e('专题','salong'); ?>
            </a>
        </li>
        <?php } if (in_array( 'video', $metas)) { ?>
        <li>
            <a href="<?php echo $video_url; ?>">
                <?php echo svg_video(); _e('视频','salong'); ?>
            </a>
        </li>
        <?php } if (in_array( 'download', $metas)) { ?>
        <li>
            <a href="<?php echo $download_url; ?>">
                <?php echo svg_download(); _e('下载','salong'); ?>
            </a>
        </li>
        <?php } if (in_array( 'wechat', $metas)) { ?>
        <li class="wechat">
            <a href="#wechat">
                <?php echo svg_wechat(); _e('公众号','salong'); ?>
            </a>
        </li>
        <?php } if (in_array( 'shop', $metas) && class_exists('woocommerce')) { ?>
        <li>
            <a href="<?php echo $shop_url; ?>">
                <?php echo svg_shop(); _e('商城','salong'); ?>
            </a>
        </li>
        <?php } if (in_array( 'cart', $metas) && class_exists('woocommerce')) { ?>
        <li>
            <a href="<?php echo $cart_url; ?>" class="cart">
                <?php echo svg_cart(); _e('购物车','salong'); ?><span class="cart-contents">0</span>
            </a>
        </li>
        <?php } if (in_array( 'myaccount', $metas) && class_exists('woocommerce')) { ?>
        <li>
            <a href="<?php echo $login_url; ?>">
                <?php echo svg_myaccount(); _e('我的','salong'); ?>
            </a>
        </li>
        <?php } ?>
    </ul>
</aside>
<?php } ?>
