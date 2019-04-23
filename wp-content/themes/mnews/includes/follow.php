<?php

global $salong;
header("Content-type: text/html; charset=utf-8");
if(!$salong['switch_follow_btn'])return;





 function salong_process_new_follow() {
    if ( isset( $_POST['user_id'] ) && isset( $_POST['follow_id'] ) ) {
        if( salong_follow_user( $_POST['user_id'], $_POST['follow_id'] ) ) {
            echo 'success';
        } else {
            echo 'failed';
        }
    }
    die();
 }
 add_action('wp_ajax_follow', 'salong_process_new_follow');




 function salong_process_unfollow() {
    if ( isset( $_POST['user_id'] ) && isset( $_POST['follow_id'] ) ) {
        if( salong_unfollow_user( $_POST['user_id'], $_POST['follow_id'] ) ) {
            echo 'success';
        } else {
            echo 'failed';
        }
    }
    die();
 }
 add_action('wp_ajax_unfollow', 'salong_process_unfollow');






 function salong_get_following( $user_id = 0 ) {

 	if ( empty( $user_id ) ) {
 		$user_id = get_current_user_id();
 	}

 	$following = get_user_meta( $user_id, 'salong_following', true );

 	return apply_filters( 'salong_get_following', $following, $user_id );
 }




 function salong_get_followers( $user_id = 0 ) {

 	if ( empty( $user_id ) ) {
 		$user_id = get_current_user_id();
 	}

 	$followers = get_user_meta( $user_id, 'salong_followers', true );

 	return apply_filters( 'salong_get_followers', $followers, $user_id );

 }




 function salong_follow_user( $user_id = 0, $user_to_follow = 0 ) {

 	 	$following = salong_get_following( $user_id );

 	if ( ! empty( $following ) && is_array( $following ) ) {
 		$following[] = $user_to_follow;
 	} else {
 		$following = array();
 		$following[] = $user_to_follow;
 	}

 	 	$followers = salong_get_followers( $user_to_follow );

 	if ( ! empty( $followers ) && is_array( $followers ) ) {
 		$followers[] = $user_id;
 	} else {
 		$followers = array();
 		$followers[] = $user_id;
 	}

 	do_action( 'salong_pre_follow_user', $user_id, $user_to_follow );

 	 	$followed = update_user_meta( $user_id, 'salong_following', $following );

 	 	$followers = update_user_meta( $user_to_follow, 'salong_followers', $followers );

 	 	$followed_count = salong_increase_followed_by_count( $user_to_follow );

 	if ( $followed ) {

 		do_action( 'salong_post_follow_user', $user_id, $user_to_follow );

 		return true;
 	}
 	return false;
 }




