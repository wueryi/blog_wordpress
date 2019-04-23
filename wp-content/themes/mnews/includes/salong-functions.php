<?php
global $salong;

function Salong_header_nav_fallback(){
  echo '<nav class="header_menu"><ul class="empty"><li><a href="'.get_option('home').'/wp-admin/nav-menus.php?action=locations">'.__('请在 "后台——外观——菜单" 添加导航菜单','salong').'</a></ul></li></nav>';
}
function Salong_footer_nav_fallback(){
  echo '<nav class="footer_menu"><ul class="empty"><li><a href="'.get_option('home').'/wp-admin/nav-menus.php?action=locations">'.__('请在 "后台——外观——菜单" 添加页脚菜单','salong').'</a></ul></li></nav>';
}


function salong_script(){
    global $salong,$post;
    wp_reset_query();
    wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '2017.03.18' );
    wp_enqueue_style( 'main', get_template_directory_uri().'/stylesheets/main.css', false, '1.0', false);

    wp_deregister_script( 'jquery' );
    wp_deregister_style( 'font-awesome' );
    wp_enqueue_script('jquery', get_template_directory_uri().'/js/jquery.min.js', false, '3.1.1', false);

    if($salong['switch_header_show_hide']){
        wp_enqueue_script('headroom', get_template_directory_uri().'/js/headroom.min.js', false, '0.9.4', false);
    }

    if(is_home()){
        wp_enqueue_script('slick', get_template_directory_uri().'/js/slick.min.js', false, '1.1', false);
    }

    if(!is_singular(array('post','video','download','product','kx'))){
        wp_enqueue_script('ias', get_template_directory_uri().'/js/jquery-ias.min.js', false, '2.2.2', true);
    }

    wp_enqueue_script('scrollchaser', get_template_directory_uri().'/js/jquery.scrollchaser.min.js', false, '2.2.2', true);

    if ($salong['switch_lazyload']) {
        wp_enqueue_script('lazyload', get_template_directory_uri().'/js/jquery.lazyload.min.js', false, '1.9.3', true);
    }

    if (in_array( 'gb2big5', $salong[ 'side_metas'])) {
        wp_enqueue_script('gb2big5', get_template_directory_uri().'/js/gb2big5.js', false, '1.0', true);
    }

    $get_tab = @$_GET['tab'];    if($get_tab == 'edit-profile' || $get_tab == 'edit' || $get_tab == 'edit-profile-extension'){
        wp_enqueue_media();
    }

    if($get_tab != 'contribute' && $get_tab != 'edit-profile' && $get_tab != 'edit' && $get_tab != 'message' && $get_tab != 'edit-profile-extension'){
        wp_deregister_script( 'mediaelement' );
    }

    if($get_tab == 'edit-profile-extension'){
        wp_enqueue_script('cityselect', get_template_directory_uri().'/js/cityselect.js', false, '1.0', false);
    }

    
    if($get_tab == 'message'){
        wp_enqueue_script( 'jquery-ui-autocomplete' );
    }

    wp_enqueue_script('custom', get_template_directory_uri().'/js/custom-min.js', false, '1.0', true);
    
        if ( !function_exists( 'salong_css_code' ) ) {
        function salong_css_code() {
            global $salong;
            $custom_css_code = $salong['css_code'];
            if ( !empty( $custom_css_code ) ) {
                $custom_css_trim =  preg_replace( '/\s+/', ' ', $custom_css_code );
                $custom_css_out = "<!-- Dynamic css -->\n<style type=\"text/css\">\n" . $custom_css_trim . "\n</style>";
                echo $custom_css_out;
            }
        }
    }
    add_action('wp_head', 'salong_css_code');

}

function salong_single(){
    global $salong,$post;

    if(is_singular()){
        
        if( has_shortcode( $post->post_content, 'ali') || get_post_meta( $post->ID, 'ali_id', true ) ) {
            wp_enqueue_script('aliplayer-js', 'https://g.alicdn.com/de/prismplayer/2.5.0/aliplayer-h5-min.js', false, '2.5.0', false);
            wp_enqueue_style( 'aliplayer-css', 'https://g.alicdn.com/de/prismplayer/2.5.0/skins/default/aliplayer-min.css', false, '2.5.0', false);
        }

        wp_enqueue_script('fancybox', get_template_directory_uri().'/js/jquery.fancybox.min.js', false, '3.0.6', true);
        wp_enqueue_style( 'fancybox', get_template_directory_uri().'/stylesheets/jquery.fancybox.min.css', false, '3.0.6', 'screen');

        if( has_shortcode( $post->post_content, 'sl_video') || get_post_meta( $post->ID, 'source', true ) ) {
            wp_enqueue_script('mediaelementplayer', get_template_directory_uri().'/js/mediaelement-and-player.min.js', false, '4.2.9', true);
            wp_enqueue_script('mediaelement-zh-cn', get_template_directory_uri().'/js/zh-cn.js', false, '4.2.9', true);
            wp_enqueue_script('mediaelement-demo', get_template_directory_uri().'/js/mediaelement-min.js', false, '4.2.9', true);
            wp_enqueue_style( 'mediaelementplayer', get_template_directory_uri().'/stylesheets/mediaelementplayer.min.css', false, '4.2.9', 'screen');
        }
        
        if( $salong[ 'share_metas'] && in_array( 'cover', $salong[ 'share_metas'])){
            wp_enqueue_script('cover', get_template_directory_uri().'/js/phpcover-min.js', false, '1.1', false);
        }
        
        if ($salong['switch_highlight']) {
            wp_enqueue_script('prettify', get_template_directory_uri().'/js/prettify.js', false, '1.0', false);
            wp_enqueue_style( 'prettify', get_template_directory_uri().'/stylesheets/prettify.css', false, '1.0', 'screen');
        }
    }

    if(is_single() || is_page_template('template-kx.php')){
        if(is_page_template('template-kx.php')){
            $type = 'kx';
        }else{
            $type = get_post_type();
        }
        $switch = $salong['switch_'.$type.'_share'];
        if($switch){
            wp_enqueue_script('qrcode-js', get_template_directory_uri().'/js/jquery.qrcode.min.js', false, '1.0', false);
        }
    }

}

function salong_breadcrumbs() {
    global $salong,$post;
    if($salong['switch_crumbs'] == 0 && !is_admin()){
        return;
    }
    $delimiter = '&nbsp;'.$salong['delimiter'].'&nbsp;';     $before = '<span class="current">';     $after = '</span>';     if ( !is_home() && !is_front_page() || is_paged() ) {
        global $post;
        echo '<article class="crumbs">';
        if(!is_singular(array('post','download','video'))){
            echo '<div class="wrapper">';
        }
        $homeLink = home_url();
        echo ' <a itemprop="breadcrumb" href="' . $homeLink . '">'.svg_home().__( '首页' , 'salong' ).'</a>'.$delimiter.'';
        if ( is_category() ) {             global $wp_query;
            $cat_obj    = $wp_query->get_queried_object();
            $thisId     = $cat_obj->term_id;
            $thisCat    = get_categories(array('include'=>$thisId,'taxonomy'=>'any'));
            $thisPCat   = isset($thisCat->parent);
            $parentCat  = get_categories(array('include'=>$thisPCat,'taxonomy'=>'any'));
            $page_id    = get_page_id_from_template('template-post.php');
            echo '<a itemprop="breadcrumb" href="'.get_permalink($page_id).'">'.get_the_title($page_id).'</a>'.$delimiter;
          if (isset($thisCat->parent) != 0){
            $cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
            echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
          }
          echo $before . '' . single_cat_title('', false) . '' . $after;
        }else if ( is_tax() ) {                         $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
            $parent = $term->parent;
            
            if(is_tax('tcat') || is_tax('ttag')){
                $page_id = get_page_id_from_template('template-topic.php');
            }else if(is_tax('dcat') || is_tax('dtag')){
                $page_id = get_page_id_from_template('template-download.php');
            }else if(is_tax('vcat') || is_tax('vtag')){
                $page_id = get_page_id_from_template('template-video.php');
            }else if(is_tax('product_cat') || is_tax('product_tag')){
                $page_id = wc_get_page_id( 'shop' );
            }
            echo '<a itemprop="breadcrumb" href="'.get_permalink($page_id).'">'.get_the_title($page_id).'</a>'.$delimiter;
            
            while ($parent):
            $parents[]  = $parent;
            $new_parent = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
            $parent     = $new_parent->parent;
            endwhile;
            if(!empty($parents)):
            $parents = array_reverse($parents);
            foreach ($parents as $parent):
            $item           = get_term_by( 'id', $parent, get_query_var( 'taxonomy' ));
            $post_type_name = get_post_type();
            $cat_name       = 'category';
            $url            = get_bloginfo('url').'/'.$post_type_name.'-'.$cat_name.'/'.$item->slug;
            echo '<a href="'.$url.'">'.$item->name.'</a>'.$delimiter;
            endforeach;
            endif;
                        echo $before.$term->name.$after;
        }else if ( is_day() ) {             echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>'.$delimiter.'';
            echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a>'.$delimiter.'';
            echo $before . get_the_time('d') . $after;
        } elseif ( is_month() ) {             echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>'.$delimiter.'';
            echo $before . get_the_time('F') . $after;
        } elseif ( is_year() ) {             echo $before . get_the_time('Y') . $after;
        } elseif ( is_single() && !is_attachment() ) {
            $post_type = get_post_type();
            if($post_type == 'topic'){
                $tax_name = 'tcat';
            }else if($post_type == 'download'){
                $tax_name = 'dcat';
            }else if($post_type == 'video'){
                $tax_name = 'vcat';
            }else{
                $tax_name = 'category';
            }
            if ( is_singular('product') ) {                 global $salong;
                echo '<a itemprop="breadcrumb" href="'.get_page_link(wc_get_page_id( 'shop' )).'">' .get_page(wc_get_page_id( 'shop' ))->post_title. '</a>'.$delimiter.'';
                echo the_terms( $post->ID, 'product_cat','' ).$delimiter;
            }else if ( is_singular($post_type) ) {                 global $salong;
                echo '<a itemprop="breadcrumb" href="'.get_page_link(get_page_id_from_template('template-'.$post_type.'.php')).'">' .get_page(get_page_id_from_template('template-'.$post_type.'.php'))->post_title. '</a>'.$delimiter;
                echo the_terms( $post->ID, $tax_name,'' ).$delimiter;
            }
            echo $before . __('正文','salong') . $after;
        } elseif ( is_attachment() ) {             $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            echo '<a itemprop="breadcrumb" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>'.$delimiter.'';
            echo $before . get_the_title() . $after;
        } else if ( is_page() && !$post->post_parent ) {             echo $before . get_the_title() . $after;
        } elseif ( is_page() && $post->post_parent ) {             $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo $crumb . ''.$delimiter.'';
            echo $before . get_the_title() . $after;
        } elseif ( is_search() ) {             echo $before ;
            printf(__( '%s 的搜索结果' , 'salong' ),  get_search_query() );
            echo  $after;
        } elseif ( is_tag() ) {             echo $before ;
            printf(__( '%s 的标签存档' , 'salong' ), single_tag_title( '', false ) );
            echo  $after;
        } elseif ( is_author() ) {             global $author;
            $userdata = get_userdata($author);
            echo $before ;
            printf(__( '%s 的个人中心' , 'salong' ),  $userdata->display_name );
            echo  $after;
        } elseif ( is_404() ) {             echo $before;
            __( '404公益页面' , 'salong' );
            echo  $after;
        }else if (class_exists('woocommerce')){
            if(is_shop()){
                echo $before . woocommerce_page_title() . $after;
            }
        }
        if ( get_query_var('paged') ) {             if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
                echo sprintf(__( '（第%s页）' , 'salong' ), get_query_var('paged') );
        }
        if(is_page_template('template-post.php')){
            echo sprintf(__('<span class="count">共<b>%s</b>篇</span>','salong'),wp_count_posts('post')->publish);
        }else if(is_page_template('template-topic.php')){
            echo sprintf(__('<span class="count">共<b>%s</b>篇</span>','salong'),wp_count_posts('topic')->publish);
        }else if(is_page_template('template-download.php')){
            echo sprintf(__('<span class="count">共<b>%s</b>篇</span>','salong'),wp_count_posts('download')->publish);
        }else if(is_page_template('template-video.php')){
            echo sprintf(__('<span class="count">共<b>%s</b>篇</span>','salong'),wp_count_posts('video')->publish);
        }else if(is_page_template('template-kx.php')){
            echo sprintf(__('<span class="count">共<b>%s</b>篇</span>','salong'),wp_count_posts('kx')->publish);
        }else if(is_singular('topic')){
            global $post,$salong;
            $topic_post_id = explode(',',get_post_meta( $post->ID, 'topic_post_id', 'true' ));
            foreach($topic_post_id as $post_count=>$post_id){
                $post_count++;
            }
            echo sprintf(__('<span class="count">共<b>%s</b>篇</span>','salong'),$post_count);
        }else if(is_search()){
            global $wp_query;
            $get_tab = $_GET['post_type'];
            echo sprintf(__('<span class="count">共<b>%s</b>篇</span>','salong'),$wp_query->found_posts);
        }else if(is_category() || is_tax() || is_tag()){
            $cat      = get_queried_object();
            $cat_id   = $cat->term_id;
            $taxonomy = $cat->taxonomy;
            echo sprintf(__('<span class="count">共<b>%s</b>篇</span>','salong'),salong_category_post_count($cat_id,$taxonomy));
        }else if (class_exists('woocommerce')){
            if(is_shop() && !is_search()){
                echo sprintf(__('<span class="count">共<b>%s</b>篇</span>','salong'),wp_count_posts('product')->publish);
            }
        }if(!is_singular(array('post','download','video'))){
            echo '</div>';
        }
        echo '</article>';
    }
}


function home_ad($id){
    global $salong;
    $home_ad = $salong['home_ad'.$id];
    if($home_ad) {
        echo '<section class="ad">';
        echo $home_ad;
        echo '</section>';
    }
}

function salong_ad($id){
    global $salong;
    $ad = $salong['ad_'.$id];
    if($ad) {
        echo '<section class="ad">';
        echo $ad;
        echo '</section>';
    }
}


include_once get_template_directory() . '/includes/aliyun-php-sdk/aliyun-php-sdk-core/Config.php';
use vod\Request\V20170321 as vod;

$regionId           = "cn-shanghai";
$access_key_id      = $salong['access_key_id'];
$access_key_secret  = $salong['access_key_secret'];
$profile            = DefaultProfile::getProfile($regionId, $access_key_id, $access_key_secret);

$client_ali = new DefaultAcsClient($profile);
  
function salong_GetVideoPlayAuth($client_ali, $regionId, $ali_id) {
   $request = new vod\GetVideoPlayAuthRequest();
   $request->setAcceptFormat('JSON');
   $request->setRegionId($regionId);
   $request->setVideoId($ali_id);   $response = $client_ali->getAcsResponse($request);
   return $response;
}


function salong_ali_video($type,$ali_id) {
    global $salong,$client_ali,$regionId;

    $PlayAuth_object = salong_GetVideoPlayAuth($client_ali, $regionId, $ali_id);
    
    $PlayAuth_array  = get_object_vars($PlayAuth_object);
    $VideoMeta_array = get_object_vars($PlayAuth_array['VideoMeta']);
    
    if($type == 'CoverURL'){
        return $VideoMeta_array['CoverURL'];
    }else if($type == 'Duration'){
        return $VideoMeta_array['Duration'];
    }else if($type == 'PlayAuth'){
        return $PlayAuth_array['PlayAuth'];
    }
}

function getSslPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function get_youku_video($type){
    global $post,$salong;
    $client_youku = $salong['client_youku'];
    $youku_id = get_post_meta($post->ID, "youku_id", true);
    
        $link = "https://api.youku.com/videos/show.json?video_id=$youku_id&client_id={$client_youku}";
    $cexecute= getSslPage($link);
    if ($cexecute) {
                $result = json_decode($cexecute,true);
        $json = $result['data'][0];
                if($type == 'thumb'){
            return $result['bigThumbnail'];
        }else if($type == 'duration'){
            return $result['duration'];
        }
    }
}

