<?php


add_action('init', 'FanlySubmit', 100);
function FanlySubmit() { 	$Fanly = get_option('FanlySubmit');	if ( is_array($Fanly['Types']) ) {
		foreach($Fanly['Types'] as $type) {
			add_action('save_'.$type, 'fanly_submit', 10, 2);
			add_filter('manage_'.$type.'_posts_columns', 'fanly_submit_add_post_columns');
			add_action('manage_'.$type.'s_custom_column', 'fanly_submit_render_post_columns', 10, 2);
		}
	} 
}
function fanly_submit($post_ID, $post) {
	if( isset($_POST['Fanly_Submit_CHECK']) ){
				$Fanly = get_option('FanlySubmit');
		$P_Fanly_Submit = $_POST['Fanly_Submit'];
		$P_original = $_POST['original'];
		$Fanly_Submit = get_post_meta($post_ID,'Fanly_Submit',true);
				if($Fanly_Submit!='-1'&&$Fanly_Submit!='OK'){
			if($P_original=='1'){update_post_meta($post_ID, 'Fanly_Submit', 'original');}elseif($P_Fanly_Submit=='realtime'){update_post_meta($post_ID, 'Fanly_Submit', 'realtime');}		}
		
				if( $post->post_status != 'publish' || $Fanly_Submit == 'Original' )return;
				if( $P_Fanly_Submit || $P_original ){
			$type = $P_original==1 ? 'original' : ($P_Fanly_Submit=='batch' ? 'batch' : 'realtime');
			$cambrian_api_url = 'http://data.zz.baidu.com/urls?appid='.$Fanly['APPID'].'&token='.$Fanly['APPToken'].'&type='.$type;
			$link = $Fanly['Site']==home_url() ? get_permalink($post_ID) : str_replace(home_url(),$Fanly['Site'],get_permalink($post_ID));

			$cambrian_re = wp_remote_post($cambrian_api_url, array(
				'headers'	=> array('Accept-Encoding'=>'','Content-Type'=>'text/plain'),
				'timeout'	=> 10,
				'sslverify'	=> false,
				'blocking'	=> true,
				'body'		=> $link
			));

			if ( is_wp_error( $cambrian_re ) ) {
				update_post_meta($post_ID, 'Fanly_Submit', '-1');
				$Fanly['msg'] = $cambrian_re->get_error_message();				update_option('FanlySubmit', $Fanly);			} else {
								$res = json_decode($cambrian_re['body'], true);
				if($res['success_'.$type]==1){
					$Fanly[$type] = $res['remain_'.$type].'|'.date('Ymd');
					update_post_meta($post_ID, 'Fanly_Submit', $type=='original' ? 'Original' : 'OK');				}elseif($res['remain_'.$type]==0){					$Fanly[$type] = $res['remain_'.$type].'|'.date('Ymd');
				}else{					update_post_meta($post_ID, 'Fanly_Submit', '-1');
					$Fanly['err']=$res;				}
				update_option('FanlySubmit', $Fanly);			}
		}
	}
}

function fanly_submit_get_post_type() {
  global $post, $typenow, $current_screen;
    if ( $post && $post->post_type ) {
    return $post->post_type;
  } elseif ( $typenow ) {    return $typenow;
  } elseif ( $current_screen && $current_screen->post_type ) {    return $current_screen->post_type;
  } elseif ( isset( $_REQUEST['post_type'] ) ) {    return sanitize_key( $_REQUEST['post_type'] );
  } elseif ( isset( $_REQUEST['post'] ) ) {    return get_post_type( $_REQUEST['post'] );
  }
  return 'post';}

