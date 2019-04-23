<?php


global $salong;
if(!$salong['switch_like_btn'])return;
add_action( 'wp_ajax_nopriv_process_simple_like', 'process_simple_like' );
add_action( 'wp_ajax_process_simple_like', 'process_simple_like' );
function process_simple_like() {
		$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0;
	if ( !wp_verify_nonce( $nonce, 'simple-likes-nonce' ) ) {
		exit( __( 'Not permitted', 'salong' ) );
	}
		$disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;
		$is_comment = ( isset( $_REQUEST['is_comment'] ) && $_REQUEST['is_comment'] == 1 ) ? 1 : 0;
		$post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : '';
	$result = array();
	$post_users = NULL;
	$like_count = 0;
		if ( $post_id != '' ) {
		$count = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "salong_comment_like_count", true ) : get_post_meta( $post_id, "salong_post_like_count", true ); 		$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
		if ( !already_liked( $post_id, $is_comment ) ) { 			if ( is_user_logged_in() ) { 				$user_id = get_current_user_id();
				$post_users = post_user_likes( $user_id, $post_id, $is_comment );
                
                                if (function_exists( 'woocommerce_points_rewards_my_points' ) ) {
                    global $wc_points_rewards;
                    $points = get_option( 'wc_points_rewards_like_points' );
                    WC_Points_Rewards_Manager::increase_points( get_current_user_id(), $points, 'like', $post_id );
                }
				if ( $is_comment == 1 ) {
										$user_like_count = get_user_option( "salong_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "salong_comment_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_comment_meta( $post_id, "salong_user_comment_liked", $post_users );
					}
				} else {
										$user_like_count = get_user_option( "salong_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "salong_user_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_post_meta( $post_id, "salong_user_liked", $post_users );
					}
				}
			} else { 				$user_ip = sl_get_ip();
				$post_users = post_ip_likes( $user_ip, $post_id, $is_comment );
								if ( $post_users ) {
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "salong_user_comment_IP", $post_users );
					} else { 
						update_post_meta( $post_id, "salong_user_IP", $post_users );
					}
				}
			}
			$like_count = ++$count;
			$response['status'] = "liked";
			$response['icon'] = get_liked_icon();
		} else { 			if ( is_user_logged_in() ) { 				$user_id = get_current_user_id();
				$post_users = post_user_likes( $user_id, $post_id, $is_comment );
                
                                if (function_exists( 'woocommerce_points_rewards_my_points' ) ) {
                    global $wc_points_rewards;
                    $points = '-'.get_option( 'wc_points_rewards_like_points' );
                    WC_Points_Rewards_Manager::increase_points( get_current_user_id(), $points, 'like', $post_id );
                }
								if ( $is_comment == 1 ) {
					$user_like_count = get_user_option( "salong_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, "salong_comment_like_count", --$user_like_count );
					}
				} else {
					$user_like_count = get_user_option( "salong_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, 'salong_user_like_count', --$user_like_count );
					}
				}
								if ( $post_users ) {	
					$uid_key = array_search( $user_id, $post_users );
					unset( $post_users[$uid_key] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "salong_user_comment_liked", $post_users );
					} else { 
						update_post_meta( $post_id, "salong_user_liked", $post_users );
					}
				}
			} else { 				$user_ip = sl_get_ip();
				$post_users = post_ip_likes( $user_ip, $post_id, $is_comment );
								if ( $post_users ) {
					$uip_key = array_search( $user_ip, $post_users );
					unset( $post_users[$uip_key] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "salong_user_comment_IP", $post_users );
					} else { 
						update_post_meta( $post_id, "salong_user_IP", $post_users );
					}
				}
			}
			$like_count = ( $count > 0 ) ? --$count : 0; 			$response['status'] = "unliked";
			$response['icon'] = get_unliked_icon();
		}
		if ( $is_comment == 1 ) {
			update_comment_meta( $post_id, "salong_comment_like_count", $like_count );
			update_comment_meta( $post_id, "salong_comment_like_modified", date( 'Y-m-d H:i:s' ) );
		} else { 
			update_post_meta( $post_id, "salong_post_like_count", $like_count );
			update_post_meta( $post_id, "salong_post_like_modified", date( 'Y-m-d H:i:s' ) );
		}
		$response['count'] = get_like_count( $like_count );
		$response['testing'] = $is_comment;
		if ( $disabled == true ) {
			if ( $is_comment == 1 ) {
				wp_redirect( get_permalink( get_the_ID() ) );
				exit();
			} else {
				wp_redirect( get_permalink( $post_id ) );
				exit();
			}
		} else {
			wp_send_json( $response );
		}
	}
}



