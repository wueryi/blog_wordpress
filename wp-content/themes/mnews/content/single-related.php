<?php
global $salong,$post;

$taxonomy   = get_post_taxonomies();
$type       = get_post_type();
$title      = $salong[$type.'_related_title'];
$count      = $salong[$type.'_related_count'];

/*广告*/
salong_ad($type.'_related');

/*获取规则*/
if($salong[$type.'_related_rule'] == 'tag'){
    $rule = 1;
}else{
    $rule = 0;
}

$taxonomy_name  = $taxonomy[$rule];
$taxterms       = wp_get_object_terms( $post->ID, $taxonomy_name, array('fields' => 'ids') );

$args = array(
    'post_type'           => $type,
    'posts_per_page'      => $count,
    'orderby'             =>'rand',
    'post__not_in'        => array($post->ID),
    'ignore_sticky_posts' => 1,
    'tax_query'           => array(
        array(
            'taxonomy'  => $taxonomy_name,
            'field'     => 'id',
            'terms'     => $taxterms
        )
    )
);
$wp_query = new WP_Query( $args );
if ( $wp_query->have_posts() ) :

?>

<section class="related_posts <?php echo $post_type; ?>">
    <h4>
        <?php echo $title; ?>
    </h4>
    <ul class="layout_ul">
        <?php while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
        <li class="layout_li">
            <?php if($post_type == 'topic'){
            $topic_post_id = explode(',',get_post_meta( $post->ID, 'topic_post_id', 'true' ));
            foreach($topic_post_id as $post_count=>$post_id){
                $post_count++;
            }
            ?>
            <article class="topic_popup">
                <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
                    <?php post_thumbnail(); ?>
                    <h3><?php the_title(); ?></h3>
                </a>
                <span class="post_count"><?php echo sprintf(__('%s 篇','salong'),$post_count); ?></span>
            </article>
            <?php }else{ ?>
            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="imgeffect" <?php echo new_open_link(); ?>>
                <?php post_thumbnail(); ?>
                <h3><?php the_title(); ?></h3>
                <?php if($post_type == 'video'){
                    /*视频时长*/
                    $Duration = get_post_meta($post->ID, "time", true);
                    ?>
                    <span class="player"><?php echo svg_player(); ?></span>
                    <?php if (in_array( 'time', $salong[ 'video_metas'])) { ?>
                    <!--下载-->
                    <span class="time"><?php echo $Duration; ?></span>
                    <?php } ?>
                <?php } ?>
            </a>
            <?php } ?>
        </li>
        <?php endwhile; ?>
    </ul>
</section>
<?php endif; wp_reset_query(); ?>