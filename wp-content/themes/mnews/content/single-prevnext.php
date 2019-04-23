<?php global $salong;
$prev_post  = get_previous_post();
$prev_id    = $prev_post->ID;
$prev_title = $prev_post->post_title;
$next_post  = get_next_post();
$next_id    = $next_post->ID;
$next_title = $next_post->post_title;
?>
<section class="prevnext<?php if(!$prev_post){ echo ' noprev'; }else if(!$next_post){ echo ' nonext'; } ?>">
    <?php if($prev_post){ ?>
    <a href="<?php echo get_permalink($prev_id);?>" class="prev" title="<?php echo _e('上一篇：').$prev_title; ?>" <?php echo new_open_link(); ?>><?php echo _e('上一篇：').$prev_title; ?></a>
    <?php } ?>
    <?php if($next_post){ ?>
    <a href="<?php echo get_permalink($next_id);?>" class="next" title="<?php echo _e('下一篇：').$next_title; ?>" <?php echo new_open_link(); ?>><?php echo _e('下一篇：').$next_title; ?></a>
    <?php } ?>
</section>
