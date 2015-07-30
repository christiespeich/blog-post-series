<?php
function mbdbps_get_seriesID_by_slug ($series_slug) {
	$mbdbps_series = get_option('mbdbps_series');
	foreach ($mbdbps_series as $key => $series) {
		if (array_search($series_slug, $series) !== false ) {
			return $key;
		} else {
			return null;
		}
	}
}

function mbdbps_get_series_name( $seriesID) {
	$mbdbps_series = get_option('mbdbps_series');
	if (isset($mbdbps_series[$seriesID])) {
		return $mbdbps_series[$seriesID]['title'];
	} else {
		return '';
	}
}

function mbdbps_get_series_list( $include_blank = true, $include_new = true ) {

	$series_list = array();
	// first two options in the drop down
	if ($include_blank) {
		$series_list['0'] = '';
	}
	if ($include_new) {
		$series_list['-1'] = 'Create New Series';
	}
	// previously saved series
	$mbdbps_series = get_option('mbdbps_series');
	
	// initialize arrays if there aren't any saved series
	if (!isset($mbdbps_series) || $mbdbps_series == null || $mbdbps_series == '') {
		$mbdbps_series = array();
		$series_names = array();
	}
	
	// create a new array that's just guids and titles
	foreach ($mbdbps_series as $guid => $series) {
		$series_names[$guid] = $mbdbps_series[$guid]['title'];
	}
	
	// sort by title
	array_multisort($series_names, SORT_ASC, SORT_NATURAL | SORT_FLAG_CASE); 
	$series_list = $series_list + $series_names;
	
	return $series_list;
}

function mbdbps_get_posts_list( $seriesID, $postID = null ) {
	
	$mbdbps_series = get_option('mbdbps_series');
	
	$posts_list = $mbdbps_series[$seriesID]['posts'];
	$args = array(
				'posts_per_page' => -1,
				'post_status'	=> 'publish',
				'post__in' => $posts_list);
	
	$posts = get_posts( $args ); 
	
	
	foreach ($posts as $post) {
	
		$key = array_search($post->ID, $posts_list);
		if ($key !== false) {
			$posts_list[$key] = array('ID' => $post->ID,
									'title' => $post->post_title,
									'link'	=> get_permalink($post->ID),
									'order'	=> $key);
		}
	}
	
	wp_reset_postdata();
	
	return $posts_list;
}

function mbdbsp_get_series_dropdown( $include_blank = true, $include_new = true, $selected = null ) {
	$series_list = mbdbps_get_series_list( $include_blank, $include_new );
	$html = '';
	foreach( $series_list as $key => $series) {
	
		$html .= '<option value="' . $key . '"';
		if (isset($selected) && $selected == $key) {
			$html .= ' selected';
		}
		$html .= '>' . $series . '</option>\n';
	}	
	return $html;
}


// generate uniqueIDs 
function mbdbps_uniqueID_generator( $value ) {
	if ($value=='') {
		$value =  uniqid();
	}
	return apply_filters('mbdb_settings_uniqid', $value);
}