<?php global $salong;
$user_id = get_the_author_meta('ID');
$user_name = get_the_author_meta('display_name');

$post_like_count = get_post_meta($post->ID, "salong_post_like_count", true);
if($post_like_count){
    $post_like = $post_like_count;
}else{
    $post_like = '0';
}
$download_count = get_post_meta($post->ID, "download_count", true);
if($download_count){
    $dl_count = $download_count;
}else{
    $dl_count = '0';
}

if ($salong[ 'download_metas'] == 0 )
    return;
?>

<div class="postinfo">
    <div class="left">
        <?php if (in_array( 'author', $salong[ 'download_metas']) && is_single() ) { ?>
        <!--作者-->
        <span class="author"><a href="<?php echo get_author_posts_url($user_id); ?>" title="<?php echo $user_name; ?>"<?php echo new_open_link(); ?>><?php echo salong_get_avatar($user_id,$user_name); ?><?php echo $user_name; ?></a></span>
        <?php } if (in_array( 'category', $salong[ 'blog_metas']) && is_single()) { ?>
        <!--分类-->
        <span class="category"><?php echo svg_category(); the_terms( $post->ID, 'dcat','' ); ?></span>
        <?php } if (in_array( 'date', $salong[ 'download_metas'])) { ?>
        <!--时间-->
        <span class="date"><?php echo svg_date(); ?><b><?php echo get_the_date(); ?></b></span>
        <?php } ?>
    </div>
    <div class="right">
        <?php if (in_array( 'download', $salong[ 'download_metas'])) { ?>
        <!--下载-->
        <span class="download"><?php echo svg_download(); ?><b><?php echo $dl_count; ?></b></span>
        <?php } if (in_array( 'view', $salong[ 'download_metas'])) { ?>
        <!--浏览量-->
        <span class="view"><?php echo svg_view(); ?><b><?php echo getPostViews(get_the_ID()); ?></b></span>
        <?php } if (in_array( 'like', $salong[ 'download_metas'])) { ?>
        <!--点赞-->
        <span class="like"><?php echo svg_like(); ?><b><?php echo $post_like; ?></b></span>
        <?php } ?>
    </div>
</div>
