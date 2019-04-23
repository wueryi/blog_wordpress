<?php
global $salong;
$metas = $salong['kx_metas'];
$share = $salong['switch_kx_share'];
?>

    <?php if($metas || $share){ ?>
    <div class="postinfo">
        <?php if($metas){ ?>
        <div class="left">
            <?php if(in_array( 'time', $metas)) { ?>
            <span class="time"><?php echo svg_time(); ?><b><?php echo get_the_time(); ?></b></span>
            <?php }if(in_array( 'view', $metas)) { ?>
            <span class="view"><?php echo svg_view(); ?><b><?php echo getPostViews(get_the_ID()); ?></b></span>
            <?php } ?>
        </div>
        <?php }if($share) { ?>
        <div class="right">
            <?php get_template_part( 'content/share', 'btn'); ?>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