function salong_unfollow_user( $user_id = 0, $unfollow_user = 0 ) {
    
 	do_action( 'salong_pre_unfollow_user', $user_id, $unfollow_user );

 	 	$following = salong_get_following( $user_id );

 	if ( is_array( $following ) && in_array( $unfollow_user, $following ) ) {

 		$modified = false;

 		foreach ( $following as $key => $follow ) {
 			if ( $follow == $unfollow_user ) {
 				unset( $following[$key] );
 				$modified = true;
 			}
 		}

 		if ( $modified ) {
 			if ( update_user_meta( $user_id, 'salong_following', $following ) ) {
 				salong_decrease_followed_by_count( $unfollow_user );
 			}
 		}

 	}

 	 	$followers = salong_get_followers( $unfollow_user );

 	if ( is_array( $followers ) && in_array( $user_id, $followers ) ) {

 		$modified = false;

 		foreach ( $followers as $key => $follower ) {
 			if ( $follower == $user_id ) {
 				unset( $followers[$key] );
 				$modified = true;
 			}
 		}

 		if ( $modified ) {
 			update_user_meta( $unfollow_user, 'salong_followers', $followers );
 		}

 	}

 	if ( $modified ) {
 		do_action( 'salong_post_unfollow_user', $user_id, $unfollow_user );
 		return true;
 	}

 	return false;
 }



 function salong_get_following_count( $user_id = 0 ) {

 	if ( empty( $user_id ) ) {
 		$user_id = get_current_user_id();
 	}

 	$following = salong_get_following( $user_id );

 	$count = 0;

 	if ( $following ) {
 		$count = count( $following );
 	}

 	return (int) apply_filters( 'salong_get_following_count', $count, $user_id );
 }




 function salong_get_follower_count( $user_id = 0 ) {

 	if ( empty( $user_id ) ) {
 		$user_id = get_current_user_id();
 	}

 	$followed_count = get_user_meta( $user_id, 'salong_followed_by_count', true );

 	$count = 0;

 	if ( $followed_count ) {
 		$count = $followed_count;
 	}

 	return (int) apply_filters( 'salong_get_follower_count', $count, $user_id );
 }





 function salong_increase_followed_by_count( $user_id = 0 ) {

 	do_action( 'salong_pre_increase_followed_count', $user_id );

 	$followed_count = salong_get_follower_count( $user_id );

 	if ( $followed_count !== false ) {

 		$new_followed_count = update_user_meta( $user_id, 'salong_followed_by_count', $followed_count + 1 );
                if (function_exists( 'woocommerce_points_rewards_my_points' ) ) {
            global $wc_points_rewards;
            $points = get_option( 'wc_points_rewards_follow_points' );
            WC_Points_Rewards_Manager::increase_points( get_current_user_id(), $points, 'follow', $post_id );
        }

 	} else {

 		$new_followed_count = update_user_meta( $user_id, 'salong_followed_by_count', 1 );

 	}

 	do_action( 'salong_post_increase_followed_count', $user_id );

 	return $new_followed_count;
 }




 function salong_decrease_followed_by_count( $user_id = 0 ) {

 	do_action( 'salong_pre_decrease_followed_count', $user_id );

 	$followed_count = salong_get_follower_count( $user_id );

 	if ( $followed_count ) {

 		$count = update_user_meta( $user_id, 'salong_followed_by_count', ( $followed_count - 1 ) );

 		do_action( 'salong_post_increase_followed_count', $user_id );
        
                if (function_exists( 'woocommerce_points_rewards_my_points' ) ) {
            global $wc_points_rewards;
            $points = '-'.get_option( 'wc_points_rewards_follow_points' );
            WC_Points_Rewards_Manager::increase_points( get_current_user_id(), $points, 'follow', $post_id );
        }

 	}
 	return $count;
 }




 function salong_is_following( $user_id = 0, $followed_user = 0 ) {

 	$following = salong_get_following( $user_id );
 	$ret = false;  	if ( is_array( $following ) && in_array( $followed_user, $following ) ) {
 		$ret = true;  	}
 	return (bool) apply_filters( 'salong_is_following', $ret, $user_id, $followed_user );

 }






function salong_follow_unfollow_links( $follow_id = null ) {

	echo salong_get_follow_unfollow_links( $follow_id );
}