if($salong['switch_comment_rate']){
    
    add_action('comment_post','comment_ratings');

    function comment_ratings($comment_id) {
        add_comment_meta($comment_id, 'rate', $_POST['rate']);
    }

    function movie_grade($grade) {
        $items = '';
        switch ($grade) {
            case '0':
                $alt = __('无 - 0 星','salong');
                break;
            case '1':
                $alt = __('非常糟糕 - 1 星','salong');
                break;
            case '2':
                $alt = __('糟糕 - 2 星','salong');
                break;
            case '3':
                $alt = __('好 - 3 星','salong');
                break;
            case '4':
                $alt = __('非常好 - 4 星','salong');
                break;
            case '5':
                $alt = __('优秀 - 5 星','salong');
                break;
            default:
                $alt = __('没有评分','salong');
                break;
        }

        $items .= '<div class="rate" title="'.$alt.'">';
        if (!isset($grade) || $grade == '')
            $items .= $alt;
        else {
            $items .= __('评分：','salong');
            for ($i = 1; $i < 6; $i++) {
                if ($grade >= $i)
                    $items .= svg_star_full();
                else
                    $items .= svg_star_line();
            }
        }
        $items .= '</div>';
        return $items;
    }


    function get_average_ratings($id) {
        $comment_array = get_approved_comments($id);
        $count = 1;

        if ($comment_array) {
            $i = 0;
            $total = 0;
            foreach($comment_array as $comment){
                $rate = get_comment_meta($comment->comment_ID, 'rate');
                if(isset($rate[0]) && $rate[0] !== '') {
                    $i++;
                    $total += $rate[0];
                }
            }

            if($i == 0)
                return false;
            else
                return round($total/$i);
        } else {
            return false;
        }
    }
}



function salong_user_main($user,$class,$key){
    global $salong,$wp_query,$post;
    $user_id          = $user->ID;
    $user_name        = get_the_author_meta('display_name',$user_id);    $user_description = get_the_author_meta('user_description',$user_id);
        if($salong['switch_follow_btn']){
        $following = salong_get_following_count($user_id);        $follower  = salong_get_follower_count($user_id);    }

        $items .= '<li class="layout_li'.$class.'">';
    $items .= '<article class="user_main">';
    if($key!=null){
        $items .= '<span class="num">'.$key.'</span>';
    }
    $items .= '<div class="img" title="'.$user_description.'">'.salong_get_avatar($user_id,$user_name).'</div>';
    $items .= '<a href="'.get_author_posts_url($user_id).'" title="'.$user_description.'" class="title" title="'.$user_description.'"><h3>'.$user_name.salong_add_v($user_id).'</h3><span>'.user_role($user_id).'</span></a>';
    if($salong['switch_follow_btn']){
        $items .= salong_get_follow_unfollow_links($user_id,$allow = 0);
    }
    $items .= '<div class="post">';
    $items .= '<span>'.svg_post().'<b>'.salong_author_post_count($user_id,'post').'</b></span>';
    $items .= '<span>'.svg_view().'<b>'.salong_all_post_field_count($user_id,'views').'</b></span>';
    $items .= '<span>'.svg_like().'<b>'.salong_all_post_field_count($user_id,'salong_post_like_count').'</b></span>';
    $items .= '</div>';
    $items .= '</article>';
    $items .= '</li>';
    return $items;
}


function salong_all_user(){
    global $salong;
    $number       = $salong[ 'all_user_count'];
    $blog_id      = get_current_blog_id();
    
        $recommend_user = $salong['recommend_user'];
    if(!empty($recommend_user)){
        $user_r = implode(',',$recommend_user);
    }
    
        $paged        = ( get_query_var( 'paged')) ? get_query_var( 'paged') : 1;
    $offset       = ( $paged - 1) * $number;
    $current_page = max(1, get_query_var('paged'));
    $users        = get_users( 'blog_id='.$blog_id.'&exclude='.$user_r);
    $query        = get_users( 'offset='.$offset. '&number='.$number.'&exclude='.$user_r.'&orderby=post_count&order=DESC');
    $total_users  = count($users);
    $total_pages  = ceil($total_users / $number);
    $items .= '<section class="all_user_list">';
    $items .= '<ul class="layout_ul">';
    
        if(!empty($recommend_user)){
        $query_r = get_users( 'include='.$user_r.'&orderby=post_count&order=DESC');
        foreach ($query_r as $value=>$user) {
            $class = ' recommend';
            $key = $value+1;
            $items .= salong_user_main($user,$class,$key);
        }
        $items .= '<hr>';
    }
    
    foreach ($query as $user) {
        $class = ' other';
        $key = '';
        $items .= salong_user_main($user,$class,$key);
    }
    $items .= '</ul>';
    $items .= '</section>';
    $items .= '<div class="pagination">';
    $items .= paginate_links(array(
        'base'      => get_pagenum_link(1) . '%_%',
        'format'    => '/page/%#%/',
        'current'   => $current_page,
        'total'     => $total_pages,
        'end_size'  => 2,
        'mid-size'  => 3
    ));
    $items .= '</div>';
    return $items;
}
add_shortcode('all_user', 'salong_all_user');

function salong_edit_post(){
    global $salong,$post,$current_user;
    
    $get_post_id              = $_GET['post_id'];
    $tg_max                   = $salong['post_tg_max'];
    $tg_min                   = $salong['post_tg_min'];
    $direct_contribute_access = $salong['direct_contribute_access'];
    
    $edit_url  = get_author_posts_url($user_id).'?tab=edit';    $post_url  = get_author_posts_url($user_id).'?tab=post';    
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty($_POST['post_id']) && ! empty($_POST['post_title']) && isset($_POST['update_post_nonce']) && isset($_POST['post_content']) ){
        $post_id   = $_POST['post_id'];
        $post_type = get_post_type($post_id);
        $capability = ( 'page' == $post_type ) ? 'edit_page' : 'edit_post';
        
        $thumb      = isset( $_POST['tougao_thumb'] ) ? trim(htmlspecialchars($_POST['tougao_thumb'], ENT_QUOTES)) : '';
        $from_name  = isset( $_POST['post_from_name'] ) ? trim(htmlspecialchars($_POST['post_from_name'], ENT_QUOTES)) : '';
        $from_link  = isset( $_POST['post_from_link'] ) ? trim(htmlspecialchars($_POST['post_from_link'], ENT_QUOTES)) : '';
        $title      = isset( $_POST['post_title'] ) ? trim(htmlspecialchars($_POST['post_title'], ENT_QUOTES)) : '';
        $category   = isset( $_POST['term_id'] ) ? (int)$_POST['term_id'] : 0;
        $content    = isset( $_POST['post_content'] ) ? $_POST['post_content'] : '';
        $status     = isset( $_POST['post_status'] ) ? $_POST['post_status'] : '';
        
        $post = array(
            'ID'            => $post_id,
            'post_content'  => $content,
            'post_title'    => $title,
            'post_status'   => $status,
            'post_category' => array($category)
        );
                if ( empty($title) || mb_strlen($title) > 100 ) {
            echo '<span class="warningbox">'.sprintf(__('标题必须填写，且长度不得超过100字，重新输入或者<a href="%s">点击刷新</a>','salong'),$edit_url).'</span>';
        }else if ( empty($content)) {
            echo '<span class="warningbox">'.sprintf(__('内容必须填写，重新输入或者<a href="%s">点击刷新</a>','salong'),$edit_url).'</span>';
        }else if ( mb_strlen($content) > $tg_max ) {
            echo '<span class="warningbox">'.sprintf(__('内容长度不得超过%s字，重新输入或者<a href="%s">点击刷新</a>','salong'),$tg_max,$edit_url).'</span>';
        }else if ( mb_strlen($content) < $tg_min) {
            echo '<span class="warningbox">'.sprintf(__('内容长度不得少于%s字，重新输入或者<a href="%s">点击刷新</a>','salong'),$tg_min,$edit_url).'</span>';
        }else if ( $_POST['are_you_human'] == '' ) {
            echo '<span class="warningbox">'.sprintf(__('请输入本站名称：%s','salong'),get_option('blogname')).'</span>';
        }else if ( $_POST['are_you_human'] !== get_bloginfo( 'name' ) ) {
            echo '<span class="warningbox">'.sprintf(__('本站名称输入错误，正确名称为：%s','salong'),get_option('blogname')).'</span>';
        }else if ( current_user_can($capability, $post_id) && wp_verify_nonce( $_POST['update_post_nonce'], 'update_post_'. $post_id ) ){
            
            wp_update_post($post);
            
            if($from_name){
                add_post_meta($status, 'from_name', $from_name, TRUE);
            }
            if($from_link){
                add_post_meta($status, 'from_link', $from_link, TRUE);
            }
            
            if($thumb){
                add_post_meta($posts, 'thumb', $thumb, TRUE);
            }
            if($status == 'draft'){
                echo '<span class="successbox">'.__('草稿更新成功！','salong').'</span>';
            }

        }else{
           echo '<span class="errorbox">'.__('你不能修改此文章！','salong').'</span>';
        }
    }
    
    $post_status = get_post_status( $get_post_id );    if($post_status == 'pending'){
        echo '<span class="successbox">'.__('文章更新成功，已提交审核！','salong').'</span>';
                $email_content = $salong['contribute_email_pending'];
        wp_mail(get_option('admin_email'),get_option('blogname').__('用户投稿','salong'),$email_content);
        return;
    }else if($post_status == 'publish'){
        echo '<span class="successbox">'.__('文章更新成功并已发布！','salong').'</span>';
                $email_content = $salong['contribute_email_publish'];
        wp_mail(get_option('admin_email'),get_option('blogname').__('用户投稿','salong'),$email_content);
        return;
    }
    
    $args=array( 'post_type'=> 'post','ignore_sticky_posts' => 1,'p'=>$get_post_id );$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
    
    echo '<form id="post" class="contribute_form" method="post" enctype="multipart/form-data">';
    echo '<input type="hidden" name="post_id" value="'.get_the_ID().'" />';
    wp_nonce_field( 'update_post_'. get_the_ID(), 'update_post_nonce' );
    echo '<p><label for="post_title"><b class="required">*</b>'.__('文章标题','salong').'</label><input type="text" value="'.$post->post_title.'" id="post_title" name="post_title" required /><span>'.sprintf(__('标题长度不得超过%s字。','salong'),100).'</span></p>';
    echo '<p><label for="post_category"><b class="required">*</b>'.__('文章分类','salong').'</label>';
        $contribute_cat_arr = $salong[ 'contribute_cat'];
    if($contribute_cat_arr){
        $contribute_cat = implode(',',$salong[ 'contribute_cat']);
    }else{
        $contribute_cat = '';
    }
    $from_name    = get_post_meta( $get_post_id, 'from_name', true );
    $from_link    = get_post_meta( $get_post_id, 'from_link', true );
    $thumb        = get_post_meta( $get_post_id, 'thumb', true );
    $categories   = get_the_category();
    $category_id  = $categories[0]->cat_ID;
    $post_status  = get_post_status( $get_post_id );
    
    wp_dropdown_categories('include='.$contribute_cat.'&selected='.$category_id.'&hide_empty=0&id=post_category&show_count=1&hierarchical=1&taxonomy=category&name=term_id&id=term_id');
    echo '</p>';
    echo '<p>'.wp_editor(wpautop($post->post_content), 'post_content', array('media_buttons'=>true, 'quicktags'=>true, 'editor_class'=>'form-control' ) ).'<span>'.sprintf(__('内容必须填写，且长度不得超过 %s 字，不得少于 %s 字。','salong'),$tg_max,$tg_min).'</span></p>';
    if (current_user_can( 'edit_posts' ) || $salong['switch_contributor_uploads']) {
        echo '<div class="salong_field_main"><label for="tougao_thumb">'.__('缩略图','salong').'</label><div class="salong_field_area"><div class="salong_file_button';
        if($thumb){
            echo ' active';
        }
        echo '"><a href="#" class="salong_upload_button"><b>+</b><span>'.__('更改图片','salong').'</span></a><div class="salong_file_preview">';
        if($thumb){
            echo '<img src="'.$thumb.'">';
        }
        echo '</div><div class="bg"></div><input class="salong_field_upload" type="hidden" value="'.$thumb.'" id="tougao_thumb" name="tougao_thumb" /></div><div class="salong_file_hint"><p>'.__('自定义缩略图，建议比例：460*280。','salong').'</p><span>'.__('支持≤3MB，JPG，JEPG，PNG格式文件','salong').'</span></div></div></div><hr>';
    }
    echo '<p><label for="post_from_name">'.__('文章来源网站名称','salong').'</label><input type="text" value="'.$from_name.'" id="post_from_name" name="post_from_name" /></p>';
    echo '<p><label for="post_from_link">'.__('文章来源网站链接','salong').'</label><input type="text" value="'.$from_link.'" id="post_from_link" name="post_from_link" /></p><hr>';
    echo '<p><label for="are_you_human"><b class="required">*</b>'.sprintf(__('本站名称（请输入：%s）','salong'),get_option('blogname')).'<br/><input id="are_you_human" class="input" type="text" value="" name="are_you_human" required /></label></p>';
    echo '<div class="status_btn">';
    echo '<select name="post_status">';
    if ( salong_is_administrator() || current_user_can( $direct_contribute_access ) || $current_user->roles[0] == 'vip' ) {
        echo '<option value="publish">'.__('直接发布','salong').'</option>';
    }else{
        echo '<option value="pending">'.__('提交审核','salong').'</option>';
    }
    echo '<option value="draft" selected="selected">'.__('保存草稿','um').'</option></select>';
    echo '<p><input type="submit" class="submit" value="'.__('更新','salong').'" /></p>';
    echo '</div>';
    echo '</form>';
    endwhile;endif;wp_reset_query();
}
add_shortcode('edit_post', 'salong_edit_post');

function salong_download_code(){
    global $salong,$post,$current_user;
    $download_info  = get_post_meta( $post->ID, 'download_info', true );
    $download_link  = get_post_meta( $post->ID, 'download_link', true );
    $link_home      = get_post_meta( $post->ID, 'link_home', true );
    $product_id     = get_post_meta($post->ID, "product_id", true);
    $access_level   = $salong['vip_access'];

    $items .= '<section class="download_code">';
    $items .= '<h3>'.__('文件信息：','salong').'</h3>';
    $items .= '<div class="download_info">';
    $items .= '<ol>';
    if($link_home){
        $items .= '<li>';
        $items .= '<span>';
        $items .= __('官方网站','salong');
        $items .= '</span>';
        $items .= '<a href="'.$link_home.'" target="_blank" rel="nofollow external">'.$link_home.'</a>';;
        $items .= '</li>';
    }
    if($download_info){
        foreach($download_info as $info){
            $items .= '<li>';
            $items .= '<span>';
            $items .= $info['info_title'];
            $items .= '</span>';
            $items .= $info['info_value'];
            $items .= '</li>';
        }
    }
    $items .= '</ol>';
    $items .= '</div>';
    if($download_link){
        if ( salong_is_administrator() || empty($product_id) || current_user_can( $access_level ) || wc_customer_bought_product( $current_user->email, $current_user->ID, $product_id ) || $current_user->roles[0] == 'vip' ) {
            $items .= '<div class="download_link">';
            $items .= '<h4>'.__('下载地址：','salong').'</h4>';
            $items .= '<ol>';
            foreach($download_link as $link){
                if ($salong['switch_link_go']) {
                    $url = external_link($link['link_value'].'|'.$post->ID);
                } else {
                    $url = esc_url($link['link_value']);
                }
                $items .= '<li>';
                $items .= '<a href="'.$url.'" target="_blank" rel="nofollow external">'.$link['link_title'].'</a>';
                $items .= '</li>';
            }
            $items .= '</ol>';
            $items .= '</div>';
        }else{
            if ( is_user_logged_in() ) {
                $items .= sprintf('<div class="warningbox">'.__('当前下载链接只有购买了&nbsp;【%s】&nbsp;产品的用户才能查看，点击&nbsp;<a href="%s" target="_blank" title="前往购买">前往购买</a>。','salong').'</div>',get_the_title($product_id),get_permalink($product_id));
            }else{
                if ( class_exists( 'XH_Social' ) ){
                    $login_url = '#login';
                }else{
                    $login_url  = wp_login_url($_SERVER['REQUEST_URI']);                }
                $items .= sprintf('<div class="warningbox">'.__('当前下载链接只有购买了&nbsp;【%s】&nbsp;产品的用户才能查看，点击&nbsp;<a href="%s" target="_blank" title="前往购买">前往购买</a>，如果您已经购买，<a href="%s" title="">请登录</a>。','salong').'</div>',get_the_title($product_id),get_permalink($product_id),$login_url);
            }
        }
    }
    $items .= '</section>';
    return $items;
}
add_shortcode('download_code' , 'salong_download_code' );

