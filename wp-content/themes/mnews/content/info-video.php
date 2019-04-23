<?php global $salong,$post;
$user_id    = get_the_author_meta('ID');
$user_name  = get_the_author_meta('display_name');

$post_like_count = get_post_meta($post->ID, "salong_post_like_count", true);
if($post_like_count){
    $post_like = $post_like_count;
}else{
    $post_like = '0';
}

if ($salong[ 'video_metas'] == 0 )
    return;
?>

<div class="postinfo">
    <div class="left">
        <?php if (in_array( 'author', $salong[ 'video_metas']) && is_single() ) { ?>
        <!--作者-->
        <span class="author"><a href="<?php echo get_author_posts_url($user_id); ?>" title="<?php echo $user_name; ?>"<?php echo new_open_link(); ?>><?php echo salong_get_avatar($user_id,$user_name); ?><?php echo $user_name; ?></a></span>
        <?php } if (in_array( 'category', $salong[ 'video_metas']) && is_single()) { ?>
        <!--分类-->
        <span class="category"><?php echo svg_category(); the_terms( $post->ID, 'vcat','' ); ?></span>
        <?php } if (in_array( 'date', $salong[ 'video_metas']) ) { ?>
        <!--时间-->
        <span class="date"><?php echo svg_date(); ?><b><?php echo get_the_date(); ?></b></span>
        <?php } ?>
    </div>
    <div class="right">
        <?php if (in_array( 'view', $salong[ 'video_metas'])) { ?>
        <!--浏览量-->
        <span class="view"><?php echo svg_view(); ?><b><?php echo getPostViews(get_the_ID()); ?></b></span>
        <?php } if (in_array( 'comment', $salong[ 'video_metas']) && is_single()  && 'open'==$post->comment_status) { ?>
        <!--评论-->
        <span class="comment"><?php echo svg_comment(); ?><b><?php comments_popup_link('0', '1', '%'); ?></b></span>
        <?php } if (in_array( 'like', $salong[ 'video_metas'])) { ?>
        <!--点赞-->
        <span class="like"><?php echo svg_like(); ?><b><?php echo $post_like; ?></b></span>
        <?php } ?>
    </div>
</div>
