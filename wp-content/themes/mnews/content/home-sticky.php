<?php global $salong; ?>
<article class="home_sticky_main">
    <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
        <?php post_thumbnail(); ?>
        <div class="title">
            <h2><?php the_title(); ?></h2>
        </div>
    </a>
    <span class="is_category"><?php the_category(' ') ?></span>
</article>
