<?php
register_nav_menus(array('header-menu' => __('导航菜单', 'salong'), 'footer-menu' => __('页脚菜单', 'salong')));
if (!function_exists('salong_script') && !is_admin()) {
    add_action('wp', 'salong_script');
	add_action('wp_enqueue_scripts', 'salong_single');
}
add_action('wp_enqueue_scripts', 'sl_enqueue_scripts');
function sl_enqueue_scripts()
{
    wp_enqueue_script('post-like', get_template_directory_uri() . '/js/post-like-min.js', array('jquery'), '0.5', false);
    wp_localize_script('post-like', 'simpleLikes', array('ajaxurl' => admin_url('admin-ajax.php'), 'like' => __('赞', 'salong'), 'unlike' => __('已赞', 'salong')));
}

add_action('admin_menu', 'salong_activate');
function salong_activate()
{
	global $wpdb;
	$activate = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'pm (
		`id` bigint(20) NOT NULL auto_increment,
		`subject` text NOT NULL,
		`content` text NOT NULL,
		`type` varchar(20),
		`sender` int,
		`recipient` int,
        `from_to` tinytext,
		`date` datetime NOT NULL,
		`read` tinyint(1) NOT NULL,
		`deleted` tinyint(1) NOT NULL,
		PRIMARY KEY (`id`)
	) COLLATE utf8_general_ci;';
	$wpdb->query($activate);
}

function salong_load_scripts()
{
	wp_enqueue_script('salong-follow', get_template_directory_uri() . '/js/follow-min.js', array('jquery'));
	wp_localize_script('salong-follow', 'salong_vars', array('processing_error' => __('处理请求时出现错误！', 'salong'), 'login_required' => __('呼，您必须登录才能关注用户！', 'salong'), 'logged_in' => is_user_logged_in() ? 'true' : 'false', 'ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('follow_nonce')));
}
add_action('wp_enqueue_scripts', 'salong_load_scripts');
function salong_post_like()
{
	global $salong;
	if ($salong['switch_like_btn']) {
		echo '<div class="post_like">';
		echo get_post_likes_button(get_the_ID()) . salong_user_follow_post();
		echo '</div>';
	}
}
function posts_pagination()
{
	echo the_posts_pagination(array('mid_size' => 1, 'prev_text' => svg_more(), 'next_text' => svg_more()));
}
function salong_link_pages()
{
	$link_pages = array('before' => '<div class="pagination"><p>' . __('分页：', 'salong') . '</p>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>', 'next_or_number' => 'number', 'separator' => '', 'nextpagelink' => svg_more(), 'previouspagelink' => svg_more(), 'pagelink' => '%', 'echo' => 1);
	wp_link_pages($link_pages);
}
function salong_custom_social()
{
    echo '<style type="text/css">.social_bd{margin-bottom:12px}.social_bd a{width:26px;height:26px;border-radius:100%;border:1px #eee solid;display:inline-block;margin-right:8px;text-align:center;line-height:24px;background-color:#fff}.social_bd .wechat svg{fill:#25d38a}.social_bd .qq svg{fill:#0085ff}.social_bd .sina svg{fill:#ec4141}.social_bd .github svg{fill:#1277eb}.social_bd .dingding svg{fill:#3795f9;margin:4px 0 4px 6px}.social_bd svg{width:18px;height:18px;margin:4px}.social_bd b{display:inline-block;vertical-align:middle}</style>';
}
add_action('login_head','salong_custom_social');