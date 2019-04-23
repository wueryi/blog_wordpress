<?php get_header();
global $salong;

setPostViews(get_the_ID());
$switch_crumbs  = $salong['switch_crumbs'];
$switch_comment = $salong[ 'switch_video_comment'];

?>
<main class="container">
    <section class="wrapper">
        <?php if($switch_crumbs){ echo salong_breadcrumbs(); } ?>
        <?php get_template_part( 'content/video', 'player'); ?>
        <section class="content" id="scroll">
            <section class="content_left">
                <article class="entry">
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <header class="post_header">
                        <h1>
                            <?php echo get_the_title(); ?>
                        </h1>
                        <?php get_template_part( 'content/info', 'video'); ?>
                    </header>
                    <div class="content_post">
                        <!-- 摘要 -->
                        <?php if (has_excerpt()) { ?>
                        <div class="excerpt">
                            <?php echo get_the_excerpt(); ?>
                        </div>
                        <?php } salong_ad('video_single'); ?>
                        <?php the_content(); ?>
                        <?php salong_link_pages(); ?>
                        <?php get_template_part( 'content/single', 'tag'); ?>
                    </div>
                    <?php endwhile; endif; ?>
                    <?php if($salong[ 'switch_video_share']) { get_template_part('content/share', 'btn'); } ?>
                    <!--文章点赞-->
                    <?php salong_post_like(); ?>
                    <!--上下篇文章-->
                    <?php if($salong[ 'switch_video_prevnext']) { get_template_part( 'content/single', 'prevnext'); } ?>
                </article>
                <!-- 相关文章 -->
                <?php if($salong[ 'switch_video_related']) { get_template_part( 'content/single', 'related'); } ?>
                <?php get_template_part( 'content/single', 'recommended'); ?>
                <?php if($switch_comment) { comments_template(); } ?>
            </section>
            <!-- 博客边栏 -->
            <?php salong_sidebar(6); ?>
        </section>
    </section>
</main>
<?php get_footer(); ?>
