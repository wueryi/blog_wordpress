<?php global $salong,$wp_query;
$app_android_qr = $salong['app_android_qr']['url'];
$app_apple_qr   = $salong['app_apple_qr']['url'];
$wechat_qr      = $salong['wechat_qr']['url'];

wp_reset_query();

/*打赏二维码*/
$curauth    = $wp_query->get_queried_object();//当前用户
if(is_author()){
    $curauth_id = $curauth->ID;//当前用户 ID
}else{
    $curauth_id = get_the_author_meta('ID');
}

$salong_alipay    = get_user_meta( $curauth_id, 'salong_alipay', true);
$salong_wechatpay = get_user_meta( $curauth_id, 'salong_wechatpay', true);
?>

<?php if ($salong[ 'switch_program_menu'] && $salong['program_qr']) { ?>
<!--小程序弹窗-->
<a href="#m" class="overlay" rel="external nofollow" id="program"></a>
<article class="popup program">
    <section class="popup_main">
        <h3>
            <?php echo $salong['program_title']; ?>
        </h3>
        <img src="<?php echo $salong['program_qr']['url']; ?>" alt="<?php echo bloginfo('name'); ?>">
        <a class="close" rel="external nofollow" href="#m"><?php echo svg_close(); ?></a>
    </section>
</article>
<!--二维码弹窗end-->

<?php } if ($app_android_qr ) { ?>
<!-- APP 安卓版 -->
<a id="android" class="overlay" rel="external nofollow" href="#m"></a>
<article class="android popup">
    <section class="popup_main">
        <h3>
            <?php _e( '扫描下载安卓客户端', 'salong' ); ?>
        </h3>
        <img src="<?php echo $app_android_qr; ?>" alt="<?php bloginfo( 'name' ); ?><?php _e( '安卓客户端', 'salong' ); ?>" />
        <a class="close" rel="external nofollow" href="#m"><?php echo svg_close(); ?></a>
    </section>
</article>

<?php } if ($app_apple_qr ) { ?>
<!-- APP 苹果版 -->
<a id="apple" class="overlay" rel="external nofollow" href="#m"></a>
<article class="apple popup">
    <section class="popup_main">
        <h3>
            <?php _e( '扫描下载苹果客户端', 'salong' ); ?>
        </h3>
        <img src="<?php echo $app_apple_qr; ?>" alt="<?php bloginfo( 'name' ); ?><?php _e( '苹果客户端', 'salong' ); ?>" />
        <a class="close" rel="external nofollow" href="#m"><?php echo svg_close(); ?></a>
    </section>
</article>

<?php } if ($wechat_qr ) { ?>
<!-- 微信公众号 -->
<a id="wechat" class="overlay" rel="external nofollow" href="#m"></a>
<article class="wechat popup">
    <section class="popup_main">
        <h3>
            <?php _e( '关注', 'salong' ); ?>
            <?php bloginfo( 'name' ); ?>
            <?php _e( '微信公众号', 'salong' ); ?>
        </h3>
        <img src="<?php echo $wechat_qr; ?>" alt="<?php bloginfo( 'name' ); ?><?php _e( '微信公众号', 'salong' ); ?>" />
        <a class="close" rel="external nofollow" href="#m"><?php echo svg_close(); ?></a>
    </section>
</article>

<?php } if ( $salong_alipay || $salong_wechatpay ) { ?>
<!-- 打赏二维码 -->
<a id="payqr" class="overlay" rel="external nofollow" href="#m"></a>
<article class="payqr popup">
    <section class="popup_main<?php if ($salong_alipay && $salong_wechatpay){ echo ' two'; } ?>">
        <h3>
            <?php _e( '给 TA 打赏', 'salong' ); ?>
        </h3>
        <?php if($salong_alipay){ ?><span class="alipay"><img src="<?php echo $salong_alipay; ?>" alt="<?php _e('支付宝收款二维码','salong'); ?>"><?php echo svg_alipay(); _e('支付宝收款二维码','salong'); ?></span>
        <?php } if($salong_wechatpay){ ?><span class="wechatpay"><img src="<?php echo $salong_wechatpay; ?>" alt="<?php _e('微信收款二维码','salong'); ?>"><?php echo svg_wechat(); _e('微信收款二维码','salong'); ?></span>
        <?php } ?>
        <a class="close" rel="external nofollow" href="#m"><?php echo svg_close(); ?></a>
    </section>
</article>

<?php } ?>

