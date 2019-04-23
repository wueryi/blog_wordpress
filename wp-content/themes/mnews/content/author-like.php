<?php global $salong,$wp_query;
$curauth    = $wp_query->get_queried_object();//当前用户
$curauth_id = $curauth->ID;//当前用户 ID
$get_tab    = $_GET['tab'];//获取连接中 tab 后面的参数

$tab_name = explode('-',$get_tab);
if($tab_name[1]){
    $post_type = $tab_name[1];
    $ul = ' layout_ul';
    $li = ' layout_li';
}else{
    $post_type = 'post';
}

?>

<section class="author_subtabs">
    <ul class="tabs">
        <li<?php if( $get_tab=='like' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=like">
                <h5>
                    <?php _e('文章','salong'); ?>
                </h5>
                <?php if( salong_author_post_like_count('post',$curauth_id) > 0 ){ ?><?php } ?>
                <span class="count">（<?php echo salong_author_post_like_count('post',$curauth_id); ?>）</span>
            </a>
        </li>
        <?php if( salong_author_post_like_count('topic',$curauth_id) > 0 && $salong[ 'switch_topic_type']){ ?>
        <li<?php if( $get_tab=='like-topic' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=like-topic">
                <h5>
                    <?php _e('专题','salong'); ?>
                </h5>
                <span class="count">（<?php echo salong_author_post_like_count('topic',$curauth_id); ?>）</span>
            </a>
        </li>
        <?php } if( salong_author_post_like_count('download',$curauth_id) > 0 && $salong[ 'switch_download_type']){ ?>
        <li<?php if( $get_tab=='like-download' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=like-download">
                <h5>
                    <?php _e('下载','salong'); ?>
                </h5>
                <span class="count">（<?php echo salong_author_post_like_count('download',$curauth_id); ?>）</span>
            </a>
        </li>
        <?php } if( salong_author_post_like_count('video',$curauth_id) > 0 && $salong[ 'switch_video_type']){ ?>
        <li<?php if( $get_tab=='like-video' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=like-video">
                <h5>
                    <?php _e('视频','salong'); ?>
                </h5>
                <span class="count">（<?php echo salong_author_post_like_count('video',$curauth_id); ?>）</span>
            </a>
        </li>
        <?php } if( salong_author_post_like_count('product',$curauth_id) > 0 && class_exists('woocommerce')){ ?>
        <li<?php if( $get_tab=='like-product' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=like-product">
                <h5>
                    <?php _e('产品','salong'); ?>
                </h5>
                <span class="count">（<?php echo salong_author_post_like_count('product',$curauth_id); ?>）</span>
            </a>
        </li>
        <?php } ?>
    </ul>
    <section class="<?php echo $post_type; ?>_list">
        <ul class="ajaxposts<?php echo $ul; ?>">
            <?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;$args=array( 'post_type'=> $post_type,'ignore_sticky_posts' => 1,'meta_query' => array (array ('key' => 'salong_user_liked','value' => $curauth_id,'compare' => 'LIKE')),'paged' => $paged );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
            if($post_type == 'product'){
                wc_get_template_part( 'content', 'product' );
            }else{ ?>
            <li class="ajaxpost<?php echo $li; ?>">
                <?php get_template_part( 'content/list', $post_type); ?>
            </li>
            <?php } ?>
            <?php endwhile; else: ?>
            <p class="warningbox">
                <?php _e( '非常抱歉，没有相关内容。', 'salong'); ?>
            </p>
            <?php endif; ?>
            <!-- 分页 -->
            <?php posts_pagination(); ?>
        </ul>
    </section>

</section>