function salong_map(){
    require_once get_template_directory() . '/content/contact-map.php';
}
add_shortcode('map', 'salong_map');





function unregister_default_widgets() {
    unregister_widget("Akismet_Widget");    unregister_widget("WP_Widget_Calendar");    unregister_widget("WP_Widget_Meta");    unregister_widget("WP_Widget_Search");    unregister_widget("WP_Widget_Text");    unregister_widget("WP_Widget_Recent_Posts");    unregister_widget("WP_Widget_Recent_Comments");    unregister_widget("WP_Widget_RSS");    unregister_widget("WP_Widget_Tag_Cloud");    unregister_widget("WP_Nav_Menu_Widget");    unregister_widget("WP_Widget_Media_Gallery");}
add_action("widgets_init", "unregister_default_widgets", 11);


if($salong['switch_post_type_slug']) {
    global $salong,$dwqa_general_settings;
    $posttypes = array(
        'kx'            => $salong['kx_post_slug'],
        'video'         => $salong['video_post_slug'],
        'download'      => $salong['download_post_slug'],
        'product'       => $salong['product_post_slug'],
        'dwqa-question' => $dwqa_general_settings['question-rewrite']
    );
    add_filter('post_type_link', 'custom_salong_link', 1, 3);
    function custom_salong_link( $link, $post = 0 ){
        global $posttypes;
        if ( in_array( $post->post_type,array_keys($posttypes) ) ){
            global $salong;
            if ($salong[ 'post_type_slug']=='Postname'){$postlink='post_name';}else{$postlink='ID';}
            return home_url( $posttypes[$post->post_type].'/' . $post->$postlink.'.html' );
        } else {
            return $link;
        }
    }
    add_action( 'init', 'custom_salong_rewrites_init' );
    function custom_salong_rewrites_init(){
        global $posttypes;
        foreach( $posttypes as $k => $v ) {
            global $salong;
            if ($salong[ 'post_type_slug']=='Postname'):
            add_rewrite_rule(
                $v.'/([一-龥a-zA-Z0-9_-]+)?.html([\s\S]*)?$',
                'index.php?post_type='.$k.'&name=$matches[1]',
                'top' );
            else:
            add_rewrite_rule(
                $v.'/([0-9]+)?.html$',
                'index.php?post_type='.$k.'&p=$matches[1]',
                'top' );
            endif;
        }
    }
}

add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
    return is_array($var) ? array_intersect($var, array(
        'current-menu-item',
        'current-post-ancestor',
        'current-menu-ancestor',
        'current-menu-parent',
        'menu-item-has-children'
    )) : '';
}

function salong_wptheme_title() {
    global $salong,$post,$wp_query;
    $term_id        = get_queried_object()->term_id;    $delimiter      = $salong['delimiter'];
    $cate_seo_title = get_term_meta($term_id,'seo_title',true);
    $post_seo_title = get_post_meta( $post->ID, 'seo_title', 'true');
    $paged_list     = get_query_var('paged');
    $paged_single   = $wp_query->get('page');
    if (is_home()) {
        $item .= get_bloginfo('name').$delimiter.get_bloginfo('description');
    }else if ( is_category() || is_tax() ) {
        $item .= single_cat_title();
        if($cate_seo_title){
            $item .= $delimiter.$cate_seo_title;
        }
        $item .= $delimiter.get_bloginfo('name');
    }else if( class_exists('woocommerce') && is_shop() ){
        $shop_id = wc_get_page_id( 'shop' );
        $shop_seotitle = get_post_meta($shop_id, "seo_title", true);
        $item .= woocommerce_page_title();
        if($shop_seotitle){
            $item .= $delimiter.$shop_seotitle;
        }
        $item .= $delimiter.get_bloginfo('name');
        if($paged_single){
            $item .= $delimiter.sprintf(__('(第%s页)' , 'salong' ), $paged_single );
        }
    }else if(is_singular()){
        $item .= is_sticky() ? $salong['post_sticky_text'].'&nbsp;' : '';
        $item .= get_the_title();
        if($post_seo_title){
            $item .= $delimiter.$post_seo_title;
        }
        $item .= $delimiter.get_bloginfo('name');
        if($paged_single && is_single()){
            $item .= $delimiter.sprintf(__('(第%s页)' , 'salong' ), $paged_single );
        }
    }else if(is_author()){
        global $salong,$wp_query;
        $curauth = $wp_query->get_queried_object();        $user_id = $curauth->ID;        $user_name = $curauth->display_name;
        $get_tab = $_GET['tab'];        $item .= $user_name;
        $item .= $delimiter;
        if($get_tab == 'post'){
            $item .= __('文章','salong');
        }else if($get_tab == 'topic'){
            $item .= __('专题','salong');
        }else if($get_tab == 'download'){
            $item .= __('下载','salong');
        }else if($get_tab == 'like'){
            $item .= __('点赞的文章','salong');
        }else if($get_tab == 'like-topic'){
            $item .= __('点赞的专题','salong');
        }else if($get_tab == 'like-download'){
            $item .= __('点赞的下载','salong');
        }else if($get_tab == 'message'){
            $item .= __('发送私信','salong');
        }else if($get_tab == 'message-inbox'){
            $item .= __('收件箱','salong');
        }else if($get_tab == 'message-outbox'){
            $item .= __('发件箱','salong');
        }else if($get_tab == 'comment'){
            $item .= __('评论','salong');
        }else if($get_tab == 'following'){
            $item .= __('关注','salong');
        }else if($get_tab == 'follower'){
            $item .= __('粉丝','salong');
        }else if($get_tab == 'contribute'){
            $item .= __('投稿','salong');
        }else if($get_tab == 'profile'){
            $item .= __('编辑资料','salong');
        }else if($get_tab == 'edit'){
            $item .= __('编辑文章','salong');
        }else{
            $item .= __('资料','salong');
        }
        $item .= $delimiter.get_bloginfo('name');
        if(is_sticky()){
            global $salong;
            $item .= $salong['sticky_title'];
        }
    }else{
        $item .= wp_title( $delimiter, true, 'right');
        $item .= get_bloginfo( 'name');
    }
    if($paged_list){
        $item .= $delimiter.sprintf(__('(第%s页)' , 'salong' ), $paged_list );
    }
    return $item;
}

function user_role($user_id){
    if( user_can( $user_id, 'administrator' )){
        $items .= __('管理员','salong');
    }else if(user_can( $user_id, 'editor' )){
        $items .= __('编辑','salong');
    }else if(user_can( $user_id, 'author' )){
        $items .= __('认证作者','salong');
    }else if(user_can( $user_id, 'contributor' )){
        $items .= __('投稿者','salong');
    }else if(user_can( $user_id, 'subscriber' )){
        $items .= __('订阅者','salong');
    }else if(user_can( $user_id, 'shop_manager' )){
        $items .= __('产品管理者','salong');
    }else if(user_can( $user_id, 'bbp_keymaster' )){
        $items .= __('Keymaster','salong');
    }else if(user_can( $user_id, 'customer' )){
        $items .= __('顾客','salong');
    }else if(user_can( $user_id, 'vip' )){
        $items .= __('VIP','salong');
    }else if(user_can( $user_id, 'bbp_spectator' )){
        $items .= __('观众','salong');
    }else if(user_can( $user_id, 'bbp_blocked' )){
        $items .= __('禁闭','salong');
    }
    return $items;
}

function salong_archive_link( $paged = true ) {
    $link = false;
    if ( is_front_page() ) {
        $link = home_url( '/' );
    } else if ( is_home() && "page" == get_option('show_on_front') ) {
        $link = get_permalink( get_option( 'page_for_posts' ) );
    } else if ( is_tax() || is_tag() || is_category() ) {
        $term = get_queried_object();
        $link = get_term_link( $term, $term->taxonomy );
    } else if ( is_post_type_archive() ) {
        $link = get_post_type_archive_link( get_post_type() );
    } else if ( is_author() ) {
        $link = get_author_posts_url( get_query_var('author'), get_query_var('author_name') );
    } else if ( is_archive() ) {
        if ( is_date() ) {
            if ( is_day() ) {
                $link = get_day_link( get_query_var('year'), get_query_var('monthnum'), get_query_var('day') );
            } else if ( is_month() ) {
                $link = get_month_link( get_query_var('year'), get_query_var('monthnum') );
            } else if ( is_year() ) {
                $link = get_year_link( get_query_var('year') );
            }
        }
    }

    if ( $paged && $link && get_query_var('paged') > 1 ) {
        global $wp_rewrite;
        if ( !$wp_rewrite->using_permalinks() ) {
            $link = add_query_arg( 'paged', get_query_var('paged'), $link );
        } else {
            $link = user_trailingslashit( trailingslashit( $link ) . trailingslashit( $wp_rewrite->pagination_base ) . get_query_var('paged'), 'archive' );
        }
    }
    return $link;
}

add_filter( 'the_content', 'v13_seo_wl');
function v13_seo_wl( $content ) {
    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
    if(preg_match_all("/$regexp/siU", $content, $matches, PREG_SET_ORDER)) {
        if( !empty($matches) ) {
            $srcUrl = get_option('siteurl');
            for ($i=0; $i < count($matches); $i++)
            {
                $tag = $matches[$i][0];
                $tag2 = $matches[$i][0];
                $url = $matches[$i][0];
                $noFollow = '';
                $pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
                preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
                if( count($match) < 1 )
                    $noFollow .= ' rel="nofollow" ';
                $pos = strpos($url,$srcUrl);
                if ($pos === false) {
                    $tag = rtrim ($tag,'>');
                    $tag .= $noFollow.'>';
                    $content = str_replace($tag2,$tag,$content);
                }
            }
        }
    }
    $content = str_replace(']]>', ']]>', $content);
    return $content;
}

add_filter('the_content', 'fancybox_replace');
function fancybox_replace ($content){
    global $post;
    $pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
        $replacement = '<a$1href=$2$3.$4$5 data-fancybox="gallery"$6>$7</a>';
    $content = preg_replace($pattern, $replacement, $content);
    return $content;
}

add_filter( 'pre_option_link_manager_enabled', '__return_true' );



function salong_avatar_name(){
    global $salong,$wp_query,$comment,$current_user;
    $user_id        = $current_user->ID;    $GLOBALS['comment'] = $comment;
    $user_avatar        = get_user_meta( $user_id, 'salong_avatar', true);    $social_avatar      = get_user_meta( $user_id, '_social_img', true);    
    if($user_avatar){
        $avatar_name = __('自定义头像','salong');
    } else if($social_avatar){
        $avatar_name = __('社交头像','salong');
    } else {
        $avatar_name = __('默认头像','salong');
    }
    return $avatar_name;
}


function salong_get_avatar($user_id,$user_name){
    global $salong,$comment;
    $GLOBALS['comment'] = $comment;
    $user_avatar        = get_user_meta( $user_id, 'salong_avatar', true);    $social_avatar      = get_user_meta( $user_id, '_social_img', true);    $default_avatar = $salong['default_avatar']['url'];    $avatar_loading = $salong['avatar_loading']['url'];
    if($user_avatar){
        $avatar_img = $user_avatar;
    } else if($social_avatar){
        $avatar_img = $social_avatar;
    } else {
        $avatar_img = $default_avatar;
    }
    if($salong['switch_lazyload'] && !is_admin()){
        return '<img class="avatar" src="'.$avatar_loading.'" data-original="'.$avatar_img.'" alt="'.$user_name.'" />';
    }else{
        return '<img class="avatar" src="'.$avatar_img.'" alt="'.$user_name.'" />';
    }
}

if(is_admin()){
    function get_ssl_avatar($avatar) {
        
        $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=32" class="avatar avatar-32" height="32" width="32">',$avatar);
        return $avatar;
    }
} else {
    function get_ssl_avatar($avatar) {
        global $current_user;
        $user_id = $current_user->ID;        $user_name = $current_user->display_name;
        $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/',salong_get_avatar($user_id,$user_name),$avatar);
        return $avatar;
    }
}
add_filter('get_avatar', 'get_ssl_avatar');






function salong_crumb(){
    global $salong,$wp_query,$post;
    if(is_singular('topic')){
        $thumb  = get_post_meta($post->ID,'top_thumb',true);
        if($thumb){
            echo '<section class="crumbs_img" style="background-image: url('.$thumb.');">';
            echo '</section>';
        }
    }else{
        
        if(is_tax() || is_category()){
            $cat_ID   = get_queried_object()->term_id;
            $thumb    = get_term_meta($cat_ID,'thumb',true);
            $opacity  = '.'.get_term_meta($cat_ID,'thumb_opacity',true);
            $desc     = category_description();
            $title    = $wp_query->queried_object->name;
        }else if(is_page_template('template-post.php')){
            $thumb    = $salong['post_bg']['url'];
            $opacity  = $salong['post_bg_rgba']['alpha'];
            $desc     = $salong['post_desc'];
            $title    = get_the_title();
        }else if(is_page_template('template-download.php')){
            $thumb    = $salong['download_bg']['url'];
            $opacity  = $salong['download_bg_rgba']['alpha'];
            $desc     = $salong['download_desc'];
            $title    = get_the_title();
        }else if(is_page_template('template-topic.php')){
            $thumb    = $salong['topic_bg']['url'];
            $opacity  = $salong['topic_bg_rgba']['alpha'];
            $desc     = $salong['topic_desc'];
            $title    = get_the_title();
        }else if(is_page_template('template-video.php')){
            $thumb    = $salong['video_bg']['url'];
            $opacity  = $salong['video_bg_rgba']['alpha'];
            $desc     = $salong['video_desc'];
            $title    = get_the_title();
        }else if(is_page_template('template-kx.php')){
            $thumb    = $salong['kx_bg']['url'];
            $opacity  = $salong['kx_bg_rgba']['alpha'];
            $desc     = $salong['kx_desc'];
            $title    = get_the_title();
        }
        if($thumb){
            echo '<section class="crumbs_img" style="background-image: url('.$thumb.');">';
            echo '<section class="crumbs_con">';
            echo '<h1>'.$title.'</h1>';
            if($desc){
                echo '<p>'.$desc.'</p>';
            }
            echo '</section>';
            if($opacity){
                echo '<div class="bg" style="opacity: '.$opacity.';"></div>';
            }
            echo '</section>';
        }
    }
    if($salong['switch_crumbs']){
        echo salong_breadcrumbs();
    }
}

function salong_add_v($curauth_id){
    global $salong;
    $admin_field  = $salong['admin_field'];
    $editor_field = $salong['editor_field'];
    $role         = get_userdata($curauth_id)->roles;
    if($role[0]=='administrator' && $admin_field){
        $item = '<span class="admin_field">'.$admin_field.'</span>';
    }else if($role[0]=='editor' && $editor_field){
        $item = '<span class="admin_field">'.$editor_field.'</span>';
    }else if($role[0]=='author'){
        $item = '<span class="yellow addv">'.svg_v().'</span>';
    }
    return $item;
}

function salong_sidebar($id){
    global $wp_registered_sidebars,$salong;
    $index = "m-".$id;     $sidebar_name = $wp_registered_sidebars[$index]['name'];    echo '<aside class="sidebar">';
        if ( is_active_sidebar($index) ) {
        $post_type = get_post_type();
        $post_option = 'switch_author_'.$post_type;
        if(is_singular($post_type) && $salong[$post_option]) { get_template_part( 'includes/widgets/widget', 'author'); }
        dynamic_sidebar($sidebar_name);
        echo '<article id="move" class="move">';
        dynamic_sidebar(__( '移动', 'salong'));
        echo '</article>';
    }else{
        echo '<article class="sidebar_widget widget_salong_init">';
        echo '<div class="sidebar_title">';
        echo '<h3>';
        echo __('温馨提示','salong');
        echo '</h3>';
        echo '</div>';
        echo '<div class="init"><a href="'.get_home_url().'/wp-admin/widgets.php">';
        echo sprintf(__('请到后台外观——小工具中添加小工具到<b>%s</b>边栏中。','salong'),$sidebar_name);
        echo '</a></div>';
        echo '</article>';
    }
    echo '</aside>';
}

