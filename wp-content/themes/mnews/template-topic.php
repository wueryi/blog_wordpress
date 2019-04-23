<?php
/*
Template Name: 所有专题
*/ 
get_header();
global $salong;

if($salong['switch_topic_cat_order']){
    switch ($_GET['order']){
        case 'like' : $orderby = 'meta_value_num'; $meta_key = 'salong_post_like_count';
            break;
        case 'views' : $orderby = 'meta_value_num'; $meta_key = 'views';
            break;
        case 'title' : $orderby = 'title'; $meta_key = '';
            break;
        case 'comment' : $orderby = 'comment_count'; $meta_key = '';
            break;
        case 'rand' : $orderby = 'rand'; $meta_key = '';
            break;
        default : $orderby = 'post_date'; $meta_key = '';
    }
}else{
    $orderby  = 'post_date';
    $meta_key = '';
}

?>
<main class="container">
    <?php echo salong_crumb(); ?>
    <div class="wrapper">
        <?php salong_ad('topic_list'); if($salong['switch_topic_cat_order']){ get_template_part( 'content/sift', 'cat'); } ?>
        <section class="topic_all">
            <ul class="layout_ul ajaxposts">
                <?php $paged=( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;$args=array( 'post_type'=> 'topic','ignore_sticky_posts' => 1,'meta_key'=> $meta_key,'orderby' => $orderby,'paged' => $paged );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
                <li class="layout_li ajaxpost">
                    <?php get_template_part( 'content/list', 'topic'); ?>
                </li>
                <?php endwhile; else: ?>
                <p class="warningbox">
                    <?php _e( '非常抱歉，没有相关文章。'); ?>
                </p>
                <?php endif; ?>
                <!-- 分页 -->
                <?php posts_pagination(); ?>
            </ul>
        </section>
    </div>
</main>
<?php get_footer(); ?>
