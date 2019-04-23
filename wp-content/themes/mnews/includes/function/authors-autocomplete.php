<?php
global $salong;
if(!is_admin() || !$salong['switch_authors_autocomplete'])
return;

add_action( 'admin_enqueue_scripts', 'authors_autocomplete_mb_admin_enqueue_scripts_styles' );
function authors_autocomplete_mb_admin_enqueue_scripts_styles( $page ) {
	switch( $page ) {
		case 'post.php':
		case 'post-new.php':
			wp_enqueue_style( 'authors-autocomplete-css', get_template_directory_uri() . '/stylesheets/authors-autocomplete.css' );
			wp_enqueue_script( 'authors-autocomplete-js', get_template_directory_uri() . '/js/authors-autocomplete-min.js', array( 'jquery', 'post', 'jquery-ui-autocomplete' ), '', true );
			break;
	}
}

function authors_autocomplete_mb_allow_user( $userdata, $post_id = 0, $post_type = '' ) {
	global $wpdb, $blog_id, $wp_roles;

	if ( ! is_object( $userdata ) )
		return false;

	if ( ! isset( $userdata->ID ) )
		return false;

	if ( apply_filters( 'authors_autocomplete_mb_allow_user_id', true, $userdata->ID, $post_id, $post_type ) ) {

		if ( ! isset( $userdata->capabilities ) )
			$userdata->capabilities = $wpdb->get_var( "SELECT meta_value FROM $wpdb->usermeta WHERE user_id = {$userdata->ID} AND meta_key = '{$wpdb->get_blog_prefix( $blog_id )}capabilities'" );

		if ( ( $user_roles = maybe_unserialize( $userdata->capabilities ) ) && is_array( $user_roles ) ) {

			foreach( $user_roles as $role => $has_role ) {

				if ( apply_filters( 'authors_autocomplete_mb_allow_user_role', true, $role, $post_id, $post_type ) ) {

					$default_author_capability = ( $post_type && ( $post_type_object = get_post_type_object( $post_type ) ) && isset( $post_type_object->cap ) && isset( $post_type_object->cap->edit_posts ) ) ? $post_type_object->cap->edit_posts : 'edit_posts';

					$author_capability = apply_filters( 'authors_autocomplete_mb_author_capability', $default_author_capability, $post_id, $post_type );

					if ( isset( $wp_roles->roles[ $role ] )
						&& isset( $wp_roles->roles[ $role ][ 'capabilities' ] )
						&& isset( $wp_roles->roles[ $role ][ 'capabilities' ][ $author_capability ] )
						&& $wp_roles->roles[ $role ][ 'capabilities' ][ $author_capability ] ) {

						return true;

					}

				}

			}

		}

	}

	return false;

}

