<?php global $salong,$post;
$topic_post_id = explode(',',get_post_meta( $post->ID, 'topic_post_id', 'true' ));
?>
<article class="topic_main">
    <section class="topic_post">
        <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
            <?php post_thumbnail(); ?>
        </a>
        <h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>><?php the_title(); ?></a></h2>
        <?php if ($salong[ 'topic_metas'] != 0 && in_array( 'category', $salong[ 'topic_metas'])) { ?>
        <!--分类-->
        <span class="is_category"><?php the_terms( $post->ID, 'tcat','' );?></span>
        <?php } ?>
        <!-- 摘要 -->
        <div class="excerpt">
            <?php if (has_excerpt()) { ?>
            <?php echo wp_trim_words(get_the_excerpt(),46); ?>
            <?php } else{ echo wp_trim_words(get_the_content(),46); } ?>
        </div>
        <!-- 摘要end -->
        <?php get_template_part( 'content/info', 'topic'); ?>
    </section>
    <!--专题文章-->
    <?php if($topic_post_id){ ?>
    <ul>
        <?php foreach($topic_post_id as $value=>$post_id){
        if($value == $salong['home_topic_post_count']){
            break;
        }
        $tp = get_post($post_id);
        $tp_id = $tp->ID;
        $post_categories = wp_get_post_categories( $post_id );
        ?>
        <li>
            <a href="<?php echo get_permalink($tp_id) ?>" title="<?php echo $tp->post_title; ?>" <?php echo new_open_link(); ?>><?php echo $tp->post_title; ?></a>
            <span>
            <?php foreach($post_categories as $key=>$category){
            $cat = get_category( $category );
            if($key==0){
                echo $cat->cat_name;
            }else{
                echo __('，','salong').$cat->cat_name;
            }
            } ?>
            </span>
        </li>
        <?php } ?>
    </ul>
    <?php } ?>
</article>
