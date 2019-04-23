<?php
global $salong;
add_theme_support( 'post-thumbnails' );
if( $salong['switch_lazyload']== true ){
        add_filter ('the_content', 'lazyload');
    function lazyload($content) {
        global $salong;
        $loadimg = $salong['post_loading']['url'];
        if(!is_feed()||!is_robots) {
            $content=preg_replace('/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i',"<img\$1data-original=\"\$2\" src=\"$loadimg\"\$3>",$content);
        }
        return $content;
    }
        function post_thumbnail($width = 460,$height = 280){
        global $post,$salong;
        $thumb = get_post_meta($post->ID, "thumb", true);
        if($thumb) {
            echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['thumb_loading']['url'].'&amp;h='.$height.'&amp;w='.$width.'" data-original="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$thumb.'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        } else if( has_post_thumbnail() ){
                        $timthumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
            echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['thumb_loading']['url'].'&amp;h='.$height.'&amp;w='.$width.'" data-original="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$timthumb[0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        } else {
            $content = $post->post_content;
            preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
            $n = count($strResult[1]);
            if($n > 0){
                                echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['thumb_loading']['url'].'&amp;h='.$height.'&amp;w='.$width.'" data-original="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$strResult[1][0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
            } else {
                                echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['thumb_loading']['url'].'&amp;h='.$height.'&amp;w='.$width.'" data-original="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['default_thumb']['url'].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
            }
        }
    }
}
else {
        function post_thumbnail($width = 460,$height = 280){
        global $post,$salong;
        $thumb = get_post_meta($post->ID, "thumb", true);
        if($thumb) {
            echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$thumb.'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        } else if( has_post_thumbnail() ){
                        $timthumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
            echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$timthumb[0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        } else {
            $content = $post->post_content;
            preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
            $n = count($strResult[1]);
            if($n > 0){
                                echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$strResult[1][0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
            } else {
                                echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['default_thumb']['url'].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
            }
        }
    }
}



function no_post_thumbnail($width = 690,$height = 420){
    global $post,$salong;
    $thumb = get_post_meta($post->ID, "thumb", true);
    if($thumb) {
        echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$thumb.'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
    } else if( has_post_thumbnail() ){
                $timthumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$timthumb[0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
    } else {
        $content = $post->post_content;
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
        $n = count($strResult[1]);
        if($n > 0){
                        echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$strResult[1][0].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        } else {
                        echo '<img class="thumb" src="'.get_bloginfo("template_url").'/includes/timthumb.php?src='.$salong['default_thumb']['url'].'&amp;h='.$height.'&amp;w='.$width.'" alt="'.$post->post_title.'" />';
        }
    }
}