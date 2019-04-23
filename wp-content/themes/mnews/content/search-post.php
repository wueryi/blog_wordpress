<?php get_header();global $salong; ?>
<main class="container">
    <?php if($salong['switch_crumbs']){ echo salong_breadcrumbs(); } ?>
    <div class="wrapper">
        <section class="content" id="scroll">
            <section class="content_left">
                <ul class="ajaxposts">
                    <?php $i = 0; if ( have_posts() ) : while ( have_posts() ) : the_post(); if($i==$salong['ad_post_flow_show_count']){ salong_ad('post_flow'); }$i++;//输出广告 ?>
                    <li class="ajaxpost">
                        <?php get_template_part( 'content/list', 'post'); ?>
                    </li>
                    <?php endwhile; else: ?>
                    <p class="warningbox">
                        <?php _e( '非常抱歉，没有相关文章。'); ?>
                    </p>
                    <?php endif; ?>
                    <!-- 分页 -->
                    <?php posts_pagination(); ?>
                </ul>
            </section>
            <!--边栏-->
            <?php salong_sidebar(2); ?>
        </section>
    </div>
</main>
<?php get_footer(); ?>