function salong_add_search_to_wp_menu() {
    global $salong,$current_user;
    $user_id    = $current_user->id;
    $user_name  = $current_user->display_name;
    if ( class_exists( 'XH_Social' ) ){
        $login_url = '#login';
    }else{
        $login_url  = wp_login_url($_SERVER['REQUEST_URI']);    }
    $register_url  = wp_registration_url();    
    if( $salong['switch_search_menu'] ) {
        echo '<li class="search">';
        echo '<a href="#search" title="'.__('点击搜索','salong').'">'.svg_search().'</a>';
        echo '</li>';
    }
    if( $salong['switch_program_menu'] ) {
        echo '<li class="program">';
        echo '<a href="#program" title="'.$salong['program_title'].'">'.svg_wechat().'</a>';
        echo '</li>';
    }
    if( $salong['switch_loginreg_menu'] ) {
        if ( is_user_logged_in() ) {
            echo '<li class="center menu-item-has-children">';
            echo '<a href="'.get_author_posts_url($user_id).'" title="'.$user_name.'">'.salong_get_avatar($user_id,$user_name).'<span class="name">'.$user_name.'</span></a>';
            echo '<ul class="sub-menu">';
            echo salong_user_menu($user_id);
            echo '</ul></li>';
        } else {
            echo '<li class="login"><a href="'.$login_url.'">'.__( '登录', 'salong').'</a></li>';
            if(get_option( 'users_can_register')==1){
                echo '<li class="reg"><a href="'.$register_url.'">'.__( '注册', 'salong' ).'</a></li>';
            }
        }
    }
    if($salong['switch_contribute_menu']){
        if ( is_user_logged_in() ) {
            echo '<li class="contribute_btn"><a href="'.get_author_posts_url($user_id).'?tab=contribute">'.$salong['contribute_field'].'</a></li>';
        }else{
            echo '<li class="contribute_btn"><a href="'.$login_url.'">'.$salong['contribute_field'].'</a></li>';
        }
    }
}

function salong_payqr($curauth_id){
    global $salong;

    $salong_alipay    = get_user_meta( $curauth_id, 'salong_alipay', true);
    $salong_wechatpay = get_user_meta( $curauth_id, 'salong_wechatpay', true);
    
    if ( ($salong_alipay || $salong_wechatpay) ) {
        $items .= '<a id="payqr" class="overlay" rel="external nofollow" href="#m"></a>';
        $items .= '<article class="payqr popup">';
        $items .= '<section class="popup_main';
        if ($salong_alipay && $salong_wechatpay){
            $items .= ' two';
        }
        $items .= '">';
        $items .= '<h3>'.__( '给 TA 打赏', 'salong' ).'</h3>';
        if($salong_alipay){
            $items .= '<span class="alipay"><img src="'.$salong_alipay.'" alt="'.__('支付宝收款二维码','salong').'">'.svg_alipay().__('支付宝收款二维码','salong').'</span>';
        } if($salong_wechatpay){
            $items .= '<span class="wechatpay"><img src="'.$salong_wechatpay.'" alt="'.__('微信收款二维码','salong').'">'.svg_wechat().__('微信收款二维码','salong').'</span>';
        }
        $items .= '<a class="close" rel="external nofollow" href="#m">'.svg_close().'</a>';
        $items .= '</section></article>';
    }
    return $items;
}







function salong_all_post_field_count($curauth_id,$type) {
    global $wpdb,$salong;
    $sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = '$type' AND post_author = $curauth_id";
    $views = intval($wpdb->get_var($sql));
    if($salong['switch_filter_count']){
        $count = salong_format_count($views);
    }else{
        $count = $views;
    }
    return $count;
}


function salong_following_count($curauth_id) {
    global $salong;
    if($salong['switch_follow_btn'] == 0 )
        return;
    $following  = salong_get_following_count($curauth_id);
    if($following){
        if($salong['switch_filter_count']){
            $count = salong_format_count($following);
        }else{
            $count = $following;
        }
    }else{
        $count = 0;
    }
    return $count;
}


function salong_follower_count($curauth_id) {
    global $salong;
    if($salong['switch_follow_btn'] == 0 )
        return;
    $follower   = salong_get_follower_count($curauth_id);
    if($follower){
        if($salong['switch_filter_count']){
            $count = salong_format_count($follower);
        }else{
            $count = $follower;
        }
    }else{
        $count = 0;
    }

    return $count;
}


function salong_author_post_like_count($post_type,$curauth_id) {
    global $salong;
    $post_args = array(
        'post_type'       => $post_type,
        'meta_query'      => array(
            array(
                'key'     => 'salong_user_liked',
                'value'   => $curauth_id,
                'compare' => 'LIKE'
            )
        ));
    $posts = new WP_Query( $post_args );
    
    if($salong['switch_filter_count']){
        $count = salong_format_count($posts->found_posts);
    }else{
        $count = $posts->found_posts;
    }

    return $count;
}


function salong_author_post_count($curauth_id,$post_type) {
    global $salong;
    $post_count = count_user_posts( $curauth_id, $post_type);    if($salong['switch_filter_count']){
        $count = salong_format_count($post_count);
    }else{
        $count = $post_count;
    }

    return $count;
}


function salong_author_post_field_count($post_type,$curauth_id,$field) {
    global $salong,$post;

    $post_args = get_posts( array(
        'posts_per_page' => -1,
        'post_type'      => $post_type,
        'post_status'    => 'publish',
        'author'         => $curauth_id
    ) );

    $counter = 0;

    foreach ( $post_args as $post ){
        $views = absint( get_post_meta( $post->ID, $field, true ) );
        $counter += $views;
    }
    
    if($salong['switch_filter_count']){
        $count = salong_format_count($counter);
    }else{
        $count = $counter;
    }
    
    return $count;
}

function getPostViews($postID){
    global $salong;
    $count_key = 'views';
   
    $views = get_post_meta($postID, $count_key, true);
    if($salong['switch_filter_count']){
        $count = salong_format_count($views);
    }else{
        $count = $views;
    }
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
    return $count.'';
}
function setPostViews($postID) {
    global $salong;
    $count_key = 'views';     $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $number = range(1,$salong['views_loop_count']);
        foreach($number as $value){
            $count++;
        }
        update_post_meta($postID, $count_key, $count);
    }
        if (function_exists( 'woocommerce_points_rewards_my_points' ) && is_single() ) {
        global $wc_points_rewards;
        $user_id = get_current_user_id();
        $author_id = get_the_author_meta('ID');
        if($user_id!=$author_id){
            $user_points = get_option( 'wc_points_rewards_view_user_points' );
            WC_Points_Rewards_Manager::increase_points( $user_id, $user_points, 'view_user', $post_id );
            $author_points = get_option( 'wc_points_rewards_view_author_points' );
            WC_Points_Rewards_Manager::increase_points( $author_id, $author_points, 'view_author', $post_id );
        }
    }
}

function get_page_id_from_template($template) {
   global $wpdb;

      $page_id = $wpdb->get_var($wpdb->prepare("SELECT `post_id`
                              FROM `$wpdb->postmeta`, `$wpdb->posts`
                              WHERE `post_id` = `ID`
                                    AND `post_status` = 'publish'
                                    AND `meta_key` = '_wp_page_template'
                                    AND `meta_value` = %s
                                    LIMIT 1;", $template));

   return $page_id;
}

function salong_category_top_parent_id($current_cat_ID, $taxonomy){
    $parent  = get_term_by( 'id', $current_cat_ID, $taxonomy);
    while ($parent->parent != '0'){
        $current_cat_ID = $parent->parent;
        $parent  = get_term_by( 'id', $current_cat_ID, $taxonomy);
    }
    return $parent->term_id;
}

function salong_category_post_count($cat_id,$taxonomy){
    $sub_args_product = new Wp_query(array(
        'post_type'         => get_post_type(),
        'posts_per_page'    => -1,
        'tax_query'         => array(
            array(
                'taxonomy'  => $taxonomy,
                'field'     => 'id',
                'terms'     => $cat_id,
            ),
        )
    ));
    while( $sub_args_product->have_posts() ) : $sub_args_product->the_post();
    $sub_count = $sub_args_product->post_count;
    endwhile; wp_reset_postdata();
    return $sub_count;
}

function get_today_post_count(){
    $date_query = array(
        array(
            'after'=>'1 day ago'
        )
    );
    $args = array(
        'post_type'         => 'post',
        'post_status'       => 'publish',
        'date_query'        => $date_query,
        'no_found_rows'     => true,
        'suppress_filters'  => true,
        'fields'            => 'ids',
        'posts_per_page'    => -1
    );
    $query = new WP_Query( $args );
    return $query->post_count;
}





add_role('vip', 'VIP', array(
    'read'         => true,     'edit_posts'   => true,
    'upload_files' => true
));


function salong_secsToStr($Duration) {
    if($Duration>=3600){
        $hours    = floor($Duration/3600);
        $Duration = $Duration%3600;
        $r        .= $hours.__('小时','salong');
    }
    if($Duration>=60){
        $minutes  = floor($Duration/60);
        $Duration = $Duration%60;
        $r        .= $minutes.__('分','salong');
    }
    $r .= (int)$Duration.__('秒','salong');
    return $r;
}

function deletehtml($description) {
    $description = trim($description);
    $description = strip_tags($description,"");
    return ($description);
}
add_filter('category_description', 'deletehtml');

function enable_more_buttons($buttons) {
     $buttons[] = 'hr';
     $buttons[] = 'del';
     $buttons[] = 'sub';
     $buttons[] = 'sup';
     $buttons[] = 'fontselect';
     $buttons[] = 'fontsizeselect';
     $buttons[] = 'cleanup';
     $buttons[] = 'styleselect';
     $buttons[] = 'wp_page';
     $buttons[] = 'anchor';
     $buttons[] = 'backcolor';
     return $buttons;
     }
add_filter("mce_buttons_3", "enable_more_buttons");

 function wp_remove_open_sans_from_wp_core() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans','');
}
add_action( 'init', 'wp_remove_open_sans_from_wp_core' );

add_action('wp_login', 'set_last_login');
 
function set_last_login($login) {
   $user = get_userdatabylogin($login);
 
      update_usermeta( $user->ID, 'last_login', current_time('mysql') );
}

function get_last_login($user_id) {
   $last_login = get_user_meta($user_id, 'last_login', true);
 
      $date_format = get_option('date_format') . ' ' . get_option('time_format');
 
      $the_last_login = mysql2date($date_format, $last_login, false);
 
      return $the_last_login;
}

if($salong['switch_smtp']){
    function mail_smtp( $phpmailer ) {
        global $salong;
        $phpmailer->IsSMTP();
        $phpmailer->FromName  = sanitize_text_field($salong['smtp_name']);         $phpmailer->From      = sanitize_text_field($salong['smtp_username']);         $phpmailer->Username  = sanitize_text_field($salong['smtp_username']);         $phpmailer->Password  = sanitize_text_field($salong['smtp_password']);         $phpmailer->Host      = sanitize_text_field($salong['smtp_host']);         $phpmailer->Port      = intval($salong['smtp_port']);         $phpmailer->SMTPAuth  = true;   
        if($salong['switch_secure']){$phpmailer->SMTPSecure = 'ssl';}     }
    add_action('phpmailer_init', 'mail_smtp');
}

remove_filter('the_content', 'wptexturize');


if($salong['switch_user_media']){
    function my_upload_media( $wp_query_obj ) {
        global $current_user, $pagenow;
        if( !is_a( $current_user, 'WP_User') )
            return;
        if( 'admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments' )
            return;
        if( !current_user_can( 'manage_options' ) && !current_user_can('manage_media_library') )
            $wp_query_obj->set('author', $current_user->ID );
        return;
    }
    add_action('pre_get_posts','my_upload_media');

        function my_media_library( $wp_query ) {
        if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
            if ( !current_user_can( 'manage_options' ) && !current_user_can( 'manage_media_library' ) ) {
                global $current_user;
                $wp_query->set( 'author', $current_user->id );
            }
        }
    }
    add_filter('parse_query', 'my_media_library' );
}

if($salong['switch_upload_filter']){
    add_filter('wp_handle_upload_prefilter', 'custom_upload_filter' );
    function custom_upload_filter( $file ){
        $info = pathinfo($file['name']);
        $ext = $info['extension'];
        $filedate = date('YmdHis').rand(10,99);        $file['name'] = $filedate.'.'.$ext;
        return $file;
    }
}

function salong_allow_contributor_uploads() {
    if ( current_user_can('contributor') ){
        $contributor = get_role('contributor');
        global $salong;
        if($salong['switch_contributor_uploads']){
            $contributor->add_cap('upload_files');
            $contributor->add_cap('edit_published_posts');
        }else{
            $contributor->remove_cap('upload_files');
            $contributor->remove_cap('edit_published_posts');
        }
    } 
}
add_action('wp', 'salong_allow_contributor_uploads');

function salong_check_upload_mimes( $mimes ) {
    global $salong;
	$site_exts = explode( ' ', $salong['salong_upload_filetypes']);
	$site_mimes = array();
	foreach ( $site_exts as $ext ) {
		foreach ( $mimes as $ext_pattern => $mime ) {
			if ( $ext != '' && strpos( $ext_pattern, $ext ) !== false )
				$site_mimes[$ext_pattern] = $mime;
		}
	}
	return $site_mimes;
}
if(!is_multisite() && !is_admin() && !current_user_can( 'manage_options')){
    add_filter( 'upload_mimes', 'salong_check_upload_mimes' );
}

if( !is_admin() && !current_user_can( 'manage_options')){
    add_filter( 'wp_handle_upload_prefilter', 'salong_images_size_upload' );
}
function salong_images_size_upload( $file ){
    global $salong;
    $w = $salong['image_width'];
    $h = $salong['image_height'];
        $mimes = array( 'image/jpeg', 'image/png', 'image/gif' );
        if( !in_array( $file['type'], $mimes ) )
        return $file;
    $img = getimagesize( $file['tmp_name'] );
        $minimum = array( 'width' => $w, 'height' => $h );
    if ( $img[0] > $minimum['width'] )
        $file['error'] = sprintf(__('图片太大了，最大宽度是%spx，当前上传的图片宽度是%spx'),$minimum['width'],$img[0]);
    elseif ( $img[1] > $minimum['height'] )
        $file['error'] = sprintf(__('图片太大了，最大高度是%spx，当前上传的图片高度是%spx'),$minimum['height'],$img[1]);
    return $file;
}

add_filter('user_contactmethods', 'my_user_contactmethods');
 
function my_user_contactmethods($user_contactmethods){
    
    $user_contactmethods['salong_qq']       = 'QQ';
    $user_contactmethods['salong_wechat']   = __('微信','salong');
    $user_contactmethods['salong_weibo']    = __('微博','salong');
    $user_contactmethods['salong_locality'] = __('坐标','salong');
    $user_contactmethods['salong_gender']   = __('性别','salong');
    $user_contactmethods['salong_phone']    = __('手机','salong');
    $user_contactmethods['salong_company']  = __('公司','salong');
    $user_contactmethods['salong_position'] = __('职位','salong');
    $user_contactmethods['salong_avatar']   = __('头像','salong');
    $user_contactmethods['salong_alipay']   = __('支付宝收款二维码','salong');
    $user_contactmethods['salong_wechatpay']= __('微信收款二维码','salong');
    $user_contactmethods['salong_open']     = __('公开显示','salong');
 
    return $user_contactmethods;
}




