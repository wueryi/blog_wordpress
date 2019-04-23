<?php global $salong,$post,$wp,$wp_query;

$current_url = home_url(add_query_arg(array(),$wp->request));//当前链接

if(is_page()){
    if(is_page_template('template-topic.php')){
        $type     = 'topic';
        $taxonomy = 'tcat';
        $link     = get_permalink(get_page_id_from_template('template-topic.php'));
    }else if(is_page_template('template-video.php')){
        $type     = 'video';
        $taxonomy = 'vcat';
        $link     = get_permalink(get_page_id_from_template('template-video.php'));
    }else if(is_page_template('template-download.php')){
        $type     = 'download';
        $taxonomy = 'dcat';
        $link     = get_permalink(get_page_id_from_template('template-download.php'));
    }else{
        $type     = 'post';
        $taxonomy = 'category';
        $link     = get_permalink(get_page_id_from_template('template-post.php'));
    }
    $li_class = ' class="current-cat"';
}else{
    $type               = get_post_type();
    $current_cat        = $wp_query->queried_object;//当前分类
    $taxonomy           = $current_cat->taxonomy;//当前分类
    $current_cat_ID     = $current_cat->term_id;//当前分类 ID
    $current_parent_id  = $current_cat->parent;//父级分类 ID
    $top_cat_id         = salong_category_top_parent_id ($current_cat_ID,$taxonomy);//顶级分类ID
    $children_cat       = get_terms( $taxonomy, array('parent'=> $current_cat_ID,'hide_empty' => false) );//子分类
    
    $link               = get_permalink(get_page_id_from_template('template-'.$type.'.php'));

    if($current_parent_id && $top_cat_id != $current_parent_id){
        //三级分类
        $cate3_id     = $current_parent_id;
        $cate2_id     = $top_cat_id;
        $cate2_url    = get_term_link( (int)$top_cat_id, $taxonomy );
        $cate3_url    = get_term_link( (int)$current_parent_id, $taxonomy );
        $subli_class  = '';
        $level = 3;
    }else if($current_parent_id && $top_cat_id == $current_parent_id){
        //二级分类
        $cate3_id     = $current_cat_ID;
        $subsubli_class  = ' class="current-cat"';
        $cate2_id     = $current_parent_id;
        $cate2_url    = get_term_link( (int)$current_parent_id, $taxonomy );
        $subli_class  = '';
        $level = 2;
    }else{
        //一级分类
        $cate2_id     = $current_cat_ID;
        $cate2_url    = get_term_link( (int)$current_cat_ID, $taxonomy );
        $subli_class  = ' class="current-cat"';
        $level = 1;
    }
    
    $li_class = '';
    $args_cate2 = array('title_li'=>'','show_count'=>1,'orderby'=>'count','order'=>'DESC','taxonomy'=>$taxonomy,'child_of'=>$cate2_id,'depth'=>1);//二级分类
    $args_cate3 = array('title_li'=>'','show_count'=>1,'orderby'=>'count','order'=>'DESC','taxonomy'=>$taxonomy,'child_of'=>$cate3_id,'depth'=>1);//三级分类
}
$args_cate1 = array( 'title_li'=>'','show_count'=>1,'orderby'=>'count','order'=>'DESC','taxonomy'=>$taxonomy,'depth'=>1);//一级分类

?>