add_action( 'admin_menu', 'fanly_submit_create' );
function fanly_submit_create(){
	$Fanly = get_option('FanlySubmit');	if(is_array($Fanly['Types']) && in_array(fanly_submit_get_post_type(),$Fanly['Types'])){
		add_action( 'post_submitbox_misc_actions', 'fanly_submit_to_publish_metabox' );	}
}
function fanly_submit_to_publish_metabox() {
	$Fanly = get_option('FanlySubmit');	if($Fanly['APPID']=='' || $Fanly['APPToken']=='')return;
    global $post,$post_ID;
	$fanly_submit	= get_post_meta($post_ID,'Fanly_Submit',true);
	$original		= get_post_meta($post_ID,'original',true);
	$remain_z		= explode('|',@$Fanly['realtime']);	$remain_c		= explode('|',$Fanly['batch']);	$remain_o		= explode('|',@$Fanly['original']);	$checked		= $Fanly['Default']=='true' || $fanly_submit=='realtime' || $fanly_submit=='original' || $fanly_submit=='Original' ? 'checked="checked"' : '';
	$checked_o		= $Fanly['Original']=='true' || $fanly_submit=='original' || $fanly_submit=='Original' ? 'checked="checked"' : '';
	
	if($fanly_submit=='Original'){
		$original_box = '（<label><input name="original" type="checkbox" value="1" disabled '.$checked_o.'>原创</label>）';
	}elseif(@$remain_o[0]==0 && @$remain_o[1]==date('Ymd')){		$original_box = '（<label><input name="original" type="checkbox" value="1" disabled>原创[0]</label>）';
	}else{
		$o = @$remain_o[1]==date('Ymd') ? '['.$remain_o[0].']' : '';
		$original_box = '（<label><input name="original" type="checkbox" value="1" '.$checked_o.'>原创'.$o.'</label>）';
	}
	
	if($fanly_submit=='OK' || $fanly_submit=='Original'){		$input = '
			<input id="Fanly_Submit" type="checkbox" checked="checked" disabled>
			<label for="Fanly_Submit" class="selectit">成功！'.$original_box.'</label>
		';
	}elseif( strtotime(date($post->post_date))+24*60*60 <= time()  ){		if($remain_c[0]==0 && $remain_c[1]==date('Ymd')){
			$input = '<label for="Fanly_Submit" class="selectit">提交上限'.$original_box.'<a style="font-weight:bold;color:#0066FF;text-decoration:none;" href="javascript:;" title="超过提交配额数量">?</a></label>';
		}else{
			$repost_text = $fanly_submit=='-1' ? '失败重试' : '历史内容';
			$input = '
			<input id="Fanly_Submit" name="Fanly_Submit" type="checkbox" value="batch" '.$checked.'>
			<label for="Fanly_Submit" class="selectit">'.$repost_text.$original_box.'</label>';
		}
	}elseif(@$remain_z[0]==0 && @$remain_z[1]==date('Ymd')){		$input = '
			<input id="Fanly_Submit" name="Fanly_Submit" type="checkbox" value="realtime" '.$checked.'>
			<label for="Fanly_Submit" class="selectit">提交上限'.$original_box.'</label>';
	}elseif($fanly_submit == 'realtime'){		$input = '
			<input id="Fanly_Submit" name="Fanly_Submit" type="checkbox" value="realtime" '.$checked.'>
			<label for="Fanly_Submit" class="selectit">新增内容'.$original_box.'</label>
		';
	}else{
		$repost_text = $fanly_submit=='-1' ? '失败重试' : '新增内容';
		$input = '
			<input id="Fanly_Submit" name="Fanly_Submit" type="checkbox" value="realtime" '.$checked.'>
			<label for="Fanly_Submit" class="selectit">'.$repost_text.$original_box.'</label>
		';
	}
	echo '<div class="misc-pub-section misc-pub-post-status"><input name="Fanly_Submit_CHECK" type="hidden" value="true">熊掌号：<span id="submit-span">'.$input.'</span></div>';
}

function fanly_submit_add_post_columns($columns) {
    $columns['Fanly_Submit'] = '原创/熊掌号';
    return $columns;
}
function fanly_submit_render_post_columns($column_name, $id) {
    switch ($column_name) {
		case 'Fanly_Submit':
			echo get_post_meta( $id, 'original', TRUE)==1 || get_post_meta( $id, 'original', TRUE) =='OK' || get_post_meta( $id, 'Fanly_Submit', TRUE)=='Original' ? '是'	: '否'; 			echo '/';
			echo get_post_meta( $id, 'Fanly_Submit', TRUE)=='OK' || get_post_meta( $id, 'Fanly_Submit', TRUE)=='Original' ? '提交成功' : (get_post_meta( $id, 'Fanly_Submit', TRUE)=='-1' ? '提交失败' : '未提交'); 			break;
    }
}


function fanly_submit_test($Site, $Appid, $Token){
	$baidu_api_url = 'http://data.zz.baidu.com/urls?appid='.$Appid.'&token='.$Token.'&type=batch';
	$response = wp_remote_post($baidu_api_url, array(
		'headers'	=> array('Accept-Encoding'=>'','Content-Type'=>'text/plain'),
		'timeout'	=> 10,
		'sslverify'	=> false,
		'blocking'	=> true,
		'body'		=> $Site
	));
	if(is_array($response) && array_key_exists('body', $response)){
		$data = json_decode( $response['body'], true );
		return $data;
	}else{return FALSE;}
}

add_action('admin_init', 'fanly_submit_default_options');
function fanly_submit_default_options(){
	$Fanly = get_option('FanlySubmit');	if( $Fanly == '' ){   
		$Fanly = array(			'Types'		=> '',
			'APPID'		=> '',
			'APPToken'	=> '',
			'Default'	=> '',
			'Original'	=> '',
		);
		update_option('FanlySubmit', $Fanly);	}
}