if(is_admin()){
        function rd_duplicate_post_as_draft(){
        global $wpdb;
        if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
            wp_die(__('没有文章可以复制！','salong'));
        }

        
        $post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post'] );
        
        $post = get_post( $post_id );

        
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;

        
        if (isset( $post ) && $post != null) {

            
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status'    => $post->ping_status,
                'post_author'    => $new_post_author,
                'post_content'   => $post->post_content,
                'post_excerpt'   => $post->post_excerpt,
                'post_name'      => $post->post_name,
                'post_parent'    => $post->post_parent,
                'post_password'  => $post->post_password,
                'post_status'    => 'draft',
                'post_title'     => $post->post_title,
                'post_type'      => $post->post_type,
                'to_ping'        => $post->to_ping,
                'menu_order'     => $post->menu_order
            );

            
            $new_post_id = wp_insert_post( $args );

            
            $taxonomies = get_object_taxonomies($post->post_type);             foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }

            
            $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
            if (count($post_meta_infos)!=0) {
                $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                foreach ($post_meta_infos as $meta_info) {
                    $meta_key = $meta_info->meta_key;
                    $meta_value = addslashes($meta_info->meta_value);
                    $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
                }
                $sql_query.= implode(" UNION ALL ", $sql_query_sel);
                $wpdb->query($sql_query);
            }


            
            wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
            exit;
        } else {
            wp_die(__('复制失败，找不到原始文章：','salong').$post_id);
        }
    }
    add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );

    
    function rd_duplicate_post_link( $actions, $post ) {
        if (current_user_can('edit_posts')) {
            $actions['duplicate'] = '<a href="admin.php?action=rd_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Duplicate this item" rel="permalink">'.__('复制','salong').'</a>';
        }
        return $actions;
    }

    add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );
    

        function salong_add_page($title,$slug,$shortcode,$page_template=''){
        $allPages = get_pages();        $exists = false;   
        foreach( $allPages as $page ){   
                        if( strtolower( $page->post_name ) == strtolower( $slug ) ){   
                $exists = true;   
            }   
        }   
        if( $exists == false ) {   
            $new_page_id = wp_insert_post(   
                array(   
                    'post_title'         => $title,
                    'post_type'          => 'page',
                    'post_name'          => $slug,
                    'comment_status'     => 'closed',
                    'ping_status'        => 'closed',
                    'post_content'       => '',
                    'post_status'        => 'publish',
                    'post_author'        => 1,
                    'menu_order'         => 0,
                    'meta_input'         => array(
                        'page_shortcode' => $shortcode
                    )
                )
            );   
                        if($new_page_id && $page_template!=''){
                                update_post_meta($new_page_id, '_wp_page_template',  $page_template);
            }
        }
    }

        function salong_add_pages() {
        global $pagenow;
                            salong_add_page(__('文章','salong'),'posts','','template-post.php');             salong_add_page(__('专题','salong'),'topics','','template-topic.php'); 
            salong_add_page(__('下载','salong'),'downloads','','template-download.php'); 
            salong_add_page(__('视频','salong'),'videos','','template-video.php'); 
            salong_add_page(__('快讯','salong'),'kxs','','template-kx.php'); 
            salong_add_page(__('外链跳转','salong'),'go','','template-go.php'); 
            salong_add_page(__('站点地图','salong'),'sitemaps','','template-sitemap.php'); 
            salong_add_page(__('所有用户','salong'),'all-user','[all_user]',''); 
            salong_add_page(__('友情链接','salong'),'links','[link]','');
            salong_add_page(__('客户留言','salong'),'messages','[message]','');
            salong_add_page(__('联系我们','salong'),'contact','[map]',''); 
            salong_add_page(__('标签云','salong'),'tags','[tag]',''); 
            salong_add_page(__('置顶文章','salong'),'sticky-posts','[sticky_like post_state="sticky"]',''); 
            salong_add_page(__('点赞排行','salong'),'like-posts','[sticky_like post_state="like"]',''); 
            }
    add_action( 'load-themes.php', 'salong_add_pages' );
    
        if ( $salong[ 'post_column_metas'] !=0 ) {
        
        function post_column_views($newcolumn){
            global $salong;
            if (in_array( 'modified', $salong[ 'post_column_metas'])) {
                $newcolumn['post_modified'] = __('修改时间','salong');
            } if (in_array( 'views', $salong[ 'post_column_metas'])) {
                $newcolumn['post_views'] = __('浏览','salong');
            } if (in_array( 'likes', $salong[ 'post_column_metas'])) {
                $newcolumn['post_likes'] = __('点赞','salong');
            } if (in_array( 'post_id', $salong[ 'post_column_metas'])) {
                $newcolumn['post_id'] = __('ID','salong');
            } if (in_array( 'thumb', $salong[ 'post_column_metas'])) {
                $newcolumn['post_thumb'] = __('缩略图','salong');
            } if (in_array( 'baidusubmit', $salong[ 'post_column_metas']) && $salong['switch_baidu_submit']) {
                $newcolumn['baidusubmit'] = __('百度推送','salong');
            }
            return $newcolumn;
        }
        add_filter( 'manage_post_posts_columns', 'post_column_views' );
        add_filter( 'manage_topic_posts_columns', 'post_column_views' );
        add_filter( 'manage_download_posts_columns', 'post_column_views' );
        add_filter( 'manage_video_posts_columns', 'post_column_views' );

                if (in_array( 'slide', $salong[ 'post_column_metas'])) {
            function default_post_column_views($newcolumn){
                $newcolumn['slide_recommend'] = __('幻灯片推送','salong');
                return $newcolumn;
            }
            add_filter( 'manage_post_posts_columns', 'default_post_column_views' );
        }

                if (in_array( 'time', $salong[ 'post_column_metas'])) {
            function default_video_column_time($newcolumn){
                $newcolumn['video_time'] = __('时长','salong');
                return $newcolumn;
            }
            add_filter( 'manage_video_posts_columns', 'default_video_column_time' );
        }

        
        function post_custom_column_views($column_name, $id){
            switch ( $column_name ) {
                case 'post_modified' :
                    $date_format = 'Y-m-d';
                    $post = get_post( get_the_ID() );
                    echo get_the_modified_date( $date_format, $post );
                    break;
                case 'post_views' :
                    echo getPostViews(get_the_ID());
                    break;
                case 'post_likes' :
                    $count = get_post_meta(get_the_ID(), 'salong_post_like_count', true );
                    echo $count != 0 ? $count : 0;
                    break;
                case 'video_time' :
                    echo get_post_meta(get_the_ID(), 'time', true );
                    break;
                case 'post_id' :
                    echo get_the_ID();
                    break;
                case 'slide_recommend' :
                    $slide_recommend = get_post_meta(get_the_ID(), 'slide_recommend', true );
                    echo $slide_recommend ? __('已推送','salong') : __('未推送','salong') ;
                    break;
                case 'post_thumb' :
                    $thumb = get_post_meta(get_the_ID(), "thumb", true);
                    if($thumb) {
                        echo '<img style="width: 80px" src="' . $thumb . '" />';
                    }
                    break;
                case 'baidusubmit' :
                    $baidusubmit = get_post_meta(get_the_ID(), 'baidusubmit', true );
                    echo $baidusubmit == 1 ? __('已推送','salong') : __('未推送','salong');
                    break;
            }
        }
        add_action('manage_post_posts_custom_column', 'post_custom_column_views',10,2);
        add_action('manage_topic_posts_custom_column', 'post_custom_column_views',10,2);
        add_action('manage_download_posts_custom_column', 'post_custom_column_views',10,2);
        add_action('manage_video_posts_custom_column', 'post_custom_column_views',10,2);

        
        function register_post_column_views_sortable( $newcolumn ) {
            global $salong;
            if (in_array( 'modified', $salong[ 'post_column_metas'])) {
                $newcolumn['post_modified'] = 'post_modified';
            } if (in_array( 'views', $salong[ 'post_column_metas'])) {
                $newcolumn['post_views'] = 'post_views';
            } if (in_array( 'likes', $salong[ 'post_column_metas'])) {
                $newcolumn['post_likes'] = 'post_likes';
            } if (in_array( 'time', $salong[ 'post_column_metas'])) {
                $newcolumn['video_time'] = 'video_time';
            } if (in_array( 'post_id', $salong[ 'post_column_metas'])) {
                $newcolumn['post_id'] = 'post_id';
            } if (in_array( 'thumb', $salong[ 'post_column_metas'])) {
                $newcolumn['post_thumb'] = 'post_thumb';
            } if (in_array( 'slide', $salong[ 'post_column_metas'])) {
                $newcolumn['slide_recommend'] = 'slide_recommend';
            } if (in_array( 'baidusubmit', $salong[ 'post_column_metas'])) {
                $newcolumn['baidusubmit'] = 'baidusubmit';
            }

            return $newcolumn;
        }
        add_filter( 'manage_edit-download_sortable_columns', 'register_post_column_views_sortable' );
        add_filter( 'manage_edit-topic_sortable_columns', 'register_post_column_views_sortable' );
        add_filter( 'manage_edit-post_sortable_columns', 'register_post_column_views_sortable' );
        add_filter( 'manage_edit-video_sortable_columns', 'register_post_column_views_sortable' );

        if (in_array( 'modified', $salong[ 'post_column_metas'])) {
            
            function sort_modified_column( $vars ){
                if ( isset( $vars['orderby'] ) && 'modified' == $vars['orderby'] ) {
                    $vars = array_merge(
                        $vars,
                        array(
                            'orderby' => 'post_modified'
                        )
                    );
                }
                return $vars;
            }
            add_filter( 'request', 'sort_modified_column' );
        } if (in_array( 'views', $salong[ 'post_column_metas'])) {
            
            function sort_views_column( $vars ){
                if ( isset( $vars['orderby'] ) && 'post_views' == $vars['orderby'] ) {
                    $vars = array_merge( $vars, array(
                        'meta_key' => 'views',
                        'orderby' => 'meta_value_num')
                    );
                }
                return $vars;
            }
            add_filter( 'request', 'sort_views_column' );
        } if (in_array( 'thumb', $salong[ 'post_column_metas'])) {
            
            function sort_thumb_column( $vars ){
                if ( isset( $vars['orderby'] ) && 'post_thumb' == $vars['orderby'] ) {
                    $vars = array_merge( $vars, array(
                        'meta_key' => 'thumb',
                        'orderby' => 'post_date')
                    );
                }
                return $vars;
            }
            add_filter( 'request', 'sort_thumb_column' );
        } if (in_array( 'likes', $salong[ 'post_column_metas'])) {
            
            function sort_likes_column( $vars ){
                if ( isset( $vars['orderby'] ) && 'post_likes' == $vars['orderby'] ) {
                    $vars = array_merge( $vars, array(
                        'meta_key' => 'salong_post_like_count',
                        'orderby' => 'meta_value_num') 
                    );
                }
                return $vars;
            }
            add_filter( 'request', 'sort_likes_column' );
        } if (in_array( 'time', $salong[ 'post_column_metas'])) {
            
            function sort_video_time_column( $vars ){
                if ( isset( $vars['orderby'] ) && 'video_time' == $vars['orderby'] ) {
                    $vars = array_merge( $vars, array(
                        'meta_key' => 'time',
                        'orderby' => 'meta_value_num') 
                    );
                }
                return $vars;
            }
            add_filter( 'request', 'sort_video_time_column' );
        } if (in_array( 'post_id', $salong[ 'post_column_metas'])) {
            
            function sort_post_id_column( $vars ){
                if ( isset( $vars['orderby'] ) && 'post_id' == $vars['orderby'] ) {
                    $vars = array_merge( $vars, array(
                        'orderby' => 'ID') 
                    );
                }
                return $vars;
            }
            add_filter( 'request', 'sort_post_id_column' );
        } if (in_array( 'slide', $salong[ 'post_column_metas'])) {
            
            function sort_slide_recommend_column( $vars ){
                if ( isset( $vars['orderby'] ) && 'slide_recommend' == $vars['orderby'] ) {
                    $vars = array_merge( $vars, array(
                        'meta_key' => 'slide_recommend',
                        'orderby' => 'post_date') 
                    );
                }
                return $vars;
            }
            add_filter( 'request', 'sort_slide_recommend_column' );
        } if (in_array( 'baidusubmit', $salong[ 'post_column_metas']) && $salong['switch_baidu_submit']) {
            
            function sort_baidusubmit_column( $vars ){
                if ( isset( $vars['orderby'] ) && 'baidusubmit' == $vars['orderby'] ) {
                    $vars = array_merge( $vars, array(
                        'meta_key'  => 'baidusubmit',
                        'orderby'   => 'post_date') 
                    );
                }
                return $vars;
            }
            add_filter( 'request', 'sort_baidusubmit_column' );
        }
        
        $get_tab = $_GET['post_type'];
        if($get_tab != 'page' && $get_tab != 'product' && $get_tab != 'kx'){
            add_action('admin_head', 'my_column_width');
            function my_column_width() {
                echo '<style type="text/css">';
                echo '.widefat.posts th { width:10%}';
                echo '.widefat.posts th.column-post_likes,.widefat.posts th.column-post_views,.widefat.posts th.column-post_id { width:80px}';
                echo '.widefat.posts th.column-post_thumb,.widefat.posts th.column-post_modified { width:100px}';
                echo '.widefat.posts th.column-slide_recommend,.widefat.posts th.column-baidusubmit { width:120px}';
                echo '.widefat.posts th.column-title { width:40%}';
                echo '</style>';
            }
        }
    }
        
        function rudr_posts_taxonomy_filter() {
        global $typenow;
        if( $typenow == 'topic' || $typenow == 'download' || $typenow == 'video' ){
            if( $typenow == 'topic' ){
                $taxonomy_names = array('tcat');
            }else if( $typenow == 'download' ){
                $taxonomy_names = array('dcat');
            }else if( $typenow == 'video' ){
                $taxonomy_names = array('vcat');

            }
            foreach ($taxonomy_names as $single_taxonomy) {
                $current_taxonomy = isset( $_GET[$single_taxonomy] ) ? $_GET[$single_taxonomy] : '';
                $taxonomy_object = get_taxonomy( $single_taxonomy );
                $taxonomy_name = strtolower( $taxonomy_object->labels->name );
                $taxonomy_terms = get_terms( $single_taxonomy );
                if(count($taxonomy_terms) > 0) {
                    echo "<select name='$single_taxonomy' id='$single_taxonomy' class='postform'>";
                    echo "<option value=''>所有$taxonomy_name</option>";
                    foreach ($taxonomy_terms as $single_term) {
                        echo '<option value='. $single_term->slug, $current_taxonomy == $single_term->slug ? ' selected="selected"' : '','>' . $single_term->name .' (' . $single_term->count .')</option>'; 
                    }
                    echo "</select>";
                }
            }
        }
    }
    add_action( 'restrict_manage_posts', 'rudr_posts_taxonomy_filter' );
    
    
        function salong_video_field($post_id) {
        global $salong,$post,$client_ali,$regionId;

        $post_type = get_post_type($post_id);
        $youku_id = get_post_meta($post_id, "youku_id", true);
        $ali_id   = get_post_meta($post_id, "ali_id", true);

        if( empty($youku_id) && empty($ali_id) && $post_type != 'video' )
            return;

        $time = get_post_meta($post_id, "time", true);
        $thumb = get_post_meta($post_id, "thumb", true);

        if($youku_id){
            $Duration = get_youku_video('duration');
            
            if(!$thumb){
                add_post_meta( $post_id, 'thumb', get_youku_video('thumb'), true );
            }
        }else if($ali_id){
            $Duration = salong_ali_video('Duration',$ali_id);
        }
        
        if(!$time){
            add_post_meta( $post_id, 'time', salong_secsToStr($Duration), true );
        }
    }
    add_action('save_post', 'salong_video_field');

        add_action('publish_product', 'add_custom_field_automatically');
    function add_custom_field_automatically($post_ID) {
        global $wpdb;
        if(!wp_is_post_revision($post_ID)) {
            add_post_meta($post_ID, 'salong_post_like_count', 0, true);
            add_post_meta($post_ID, 'views', 0, true);
        }
    }

        function change_footer_admin () {return '';}
    add_filter('admin_footer_text', 'change_footer_admin', 9999);
    function change_footer_version() {return '&nbsp;';}
    add_filter( 'update_footer', 'change_footer_version', 9999);

}



