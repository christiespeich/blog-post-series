

<?php
	
	
add_action( 'admin_menu', 'mbdbps_settings_menu');
function mbdbps_settings_menu() {
	add_options_page( __('Blog Post Series Settings', MBDBPS_TEXT_DOMAIN), __('Blog Post Series', MBDBPS_TEXT_DOMAIN), 'manage_options', 'mbdbps_settings', 'mbdbps_settings_page');
}
	
function mbdbps_settings_page() {
	$series = null;
	if (isset($_POST['_mbdbps_series'])) {
		if (!isset( $_POST['_mbdbsp_admin_settings_nonce'] ) || !wp_verify_nonce( $_POST['_mbdbsp_admin_settings_nonce'], 'mbdbps_admin_settings' )) {
			// This nonce is not valid.
			die( 'Security check' ); 
		}
		// code to save
		$mbdbps_series = get_option('mbdbps_series');		
		if (isset($mbdbps_series[$_POST['_mbdbps_series']])) {
			// TODO: Sanitize
			// TODO: make new slug based on title
			$mbdbps_series[$_POST['_mbdbps_series']]['title'] = $_POST['_mbdbps_series_name'];
			$mbdbps_series[$_POST['_mbdbps_series']]['top_intro'] = isset($_POST['_mbdbps_top_intro']);
			$mbdbps_series[$_POST['_mbdbps_series']]['bottom_intro'] = isset($_POST['_mbdbps_bottom_intro']);
		
			$mbdbps_series[$_POST['_mbdbps_series']]['top_text'] = $_POST['_mbdbps_top_text'];
			$mbdbps_series[$_POST['_mbdbps_series']]['bottom_text'] = $_POST['_mbdbps_bottom_text'];
			update_option('mbdbps_series', $mbdbps_series);
		}
		// grab the selected series
		$series = $_POST['_mbdbps_series'];
	} 
	$mbdbps_nonce = wp_create_nonce( 'mbdbps_admin_settings' );
	include('views/admin-settings-grid.php');
	
}
	
	

add_action( 'wp_ajax_get_series_data', 'mbdbps_get_series_data' );	
function mbdbps_get_series_data() {
	$nonce = $_POST['security'];
	
	// check to see if the submitted nonce matches with the
	// generated nonce we created earlier
	if ( ! wp_verify_nonce( $nonce, 'mbdbps_admin_settings_ajax_nonce' ) ) {
		die ( );
	}
	if (isset($_POST['series_id'])) {
		$posts = mbdbps_get_posts_list($_POST['series_id']);
		$mbdbps_series = get_option('mbdbps_series');
		if (isset($mbdbps_series[$_POST['series_id']])) {
			$series = $mbdbps_series[$_POST['series_id']];
			$series['posts'] = $posts;
			if (!isset($series['top_text'])) {
				$series['top_text'] = 'none';
			}
			if (!isset($series['bottom_text'])) {
				$series['bottom_text'] = 'none';
			}
			if (!isset($series['top_intro'])) {
				$series['top_intro'] = false;
			}
			if (!isset($series['bottom_intro'])) {
				$series['bottom_intro'] = false;
			}
			echo json_encode($series);
		}
	}
	wp_die();
}

add_action( 'wp_ajax_save_posts_grid', 'mbdbps_save_posts_grid' );	
function mbdbps_save_posts_grid() {

	$nonce = $_POST['security'];
	// check to see if the submitted nonce matches with the
	// generated nonce we created earlier
	if ( ! wp_verify_nonce( $nonce, 'mbdbps_admin_settings_ajax_nonce' ) ) {
		die ( );
	}

	if (isset($_POST['posts'])) {
		parse_str($_POST['posts']);
		$mbdbps_series = get_option('mbdbps_series');
		if (isset($mbdbps_series[$_POST['series_id']])) {
			$mbdbps_series[$_POST['series_id']]['posts'] = $mbdbps_post;
			update_option('mbdbps_series', $mbdbps_series);
		}
	}
}



