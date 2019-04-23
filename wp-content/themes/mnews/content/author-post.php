<?php global $salong,$wp_query;
$curauth      = $wp_query->get_queried_object();//当前用户
$user_id      = $curauth->ID;//当前用户 ID
$current_id   = wp_get_current_user()->ID;//当前登录用户 ID
$get_tab      = $_GET['tab'];//获取连接中 tab 后面的参数
$current_url  = get_author_posts_url($user_id).'?tab=edit';//当前页面链接
$admin_access = $salong['admin_access'];//允许访问后台不显示草稿和审核中的文章
if (is_user_logged_in()) {
    if (current_user_can( $admin_access ) || $user_id!=$current_id ) {
        $post_status = 'publish';
    }else{
        $post_status = 'any';
    }
}else{
    $post_status = 'publish';
}
?>
<section class="author_post_list">
    <ul class="ajaxposts">
        <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;$args=array( 'post_type'=> 'post','post_status'=>$post_status,'ignore_sticky_posts' => 1,'author'=>$user_id,'paged' => $paged );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
        $post_status = get_post_status( $get_post_id );//文章状态
        ?>
        <li class="ajaxpost<?php if($post_status == 'pending' || $post_status == 'draft'){ echo ' post_status'; } ?>">
            <?php get_template_part( 'content/list', 'post'); ?>
            <?php if(!current_user_can( $admin_access ) && is_user_logged_in()){ ?>
                <?php if($get_tab == 'post' && $salong['switch_edit_post'] && $post_status == 'draft'){ ?>
                <div class="edit_btn">
                    <a href="<?php echo $current_url.'&post_id='.$post->ID; ?>" class="edit" title="<?php _e('编辑草稿','salong'); ?>" <?php echo new_open_link(); ?>><?php echo svg_profile(); ?><span><?php _e('编辑草稿','salong'); ?></span></a>
                </div>
                <?php }else if($post_status == 'pending'){ ?>
                <div class="edit_btn">
                    <span><?php _e('审核中','salong'); ?></span>
                </div>
                <?php } ?>
            <?php } ?>
        </li>
        <?php endwhile; else: ?>
        <p class="warningbox">
            <?php _e( '非常抱歉，没有相关文章。', 'salong'); ?>
        </p>
        <?php endif; ?>
        <!-- 分页 -->
        <?php posts_pagination(); ?>
    </ul>
</section>