<section class="product_sift">
    <div class="sift_li">
        <h4>
            <?php _e('分类','salong'); ?>
        </h4>
        <ul class="sift_more">
            <li<?php echo $li_class; ?>>
                <a href="<?php echo $link; ?>">
                    <?php _e('全部','salong'); ?>
                </a>
                (<?php echo wp_count_posts($type)->publish; ?>)
            </li>
            <?php wp_list_categories($args_cate1); ?>
        </ul>
    </div>
    <?php if( ($children_cat && $level == 1 ) || $level == 2 || $level == 3){ ?>
    <div class="sift_li">
        <h4><?php _e('二级分类','salong'); ?></h4>
        <ul class="sift_more">
            <li<?php echo $subli_class; ?>>
                <a href="<?php echo $cate2_url; ?>">
                    <?php _e('全部','salong'); ?>
                </a>
                (<?php echo salong_category_post_count($cate2_id,$taxonomy); ?>)
            </li>
            <?php wp_list_categories($args_cate2); ?>
        </ul>
    </div>
    <?php } ?>
    <?php if( ($children_cat && $level == 2 ) || $level == 3){ ?>
    <div class="sift_li">
        <h4><?php _e('三级分类','salong'); ?></h4>
        <ul class="sift_more">
            <li<?php echo $subsubli_class; ?>>
                <a href="<?php echo $cate3_url; ?>">
                    <?php _e('全部','salong'); ?>
                </a>
                (<?php echo salong_category_post_count($cate3_id,$taxonomy); ?>)
            </li>
            <?php wp_list_categories($args_cate3); ?>
        </ul>
    </div>
    <?php } ?>
    <div class="sift_li">
        <h4>
            <?php _e('排序','salong'); ?>
        </h4>
        <ul class="sift_more">
            <li <?php if ( !isset($_GET[ 'order']) || ($_GET[ 'order']=='date' ) ) echo 'class="current-cat"'; ?>>
                <a href="<?php echo $current_url; ?>" rel="nofollow">
                    <?php _e( '默认', 'salong' ); ?>
                </a>
            </li>
            <li <?php if ( isset($_GET[ 'order']) && ($_GET[ 'order']=='title' ) ) echo 'class="current-cat"'; ?>>
                <a href="<?php echo $current_url; ?>/?order=title" rel="nofollow">
                    <?php _e( '标题', 'salong' ); ?>
                </a>
            </li>
            <li <?php if ( isset($_GET[ 'order']) && ($_GET[ 'order']=='like' ) ) echo 'class="current-cat"'; ?>>
                <a href="<?php echo $current_url; ?>/?order=like" rel="nofollow">
                    <?php _e( '点赞', 'salong' ); ?>
                </a>
            </li>
            <li <?php if ( isset($_GET[ 'order']) && ($_GET[ 'order']=='views' ) ) echo 'class="current-cat"'; ?>>
                <a href="<?php echo $current_url; ?>/?order=views" rel="nofollow">
                    <?php _e( '浏览', 'salong' ); ?>
                </a>
            </li>
            <?php if(is_page_template('template-download.php') || is_tax('dcat')){ ?>
            <li <?php if ( isset($_GET[ 'order']) && ($_GET[ 'order']=='download' ) ) echo 'class="current-cat"'; ?>>
                <a href="<?php echo $current_url; ?>/?order=download" rel="nofollow">
                    <?php _e( '下载', 'salong' ); ?>
                </a>
            </li>
            <?php } if(is_page_template('template-video.php') || is_tax('vcat')){ ?>
            <li <?php if ( isset($_GET[ 'order']) && ($_GET[ 'order']=='time' ) ) echo 'class="current-cat"'; ?>>
                <a href="<?php echo $current_url; ?>/?order=time" rel="nofollow">
                    <?php _e( '时长', 'salong' ); ?>
                </a>
            </li>
            <?php } ?>
            <li <?php if ( isset($_GET[ 'order']) && ($_GET[ 'order']=='comment' ) ) echo 'class="current-cat"'; ?>>
                <a href="<?php echo $current_url; ?>/?order=comment" rel="nofollow">
                    <?php _e( '热评', 'salong' ); ?>
                </a>
            </li>
            <li <?php if ( isset($_GET[ 'order']) && ($_GET[ 'order']=='rand' ) ) echo 'class="current-cat"'; ?>>
                <a href="<?php echo $current_url; ?>/?order=rand" rel="nofollow">
                    <?php _e( '随机', 'salong' ); ?>
                </a>
            </li>
        </ul>
    </div>
</section>

<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/readmore.min.js"></script>
<script type="text/javascript">
    $window_width = $(window).width();

    if($window_width > 480){
        $height = 44;
    }else{
        $height = 36;
    }

    /*更多*/
    $('.sift_more').readmore({
        moreLink: '<a href="#" class="more"><?php _e('更多','salong'); ?></a>',
        lessLink: '<a href="#" class="more"><?php _e('收起','salong'); ?></a>',
        speed: 75,
        collapsedHeight: $height
    });

</script>