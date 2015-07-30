<?php
/*
Plugin Name: Blog Post Series
Plugin URI:  http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Organizes multiple blog posts into a series
Version:     0.1
Author:      Mooberry Dreams
Author URI:  https://profiles.wordpress.org/mooberrydreams/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: mbd-blog-post-series

Blog Post Series is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Blog Post Series is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Blog Post Series. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

*/

define('MBDBPS_PLUGIN_DIR', plugin_dir_path( __FILE__ )); 

define('MBDBPS_PLUGIN_VERSION_KEY', 'mbdbps_version');
define('MBDBPS_PLUGIN_VERSION', '0.1'); 
define('MBDBPS_TEXT_DOMAIN', 'mbd-blog-post-series');


// load in CMB2
if ( file_exists( dirname( __FILE__ ) . '/includes/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/includes/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/includes/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/includes/CMB2/init.php';
}

require_once dirname( __FILE__ ) . '/post-meta-box.php';
require_once dirname( __FILE__ ) . '/includes/helper-functions.php';
require_once dirname(__FILE__) . '/admin-settings.php';
require_once dirname(__FILE__) . '/shortcodes.php';

add_action( 'admin_head', 'mbdbps_register_admin_styles' );	 
function mbdbps_register_admin_styles() {
	wp_register_style( 'mbdbps-admin-styles', plugins_url( 'css/admin-style.css', __FILE__)  );
	wp_enqueue_style( 'mbdbps-admin-styles' );
	wp_enqueue_style('mbdbps-jquery-ui-css', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css');

}

add_action( 'admin_footer', 'mbdbps_register_script');
function mbdbps_register_script() {
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script( 'mbdbps-admin-post-series', plugins_url(  'js/admin-post-series.js', __FILE__), array('jquery'));
	wp_enqueue_script( 'mbdbps-admin-settings', plugins_url(  'js/admin-settings.js', __FILE__), array('jquery')); //, 'jquery-ui', 'jquery-ui-sortable'));
	// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
	//wp_localize_script( 'admin-post-series', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce( 'mbdbps_post_series_ajax_nonce' ) ) );
	wp_localize_script( 'mbdbps-admin-settings', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'security' => wp_create_nonce( 'mbdbps_admin_settings_ajax_nonce' ) ) );
	
	$localized_array = array('message1' => __('Unsaved changes on', MBDBPS_TEXT_DOMAIN), 
								'message2' => __('will be lost if you switch to', MBDBPS_TEXT_DOMAIN));
	wp_localize_script( 'mbdbps-admin-settings', 'localized_strings', $localized_array);
}

add_filter( 'the_content', 'mbdbps_content');
function mbdbps_content($content) {
	if ( get_post_type() == 'post' && is_main_query() && !is_admin() ) {
		global $post;
		$series = get_post_meta($post->ID, '_mbdbps_series', true);
		if ($series != '') {
			$content = '[bps_posts intro="yes"] [bps_post_number total="yes"] [bps_series_name]' . $content . '[bps_prev intro="yes"][bps_next intro="yes"]';
		}
		
	}
	return $content;
}




	
	