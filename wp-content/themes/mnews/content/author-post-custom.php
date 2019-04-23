<?php global $salong,$wp_query;
$get_tab = $_GET['tab'];//获取连接中 tab 后面的参数
$curauth = $wp_query->get_queried_object();//当前用户
$user_id = $curauth->ID;//当前用户 ID
?>
<section class="<?php echo $get_tab; ?>_list">
    <ul class="layout_ul ajaxposts">
       <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;$args=array( 'post_type'=> $get_tab,'ignore_sticky_posts' => 1,'author'=>$user_id,'paged' => $paged );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
        <li class="layout_li ajaxpost">
            <?php get_template_part( 'content/list', $get_tab); ?>
        </li>
        <?php endwhile; else: ?>
        <p class="warningbox">
            <?php _e( '非常抱歉，没有相关内容。', 'salong'); ?>
        </p>
        <?php endif; ?>
        <!-- 分页 -->
        <?php posts_pagination(); ?>
    </ul>
</section>