<?php global $salong;
$user_id = get_the_author_meta('ID');
$user_name = get_the_author_meta('display_name');

$post_like_count = get_post_meta($post->ID, "salong_post_like_count", true);
if($post_like_count){
    $post_like = $post_like_count;
}else{
    $post_like = '0';
}

$topic_post_id = explode(',',get_post_meta( $post->ID, 'topic_post_id', 'true' ));
foreach($topic_post_id as $post_count=>$post_id){
    $post_count++;
}

if ($salong[ 'topic_metas'] == 0 )
    return;
?>

<div class="postinfo">
    <div class="left">
        <?php if (in_array( 'author', $salong[ 'topic_metas']) && !is_single()) { ?>
        <!--作者-->
        <span class="author"><a href="<?php echo get_author_posts_url($user_id); ?>" title="<?php echo $user_name; ?>"<?php echo new_open_link(); ?>><?php echo salong_get_avatar($user_id,$user_name); ?><?php echo $user_name; ?></a></span>
        <?php } if (in_array( 'date', $salong[ 'topic_metas'])) { ?>
        <!--时间-->
        <span class="date"><?php echo svg_date(); ?><b><?php echo get_the_date(); ?></b></span>
        <?php } ?>
    </div>
    <div class="right">
        <?php if (in_array( 'post_count', $salong[ 'topic_metas'])) { ?>
        <!--文章数量-->
        <span class="post" title="<?php _e('文章数量','salong'); ?>"><?php echo svg_post(); ?><b><?php echo $post_count; ?></b></span>
        <?php } if (in_array( 'view', $salong[ 'topic_metas'])) { ?>
        <!--浏览量-->
        <span class="view"><?php echo svg_view(); ?><b><?php echo getPostViews(get_the_ID()); ?></b></span>
        <?php } if ($salong['switch_follow_btn'] && in_array( 'like', $salong[ 'topic_metas'])) { ?>
        <!--点赞-->
        <span class="like"><?php echo svg_like(); ?><b><?php echo $post_like; ?></b></span>
        <?php } ?>
    </div>
</div>