<?php if ($salong[ 'switch_search_menu']) {

@$search_post_cat      = $salong['search_post_cat'];
@$search_topic_cat     = $salong['search_topic_cat'];
@$search_download_cat  = $salong['search_download_cat'];
@$search_video_cat     = $salong['search_video_cat'];
@$search_product_cat   = $salong['search_product_cat'];

?>
<!-- 搜索 -->
<a id="search" class="overlay" rel="external nofollow" href="#m"></a>
<article class="search popup">
    <section class="popup_main">
        <h3>
            <?php _e( '按文章类型进行搜索', 'salong' ); ?>
        </h3>
        <form method="get" class="search_form" action="<?php echo get_home_url(); ?>">
            <select name="post_type" class="search_type">
                <option value="post">
                    <?php _e( '文章', 'salong' ); ?>
                </option>
                <?php if($salong[ 'switch_topic_type']){ ?>
                <option value="topic">
                    <?php _e( '专题', 'salong' ); ?>
                </option>
                <?php } if($salong[ 'switch_download_type']){ ?>
                <option value="download">
                    <?php _e( '下载', 'salong' ); ?>
                </option>
                <?php } if($salong[ 'switch_video_type']){ ?>
                <option value="video">
                    <?php _e( '视频', 'salong' ); ?>
                </option>
                <?php } if(class_exists('woocommerce')){ ?>
                <option value="product">
                    <?php _e( '产品', 'salong' ); ?>
                </option>
                <?php } ?>
            </select>
            <input class="text_input" type="text" placeholder="<?php _e( '输入关键字…', 'salong' ); ?>" name="s" id="s" />
            <input type="submit" class="search_btn" id="searchsubmit" value="<?php _e( '搜索', 'salong' ); ?>" />
        </form>
        <?php if($search_topic_cat || $search_post_cat || $search_download_cat || $search_video_cat || $search_product_cat){ ?>
        <section class="hot_search">
            <?php if($search_post_cat){ ?>
            <div id="search_post" class="stype">
                <span><?php _e('热门搜索：'); ?></span>
                <?php foreach($search_post_cat as $cat){ ?>
                <a href="<?php echo get_category_link($cat);?>" title="<?php echo get_cat_name($cat);?>" <?php echo new_open_link(); ?>><?php echo get_cat_name($cat);?></a>
                <?php } ?>
            </div>
            <?php } if($search_topic_cat && $salong[ 'switch_topic_type'] ){ ?>
            <div id="search_topic" class="stype" style="display:none">
                <span><?php _e('热门搜索：'); ?></span>
                <?php foreach($search_topic_cat as $cat){
                    $get_category = get_categories(array('include'=>$cat,'taxonomy'=>'tcat'));
                    ?>
                <a href="<?php echo get_term_link( (int)$cat, 'tcat' ); ?>" title="<?php echo $get_category[0]->cat_name;?>" <?php echo new_open_link(); ?>><?php echo $get_category[0]->cat_name;?></a>
                <?php } ?>
            </div>
            <?php } if($search_download_cat && $salong[ 'switch_download_type'] ){ ?>
            <div id="search_download" class="stype" style="display:none">
                <span><?php _e('热门搜索：'); ?></span>
                <?php foreach($search_download_cat as $cat){
                    $get_category = get_categories(array('include'=>$cat,'taxonomy'=>'dcat'));
                    ?>
                <a href="<?php echo get_term_link( (int)$cat, 'dcat' ); ?>" title="<?php echo $get_category[0]->cat_name;?>" <?php echo new_open_link(); ?>><?php echo $get_category[0]->cat_name;?></a>
                <?php } ?>
            </div>
            <?php } if($search_video_cat && $salong[ 'switch_video_type'] ){ ?>
            <div id="search_video" class="stype" style="display:none">
                <span><?php _e('热门搜索：'); ?></span>
                <?php foreach($search_video_cat as $cat){
                    $get_category = get_categories(array('include'=>$cat,'taxonomy'=>'vcat'));
                    ?>
                <a href="<?php echo get_term_link( (int)$cat, 'vcat' ); ?>" title="<?php echo $get_category[0]->cat_name;?>" <?php echo new_open_link(); ?>><?php echo $get_category[0]->cat_name;?></a>
                <?php } ?>
            </div>
            <?php } if($search_product_cat && class_exists('woocommerce')){ ?>
            <div id="search_product" class="stype" style="display:none">
                <span><?php _e('热门搜索：'); ?></span>
                <?php foreach($search_product_cat as $cat){
                    $get_category = get_categories(array('include'=>$cat,'taxonomy'=>'product_cat'));
                    ?>
                <a href="<?php echo get_term_link( (int)$cat, 'product_cat' ); ?>" title="<?php echo $get_category[0]->cat_name;?>" <?php echo new_open_link(); ?>><?php echo $get_category[0]->cat_name;?></a>
                <?php } ?>
            </div>
            <?php } ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    $(".search_type").change(function() {
                        var item = $(this).val();
                        $(".stype").hide();
                        $("#search_" + item).show();
                    });
                });

            </script>
        </section>
        <?php } ?>
        <a class="close" rel="external nofollow" href="#m"><?php echo svg_close(); ?></a>
    </section>
