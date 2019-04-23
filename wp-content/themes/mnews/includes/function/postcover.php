<?php

function timthumb( $src, $size = null, $set = null ){
	return get_stylesheet_directory_uri().'/includes/timthumb.php?src='.$src.'&h='.$size["h"].'&w='.$size['w'].'&zc=1&a=c&q=100&s=1';
}


function salong_mi_str_encode($string)
{
	$len = strlen($string);
	$buf = "";
	$i = 0;
	while ($i < $len) {
		if (ord($string[$i]) <= 127) {
			$buf .= $string[$i];
		} else {
			if (ord($string[$i]) < 192) {
				$buf .= "&#xfffd";
			} else {
				if (ord($string[$i]) < 224) {
					$buf .= sprintf("&#%d;", ((ord($string[$i + 0]) & 31) << 6) + (ord($string[$i + 1]) & 63));
					$i += 1;
				} else {
					if (ord($string[$i]) < 240) {
						$buf .= sprintf("&#%d;", ((ord($string[$i + 0]) & 15) << 12) + ((ord($string[$i + 1]) & 63) << 6) + (ord($string[$i + 2]) & 63));
						$i += 2;
					} else {
						$buf .= sprintf("&#%d;", ((ord($string[$i + 0]) & 7) << 18) + ((ord($string[$i + 1]) & 63) << 12) + ((ord($string[$i + 2]) & 63) << 6) + (ord($string[$i + 3]) & 63));
						$i += 3;
					}
				}
			}
		}
		$i = $i + 1;
	}
	return $buf;
}


function salong_draw_txt_to($card, $pos, $str, $iswrite, $font_file){
    $_str_h       = $pos['top'];
    $fontsize     = $pos['fontsize'];
    $width        = $pos['width'];
    $margin_lift  = $pos['left'];
    $hang_size    = $pos['hang_size'];
    $temp_string  = '';
    $tp           = 0;
    $font_color   = imagecolorallocate($card, $pos['color'][0], $pos['color'][1], $pos['color'][2]);
    $i            = 0;
    while ($i < mb_strlen($str)) {
        $box            = imagettfbbox($fontsize, 0, $font_file, salong_mi_str_encode($temp_string));
        $_string_length = $box[2] - $box[0];
        $temptext       = mb_substr($str, $i, 1);
        $temp           = imagettfbbox($fontsize, 0, $font_file, salong_mi_str_encode($temptext));
        if ($_string_length + $temp[2] - $temp[0] < $width) {
            $temp_string .= mb_substr($str, $i, 1);
            if ($i == mb_strlen($str) - 1) {
                $_str_h  = $_str_h + $hang_size;
                $_str_h += $hang_size;
                $tp      = $tp + 1;
                if ($iswrite) {
                    imagettftext($card, $fontsize, 0, $margin_lift, $_str_h, $font_color, $font_file, salong_mi_str_encode($temp_string));
                }
            }
        } else {
            $texts = mb_substr($str, $i, 1);
            $isfuhao = preg_match('/[\\pP]/u', $texts) ? true : false;
            if ($isfuhao) {
                $temp_string .= $texts;
                $f            = mb_substr($str, $i + 1, 1);
                $fh           = preg_match('/[\\pP]/u', $f) ? true : false;
                if ($fh) {
                    $temp_string .= $f;
                    $i = $i + 1;
                }
            } else {
                $i = $i + -1;
            }
            $tmp_str_len = mb_strlen($temp_string);
            $s = mb_substr($temp_string, $tmp_str_len - 1, 1);
            if (is_firstfuhao($s)) {
                $temp_string = rtrim($temp_string, $s);
                $i = $i + -1;
            }
            $_str_h = $_str_h + $hang_size;
            $_str_h += $hang_size;
            $tp = $tp + 1;
            if ($iswrite) {
                imagettftext($card, $fontsize, 0, $margin_lift, $_str_h, $font_color, $font_file, salong_mi_str_encode($temp_string));
            }
            $temp_string = '';
        }
        $i = $i + 1;
    }
    return $tp * $hang_size;
}

function is_firstfuhao($str)
{
	$fuhaos = array("\"", "“", "'", "<", "《");
	return in_array($str, $fuhaos);
}

add_action('wp_ajax_nopriv_create-cover-image', 'get_cover_img');
add_action('wp_ajax_create-cover-image', 'get_cover_img');