function already_liked( $post_id, $is_comment ) {
	$post_users = NULL;
	$user_id = NULL;
	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "salong_user_comment_liked" ) : get_post_meta( $post_id, "salong_user_liked" );
		if ( count( $post_meta_users ) != 0 ) {
			$post_users = $post_meta_users[0];
		}
	} else {
		$user_id = sl_get_ip();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "salong_user_comment_IP" ) : get_post_meta( $post_id, "salong_user_IP" ); 
		if ( count( $post_meta_users ) != 0 ) { 			$post_users = $post_meta_users[0];
		}
	}
	if ( is_array( $post_users ) && in_array( $user_id, $post_users ) ) {
		return true;
	} else {
		return false;
	}
}


function get_post_likes_button( $post_id, $is_comment = NULL ) {
    global $salong;
	$is_comment = ( NULL == $is_comment ) ? 0 : 1;
	$output = '';
	$nonce = wp_create_nonce( 'simple-likes-nonce' );   
   
	if ( $is_comment == 1 ) {
		$post_id_class = esc_attr( ' sl-comment-button-' . $post_id );
		$comment_class = esc_attr( ' sl-comment' );
		$like_count = get_comment_meta( $post_id, "salong_comment_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	} else {
		$post_id_class = esc_attr( ' sl-button-' . $post_id );
		$comment_class = esc_attr( '' );
		$like_count = get_post_meta( $post_id, "salong_post_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	}
	$count = get_like_count( $like_count );
	$icon_empty = get_unliked_icon();
	$icon_full = get_liked_icon();
		$loader = '<span id="sl-loader" style="display:none;"><img src="'.get_template_directory_uri().'/images/loading.gif" class="salong-ajax"/></span>';
   
		if ( already_liked( $post_id, $is_comment ) ) {
		$class = esc_attr( ' liked' );
        $title = __( '已赞', 'salong' );
		$icon = $icon_full;
	} else {
		$class = '';
        $title = __( '点赞同时也收藏该文章', 'salong' );
		$icon = $icon_empty;
	}
    if(is_user_logged_in() || $salong['switch_like_nologin']){
        $like_url = admin_url( 'admin-ajax.php?action=process_simple_like' . '&post_id=' . $post_id . '&nonce=' . $nonce . '&is_comment=' . $is_comment . '&disabled=true' );
        $output = '<a href="' . $like_url . '" class="sl-button' . $post_id_class . $class . $comment_class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" data-iscomment="' . $is_comment . '" title="' . $title . '">' . $icon . $count .'</a>' . $loader . '';
    }else{
        if ( class_exists( 'XH_Social' ) ){
            $login_url = '#login';
        }else{
            $login_url  = wp_login_url($_SERVER['REQUEST_URI']);        }
        $output = '<a href="' .$login_url . '" class="sl-login" title="' . $title . '">' . $icon . $count . '</a>';
    }
	return $output;
}


function post_user_likes( $user_id, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "salong_user_comment_liked" ) : get_post_meta( $post_id, "salong_user_liked" );
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !in_array( $user_id, $post_users ) ) {
		$post_users['user-' . $user_id] = $user_id;
	}
	return $post_users;
}


function post_ip_likes( $user_ip, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "salong_user_comment_IP" ) : get_post_meta( $post_id, "salong_user_IP" );
		if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !in_array( $user_ip, $post_users ) ) {
		$post_users['ip-' . $user_ip] = $user_ip;
	}
	return $post_users;
}


