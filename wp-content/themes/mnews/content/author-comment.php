<?php global $salong,$wp_query;
$curauth            = $wp_query->get_queried_object();//当前用户
$user_id            = $curauth->ID;//当前用户 ID
$user_name          = $curauth->display_name;
$comments_count     = get_comments( array('status' => '1', 'user_id'=>$user_id, 'count' => true) );//当前用户评论数量
$all_comments_count = get_comments( array('status' => '', 'user_id'=>$curauth->ID, 'count' => true) );

/*每页显示的评论数量*/
$comments_per_page= get_option( 'comments_per_page' );
/*分页*/
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
/*评论数量偏移*/
$offset = (($paged-1) * $comments_per_page) ;
/*最大页面数量*/
$max_num_pages = ceil( $comments_count / $comments_per_page );
/*获取作者评论*/
$comments = get_comments(array(
    'status'  => 'approve',
    'number'  => $comments_per_page,
    'user_id' => $user_id,
    'offset'  => $offset
));
?>
<section class="comment_list">
    <?php if($comments){ echo '<span class="count">'.sprintf(__('%1$s 已经发表了 %2$s 条评论，其中 %3$s 条已获准， %4$s 条正等待审核。','salong'),$user_name,$all_comments_count, $comments_count, $all_comments_count-$comments_count).'</span>'; }else{ echo sprintf(__('<p class="warningbox">%s 还没有发表评论！</p>','salong'),$user_name); } ?>
    <ul class="ajaxposts">
        <?php
        /*显示评论*/
        if ( $comments ) {
            foreach ( $comments as $comment ) { ?>
               <li class="ajaxpost">
                   <?php echo salong_get_avatar($user_id,$user_name); ?>
                   <a href="<?php echo $comment->comment_author_url; ?>" title="<?php echo sprintf(__('访问 %s 的站点','salong'),$user_name); ?>" class="author_name"><h4><?php echo $comment->comment_author; ?></h4></a>
                   <time class="datetime"><?php echo $comment->comment_date; ?></time>
                   <span><?php echo sprintf(__('发表在：<a href="%s#comment-%s">%s</a>','salong'),get_permalink($comment->comment_post_ID),$comment->comment_ID,$comment->post_title); ?></span>
                   <?php $rate = get_comment_meta($comment->comment_ID, 'rate', true); if ($rate && $salong['switch_comment_rate']) { echo movie_grade($rate); } ?>
                   <p><?php echo $comment->comment_content; ?></p>
               </li>
            <?php }
        }?>
    </ul>
    
    <section class="pagination navigation">
    <?php
    /*当前分数*/
    $current_page = max(1, get_query_var('paged'));
    /*输出分页链接*/
    echo paginate_links(array(
        'base'      => get_author_posts_url($user_id).'/'. '%_%',
        'current'   => $current_page,
        'total'     => $max_num_pages,
        'prev_text' => '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 49.656 49.656" style="enable-background:new 0 0 49.656 49.656;" xml:space="preserve"><polygon points="14.535,48.242 11.707,45.414 32.292,24.828 11.707,4.242 14.535,1.414 37.949,24.828"/><path d="M14.535,49.656l-4.242-4.242l20.585-20.586L10.293,4.242L14.535,0l24.829,24.828L14.535,49.656z M13.121,45.414l1.414,1.414l22-22l-22-22l-1.414,1.414l20.585,20.586L13.121,45.414z"/></svg>','next_text' => '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 49.656 49.656" style="enable-background:new 0 0 49.656 49.656;" xml:space="preserve"><polygon points="14.535,48.242 11.707,45.414 32.292,24.828 11.707,4.242 14.535,1.414 37.949,24.828"/><path d="M14.535,49.656l-4.242-4.242l20.585-20.586L10.293,4.242L14.535,0l24.829,24.828L14.535,49.656z M13.121,45.414l1.414,1.414l22-22l-22-22l-1.414,1.414l20.585,20.586L13.121,45.414z"/></svg>',
        'next_text' => '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 49.656 49.656" style="enable-background:new 0 0 49.656 49.656;" xml:space="preserve"><polygon points="14.535,48.242 11.707,45.414 32.292,24.828 11.707,4.242 14.535,1.414 37.949,24.828"/><path d="M14.535,49.656l-4.242-4.242l20.585-20.586L10.293,4.242L14.535,0l24.829,24.828L14.535,49.656z M13.121,45.414l1.414,1.414l22-22l-22-22l-1.414,1.414l20.585,20.586L13.121,45.414z"/></svg>','next_text' => '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 49.656 49.656" style="enable-background:new 0 0 49.656 49.656;" xml:space="preserve"><polygon points="14.535,48.242 11.707,45.414 32.292,24.828 11.707,4.242 14.535,1.414 37.949,24.828"/><path d="M14.535,49.656l-4.242-4.242l20.585-20.586L10.293,4.242L14.535,0l24.829,24.828L14.535,49.656z M13.121,45.414l1.414,1.414l22-22l-22-22l-1.414,1.414l20.585,20.586L13.121,45.414z"/></svg>',
        'end_size'  => 2,
        'mid-size'  => 3
    ));
    ?>
    </section>
</section>