add_action( 'wp_ajax_authors_autocomplete_mb_autocomplete_callback', 'ajax_authors_autocomplete_mb_autocomplete_callback' );
function ajax_authors_autocomplete_mb_autocomplete_callback() {
	global $wpdb, $blog_id;
    
	if ( $search_term = ( isset( $_POST[ 'authors_autocomplete_mb_search_term' ] ) && ! empty( $_POST[ 'authors_autocomplete_mb_search_term' ] ) ) ? $_POST[ 'authors_autocomplete_mb_search_term' ] : NULL ) {

		$post_id = ( isset( $_POST[ 'authors_autocomplete_mb_post_id' ] ) && $_POST[ 'authors_autocomplete_mb_post_id' ] > 0 ) ? $_POST[ 'authors_autocomplete_mb_post_id' ] : 0;

		$post_type = ( isset( $_POST[ 'authors_autocomplete_mb_post_type' ] ) && ! empty( $_POST[ 'authors_autocomplete_mb_post_type' ] ) ) ? $_POST[ 'authors_autocomplete_mb_post_type' ] : 0;

		if ( $custom_user_search_user_ids = apply_filters( 'authors_autocomplete_mb_custom_user_search_user_ids', array(), $search_term, $post_id, $post_type ) ) {

			$valid_custom_user_search_user_ids = array();
			foreach( $custom_user_search_user_ids as $id ) {
				if ( ( $id = intval( $id ) ) && $id > 0 )
					$valid_custom_user_search_user_ids[] = $id;
			}

			$custom_user_search_user_ids = $valid_custom_user_search_user_ids;

		}

		if ( $custom_user_search_user_ids )
			$custom_user_search_user_ids = "('" . implode( "','", $custom_user_search_user_ids ) . "')";
		else
			$custom_user_search_user_ids = "('')";

		if ( $users = $wpdb->get_results( "SELECT users.*, usermeta.meta_value AS capabilities FROM $wpdb->users users INNER JOIN $wpdb->usermeta usermeta ON usermeta.user_id = users.ID AND usermeta.meta_key = '{$wpdb->get_blog_prefix( $blog_id )}capabilities' WHERE ( ( users.user_login LIKE '%$search_term%' OR users.display_name LIKE '%$search_term%' OR users.user_email LIKE '%$search_term%' ) OR users.ID IN $custom_user_search_user_ids ) ORDER BY users.display_name" ) ) {

			$results = array();

			foreach ( $users as $user ) {
				if ( authors_autocomplete_mb_allow_user( $user, $post_id, $post_type ) ) {

					$results[] = array(
						'user_id'		=> $user->ID,
						'user_login'	=> $user->user_login,
						'display_name'	=> $user->display_name,
						'email'			=> $user->user_email,
						'value'			=> $user->ID,
						'label'			=> $user->display_name,
						);

				}
			}

			echo json_encode( $results );

		}

	}

	die();
}

add_action( 'wp_ajax_authors_autocomplete_mb_if_user_exists_by_value', 'ajax_authors_autocomplete_mb_if_user_exists_by_value' );
function ajax_authors_autocomplete_mb_if_user_exists_by_value() {
	global $wpdb, $blog_id;

	if ( $user_value = ( isset( $_POST[ 'authors_autocomplete_mb_user_value' ] ) && ! empty( $_POST[ 'authors_autocomplete_mb_user_value' ] ) ) ? $_POST[ 'authors_autocomplete_mb_user_value' ] : NULL ) {

		$post_id = ( isset( $_POST[ 'authors_autocomplete_mb_post_id' ] ) && $_POST[ 'authors_autocomplete_mb_post_id' ] > 0 ) ? $_POST[ 'authors_autocomplete_mb_post_id' ] : 0;

		$post_type = ( isset( $_POST[ 'authors_autocomplete_mb_post_type' ] ) && ! empty( $_POST[ 'authors_autocomplete_mb_post_type' ] ) ) ? $_POST[ 'authors_autocomplete_mb_post_type' ] : 0;

		if ( $user = $wpdb->get_row( "SELECT users.*, usermeta.meta_value AS capabilities FROM $wpdb->users users INNER JOIN $wpdb->usermeta usermeta ON usermeta.user_id = users.ID AND usermeta.meta_key = '{$wpdb->get_blog_prefix( $blog_id )}capabilities' WHERE ( users.user_login LIKE '$user_value' OR users.display_name LIKE '$user_value' OR users.user_email LIKE '$user_value' )" ) ) {

			if ( authors_autocomplete_mb_allow_user( $user, $post_id, $post_type ) ) {
				echo json_encode( $user );
				die();
			}

			else {
				echo json_encode( (object) array( 'notallowed' => sprintf( __( "用户'%s'没有权限编辑该文章。", 'salong' ), $user_value ) ) );
				die();
			}

		}

		echo json_encode( (object) array( 'doesnotexist' => sprintf( __( "用户'%s'不存在。", 'salong' ), $user_value ) ) );

	}

	die();
}

add_action( 'wp_ajax_authors_autocomplete_mb_get_user_gravatar', 'ajax_authors_autocomplete_mb_get_user_gravatar' );
function ajax_authors_autocomplete_mb_get_user_gravatar() {

	if ( $user_id = ( isset( $_POST[ 'authors_autocomplete_mb_user_id' ] ) && ! empty( $_POST[ 'authors_autocomplete_mb_user_id' ] ) ) ? $_POST[ 'authors_autocomplete_mb_user_id' ] : NULL ) {

    $user = get_userdata($user_id);
		echo salong_get_avatar($user_id,$user->display_name);

	}

	die();
}