function salong_contribute_post($atts){
    global $salong,$current_user,$post,$wp_query,$wpdb;
    $user_id                  = $current_user->ID;    $user_name                = $current_user->display_name;
    $user_email               = $current_user->user_email;
    $user_url                 = $current_user->user_url;
    $direct_contribute_access = $salong['direct_contribute_access'];
    $current_url              = get_author_posts_url($user_id).'?tab=contribute';    
    extract(
        shortcode_atts(
            array(
                "post_type" => 'post'
            ),
            $atts
        )
    );
    
    $post_name      = __('文章','salong');
    $taxonomy_name  = 'category';
    $tg_max         = $salong['post_tg_max'];
    $tg_min         = $salong['post_tg_min'];
    
        $contribute_access = $salong['contribute_access'];
    if (current_user_can( $contribute_access )) {

        if( isset($_POST['tougao_form']) && $_POST['tougao_form'] == 'send') {

                        $thumb      = isset( $_POST['tougao_thumb'] ) ? trim(htmlspecialchars($_POST['tougao_thumb'], ENT_QUOTES)) : '';
            $name       = isset( $_POST['tougao_authorname'] ) ? trim(htmlspecialchars($_POST['tougao_authorname'], ENT_QUOTES)) : '';
            $email      = isset( $_POST['tougao_authoremail'] ) ? trim(htmlspecialchars($_POST['tougao_authoremail'], ENT_QUOTES)) : '';
            $blog       = isset( $_POST['tougao_authorblog'] ) ? trim(htmlspecialchars($_POST['tougao_authorblog'], ENT_QUOTES)) : '';
            $from_name  = isset( $_POST['tougao_from_name'] ) ? trim(htmlspecialchars($_POST['tougao_from_name'], ENT_QUOTES)) : '';
            $from_link  = isset( $_POST['tougao_from_link'] ) ? trim(htmlspecialchars($_POST['tougao_from_link'], ENT_QUOTES)) : '';
            $title      = isset( $_POST['tougao_title'] ) ? trim(htmlspecialchars($_POST['tougao_title'], ENT_QUOTES)) : '';
            $category   = isset( $_POST['term_id'] ) ? (int)$_POST['term_id'] : 0;
            $content    = isset( $_POST['tougao_content'] ) ? $_POST['tougao_content'] : '';
            $status     = isset( $_POST['post_status'] ) ? $_POST['post_status'] : '';

            $last_post  = $wpdb->get_var("SELECT `post_date` FROM `$wpdb->posts` ORDER BY `post_date` DESC LIMIT 1");


            $tougao = array(
                'post_title'    => $title,
                'post_content'  => $content,
                'post_author'   => $user_id,
                'post_type'     => $post_type,
                'ping_status'   => 'closed',
                'post_status'   => $status,
                'post_category' => array($category)
            );

            if ( (date_i18n('U') - strtotime($last_post)) < $salong['tg_time'] ) {
                echo '<span class="warningbox">'.__('您投稿也太勤快了吧，先歇会儿！','salong').'</span>';
            }else if ( empty($title) || mb_strlen($title) > 100 ) {
                echo '<span class="warningbox">'.sprintf(__('标题必须填写，且长度不得超过100字，重新输入或者<a href="%s">点击刷新</a>','salong'),$current_url).'</span>';
            }else if ( empty($content)) {
                echo '<span class="warningbox">'.sprintf(__('内容必须填写，重新输入或者<a href="%s">点击刷新</a>','salong'),$current_url).'</span>';
            }else if ( mb_strlen($content) > $tg_max ) {
                echo '<span class="warningbox">'.sprintf(__('内容长度不得超过%s字，重新输入或者<a href="%s">点击刷新</a>','salong'),$tg_max,$current_url).'</span>';
            }else if ( mb_strlen($content) < $tg_min) {
                echo '<span class="warningbox">'.sprintf(__('内容长度不得少于%s字，重新输入或者<a href="%s">点击刷新</a>','salong'),$tg_min,$current_url).'</span>';
            }else if ( $_POST['are_you_human'] == '' ) {
                echo '<span class="warningbox">'.sprintf(__('请输入本站名称：%s','salong'),get_option('blogname')).'</span>';
            }else if ( $_POST['are_you_human'] !== get_bloginfo( 'name' ) ) {
                echo '<span class="warningbox">'.sprintf(__('本站名称输入错误，正确名称为：%s','salong'),get_option('blogname')).'</span>';
            }else if ($tougao != 0) {

                                $posts = wp_insert_post( $tougao );
                
                                wp_set_object_terms( $posts, $category, $taxonomy_name);
                
                                if($status == 'pending'){
                    $email_content = $salong['contribute_email_pending'];
                    $init = $salong['contribute_init_pending'];
                }else if($status == 'draft'){
                    $email_content = $salong['contribute_email_draft'];
                    $init = $salong['contribute_init_draft'];
                }else{
                    $email_content = $salong['contribute_email_publish'];
                    $init = $salong['contribute_init_publish'];
                }
                wp_mail(get_option('admin_email'),get_option('blogname').__('用户投稿','salong'),$email_content);

                                add_post_meta($posts, 'salong_tougao_email', $email, TRUE);
                add_post_meta($posts, 'salong_tougao_userid', $user_id, TRUE);
                
                if($thumb){
                    add_post_meta($posts, 'thumb', $thumb, TRUE);
                }
                if($from_name){
                    add_post_meta($posts, 'from_name', $from_name, TRUE);
                }
                if($from_link){
                    add_post_meta($posts, 'from_link', $from_link, TRUE);
                }
                echo '<span class="successbox">'.$init.'</span>';
                return;
            }else {
                echo '<span class="errorbox">'.__('投稿失败!','salong').'</span>';
                return;
            }
        }

        echo '<form class="contribute_form" method="post" action="'.$current_url.'">';
        echo '<p><label for="tougao_title"><b class="required">*</b>'.__('文章标题','salong').'</label><input type="text" value="" id="tougao_title" name="tougao_title" placeholder="'.__('请输入文章标题','salong').'" required /><span>'.sprintf(__('标题长度不得超过%s字。','salong'),100).'</span></p>';
        echo '<p><label for="tougao_category"><b class="required">*</b>'.__('文章分类','salong').'</label>';
                $contribute_cat_arr = $salong[ 'contribute_cat'];
        if($contribute_cat_arr){
            $contribute_cat = implode(',',$salong[ 'contribute_cat']);
        }else{
            $contribute_cat = '';
        }
        wp_dropdown_categories('include='.$contribute_cat.'&hide_empty=0&id=tougao_category&show_count=1&hierarchical=1&taxonomy='.$taxonomy_name.'&name=term_id&id=term_id');
        echo '</p>';
        echo '<p>'.wp_editor('', 'tougao_content', array('media_buttons'=>true, 'quicktags'=>true, 'editor_class'=>'form-control' ) ).'<span>'.sprintf(__('内容必须填写，且长度不得超过 %s 字，不得少于 %s 字。','salong'),$tg_max,$tg_min).'</span></p>';
        if (current_user_can( 'edit_posts' ) || $salong['switch_contributor_uploads']) {
            echo '<div class="salong_field_main"><label for="tougao_thumb">'.__('缩略图','salong').'</label><div class="salong_field_area"><div class="salong_file_button"><a href="#" class="salong_upload_button"><b>+</b><span>'.__('上传封面','salong').'</span></a><div class="salong_file_preview"></div><div class="bg"></div><input class="salong_field_upload" type="hidden" value="" id="tougao_thumb" name="tougao_thumb" /></div><div class="salong_file_hint"><p>'.__('自定义缩略图，建议比例：460*280。','salong').'</p><span>'.__('支持≤3MB，JPG，JEPG，PNG格式文件','salong').'</span></div></div></div><hr>';
        }
        echo '<p><label for="tougao_from_name">'.__('文章来源网站名称','salong').'</label><input type="text" value="" id="tougao_from_name" name="tougao_from_name" /></p>';
        echo '<p><label for="tougao_from_link">'.__('文章来源网站链接','salong').'</label><input type="text" value="" id="tougao_from_link" name="tougao_from_link" /></p><hr>';
        echo '<p><label for="are_you_human"><b class="required">*</b>'.sprintf(__('本站名称（请输入：%s）','salong'),get_option('blogname')).'<br/><input id="are_you_human" class="input" type="text" value="" name="are_you_human" required /></label></p>';
        echo '<p class="hint">'.$salong['contribute_info'].'</p><hr>';
        echo '<div class="status_btn">';
        echo '<select name="post_status">';
        if ( salong_is_administrator() || current_user_can( $direct_contribute_access ) || $current_user->roles[0] == 'vip' ) {
            echo '<option value="publish">'.__('直接发布','salong').'</option>';
        }else{
            echo '<option value="pending">'.__('提交审核','salong').'</option>';
        }
        echo '<option value="draft">'.__('保存草稿','um').'</option></select>';
        echo '<p><input type="hidden" value="send" name="tougao_form" /><input type="submit" value="'.__('提交','salong').'" class="submit" /><input type="reset" value="'.__('重填','salong').'" class="reset" /></p>';
        echo '</div>';
        echo '</form>';
    }else{
        echo '<div class="infobox">'.$salong['contribute_access_info'].'</div>';
    }
}
add_shortcode('contribute_post','salong_contribute_post');


if($salong['switch_tougao_notify']){
    function salong_tougao_notify($mypost) {
        $email = get_post_meta($mypost->ID, "salong_tougao_email", true);

        if( !empty($email) ) {
                        $subject = sprintf(__('您在 %s 的投稿已发布','salong'),get_option('blogname'));
                        $message = sprintf(__('<p><strong> %s </strong> 提醒您: 您投递的文章 <strong> %s </strong> 已发布</p><p>您可以点击以下链接查看具体内容:<br /><a href="%s">点此查看完整內容</a></p><p>===================================================================</p><p><strong>感谢您对 <a href="%s" target="_blank">%s</a> 的关注和支持</strong></p><p><strong>该信件由系统自动发出, 请勿回复, 谢谢.</strong></p>','salong'),get_option('blogname'),$mypost->post_title,get_permalink( $mypost->ID ),get_home_url(),get_option('blogname'));

            add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
            @wp_mail( $email, $subject, $message );
        }
    }
        add_action('pending_to_publish', 'salong_tougao_notify', 6);
}


