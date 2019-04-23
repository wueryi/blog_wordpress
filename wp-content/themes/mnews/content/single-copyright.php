<?php
global $salong;
global $post;
$author_url = get_author_posts_url(get_the_author_meta('ID'));
$blog_name = get_bloginfo('name');
$from_name = get_post_meta($post->ID, 'from_name', true);
$from_link = get_post_meta($post->ID, 'from_link', true);
if ($salong['switch_link_go']) {
    $from_url = external_link($from_link);
} else {
    $from_url = $from_link;
}
?>
<section class="post_declare">
   <?php if(get_post_meta($post->ID, "from_name", true) && get_post_meta($post->ID, "from_link", true)){ ?>
    <?php printf( __( '本文由来源 <a href="%s" target="_blank" rel="external nofollow">%s</a>，由 %s 整理编辑，其版权均为 %s 所有，文章内容系作者个人观点，不代表 %s 对观点赞同或支持。如需转载，请注明文章来源。', 'salong' ), esc_attr($from_url), esc_attr($from_name), esc_attr(get_the_author_meta('display_name')),esc_attr($from_name), esc_attr($blog_name) ); ?>
    <?php } else if ( user_can( $post->post_author, 'administrator' ) || user_can( $post->post_author, 'editor' ) || user_can( $post->post_author, 'author' ) ) { ?>
    <?php printf( __( '本文由 %s 作者：<a href="%s">%s</a> 发表，其版权均为 %s 所有，文章内容系作者个人观点，不代表 %s 对观点赞同或支持。如需转载，请注明文章来源。', 'salong' ), esc_attr($blog_name), esc_attr($author_url), esc_attr(get_the_author_meta('display_name')), esc_attr($blog_name), esc_attr($blog_name) ); ?>
    <?php } ?>
</section>