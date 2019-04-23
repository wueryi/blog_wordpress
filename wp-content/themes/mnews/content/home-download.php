<?php global $salong;

$args=array( 'post_type'=> 'download','ignore_sticky_posts' => 1,'posts_per_page'=>$salong['home_download_count'],'tax_query'=>array(array('taxonomy'=>'dcat','field'=>'id','terms'=>$salong['exclude_download_cat'],'operator'=>'NOT IN'),),);$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : ?>
<section class="download_list">
    <!--标题-->
    <section class="home_title">
        <section class="title">
            <h3>
                <?php echo $salong['home_download_title']; ?>
            </h3>
            <span><?php echo $salong['home_download_desc']; ?></span>
        </section>
        <section class="button">
            <a href="<?php echo get_page_link(get_page_id_from_template('template-download.php')); ?>" title="<?php _e( '查看更多', 'salong' ); ?>" <?php echo new_open_link(); ?>><?php echo _e('更多','salong').svg_more(); ?></a>
        </section>
    </section>
    <!--标题end-->
    <ul class="layout_ul">
        <?php while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
        <li class="layout_li">
            <?php get_template_part( 'content/list', 'download'); ?>
        </li>
        <?php endwhile; ?>
    </ul>
</section>
<?php endif; wp_reset_query(); ?>