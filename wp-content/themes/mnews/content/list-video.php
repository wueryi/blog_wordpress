<?php global $salong,$post;
$Duration = get_post_meta($post->ID, "time", true);
?>
<article class="video_main">
    <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
        <?php post_thumbnail(); ?>
        <span class="player"><?php echo svg_player(); ?></span>
        <?php if (in_array( 'time', $salong[ 'video_metas'])) { ?>
        <!--下载-->
        <span class="time"><?php echo $Duration; ?></span>
        <?php } ?>
    </a>
    <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>><?php the_title(); ?></a></h2>
    <?php if ($salong[ 'video_metas'] != 0 && in_array( 'category', $salong[ 'video_metas'])) { ?>
    <!--分类-->
    <span class="is_category"><?php the_terms( $post->ID, 'vcat','' );?></span>
    <?php } ?>
    <?php get_template_part( 'content/info', 'video'); ?>
</article>
