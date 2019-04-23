<?php get_header();
global $salong;
setPostViews(get_the_ID());
?>
<main class="container">
    <article class="entry kx">
        <header class="post_header">
            <h1>
                <?php echo get_the_title(); ?>
            </h1>
            <?php salong_kx_info('widget'); ?>
        </header>
        <div class="content_post">
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; endif; ?>
        </div>
        <?php if($salong['switch_kx_share']){ get_template_part( 'content/share', 'btn'); } ?>
    </article>
</main>
<?php get_footer(); ?>
