<?php global $salong,$wp_query;
$curauth = $wp_query->get_queried_object();//当前用户
$user_id = $curauth->ID;//当前用户 ID
$follow = get_user_meta( $user_id, 'salong_following', true );

?>
<section class="user_list">
    <?php echo salong_followers_user($follow).salong_following_posts_shortcode(); ?>
</section>