function salong_user_menu($curauth_id){

    global $salong,$wp_query,$current_user,$wpdb;
    $get_tab        = $_GET['tab'];    $current_id     = $current_user->ID;     
    $comments_count = get_comments( array('status' => '1', 'user_id'=>$curauth_id, 'count' => true) );    if($salong['switch_follow_btn']){
        $following      = salong_get_following_count($curauth_id);        $follower       = salong_get_follower_count($curauth_id);    }
    
        $oneself = $current_id==$curauth_id;
    
		$num_unread = (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'pm WHERE `recipient` = "' . $curauth_id . '" AND `read` = 0 AND `deleted` != "2"' );
    
    $message_recipient = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'pm WHERE `recipient` = "' . $curauth_id . '" AND `deleted` != 2' );
    $message_sender = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'pm WHERE `sender` = "' . $curauth_id . '" AND `deleted` != 1' );
    $message_total = $message_recipient + $message_sender;

    ?>
    <li<?php if( is_author() && $get_tab=='' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>">
           <?php echo svg_user(); ?>
            <h4><?php _e('资料','salong'); ?></h4>
        </a>
    </li>
    <li<?php if( $get_tab=='post' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=post">
            <?php echo svg_post(); ?>
            <h4><?php _e('文章','salong'); ?></h4>
            <?php if(salong_author_post_count($curauth_id,'post')){ ?>
            <span class="count">（<?php echo salong_author_post_count($curauth_id,'post'); ?>）</span>
            <?php } ?>
        </a>
    </li>
    <?php if(salong_author_post_count($curauth_id,'topic') && $salong[ 'switch_topic_type']){ ?>
    <li<?php if( $get_tab=='topic' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=topic">
            <?php echo svg_topic(); ?>
            <h4><?php _e('专题','salong'); ?></h4>
            <span class="count">（<?php echo salong_author_post_count($curauth_id,'topic'); ?>）</span>
        </a>
    </li>
    <?php } if(salong_author_post_count($curauth_id,'download') && $salong[ 'switch_download_type']){ ?>
    <li<?php if( $get_tab=='download' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=download">
            <?php echo svg_download(); ?>
            <h4><?php _e('下载','salong'); ?></h4>
            <span class="count">（<?php echo salong_author_post_count($curauth_id,'download'); ?>）</span>
        </a>
    </li>
    <?php } if(salong_author_post_count($curauth_id,'video') && $salong[ 'switch_video_type']){ ?>
    <li<?php if( $get_tab=='video' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=video">
            <?php echo svg_video(); ?>
            <h4><?php _e('视频','salong'); ?></h4>
            <span class="count">（<?php echo salong_author_post_count($curauth_id,'video'); ?>）</span>
        </a>
    </li>
    <?php }?>
    <li<?php if( $get_tab=='like' || $get_tab=='like-topic' || $get_tab=='like-download' || $get_tab=='like-video' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=like">
            <?php echo svg_like(); ?>
            <h4><?php _e('收藏','salong'); ?></h4>
            <?php if(salong_author_post_like_count('any',$curauth_id)){ ?>
            <span class="count">（<?php echo salong_author_post_like_count('any',$curauth_id); ?>）</span>
            <?php } ?>
        </a>
    </li>
    <li<?php if( $get_tab=='comment' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=comment">
            <?php echo svg_comment(); ?>
            <h4><?php _e('评论','salong'); ?></h4>
            <?php if($comments_count){ ?>
            <span class="count">（<?php echo $comments_count; ?>）</span>
            <?php } ?>
        </a>
    </li>
    <?php if($salong['switch_follow_btn']){ ?>
    <li<?php if( $get_tab=='following' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=following">
            <?php echo svg_following(); ?>
            <h4><?php _e('关注','salong'); ?></h4>
            <?php if($following){ ?>
            <span class="count">（<?php echo salong_following_count($curauth_id); ?>）</span>
            <?php } ?>
        </a>
    </li>
    <li<?php if( $get_tab=='follower' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($curauth_id); ?>?tab=follower">
            <?php echo svg_follower(); ?>
            <h4><?php _e('粉丝','salong'); ?></h4>
            <?php if($follower){ ?>
            <span class="count">（<?php echo salong_follower_count($curauth_id); ?>）</span>
            <?php } ?>
        </a>
    </li>
    <?php } if( ( (is_user_logged_in() && $oneself ) || salong_is_administrator() ) && $salong['switch_messages'] ){ ?>
    <li class="message<?php if( $get_tab=='message' || $get_tab=='message-inbox' || $get_tab=='message-outbox' ){ echo ' current'; } ?>">
        <a href="<?php echo add_query_arg('tab', 'message', get_author_posts_url($curauth_id)); ?>"<?php if ( $num_unread ){ ?> title="<?php echo sprintf( __( '您有 %s 条新信息！', 'salong' ), $num_unread ); ?>"<?php } ?>>
            <?php echo svg_message(); ?>
            <h4><?php _e('私信','salong'); ?></h4>
            <?php if($message_total){ ?>
            <span class="count">（<?php echo $message_total; ?>）</span>
            <?php } ?>
            
            <?php if ( $num_unread ){ echo '<b></b>'; } ?>
        </a>
    </li>
    <?php } if(is_user_logged_in() && $oneself){ ?>
    <li<?php if( $get_tab=='contribute' || $get_tab=='contribute-post' || $get_tab=='contribute-download' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($current_id); ?>?tab=contribute">
            <?php echo svg_contribute(); ?>
            <h4><?php _e('投稿','salong'); ?></h4>
        </a>
    </li>
    <li<?php if( $get_tab=='edit-profile' || $get_tab == 'edit-profile-extension' || $get_tab == 'edit-profile-password' ){ echo ' class="current"'; } ?>>
        <a href="<?php echo get_author_posts_url($current_id); ?>?tab=edit-profile">
            <?php echo svg_profile(); ?>
            <h4><?php _e('编辑','salong'); ?></h4>
        </a>
    </li>
    <li>
        <a href="<?php echo wp_logout_url($_SERVER['REQUEST_URI']); ?>">
            <?php echo svg_logout(); ?>
            <h4><?php _e('退出','salong'); ?></h4>
        </a>
    </li>
    <?php }
}

function salong_link_page(){
    global $salong;
    $linkcatorderby     = $salong[ 'link_category_orderby'];
    $linkcatorder       = $salong[ 'link_category_order'];
    if($salong[ 'exclude_link_category']) {
        $linkcatexclude = implode( ',',$salong[ 'exclude_link_category']);
    }
    $linkorderby        = $salong[ 'link_orderby'];
    $linkorder          = $salong[ 'link_order'];
    $linkexclude        = $salong[ 'exclude_link'];

?>
    <section id="link-page">
        <ul>
            <?php wp_list_bookmarks( 'orderby=rand&show_images=1&category_orderby='.$linkcatorderby. '&category_order='.$linkcatorder. '&exclude_category='.$linkcatexclude. '&orderby='.$linkorderby. '&order='.$linkorder. '&exclude='.$linkexclude. '&show_description=1&link_before=<span>&link_after=</span>'); ?>
        </ul>
        <?php if($salong[ 'switch_link_icon']){ ?>
        <script>
            $("#link-page a").each(function(e) {
                $(this).prepend("<img src=https://f.ydr.me/" + this.href.replace(/^(http:\/\/[^\/]+).*$/, '$1') + ">");
            });
        </script>
        <?php } ?>
    </section>
<?php }
add_shortcode('link','salong_link_page');


function salong_message_page(){
    global $salong,$wpdb;
    $excludeemail = $salong['exclude_email']; $messagecount = $salong['message_count'];
    $query="SELECT COUNT(comment_ID) AS cnt, comment_author, comment_author_url,user_id, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_date > date_sub( NOW(), INTERVAL 24 MONTH ) AND comment_author_email != '$excludeemail' AND post_password='' AND comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author_email ORDER BY cnt DESC LIMIT $messagecount"; 
    $wall = $wpdb->get_results($query);
    $maxNum = $wall[0]->cnt;?>
    <ul class="readers-list layout_ul">
        <?php
        foreach ($wall as $comment){
            if($comment->user_id){
                $url = get_author_posts_url($comment->user_id);
            }else if( $comment->comment_author_url ){
                if ($salong['switch_link_go']) {
                    $url = commentauthor();
                } else {
                    $url = comment_author_link();
                }
            }else{
                $url = '#';
            }
            $r="rel='external nofollow'";
            ?>
            <li class="layout_li"><a target="_blank" href="<?php echo $url; ?>" <?php echo $r; ?> title="<?php _e('查看TA的站点','salong'); ?>">
            <?php $user_id = $comment->user_id; $user_name = $comment->comment_author; echo salong_get_avatar($user_id,$user_name).$user_name; ?>&nbsp;+&nbsp;<?php echo $comment->cnt;?>
            </a>
        </li>
        <?php } ?>
    </ul>
<?php }
add_shortcode('message','salong_message_page');


function salong_tag_page(){
    global $salong,$wpdb;
    $taxonomies = array('post_tag','ttag','dtag','vtag'); 
    foreach ( $taxonomies as $taxonomy ) {
        $tag_name = get_taxonomy($taxonomy);
    ?>

    <?php $tag_args=array( 'order'=> 'DESC', 'taxonomy' => $taxonomy, 'orderby' => 'count', 'number' => $sitemap_tag_count ); $tag_tags_list = get_terms($tag_args); if ($tag_tags_list) { ?>
    
    <section class="tags">
        <h3>
            <?php if($taxonomy == 'post_tag'){echo __('文章'); } echo $tag_name->labels->singular_name; ?>
        </h3>
        <section class="tag_could">
            <?php foreach($tag_tags_list as $tag) { ?>
            <a href="<?php echo get_tag_link($tag); ?>" title="<?php printf( __( '标签 %s 下有 %s 篇文章' , 'salong' ), esc_attr($tag->name), esc_attr($tag->count) ); ?>" target="_blank">
                <span><?php echo $tag->name; ?></span><b>(<?php echo $tag->count; ?>)</b></a>
            <?php } ?>
        </section>
    </section>
    <hr>
    <?php } ?>
    <?php } ?>
<?php }
add_shortcode('tag','salong_tag_page');

function salong_sticky_like($atts){
    global $salong,$post,$wp_query;
    extract(
        shortcode_atts(
            array(
                'post_state' => 'sticky'
            ),
            $atts
        )
    );
    if(trim($post_state,'&quot;') == 'like'){
        $post_in  = '';
        $orderby  = 'meta_value_num';
        $meta_key = 'salong_post_like_count';
    }else{
        $post_in  = get_option('sticky_posts');
        $orderby  = '';
        $meta_key = '';
    }
    ?>
    <section class="sticky_like">
        <ul class="ajaxposts layout_ul">
            <?php $paged=( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;$args=array( 'post_type'=> 'post','ignore_sticky_posts' => 1,'paged' => $paged,'post__in'=> $post_in,'meta_key'=>$meta_key,'orderby'=>$orderby );$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
            <li class="ajaxpost layout_li">
                <article class="sl_main">
                    <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
                        <?php post_thumbnail(); ?>
                        <h4><?php the_title(); ?></h4>
                        <?php if(trim($post_state,'&quot;') == 'like'){ ?>
                        <span class="count">
                            <?php echo get_post_meta( $post->ID, "salong_post_like_count", true ); ?>
                        </span>
                        <?php } ?>
                    </a>
                </article>
            </li>
            <?php endwhile; posts_pagination(); else: ?>
            <p class="warningbox">
                <?php _e( '非常抱歉，没有置顶文章。', 'salong'); ?>
            </p>
            <?php endif;wp_reset_query(); ?>
        </ul>
    </section>
    <?php }
add_shortcode('sticky_like','salong_sticky_like');

function salong_home_cat($post_type,$taxonomy){
    global $salong,$post;
    $cat_list   = $salong['home_cat_'.$post_type];
    if(empty($cat_list))
        return;
    $cat_count  = $salong['home_cat_count_'.$post_type];
    if($post_type == 'post'){
        $list_name  = 'grid';
        $class      = 'grid_post';
    }else{
        $list_name  = 'list';
        $class      = $post_type.'_list';
    }
    ?>

    
    <?php foreach($cat_list as $cat){ $get_category = get_terms(array('include'=>$cat,'taxonomy'=>$taxonomy)); $cat_desc = $get_category[0]->description; ?>
    <section class="<?php echo $class; ?>">
        
        <section class="home_title">
            <section class="title">
                <h3>
                    <?php echo $get_category[0]->name;?>
                </h3>
                <?php if($cat_desc && $salong['switch_home_cat_'.$post_type]){ ?>
                <span><?php echo $cat_desc; ?></span>
                <?php } ?>
            </section>
            <section class="button">
                <a href="<?php echo get_term_link( (int)$cat, $taxonomy );?>" title="<?php _e( '查看更多', 'salong' ); ?>" <?php echo new_open_link(); ?>><?php echo _e('更多','salong').svg_more(); ?></a>
            </section>
        </section>
        
        <ul class="layout_ul">
            <?php $args=array( 'post_type'=> $post_type,'posts_per_page' => $cat_count,'ignore_sticky_posts' => 1,'tax_query' => array( array( 'taxonomy' => $taxonomy, 'field' => 'id', 'terms' => $cat )));$wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();?>
            <li class="layout_li">
                <?php get_template_part( 'content/'.$list_name, $post_type); ?>
            </li>
            <?php endwhile;endif; ?>
        </ul>
    </section>
    <?php }
}

function salong_kx_info($type){
    global $salong,$post;
    $metas = $salong['kx_metas'];
    $share = $salong['switch_kx_share'];
    $link = get_post_meta( $post->ID, 'link', true );
?>

    <?php if($metas || $share){ ?>
    <div class="postinfo">
        <?php if($metas){ ?>
        <div class="left">
            <?php if(in_array( 'time', $metas)) { ?>
            <span class="time"><?php echo svg_time(); ?><b><?php if($type=='list'){ echo get_the_time(); }else{ echo get_the_date(); } ?></b></span>
            <?php }if(in_array( 'view', $metas)) { ?>
            <span class="view"><?php echo svg_view(); ?><b><?php echo getPostViews(get_the_ID()); ?></b></span>
            <?php } ?>
        </div>
        <?php } ?>
        <div class="right">
            <?php if($share && $type!='widget') { ?>
                <?php get_template_part( 'content/share', 'btn'); ?>
            <?php } ?>
            <?php if($link && is_singular( 'kx' )){ ?>
            <a href="<?php echo $salong['switch_link_go'] ? external_link($link) : $link; ?>" target="_blank"><?php echo $salong['salong_kx_from_name']; ?></a>
            <?php } ?>
        </div>
    </div>
    <?php }

}

function Baidu_Submit($post_ID) {
    global $salong,$post;
    $WEB_TOKEN  = $salong['web_token'];      $WEB_DOMAIN = get_option('home');
    if(get_post_meta($post_ID,'baidusubmit',true) == 1 && $salong['switch_baidu_submit']) return;
    $url = get_permalink($post_ID);
    $api = 'http://data.zz.baidu.com/urls?site='.$WEB_DOMAIN.'&token='.$WEB_TOKEN;
    $ch  = curl_init();
    $options =  array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $url,
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
    curl_setopt_array($ch, $options);
    $result = json_decode(curl_exec($ch),true);
        if (array_key_exists('success',$result)) {
        update_post_meta($post_ID, 'baidusubmit', 1, true);
    }
}

add_action('init', 'salong_type_baidu_submit', 100);
function salong_type_baidu_submit() {
    global $salong;
        $bd_type = $salong['baidu_post_type'];
	if ( is_array($bd_type) ) {
		foreach($bd_type as $type) {
            add_action('publish_'.$type, 'Baidu_Submit', 0);
		}
	} 
}

function salong_insert_xz($content){
    global $salong,$post;
    $index = '';
    $type = get_post_type();
    $xz_type = $salong['xiongzhang_post_type'];
    if( is_array($xz_type) && in_array($type,$xz_type) && $salong['switch_xiongzhang']){
        $index = "<script>cambrian.render('tail')</script>";
        $content = $content.$index;
    }
    return $content;
}
add_filter('the_content', 'salong_insert_xz');

function post_thumbnail_src(){
    global $post,$salong;
    $thumb = get_post_meta( $post->ID, 'thumb', true );
    if( $thumb ) {
        $src = $thumb;
    } elseif( has_post_thumbnail() ){
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        $src = $thumbnail_src [0];
    } else {
        $src = '';
        ob_start();
        ob_end_clean();
        $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
        if(!empty($matches[1][0])){
            $src = $matches[1][0];
        }else{  
            $src = $salong['default_thumb']['url'];
        }
    }
    return $src;
}



function BYMT_comment_post( $incoming_comment ) {
    $pattern = '/[一-龥]/u';
    $jpattern ='/[ぁ-ん]+|[ァ-ヴ]+/u';
    if(!preg_match($pattern, $incoming_comment['comment_content'])) {
        err( "写点汉字吧，博主英文过了四级，但还是不认识英文！Please write some chinese words！" );
    }
    if(preg_match($jpattern, $incoming_comment['comment_content'])){
        err( "日文滚粗！Japanese Get out！日本語出て行け！" );
    }
    return( $incoming_comment );
}
add_filter('preprocess_comment', 'BYMT_comment_post');

function in_comment_post_like($string, $array) {
    foreach($array as $ref) { if(strstr($string, $ref)) { return true; } }
    return false;
}
function drop_bad_comments() {
    if (!empty($_POST['comment'])) {
        global $salong;
        $bad_comments         = $salong['bad_comments'];
        $bad_comments_arr     = explode(PHP_EOL,$bad_comments);
        $post_comment_content = $_POST['comment'];
        $lower_case_comment   = strtolower($_POST['comment']);
        $bad_comment_content  = $bad_comments_arr;
        if (in_comment_post_like($lower_case_comment, $bad_comment_content)) {
            $comment_box_text = wordwrap(trim($post_comment_content), 80, "\n  ", true);
            $txtdrop = fopen('/var/log/httpd/wp_post-logger/nullamatix.com-text-area_dropped.txt', 'a');
            fwrite($txtdrop, "  --------------\n  [COMMENT] = " . $post_comment_content . "\n  --------------\n");
            fwrite($txtdrop, "  [SOURCE_IP] = " . $_SERVER['REMOTE_ADDR'] . " @ " . date("F j, Y, g:i a") . "\n");
            fwrite($txtdrop, "  [USERAGENT] = " . $_SERVER['HTTP_USER_AGENT'] . "\n");
            fwrite($txtdrop, "  [REFERER  ] = " . $_SERVER['HTTP_REFERER'] . "\n");
            fwrite($txtdrop, "  [FILE_NAME] = " . $_SERVER['SCRIPT_NAME'] . " - [REQ_URI] = " . $_SERVER['REQUEST_URI'] . "\n");
            fwrite($txtdrop, '--------------**********------------------'."\n");
            header("HTTP/1.1 406 Not Acceptable");
            header("Status: 406 Not Acceptable");
            header("Connection: Close");
            wp_die( '<p class="bad">'.$salong['bad_comment_text'].'</p>' );
        }
    }
}
add_action('init', 'drop_bad_comments');

if($salong['switch_admin_link']){
    add_action('login_enqueue_scripts','login_protection');
    function login_protection(){
        global $salong;
        if($_GET[''.$salong['admin_word'].''] != ''.$salong['admin_press'].'')header('Location: '.get_home_url().'');
    }
}

if($salong['switch_incoming_comment']){
    function salong_usecheck($incoming_comment) {
        $isSpam = 0;
        global $salong;
        if (trim($incoming_comment['comment_author_email']) == ''.$salong['admin_email'].'')
            $isSpam = 1;
        if(!$isSpam)
            return $incoming_comment;
        wp_die(__('<p class="warningbox">请勿冒充博主发表评论</p>','salong'));
    }
    if(!is_user_logged_in())
        add_filter( 'preprocess_comment', 'salong_usecheck' );
}

if ($salong['switch_weihu']) {
    function wp_maintenance_mode(){
        if(!current_user_can('edit_themes') || !is_user_logged_in()){
            wp_die(''.sprintf( __( '%s临时维护中，请稍后访问，给您带来的不便，敬请谅解！' , 'salong' ), esc_attr(get_option('blogname'))).'', ''.sprintf( __( '%s维护中' , 'salong' ), esc_attr(get_option('blogname'))).'', array('response' => '503'));
        }
    }
    add_action('get_header', 'wp_maintenance_mode');
}

function block_admin_access() {
    global $pagenow,$salong;

    if ( defined( 'WP_CLI' ) ) {
        return;
    }

    $access_level = $salong['admin_access'];
    $valid_pages  = array('admin-ajax.php', 'admin-post.php', 'async-upload.php', 'media-upload.php');

    if ( ! current_user_can( $access_level ) && !in_array( $pagenow, $valid_pages ) ) {
        wp_redirect(get_home_url());
        exit;
    }
}
add_action( 'admin_init', 'block_admin_access' );


$access_level = $salong['admin_access'];
if($salong['switch_admin_bar']){
    if ( ! current_user_can( $access_level )) {
        add_filter('show_admin_bar', '__return_false');
    }
}else{
    add_filter('show_admin_bar', '__return_false');
}

if($salong['switch_author_id']){
        function lxtx_remove_comment_body_author_class( $classes ) {
        foreach( $classes as $key => $class ) {
            if(strstr($class, "comment-author-")||strstr($class, "author-")) {
                unset( $classes[$key] );
            }
        }
        return $classes;
    }
    add_filter( 'comment_class' , 'lxtx_remove_comment_body_author_class' );
    add_filter('body_class', 'lxtx_remove_comment_body_author_class');

        add_filter( 'author_link', 'yundanran_author_link', 10, 2 );
    function yundanran_author_link( $link, $author_id) {
        global $wp_rewrite;
        $author_id = (int) $author_id;
        $link = $wp_rewrite->get_author_permastruct();

        if ( empty($link) ) {
            $file = home_url( '/' );
            $link = $file . '?author=' . $author_id;
        } else {
            $link = str_replace('%author%', $author_id, $link);
            $link = home_url( user_trailingslashit( $link ) );
        }

        return $link;
    }

    add_filter( 'request', 'yundanran_author_link_request' );
    function yundanran_author_link_request( $query_vars ) {
        if ( array_key_exists( 'author_name', $query_vars ) ) {
            global $wpdb;
            $author_id=$query_vars['author_name'];
            if ( $author_id ) {
                $query_vars['author'] = $author_id;
                unset( $query_vars['author_name'] );    
            }
        }
        return $query_vars;
    }
}

function salong_center_page(){
    global $current_user,$salong,$pagenow,$wp_query;
    $current_id     = $current_user->ID;    $current_email  = $current_user->user_email;
    $curauth        = $wp_query->get_queried_object();    @$curauth_id     = $curauth->ID;    $scheme         = is_ssl() && !is_admin() ? 'https' : 'http';
    $current_url    = $scheme . '://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];    $author_url     = get_author_posts_url($current_id);    $profile_url    = get_author_posts_url($current_id).'?tab=edit-profile-password';    $contribute_url = get_author_posts_url($current_id).'?tab=contribute';    @$get_tab        = $_GET['tab'];    @$get_post_id    = $_GET['post_id'];    $post           = get_post($get_post_id);
    $author_id      = $post->post_author;
    if (is_user_logged_in()){
        if (!$current_email && $current_url!=$profile_url && $salong['switch_user_add_email']){
            wp_redirect($profile_url);
            exit;
        }
        
        if ($curauth_id != $current_id){
            if ($get_tab == 'contribute'){
                wp_redirect($contribute_url);
                exit;
            }
            if ($get_tab == 'message'){
                wp_redirect($author_url.'?tab=message');
                exit;
            }
            if ($get_tab == 'message-inbox'){
                wp_redirect($author_url.'?tab=message-inbox');
                exit;
            }
            if ($get_tab == 'message-outbox'){
                wp_redirect($author_url.'?tab=message-outbox');
                exit;
            }
        }
        if ($get_tab == 'edit'){
            if ($author_id != $current_id || !$get_post_id || $curauth_id != $current_id){
                wp_redirect($author_url);
                exit;
            }
        }
    }else{
        if($get_tab == 'edit-profile' || $get_tab == 'edit-profile-extension' || $get_tab == 'edit-profile-password' || $get_tab == 'contribute' || $get_tab == 'edit' || $get_tab == 'message' || $get_tab == 'message-inbox' || $get_tab == 'message-outbox'){
            wp_redirect(home_url());
            exit;
        }
    }
}
add_action( 'wp', 'salong_center_page', 3 );





if($salong['switch_minify_html']){
    function salong_minify_html($html) {
        $search = array(
            '/\>[^\S ]+/s',              '/[^\S ]+\</s',              '/(\s)+/s'               );
        $replace = array(
            '>',
            '<',
            '\\1'
        );
        $html = preg_replace($search, $replace, $html);
        return $html;
    }
    if(!is_admin()){
      add_action("wp_loaded", 'wp_loaded_minify_html');
      function wp_loaded_minify_html(){
        ob_start('salong_minify_html');
      }
    }
}

if($salong['switch_filter_time']){
    function salong_filter_time(){
        global $post ;
        $to = time();
        $from = get_the_time('U') ;
        $diff = (int) abs($to - $from);
        if ($diff <= 3600) {
            $mins = round($diff / 60);
            if ($mins <= 1) {
                $mins = 1;
            }
            $time = sprintf(_n('%s 分钟', '%s 分钟', $mins), $mins) . __( '前' , 'salong' );
        }
        else if (($diff <= 86400) && ($diff > 3600)) {
            $hours = round($diff / 3600);
            if ($hours <= 1) {
                $hours = 1;
            }
            $time = sprintf(_n('%s 小时', '%s 小时', $hours), $hours) . __( '前' , 'salong' );
        }
        elseif ($diff >= 86400) {
            $days = round($diff / 86400);
            if ($days <= 1) {
                $days = 1;
                $time = sprintf(_n('%s 天', '%s 天', $days), $days) . __( '前' , 'salong' );
            }
            elseif( $days > 29){
                $time = get_the_time(get_option('date_format'));
            }
            else{
                $time = sprintf(_n('%s 天', '%s 天', $days), $days) . __( '前' , 'salong' );
            }
        }
        return $time;
    }
    add_filter('the_time','salong_filter_time');
}

if($salong['switch_filter_count']){
    function salong_format_count( $number ) {
        $precision = 2;
        if ( $number >= 1000 && $number < 10000 ) {
            $formatted = number_format( $number/1000, $precision ).'K';
        } else if ( $number >= 10000 && $number < 1000000 ) {
            $formatted = number_format( $number/10000, $precision ).'W';
        } else if ( $number >= 1000000 && $number < 1000000000 ) {
            $formatted = number_format( $number/1000000, $precision ).'M';
        } else if ( $number >= 1000000000 ) {
            $formatted = number_format( $number/1000000000, $precision ).'B';
        } else {
            $formatted = $number;         }
        $formatted = str_replace( '.00', '', $formatted );
        return $formatted;
    }
}

function new_open_link(){
    global $salong;
    $output = '';
    if($salong['switch_new_open_link']){
        $output .= ' target="_blank"';
        return $output;
    }
}

if ($salong['switch_useradd_time']) {
    
        add_action('user_register', 'log_ip');
    function log_ip($user_id){
        $ip = $_SERVER['REMOTE_ADDR'];
        update_user_meta($user_id, 'signup_ip', $ip);
    }
        add_action( 'wp_login', 'insert_last_login' );
    function insert_last_login( $login ) {
        global $user_id;
        $user = get_userdatabylogin( $login );
        update_user_meta( $user->ID, 'last_login', current_time( 'mysql' ) );
        $last_login_ip = $_SERVER['REMOTE_ADDR'];
        update_user_meta( $user->ID, 'last_login_ip', $last_login_ip);
    }
        add_filter('manage_users_columns', 'add_user_additional_column');
    function add_user_additional_column($columns) {
        $columns['reg_time'] = '注册时间';
        $columns['last_login'] = '上次登录';
        return $columns;
    }
        add_action('manage_users_custom_column',  'show_user_additional_column_content', 10, 3);
    function show_user_additional_column_content($value, $column_name, $user_id) {
        $user = get_userdata( $user_id );
                if('reg_time' == $column_name ){
            return get_date_from_gmt($user->user_registered) .'<br />'.get_user_meta( $user->ID, 'signup_ip', true);
        }
                if ( 'last_login' == $column_name && $user->last_login ){
            return get_user_meta( $user->ID, 'last_login', ture ).'<br />'.get_user_meta( $user->ID, 'last_login_ip', ture );
        }
        return $value;
    }
        add_filter( "manage_users_sortable_columns", 'cmhello_users_sortable_columns' );
    function cmhello_users_sortable_columns($sortable_columns){
        $sortable_columns['reg_time'] = 'reg_time';
        return $sortable_columns;
    }
    add_action( 'pre_user_query', 'cmhello_users_search_order' );
    function cmhello_users_search_order($obj){
        if(!isset($_REQUEST['orderby']) || $_REQUEST['orderby']=='reg_time' ){
            if( !in_array($_REQUEST['order'],array('asc','desc')) ){
                $_REQUEST['order'] = 'desc';
            }
            $obj->query_orderby = "ORDER BY user_registered ".$_REQUEST['order']."";
        }
    }
}

global $salong;
if ($salong['switch_link_go']) {
	add_filter('the_content','link_to_jump',999);
	function link_to_jump($content){
		preg_match_all('/<a(.*?)href="(.*?)"(.*?)>/',$content,$matches);
		if($matches){
		    foreach($matches[2] as $val){
			    if(strpos($val,'://')!==false && strpos($val,home_url())===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i',$val) && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$val)){
			    	$content=str_replace("href=\"$val\"", "href=\"".get_page_link(get_page_id_from_template('template-go.php'))."?url=$val\" ",$content);
				}
			}
		}
		return $content;
	}

		function commentauthor($comment_ID = 0) {
	    $url    = get_comment_author_url( $comment_ID );
	    $author = get_comment_author( $comment_ID );
	    if ( empty( $url ) || 'http://' == $url )
	    echo $author;
	    else
	    echo "<a href='".get_page_link(get_page_id_from_template('template-go.php'))."?url=$url' rel='external nofollow' target='_blank' class='url'>$author</a>";
	}
    
    	function external_link($url) {
	    if(strpos($url,'://')!==false && strpos($url,home_url())===false && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$url)) {
			$url = str_replace($url, get_page_link(get_page_id_from_template('template-go.php'))."?url=".$url,$url);
	     }
	     return $url;
	}

}

if($salong[ 'switch_upload_path']) {
    if(get_option('upload_path')=='wp-content/uploads'|| get_option('upload_path')==null){
        update_option('upload_path',WP_CONTENT_DIR.'/uploads');
    }
}

if($salong[ 'switch_date_default']) {
    date_default_timezone_set("Asia/Shanghai");
}

if($salong[ 'remove_category_slug']){
    require_once get_template_directory() . '/includes/no-category.php';
}

if($salong['switch_feed']){
    function salong_disable_feed() {
        wp_die(__('<h1>本博客不再提供 Feed，请访问网站<a href="'.get_bloginfo('url').'">首页</a>！</h1>'));
    }
    add_action('do_feed', 'salong_disable_feed', 1);
    add_action('do_feed_rdf', 'salong_disable_feed', 1);
    add_action('do_feed_rss', 'salong_disable_feed', 1);
    add_action('do_feed_rss2', 'salong_disable_feed', 1);
    add_action('do_feed_atom', 'salong_disable_feed', 1);
}

if($salong['switch_header_code']){
        remove_action('wp_head', 'wp_generator');     foreach (array('rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head', 'opml_head', 'app_head') as $action) {
        remove_action($action, 'the_generator');
    }

    remove_action('wp_head', 'rsd_link');     remove_action('wp_head', 'wlwmanifest_link'); 
    remove_action('wp_head', 'feed_links_extra', 3);     
    remove_action('wp_head', 'index_rel_link');     remove_action('wp_head', 'parent_post_rel_link', 10);
    remove_action('wp_head', 'start_post_rel_link', 10);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0); 
    remove_action('wp_head', 'rest_output_link_wp_head', 10); 
    remove_action('template_redirect', 'wp_shortlink_header', 11);     remove_action('template_redirect', 'rest_output_link_header', 11); }

if($salong['switch_capital_P_dangit']){
        remove_filter( 'the_content', 'capital_P_dangit' );
    remove_filter( 'the_title', 'capital_P_dangit' );
    remove_filter( 'comment_text', 'capital_P_dangit' );
}

if($salong['switch_shortcode_unautop']){
        remove_filter('the_content', 'wpautop');
    add_filter('the_content', 'wpautop', 12);
    remove_filter('the_content', 'shortcode_unautop');
    add_filter('the_content', 'shortcode_unautop', 13);
}

if($salong['switch_rest_api']){
        remove_action( 'init',          'rest_api_init' );
    remove_action( 'rest_api_init', 'rest_api_default_filters', 10 );
    remove_action( 'parse_request', 'rest_api_loaded' );
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');
        remove_action('wp_head', 'rest_output_link_wp_head', 10 );
    remove_action('template_redirect', 'rest_output_link_header', 11 );
}

if($salong['switch_wp_oembed']){
        remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 );
    remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
    remove_action( 'pre_post_update', array( $GLOBALS['wp_embed'], 'delete_oembed_caches' ) );
    remove_action( 'edit_form_advanced', array( $GLOBALS['wp_embed'], 'maybe_run_ajax_cache' ) );
    
        remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4 );
    add_filter( 'embed_oembed_discover', '__return_false' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_filter( 'oembed_response_data',   'get_oembed_response_data_rich',  10, 4 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'salong_disable_post_embed_tiny_mce_plugin' );
    function salong_disable_post_embed_tiny_mce_plugin($plugins){
        return array_diff( $plugins, array( 'wpembed' ) );
    }
    add_filter('query_vars', 'salong_disable_post_embed_query_var');
    function salong_disable_post_embed_query_var($public_query_vars) {
        return array_diff($public_query_vars, array('embed'));
    }
}

if($salong['switch_dashboard_widgets']){
        add_action('wp_dashboard_setup', 'salong_remove_dashboard_widgets');
    function salong_remove_dashboard_widgets(){
        global $wp_meta_boxes;
        unset($wp_meta_boxes['dashboard']['normal']);
        unset($wp_meta_boxes['dashboard']['side']);
    }
}

if($salong['switch_staticize_emoji']){
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    remove_action('embed_head', 'print_emoji_detection_script');

    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    add_filter('tiny_mce_plugins', 'wpjam_disable_emoji_tiny_mce_plugin');
    function wpjam_disable_emoji_tiny_mce_plugin($plugins)
    {
        return array_diff($plugins, array('wpemoji'));
    }

    add_filter('emoji_svg_url', '__return_false');
}

if($salong['switch_wp_cron']){
        defined('DISABLE_WP_CRON');
    remove_action( 'init', 'wp_cron' );
}

if($salong['switch_xmlrpc_enabled']){
        add_filter('xmlrpc_enabled', '__return_false');
}

if($salong['switch_pingback']){
    add_filter('xmlrpc_methods','salong_xmlrpc_methods');
    function salong_xmlrpc_methods($methods){
        $methods['pingback.ping'] = '__return_false';
        $methods['pingback.extensions.getPingbacks'] = '__return_false';
        return $methods;
    }
        remove_action( 'do_pings', 'do_all_pings', 10, 1 );
        remove_action( 'publish_post','_publish_post_hook',5, 1 );
}

if($salong['switch_admin_color_schemes']){
        remove_action( 'admin_init', 'register_admin_color_schemes', 1);
    remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
}

if($salong['switch_update_core']){
        add_filter ('pre_site_transient_update_core', '__return_null');
    remove_action ('load-update-core.php', 'wp_update_plugins');
    add_filter ('pre_site_transient_update_plugins', '__return_null');
    remove_action ('load-update-core.php', 'wp_update_themes');
    add_filter ('pre_site_transient_update_themes', '__return_null');
}

if($salong['switch_post_revision']){
        add_filter( 'wp_revisions_to_keep', 'specs_wp_revisions_to_keep', 10, 2 );
    function specs_wp_revisions_to_keep( $num, $post ) {
        return 0;
    }
}

if($salong['switch_autosave']){
        add_action('admin_print_scripts', create_function( '$a', "wp_deregister_script('autosave');"));
}

if($salong['switch_recently_active_plugins']){
        add_action('admin_head', 'disable_recently_active_plugins');
    function disable_recently_active_plugins() {
        update_option('recently_activated', array());
    }
}

if($salong['switch_max_srcset']){
        function add_image_insert_override( $sizes ){
        global $salong;
        if( $salong['thumb_mode']== 'timthumb'){
            unset( $sizes[ 'thumbnail' ]);
            unset( $sizes[ 'medium' ]);
            unset( $sizes[ 'shop_thumbnail' ]);
            unset( $sizes[ 'shop_catalog' ]);
            unset( $sizes[ 'shop_single' ]);
            unset( $sizes[ 'woocommerce_thumbnail' ]);
            unset( $sizes[ 'woocommerce_single' ]);
            unset( $sizes[ 'woocommerce_gallery_thumbnail' ]);
        }
        unset( $sizes[ 'medium_large' ] );
        unset( $sizes[ 'large' ]);
        unset( $sizes[ 'full' ] );
        return $sizes;
    }
    add_filter( 'intermediate_image_sizes_advanced', 'add_image_insert_override' );
}

if($salong['switch_login_errors']){
        function failed_login() {
        return '';
    }
    add_filter('login_errors', 'failed_login');
}

if($salong['switch_redirect_single_post']){
        add_action('template_redirect', 'salong_redirect_single_post');
    function salong_redirect_single_post() {
        if (is_search()) {
            global $wp_query;
            if ($wp_query->post_count == 1) {
                wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
            }
        }
    }
}

if($salong['switch_search_by_title_only']){
        function __search_by_title_only( $search,$wp_query ){
        global $wpdb;

        if ( empty( $search ) )
            return $search; 
        $q = $wp_query->query_vars;    
        $n = ! empty( $q['exact'] ) ? '' : '%';

        $search =
        $searchand = '';

        foreach ( (array) $q['search_terms'] as $term ) {
            $term = esc_sql( like_escape( $term ) );
            $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $searchand = ' AND ';
        }

        if ( ! empty( $search ) ) {
            $search = " AND ({$search}) ";
            if ( ! is_user_logged_in() )
                $search .= " AND ($wpdb->posts.post_password = '') ";
        }

        return $search;
    }
    add_filter( 'posts_search', '__search_by_title_only', 500, 2 );
}

if($salong['switch_remove_logo']){
        function salong_admin_bar_remove(){
        global$wp_admin_bar;
        $wp_admin_bar->remove_menu('wp-logo');
    }
    add_action('wp_before_admin_bar_render','salong_admin_bar_remove',0);
}

if($salong['switch_shortcode_auto'] && is_single()){
        remove_filter( 'the_content', 'wpautop' );
    add_filter( 'the_content', 'wpautop' , 12);
}

if($salong['switch_content_auto']){
        remove_filter (  'the_content' ,  'wpautop'  );
    remove_filter (  'the_excerpt' ,  'wpautop'  );
}