add_action( 'add_meta_boxes', 'authors_autocomplete_mb_add_meta_boxes', 1, 2 );
function authors_autocomplete_mb_add_meta_boxes( $post_type, $post ) {
	global $wp_meta_boxes;
	if ( ( $screen = get_current_screen() )
		&& ( $post_type == $screen->id )
		&& post_type_supports( $post_type, 'author' )
		&& ( $post_type_object = get_post_type_object( $post_type ) )
		&& ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) ) ) {

		foreach( $wp_meta_boxes as $mb_post_type => $mb_post_type_context ) {
			foreach( $mb_post_type_context as $context => $mb_post_type_priority ) {
				foreach( $mb_post_type_priority as $priority => $mb_post_type_meta_boxes ) {
					foreach( $mb_post_type_meta_boxes as $mb_id => $mb ) {

						if ( 'authordiv' == $mb_id )
							unset( $wp_meta_boxes[ $mb_post_type ][ $context ][ $priority ][ $mb_id ] );

					}
				}
			}
		}

		add_meta_box( 'authors_autocomplete_mb_authordiv', __( 'Author' ), 'authors_autocomplete_mb_post_author_meta_box', $post_type, 'normal', 'core' );

	}
}

function authors_autocomplete_mb_post_author_meta_box( $post, $metabox ) {
	global $user_ID;

	if ( ( $post_type = ( isset( $post->post_type ) && ! empty( $post->post_type ) ) ? $post->post_type : NULL )
		&& post_type_supports( $post_type, 'author' )
		&& ( $post_type_object = get_post_type_object( $post_type ) )
		&& ( is_super_admin() || current_user_can( $post_type_object->cap->edit_others_posts ) ) ) {

		$author = isset( $post->post_author ) ? get_user_by( 'id', $post->post_author ) : get_user_by( 'id', $user_ID );

		authors_autocomplete_mb_print_author_autocomplete_table( $author );

	}
}

function authors_autocomplete_mb_print_author_autocomplete_table( $selected_author = array() ) {
    
	if ( ! is_a( $selected_author, 'WP_User' ) )
		$selected_author = NULL;

	?><div id="authors_autocomplete_mb_dropdown" class="hide-if-js">
		<label class="screen-reader-text" for="post_author_override"><?php _e( 'Author' ); ?></label>
		<?php wp_dropdown_users( array(
			'who' => 'authors',
			'name' => 'post_author_override',
			'selected' => ( isset( $selected_author ) && isset( $selected_author->ID ) ) ? $selected_author->ID : NULL,
			'include_selected' => true
			)); ?>

	</div>
	<div class="hide-if-no-js">
		<label class="screen-reader-text" for="authors_autocomplete_mb_post_author_override_user_id"><?php _e( 'Author' ); ?></label>
		<input type="hidden" id="authors_autocomplete_mb_post_author_override_user_id" name="post_author_override" value="<?php if ( isset( $selected_author ) && isset( $selected_author->ID ) ) echo $selected_author->ID; ?>" />
		<input type="hidden" id="authors_autocomplete_mb_post_author_override_display_name" name="authors_autocomplete_mb_post_author_display_name" value="<?php if ( isset( $selected_author ) && isset( $selected_author->data->display_name ) ) echo $selected_author->data->display_name; ?>" />
		<table id="authors_autocomplete_mb_autocomplete" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td id="authors_autocomplete_mb_post_author_gravatar"><?php echo salong_get_avatar($selected_author->ID,$selected_author->display_name); ?></td>
				<td><input type="text" name="authors_autocomplete_mb_post_author" id="authors_autocomplete_mb_post_author" class="form-input-tip" size="16" autocomplete="off" value="<?php if ( isset( $selected_author ) && isset( $selected_author->data->display_name ) ) echo $selected_author->data->display_name; ?>" /></td>
			</tr>
		</table>
		<p class="howto"><?php
			_e( '您可以通过显示名称、登录名或电子邮件地址来搜索作者。', 'salong' );
		?></p>
	</div><?php

}