function create_cover_image($post_id, $date, $title, $content, $head_img, $qrcode_img = null,$author_avatar,$author_name){
    global $salong;
	$im               = imagecreatetruecolor(800, 1120);
	$white            = imagecolorallocate($im, 255, 255, 255);
	$gray             = imagecolorallocate($im, 200, 200, 200);
	$foot_text_color  = imagecolorallocate($im, 153, 153, 153);
	$foot_title_color = imagecolorallocate($im, 80, 80, 80);
	$black            = imagecolorallocate($im, 0, 0, 0);
    $date_font        = get_template_directory() . '/includes/fonts/date.ttf';
    $text_font        = get_template_directory() . '/includes/fonts/text.ttf';
	imagefill($im, 0, 0, $white);
    
	$head_img         = imagecreatefromstring(file_get_contents(timthumb($head_img, array("w" => "800", "h" => "450"), "original")));
	imagecopy($im, $head_img, 0, 120, 0, 0, 800, 450);
    
	$author_img       = imagecreatefromstring(file_get_contents(timthumb($author_avatar, array("w" => "60", "h" => "60"), "original")));
	imagecopy($im, $author_img, 30, 30, 0, 0, 60, 60);
    
	$author_name      = salong_mi_str_encode($author_name);
	imagettftext($im, 20, 0, 100, 68, $black, $text_font, $author_name);
    
	$day               = $date["day"];
	$year              = $date["year"];
	imagettftext($im, 80, 0, 50, 465, $white, $date_font, $day);
	imagettftext($im, 24, 0, 50, 515, $white, $date_font, $year);
    
	$title             = salong_mi_str_encode($title);
	imagettftext($im, 28, 0, 50, 670, $black, $text_font, $title);
    
	$conf              = array("color" => array(99, 99, 99), "fontsize" => 20, "width" => 720, "left" => 50, "top" => 690, "hang_size" => 32);
	salong_draw_txt_to($im, $conf, $content, true, $text_font);
	$style             = array($gray, $gray, $gray, $gray, $gray, $white, $white, $white, $white, $white, $white);
	imagesetstyle($im, $style);
    
	imageline($im, 30, 940, 760, 940, IMG_COLOR_STYLED);
    
	$foot_text         = salong_mi_str_encode(get_bloginfo("description"));
	$foot_title        = salong_mi_str_encode(get_bloginfo("name"));
    imagettftext($im, 20, 0, 50, 1005, $foot_title_color, $text_font, $foot_title);
    imagettftext($im, 14, 0, 50, 1045, $foot_text_color, $text_font, $foot_text);
    
    $qrcode_str   = file_get_contents($qrcode_img);
    $qrcode_size  = getimagesizefromstring($qrcode_str);
    $qrcode_img   = imagecreatefromstring($qrcode_str);
    imagecopyresized($im, $qrcode_img, 640, 960, 0, 0, 100, 100, $qrcode_size[0], $qrcode_size[1]);
    
    $upload_dir = wp_upload_dir();
	$filename = "/cover-postid-". $post_id."-time-". date('Y-m-d-H-i-s') . ".png";
	$file = $upload_dir["path"] . $filename;
	imagepng($im, $file);
	require_once ABSPATH . "wp-admin/includes/image.php";
	require_once ABSPATH . "wp-admin/includes/file.php";
	require_once ABSPATH . "wp-admin/includes/media.php";
	$src = media_sideload_image($upload_dir["url"] . $filename, $post_id, NULL, "src");
	@unlink($file);
	imagedestroy($im);
	if (is_wp_error($src)) {
		return false;
	}
	return $src;
}

function salong_substr_ext($str, $start = 0, $length, $charset = 'utf-8', $suffix = ''){
    
	if (function_exists("mb_substr")) {
		return mb_substr($str, $start, $length, $charset) . $suffix;
	}
	if (function_exists("iconv_substr")) {
		return iconv_substr($str, $start, $length, $charset) . $suffix;
	}
	$re["utf-8"] = "/[\1-]|[Â-ß][€-¿]|[à-ï][€-¿]{2}|[ð-ÿ][€-¿]{3}/";
	$re["gb2312"] = "/[\1-]|[°-÷][ -þ]/";
	$re["gbk"] = "/[\1-]|[-þ][@-þ]/";
	$re["big5"] = "/[\1-]|[-þ]([@-~]|¡-þ])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("", array_slice($match[0], $start, $length));
	return $slice . $suffix;
}

function get_cover_img(){
	global $post,$salong;
    $post_id = sanitize_text_field($_POST['id']);
    if (wp_verify_nonce($_POST['nonce'], 'create-cover-img-' . $post_id)) {
        $user_id            = get_post_field( 'post_author', $post_id );
        $user_avatar        = get_user_meta( $user_id, 'salong_avatar', true);        $social_avatar      = get_user_meta( $user_id, '_social_img', true);        $default_avatar     = $salong['default_avatar']['url'];        if($user_avatar){
            $author_avatar = $user_avatar;
        }else if($social_avatar){
            $author_avatar = $social_avatar;
        }else{
            $author_avatar = $default_avatar;
        }
        $date           = array('day' => get_the_time('d', $post_id), 'year' => get_the_time('Y/m', $post_id));
        $title          = get_the_title($post_id);
        $title          = salong_substr_ext($title, 0, 19, 'utf-8', '');
        $post           = get_post($post_id);
        $content        = $post->post_excerpt ? $post->post_excerpt : $post->post_content;
        $content        = salong_substr_ext(strip_tags(strip_shortcodes($content)), 0, 72, 'utf-8', '...');
        $content        = str_replace(PHP_EOL, '', strip_tags(apply_filters('the_excerpt', $content)));
        $head_img       = post_thumbnail_src();
        $author_name    = get_the_author_meta('display_name',$user_id);
        $qrcode_img     = get_template_directory_uri() . '/includes/function/qrcode.php?data=' . get_the_permalink($post_id);
        $result         = create_cover_image($post_id, $date, $title, $content, $head_img, $qrcode_img,$author_avatar,$author_name);
        if ($result) {
            $pic = '&pic=' . urlencode($result);
            if (get_post_meta($post_id, 'share_cover', true)) {
                update_post_meta($post_id, 'share_cover', $result);
            } else {
                add_post_meta($post_id, 'share_cover', $result);
            }
            $msg = array('s' => 200, 'src' => $result);
        } else {
            $msg = array('s' => 404, 'm' => __('分享封面生成失败，请稍后再试，窗口即将关闭！','salong'));
        }
    } else {
        $msg = array('s' => 404, 'm' => __('未知问题','salong'));
    }
    echo json_encode($msg);
    exit(0);
}