function salong_get_follow_unfollow_links( $follow_id = null, $allow = 1 ) {
    global $user_ID,$salong,$wp_query;

    if( empty( $follow_id ) )
        return;

    if ( $follow_id == $user_ID )
        return;
    
        $follower_arr = get_user_meta( $user_ID, 'salong_followers', true );
    $following_arr = get_user_meta( $user_ID, 'salong_following', true );
    if($follower_arr && $following_arr){
        $each = array_intersect($follower_arr,$following_arr);
    }
    
    if(!empty($each) && in_array($follow_id,$each)){
        $name = __('互相关注','salong');
        $class = 'each';
    }else{
        $name = __('已关注','salong');
        $class = 'followed';
    }
    
        $following = salong_get_following_count($follow_id);    $follower  = salong_get_follower_count($follow_id);    
    
    $salong_alipay    = get_user_meta( $follow_id, 'salong_alipay', true);
    $salong_wechatpay = get_user_meta( $follow_id, 'salong_wechatpay', true);
    
    
    if ( is_user_logged_in() ){
        $message_url = get_author_posts_url($user_ID).'?tab=message&page=salong_send&recipient='.$follow_id;
        $follow_url = '';
        $qr_url = 'payqr';
        $button = ' button';
    }else{
        $button = ' user-login';
        if ( class_exists( 'XH_Social' ) ){
            $message_url = $qr_url = $follow_url = '#login';
        }else{
            $message_url = $qr_url = $follow_url = wp_login_url($_SERVER['REQUEST_URI']);        }
    }
    
    ob_start(); ?>
    <div class="author_btn">
        <div class="follow_links">
            <?php if ( salong_is_following( $user_ID, $follow_id ) ) { ?>
            <a href="<?php echo $follow_url; ?>" title="<?php echo sprintf(__('关注：%s，粉丝：%s','salong'),$following,$follower); ?>" class="<?php echo $class.$button; ?>" data-user-id="<?php echo $user_ID; ?>" data-follow-id="<?php echo $follow_id; ?>"><?php echo $name; ?></a>
 			<a href="<?php echo $follow_url; ?>" title="<?php echo sprintf(__('关注：%s，粉丝：%s','salong'),$following,$follower); ?>" class="follow<?php echo $button; ?>" style="display:none;" data-user-id="<?php echo $user_ID; ?>" data-follow-id="<?php echo $follow_id; ?>"><?php _e( '关注', 'salong' ); ?></a>
            <?php } else { ?>
 			<a href="<?php echo $follow_url; ?>" title="<?php echo sprintf(__('关注：%s，粉丝：%s','salong'),$following,$follower); ?>" class="follow<?php echo $button; ?>" data-user-id="<?php echo $user_ID; ?>" data-follow-id="<?php echo $follow_id; ?>"><?php _e( '关注', 'salong' ); ?></a>
 			<a href="<?php echo $follow_url; ?>" title="<?php echo sprintf(__('关注：%s，粉丝：%s','salong'),$following,$follower); ?>" class="<?php echo $class.$button; ?>" style="display:none;" data-user-id="<?php echo $user_ID; ?>" data-follow-id="<?php echo $follow_id; ?>"><?php echo $name; ?></a>
 			<?php } ?>
            <img src="<?php echo get_template_directory_uri();?>/images/loading.gif" class="salong-ajax" style="display:none;"/>
        </div>
 		<?php if($salong['switch_messages']){ ?>
        <a href="<?php echo $message_url; ?>" class="message" <?php echo new_open_link(); ?>><?php _e('私信','salong'); ?></a>
        <?php }if( ($salong_alipay || $salong_wechatpay) && $allow == 1 ){ ?>
        <a href="#payqr" class="payqr"><?php _e('打赏','salong'); ?></a>
        <?php } ?>
 	</div>
 	<?php
 	return ob_get_clean();
 }









 function salong_follow_links_shortcode( $atts, $content = null ) {

 	extract( shortcode_atts( array(
 			'follow_id' => get_the_author_meta( 'ID' )
 		),
 		$atts, 'follow_links' )
 	);

 	return salong_get_follow_unfollow_links( $follow_id );
 }
 add_shortcode( 'follow_links', 'salong_follow_links_shortcode' );





