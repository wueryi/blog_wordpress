<?php global $salong;
$slides = $salong[ 'slides_home_list'];
if($slides){
?>

<section class="slick slides-custom">
    <?php foreach ($slides as $key=>$value){ ?>
    <div class="slick-slide<?php if($key==1){ ?> first<?php } ?>">
        <a href="<?php echo $value['url']; ?>" <?php echo new_open_link(); ?>>
           <img src="<?php echo $value['image']; ?>" alt="<?php echo $value['title']; ?>">
            <?php if ($value[ 'description']): ?>
            <div class="slick-con">
                <h2><?php echo $value['title']; ?></h2>
                <p>
                    <?php echo $value[ 'description']; ?>
                </p>
            </div>
            <?php endif; ?>
        </a>
    </div>
    <?php } ?>
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
<?php } ?>
<?php
$sticky_page  = $salong[ 'all_sticky_page'];
$sticky_count  = $salong[ 'home_sticky_count'];
?>
<?php $args=array( 'post_type'=> 'post','posts_per_page'=>$sticky_count,'post__in'=> get_option('sticky_posts'),'ignore_sticky_posts'=>1);$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() && $salong['switch_home_sticky'] ) : ?>
<section class="home_sticky">
    <!--标题-->
    <section class="home_title">
        <section class="title">
            <h3><?php echo $salong['home_sticky_title']; ?></h3>
        </section>
        <section class="button">
            <a href="<?php echo get_page_link($sticky_page); ?>" title="<?php _e( '查看更多', 'salong' ); ?>" <?php echo new_open_link(); ?>><?php echo _e('更多','salong').svg_more(); ?></a>
        </section>
    </section>
    <!--标题end-->
    <ul class="layout_ul">
        <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
        <li class="layout_li">
            <?php get_template_part( 'content/home', 'sticky'); ?>
        </li>
        <?php endwhile; ?>
    </ul>
</section>
<?php endif;wp_reset_query(); ?>