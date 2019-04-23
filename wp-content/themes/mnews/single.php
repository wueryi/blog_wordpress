<?php get_header();
global $salong;
setPostViews(get_the_ID());
$top_thumb = get_post_meta( $post->ID, 'top_thumb', true);
$switch_crumbs = $salong['switch_crumbs'];
$switch_comment = $salong[ 'switch_post_comment'];
?>
<main class="container">
    <section class="wrapper">
        <section class="content" id="scroll">
            <section class="content_left">
                <?php if($top_thumb){ ?>
                <section class="post_thumb">
                    <?php if($switch_crumbs){ echo salong_breadcrumbs(); } ?>
                    <img src="<?php echo $top_thumb; ?>" alt="<?php echo get_the_title(); ?>">
                </section>
                <?php } ?>
                <article class="entry">
                    <?php if($switch_crumbs && !$top_thumb){ echo salong_breadcrumbs(); } ?>
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <header class="post_header">
                        <h1>
                            <?php echo is_sticky() ? $salong['post_sticky_text'] : '';echo get_the_title(); ?>
                        </h1>
                        <?php get_template_part( 'content/info', 'post'); ?>
                    </header>
                    <div class="content_post">
                        <!-- 摘要 -->
                        <?php if (has_excerpt()) { ?>
                        <div class="excerpt">
                            <?php echo get_the_excerpt(); ?>
                        </div>
                        <?php } salong_ad('post_single'); ?>
                        <?php the_content(); ?>
                        <?php salong_link_pages(); ?>
                        <?php get_template_part( 'content/single', 'tag'); ?>
                        <?php if($salong[ 'switch_post_copyright']) { get_template_part( 'content/single', 'copyright'); } ?>
                    </div>
                    <?php endwhile; endif; ?>
                    <?php if($salong[ 'switch_post_share']) { get_template_part( 'content/share', 'btn'); } ?>
                    <!--文章点赞-->
                    <?php salong_post_like(); ?>
                    <!--上下篇文章-->
                    <?php if($salong[ 'switch_post_prevnext']) { get_template_part( 'content/single', 'prevnext'); } ?>
                </article>
                <!-- 相关文章 -->
                <?php if($salong[ 'switch_post_related']) { get_template_part( 'content/single', 'related'); } ?>
                <?php get_template_part( 'content/single', 'recommended'); ?>
                <?php if ($switch_comment) { comments_template(); } ?>
            </section>
            <!-- 博客边栏 -->
            <?php salong_sidebar(3); ?>
        </section>
    </section>
</main>
<?php get_footer(); ?>