function salong_followers_user( $follow ){
    global $salong,$current_user,$post,$wp_query;
    $number     = $salong[ 'author_user_count'];
    $blog_id    = get_current_blog_id();
    $curauth    = $wp_query->get_queried_object();    if($follow){
        $following  = implode( ',',$follow);
        if($following){
            $paged        = ( get_query_var( 'paged')) ? get_query_var( 'paged') : 1;
            $offset       = ( $paged - 1) * $number;
            $current_page = max(1, get_query_var('paged'));
            $users        = get_users( 'blog_id='.$blog_id. '&include='.$following.'');
            $query        = get_users( 'include='.$following.'&offset='.$offset. '&number='.$number.'&orderby=post_count&order=DESC');
            $total_users  = count($users);
                $total_pages  = ceil($total_users / $number);
            $items .= '<ul class="layout_ul">';
            foreach ($query as $user) {
                global $wp_query;
                $user_id          = $user->ID;
                $user_name        = get_the_author_meta('display_name',$user_id);
                $user_description = get_the_author_meta('user_description',$user_id);
                                $items .= '<li class="layout_li">';
                $items .= '<article class="user_main">';
                $items .= salong_get_avatar($user_id,$user_name);
                $items .= '<h3><a href="'.get_author_posts_url($user_id).'" title="'.$user_description.'">'.$user_name.'</a><span>('.user_role($user_id).')</span>'.salong_add_v($user_id).'</h3>';
                if($user_description){
                    $items .= '<p>'.$user_description.'</p>';
                }else{
                    $items .= __('<p>这家伙真懒，个人简介没有填写…</p>','salong');
                }
                if($salong['switch_follow_btn']){
                    $items .= salong_get_follow_unfollow_links($user_id,$allow=0);
                }
                $items .= '</article>';
                $items .= '</li>';
            }
            $items .= '</ul>';
            $items .= '<div class="pagination">';
            $items .= paginate_links(array(
                'base'      => get_author_posts_url($curauth->ID). '%_%',
                'format' => '/page/%#%/',
                'current'   => $current_page,
                'total'     => $total_pages,
                'end_size'  => 2,
                'mid-size'  => 3
            ));
            $items .= '</div>';
        }
    }else{
        $get_tab = $_GET['tab'];        $user_url = get_permalink($salong['all_user_page']);        if($get_tab == 'following'){
            $items .= '<div class="warningbox">'.sprintf(__('还没有关注任何用户，点击<a href="%s">【这里】</a>关注喜欢的用户！','salong'),$user_url).'</div>';
        }else if($get_tab == 'follower'){
            $items .= '<div class="warningbox">'.sprintf(__('还没有任何用户关注 TA，点击<a href="%s">【这里】</a>关注喜欢的用户！','salong'),$user_url).'</div>';
        }
    }
    return $items;
}




function salong_following_posts_shortcode() {

    global $salong,$wp_query;
    $curauth = $wp_query->get_queried_object();    $user_id = $curauth->ID;    
	
	$following = salong_get_following($user_id);

	if( empty( $following ) )
		return;

	ob_start();
    $paged=( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;$args=array( 'post_type'=> 'any','author__in'=>$following,'ignore_sticky_posts' => 1,'posts_per_page'=>$salong['following_posts_count'],'paged' => $paged );$temp_wp_query = $wp_query;$wp_query = null;$wp_query = new WP_Query( $args ); ?>
	<?php if( $wp_query->have_posts() ) : ?>
	<section class="follow_posts">
	<h2><?php _e('所关注用户的最新文章：','salong'); ?></h2>
	<ul class="layout_ul">
        <?php while( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
        <li class="layout_li">
            <a href="<?php echo the_permalink(); ?>" title="<?php echo the_title(); ?>" class="thumb">
                <h3><?php echo the_title(); ?></h3>
                <?php if( 'topic'==get_post_type()){echo __('<span>专题</span>','salong');}else if( 'download'==get_post_type()){echo __('<span>下载</span>','salong');}else if( 'video'==get_post_type()){echo __('<span>视频</span>','salong');}?>
            </a>
        </li>
        <?php endwhile; ?>
	</ul>
	</section>
	<?php endif; wp_reset_postdata(); ?>
	<?php return ob_get_clean();

}
add_shortcode( 'following_posts', 'salong_following_posts_shortcode' );