add_action('admin_menu', 'fanly_submit_menu'); 
function fanly_submit_menu() {
    $F_name = 'mne';$F_ed   = 'vated';
    if(get_option( $F_name.'ws_acti'.$F_ed )=='Acti'.$F_ed){
        add_submenu_page('options-general.php','百度熊掌号/原创保护', __('熊掌号推送','salong'), 'manage_options', 'Fanly_Submit','fanly_submit_options', '');
    }
}

function fanly_submit_options() {
		if(isset($_POST['FanlySubmit']) ){
		$APPID		= trim($_POST['APPID']);
		$APPToken	= trim($_POST['APPToken']);
		$Site		= preg_replace('#/$#','', trim(@$_POST['Site']));
		if(substr($Site,0,7)=='http://' || substr($Site,0,8)=='https://'){}else{$Site='';}
		$xzh		= fanly_submit_test($Site, $APPID, $APPToken);
		if( $xzh && @$xzh['success_batch']!=1 ){
			$APPID		= '';
			$APPToken	= '';
		}
		$Fanly = array( 
			'Site'		=> $Site,
			'APPID'		=> $APPID,
			'APPToken'	=> $APPToken,
			'Types'		=> @$_POST['Types'],
			'Default'	=> trim(@$_POST['Default']),
			'Original'	=> trim(@$_POST['Original']),
		);
		@update_option('FanlySubmit', $Fanly);
		if($Site){
			$updated = $APPID ? '设置成功！' : '熊掌号 ID/Token 错误！';
		}else{
			$updated = '站点域名必须以‘http://’或‘https://’开始';
		}
		echo '<div class="updated" id="message"><p>'.$updated.'</p></div>';
	}
	
	$Fanly		= get_option('FanlySubmit');	$Default	= $Fanly['Default']	!== '' ? 'checked="checked"' : '';
	$Original	= $Fanly['Original']	!== '' ? 'checked="checked"' : '';
	echo '<div class="wrap">';
	echo '<h2>百度熊掌号/原创保护数据推送</h2>';
	echo '<form method="post">';
	echo '<table class="form-table">';
	echo '<tr valign="top">';
	echo '<th scope="row">站点域名</th>';
	$Site = @$Fanly['Site'] ? $Fanly['Site'] : home_url();
	echo '<td><input class="all-options" type="text" name="Site" value="'.$Site.'" /></td>';
	echo '</tr>';

	echo '<tr valign="top">';
	echo '<th scope="row">熊掌号 APPID</th>';
	echo '<td><input class="all-options" type="text" name="APPID" value="'.$Fanly['APPID'].'" /></td>';
	echo '</tr>';
	
	echo '<tr valign="top">';
	echo '<th scope="row">熊掌号 Token</th>';
	echo '<td><input class="all-options" type="text" name="APPToken" value="'.$Fanly['APPToken'].'" /></td>';
	echo '</tr>';
	
	echo '<tr valign="top">';
	echo '<th scope="row">文章类型支持</th>';
	echo '<td>';
	$args = array('public' => true,);
	$post_types = get_post_types($args);
	foreach ( $post_types  as $post_type ) {
		if($post_type != 'attachment'){
			$postType = get_post_type_object($post_type);
			echo '<label><input type="checkbox" name="Types[]" value="'.$post_type.'" ';
			if(is_array($Fanly['Types'])) {if(in_array($post_type,$Fanly['Types'])) echo 'checked';}
			echo '>'.$postType->labels->singular_name.' &nbsp; &nbsp; </label>';
		}
	}
	echo '</td></tr>';
	
	echo '<tr valign="top">';
	echo '<th scope="row">是否默认提交数据</th>';
	echo '<td><label><input value="true" type="checkbox" name="Default" '.$Default.'> 勾选后默认都提交数据到百度熊掌号，文章发布时可修改！</label></td>';
	echo '</tr>';
	
	echo '<tr valign="top">';
	echo '<th scope="row">是否默认原创</th>';
	echo '<td><label><input value="true" type="checkbox" name="Original" '.$Original.'> 勾选后默认都提交为原创，文章发布时可修改！</label></td>';
	echo '</tr>';
	
	echo '</table>';
	echo '<p class="submit">';
	echo '<input type="submit" name="FanlySubmit" id="submit" class="button button-primary" value="保存更改" />';
	echo '</p>';
	echo '</form>';
	echo '<p><strong>使用提示</strong>：<br>
	熊掌号 APPID/Token 通过 百度搜索资源平台-熊掌号-API提交-推送接口 > 接口调用地址获取；<br>
	</p>';
	echo '</div>';
}