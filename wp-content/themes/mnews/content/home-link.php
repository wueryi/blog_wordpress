<?php global $salong;
$home_link_count    = $salong[ 'home_link_count'];
$home_exclude_link  = $salong[ 'home_exclude_link'];
$home_link_orderby  = $salong[ 'home_link_orderby'];
$home_link_order    = $salong[ 'home_link_order'];
$home_link_url      = $salong[ 'home_link_url'];
$home_link_cat      = $salong[ 'home_link_cat'];
if($home_link_cat){
    $cat = implode(',',$home_link_cat);
}
?>

<section class="link_list">
    <!--标题-->
    <section class="home_title">
        <section class="title">
            <h3>
                <?php echo $salong['home_link_title']; ?>
            </h3>
        </section>
        <section class="button">
            <a href="<?php echo get_page_link($home_link_url); ?>" title="<?php _e( '查看更多', 'salong' ); ?>" <?php echo new_open_link(); ?>><?php echo _e('更多','salong').svg_more(); ?></a>
        </section>
    </section>
    <ul id="link-home">
        <?php wp_list_bookmarks('link_before=<span>&link_after=</span>&categorize=0&category='.$cat.'&title_li=0&show_images=0&limit='.$home_link_count.'&orderby='.$home_link_orderby. '&order='.$home_link_order. '&exclude='.$home_exclude_link. ''); ?>
        <?php if ($salong[ 'switch_home_link_icon']) { ?>
        <script>
            $("#link-home a").each(function(e) {
                $(this).prepend("<img src=https://f.ydr.me/" + this.href.replace(/^(http:\/\/[^\/]+).*$/, '$1') + ">");
            });

        </script>
        <?php } ?>
    </ul>
</section>
