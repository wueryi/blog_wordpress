<?php
/*
Template Name: 所有快讯
*/
get_header();
global $salong;

if($salong['switch_kx_field_sift'] && $_GET['field']){
    $meta_key = 'field';
    $meta_value = $_GET['field'];
}else{
    $meta_key = '';
    $meta_value = '';
}
$sift_field = explode(PHP_EOL,$salong['sift_field']);

?>
    <main class="container">
        <?php echo salong_crumb(); ?>
        <div class="wrapper">
            <section class="content" id="scroll">
                <section class="content_left">
                    <?php if($salong['switch_kx_field_sift']){ ?>
                    <div class="sift_kx">
                        <h5>
                            <?php echo $salong['salong_kx_sift_name']; ?>
                        </h5>
                        <ul>
                            <li<?php if(!$meta_value){ echo ' class="current"'; } ?>>
                                <a href="<?php echo get_page_link(); ?>">
                                    <?php _e('全部','salong'); ?>
                                </a>
                            </li>
                            <?php foreach($sift_field as $fields){ $field = explode('=', $fields ); ?>
                            <li<?php if($meta_value==$field[0]){ echo ' class="current"'; } ?>>
                                <a href="<?php echo get_page_link().'/?field='.$field[0]; ?>">
                                    <?php echo $field[1]; ?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                    <div class="kx_list">
                        <ul class="ajaxposts">
                            <?php $paged=( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;$args=array( 'post_type'=> 'kx','ignore_sticky_posts' => 1,'meta_key'=>$meta_key,'meta_value'=>$meta_value,'paged' => $paged );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); $link = get_post_meta( $post->ID, 'link', true );?>
                            <?php the_date('','<h4>','</h4>'); ?>
                            <li class="ajaxpost">
                                <article class="kx_main">
                                    <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>><?php the_title(); ?></a></h2>
                                    <!-- 摘要 -->
                                    <div class="excerpt">
                                        <?php if (has_excerpt()) { ?>
                                        <?php echo wp_trim_words(get_the_excerpt(),100); ?>
                                        <?php } else{ echo wp_trim_words(get_the_content(),100); }if($link){ ?>
                                        <a href="<?php echo $salong['switch_link_go'] ? external_link($link) : $link; ?>" target="_blank"><?php echo $salong['salong_kx_from_name']; ?></a>
                                        <?php } ?>
                                    </div>
                                    <?php salong_kx_info('list'); ?>
                                </article>
                            </li>
                            <?php endwhile; else: ?>
                            <p class="warningbox">
                                <?php _e( '非常抱歉，没有相关文章。'); ?>
                            </p>
                            <?php endif; ?>
                            <!-- 分页 -->
                            <?php posts_pagination(); ?>
                            <?php wp_reset_query(); ?>
                        </ul>
                    </div>
                </section>
                <!--边栏-->
                <?php salong_sidebar(9); ?>
            </section>
        </div>
    </main>
    <?php get_footer(); ?>