function sl_get_ip() {
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	}
	$ip = filter_var( $ip, FILTER_VALIDATE_IP );
	$ip = ( $ip === false ) ? '0.0.0.0' : $ip;
	return $ip;
}


function get_liked_icon() {
	
	$icon = '<svg role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xml:space="preserve"><path id="heart-full" d="M124 20.4C111.5-7 73.7-4.8 64 19 54.3-4.9 16.5-7 4 20.4c-14.7 32.3 19.4 63 60 107.1C104.6 83.4 138.7 52.7 124 20.4z"/>&#9829;</svg><span class="title">'.__('已赞','salong').'</span>';
	return $icon;
}


function get_unliked_icon() {
	
	$icon = '<svg role="img" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0" y="0" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xml:space="preserve"><path id="heart" d="M64 127.5C17.1 79.9 3.9 62.3 1 44.4c-3.5-22 12.2-43.9 36.7-43.9 10.5 0 20 4.2 26.4 11.2 6.3-7 15.9-11.2 26.4-11.2 24.3 0 40.2 21.8 36.7 43.9C124.2 62 111.9 78.9 64 127.5zM37.6 13.4c-9.9 0-18.2 5.2-22.3 13.8C5 49.5 28.4 72 64 109.2c35.7-37.3 59-59.8 48.6-82 -4.1-8.7-12.4-13.8-22.3-13.8 -15.9 0-22.7 13-26.4 19.2C60.6 26.8 54.4 13.4 37.6 13.4z"/>&#9829;</svg><span class="title">'.__('赞','salong').'</span>';
	return $icon;
}


function sl_format_count( $number ) {
	$precision = 2;
	if ( $number >= 1000 && $number < 1000000 ) {
		$formatted = number_format( $number/1000, $precision ).'K';
	} else if ( $number >= 1000000 && $number < 1000000000 ) {
		$formatted = number_format( $number/1000000, $precision ).'M';
	} else if ( $number >= 1000000000 ) {
		$formatted = number_format( $number/1000000000, $precision ).'B';
	} else {
		$formatted = $number; 	}
	$formatted = str_replace( '.00', '', $formatted );
	return $formatted;
}


function get_like_count( $like_count ) {
	if ( is_numeric( $like_count ) && $like_count > 0 ) { 
		$number = sl_format_count( $like_count );
	} else {
		$number = 0;
	}
	$count = '<span class="sl-count">' . $number . '</span>';
	return $count;
}


function salong_user_follow_post(){
    global $salong,$post,$items;
    $salong_user_liked  = get_post_meta( $post->ID, 'salong_user_liked', true );
    if($salong['switch_like_user_order'] && $salong_user_liked){
        $user_liked = array_reverse($salong_user_liked);
    }else{
        $user_liked = $salong_user_liked;
    }
    $switch_like_user   = $salong['switch_like_user'];
    $like_user_count    = $salong['like_post_user_show_count'];
    $arr_count          = is_array($salong_user_liked) ? count($salong_user_liked) : 0;
    if($salong_user_liked && $switch_like_user){
        $items .= '<article class="author_list">';
        $i = 0;
        foreach ($user_liked as $user_id) {
            if($i==$like_user_count){
                break;
            }
            global $wp_query;
                        $user_name        = get_the_author_meta('display_name',$user_id);
            $user_description = get_the_author_meta('user_description',$user_id);
                        $items .= '<a href="'.get_author_posts_url($user_id).'" target="_blank" title="'.$user_name.'-'.$user_description.'">';
            $items .= salong_get_avatar($user_id,$user_name);
            $items .= '</a>';
            $i++;
        }
        if($arr_count > $like_user_count){
            $items .= '<span>…</span>';
        }
        $items .= '</article>';
    }
    return $items;
}