</article>
<?php } ?>
<!--前台登录-->
<?php if ( class_exists( 'XH_Social' ) && !is_user_logged_in() ){ ?>
<a id="login" class="overlay" rel="external nofollow" href="#m"></a>
<section class="login popup">
    <section class="popup_main">
        <?php echo do_shortcode('[xh_social_page_login]'); ?>
        <section class="login_reg">
            <a href="<?php echo wp_registration_url(); ?>" title="<?php _e('注册一个新的帐户','salong'); ?>">
                <?php _e('注册','salong'); ?>
            </a>｜
            <a href="<?php echo wp_lostpassword_url(); ?>" title="<?php _e('忘记密码','salong'); ?>">
                <?php _e('忘记密码','salong'); ?>
            </a>
        </section>
        <a class="close" rel="external nofollow" href="#m"><?php echo svg_close(); ?></a>
    </section>
</section>
<?php } if(is_single()){
    $type   = get_post_type();
    $switch = $salong['switch_'.$type.'_share'];
    if($switch && $salong[ 'share_metas'] && in_array( 'cover', $salong[ 'share_metas'])){
?>
<div class="share_cover" id="share_cover">
    <div class="cover_img">
        <?php $share_cover = get_post_meta( get_the_ID(), 'share_cover', true );if( $share_cover ){ ?>
        <img src="<?php echo $share_cover ?>" alt="<?php echo get_the_title().__('分享封面','salong'); ?>">
        <div class="cover_close"><?php echo svg_close(); ?></div>
        <?php }else{ ?>
        <div class="cover_loading"><?php echo _e('分享图片生成中…'); ?></div>
        <img class="cover_load_img" data-nonce="<?php echo wp_create_nonce('create-cover-img-'.get_the_ID() );?>" data-id="<?php the_ID(); ?>" data-action="create-cover-image" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" alt="<?php the_title()._e('的分享封面','salong'); ?>">
        <div class="cover_close" style="display:none"><?php echo svg_close(); ?></div>
        <?php } ?>
    </div>
    <div class="bg"></div>
</div>
<?php } } ?>
