<?php global $salong,$post; ?>
<article class="post_grid">
    <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
        <?php post_thumbnail(); ?>
        <h2><?php if(is_sticky($post->ID)){ echo $salong['post_sticky_text'].'&nbsp;'; } the_title(); ?></h2>
    </a>
    <?php if (in_array( 'category', $salong[ 'blog_metas'])) { ?>
    <!--分类-->
    <span class="is_category"><?php the_category(' '); ?></span>
    <?php } ?>
    <?php get_template_part( 'content/grid', 'info'); ?>
</article>
