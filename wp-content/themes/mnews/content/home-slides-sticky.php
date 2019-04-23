<?php global $salong; ?>
<section class="slick_sticky">
    <section class="slick">
        <?php $args=array( 'post_type'=> 'post','posts_per_page' => -1,'ignore_sticky_posts' => 1,'meta_key'=>'slide_recommend' );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : $count = 1; while ( $wp_query->have_posts() ) : $wp_query->the_post();$do_not_duplicate[] = $post->ID; ?>
        <div class="slick-slide<?php if($count==1){ ?> first<?php } $count++; ?>">
            <article class="home_sticky_main">
                <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
                    <?php no_post_thumbnail(); ?>
                    <div class="title">
                        <h2><?php the_title(); ?></h2>
                    </div>
                </a>
                <span class="is_category"><?php the_category(' ') ?></span>
            </article>
        </div>
        <?php endwhile;endif; ?>
        <?php wp_reset_query(); ?>
    </section>

    <script type="text/javascript">
        $(function() {
            $('.slick').slick({
                dots: true,
                <?php if($salong['slides_home_auto']){ ?>
                autoplay: true,
                <?php } if($salong['slides_home_effect']==0){ ?>
                fade: true <?php } ?>
            });
        });

    </script>
    
    <?php $args=array( 'post_type'=> 'post','posts_per_page' => 4, 'post__not_in' => $do_not_duplicate,'post__in'=> get_option('sticky_posts'),'ignore_sticky_posts'=>1);$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : ?>
    <section class="sticky_list">
        <ul class="layout_ul">
            <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
            <li class="layout_li">
                <?php get_template_part( 'content/home', 'sticky'); ?>
            </li>
            <?php endwhile; ?>
            <?php wp_reset_query(); ?>
        </ul>
    </section>
    <?php endif; ?>
</section>
