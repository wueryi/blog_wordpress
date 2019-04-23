<?php global $salong,$post; ?>
<article class="post_main">
    <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
        <?php post_thumbnail(); ?>
    </a>
    <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>><?php if(is_sticky($post->ID)){ echo $salong['post_sticky_text'].'&nbsp;'; } the_title(); ?></a></h2>
    <?php if ( $salong[ 'blog_metas'] != 0 && in_array('category', $salong[ 'blog_metas'])) { ?>
    <!--分类-->
    <span class="is_category"><?php the_category(' '); ?></span>
    <?php } ?>
    <!-- 摘要 -->
    <div class="excerpt">
        <?php if (has_excerpt()) { ?>
        <?php echo wp_trim_words(get_the_excerpt(),76); ?>
        <?php } else{ echo wp_trim_words(get_the_content(),76); } ?>
    </div>
    <!-- 摘要end -->
    <?php get_template_part( 'content/info', 'post'); ?>
</article>
