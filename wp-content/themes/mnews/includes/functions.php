<?php
global $salong;
header('Content-type: text/html; charset=utf-8');
load_theme_textdomain('salong', get_template_directory() . '/languages');
if (!class_exists('ReduxFramework') && file_exists(get_template_directory() . '/admin/ReduxCore/framework.php')) {
	require_once get_template_directory() . '/admin/ReduxCore/framework.php';
}
if (!isset($redux_demo) && file_exists(get_template_directory() . '/admin/config.php')) {
	require_once get_template_directory() . '/admin/config.php';
}
if ($salong['thumb_mode'] == 'timthumb') {
	require_once get_template_directory() . '/includes/thumb.php';
} else {
	require_once get_template_directory() . '/includes/wpauto.php';
}
require_once get_template_directory() . '/includes/function/functions.php';
require_once get_template_directory() . '/includes/post-types.php';
if ($salong['share_metas']) {
    require_once get_template_directory() . '/includes/function/postcover.php';
}
require_once get_template_directory() . '/includes/shortcodes/shortcodespanel.php';
require_once get_template_directory() . '/includes/shortcodes/shortcodes.php';
require_once get_template_directory() . '/includes/notify.php';
require_once get_template_directory() . '/includes/comment-ajax.php';
require_once get_template_directory() . '/includes/ajax-post.php';
if (is_admin()) {
	require_once get_template_directory() . '/includes/tutorial.php';
	if ($salong['switch_xiongzhang_submit']) {
		require_once get_template_directory() . '/includes/function/FanlySubmit.php';
	}
	require_once get_template_directory() . '/includes/function/authors-autocomplete.php';
}
require_once get_template_directory() . '/includes/sift.php';
require get_template_directory() . '/includes/metabox/framework-core.php';
require get_template_directory() . '/includes/metabox/meta-box.php';
require_once get_template_directory() . '/includes/messages/index.php';
require_once get_template_directory() . '/includes/post-like.php';
require_once get_template_directory() . '/includes/follow.php';
if (class_exists('woocommerce')) {
	require_once get_template_directory() . '/woocommerce/woo-config.php';
}
require_once get_template_directory() . '/includes/sidebars.php';
require_once get_template_directory() . '/includes/widgets/widget-post.php';
require_once get_template_directory() . '/includes/widgets/widget-follow-post.php';
require_once get_template_directory() . '/includes/widgets/widget-about.php';
require_once get_template_directory() . '/includes/widgets/widget-tag.php';
require_once get_template_directory() . '/includes/widgets/widget-comments.php';
require_once get_template_directory() . '/includes/widgets/widget-word.php';
require_once get_template_directory() . '/includes/widgets/widget-user.php';

if ($salong['switch_download_type']) {
	require_once get_template_directory() . '/includes/widgets/widget-download.php';
}
if ($salong['switch_video_type']) {
	require_once get_template_directory() . '/includes/widgets/widget-video.php';
}
if ($salong['switch_topic_type']) {
	require_once get_template_directory() . '/includes/widgets/widget-topic.php';
}
if ($salong['switch_kx_type']) {
	require_once get_template_directory() . '/includes/widgets/widget-kx.php';
}
function salong_is_weixin()
{
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		return true;
	}
	return false;
}
function salong_is_administrator()
{
	$users = wp_get_current_user();
	if (!empty($users->roles) && in_array('administrator', $users->roles)) {
		return 1;
	} else {
		return 0;
	}
}


if (salong_is_weixin() && $salong['switch_wechat_share']) {
	require_once get_template_directory() . '/includes/function/jssdk.php';
	add_action('wp_ajax_nopriv_wechat_share', 'wechat_share_callback');
	add_action('wp_ajax_wechat_share', 'wechat_share_callback');
	function wechat_share_callback()
	{
		$_var_278 = $_POST['postid'];
		$_var_279 = get_post_meta($_var_278, 'wechat_share_num', true) ? get_post_meta($_var_278, 'wechat_share_num', true) + 1 : 1;
		update_post_meta($_var_278, 'wechat_share_num', $_var_279);
		die;
	}
}
if (array_key_exists('home_new_filter_cate', $salong)) {
	if ($salong['home_new_filter_cate']){
		    function salong_ajax_post()
    {   
        wp_enqueue_script('ajax-post', get_template_directory_uri().'/js/ajax-post-min.js', array('jquery'), '1.0', false);
	    wp_localize_script( 'ajax-post', 'salong_ajax', array(
            'nonce'   => wp_create_nonce('salong_ajax'),
            'ajax_url'   => admin_url('admin-ajax.php'), 
        ) );
	}
    add_action('wp_enqueue_scripts', 'salong_ajax_post');
}
}
