<?php
	
add_shortcode( 'bps_posts', 'mbdbps_shortcode_posts'  );
add_shortcode( 'bps_next', 'mbdbps_shortcode_next' );
add_shortcode( 'bps_prev', 'mbdbps_shortcode_prev' );
add_shortcode( 'bps_series_name', 'mbdbps_shortcode_series_name');
add_shortcode( 'bps_post_number', 'mbdps_shortcode_post_number');

// TODO: localize and sanitize! and prettying up the display! and adding CSS!

function mbdbps_output_intro($display, $postID) {
	if ($display == 'yes') {
		$intro = get_post_meta($postID, '_mbdbps_intro', true);
		if ($intro != '') {
			return ': ' .  preg_replace('/\\n/', '<br>', $intro);
		} else {
			return '';
		}
	}
}

function mbdbps_get_seriesID($series) {
	global $post;
	if ($series == '') {
		return get_post_meta($post->ID, '_mbdbps_series', true);
	} else {
		return mbdbps_get_seriesID_by_slug($series);
	}
	
}

function mbdbps_shortcode_posts($attr, $content) {
	$attr = shortcode_atts(array('intro' => 'no',
								'series' => ''), $attr);							
	global $post;
	$html_output = '';
	$seriesID = mbdbps_get_seriesID($attr['series']);
	if ($seriesID == '') {
		return $html_output;
	}
	$posts = mbdbps_get_posts_list($seriesID);
	$series_name = mbdbps_get_series_name($seriesID);
	$html_output .= '<p>In the ' . $series_name . ' series:</p>';
	if (count($posts)>0) {
		$html_output .= '<ol class="mbdbps_posts">';
		foreach ($posts as $one_post) {
			$html_output .= '<li>';
			if ($one_post['ID'] != $post->ID) {
				$html_output .= '<a href="' . $one_post['link'] . '">' . $one_post['title'] . '</a>';
			} else {
				$html_output .= '<strong>' . $one_post['title'] . '</strong>';
			}
			$html_output .= mbdbps_output_intro($attr['intro'], $one_post['ID']);
			$html_output .= '</li>';
		}
		$html_output .= '</ul>';
	}
	return $html_output;
																	
}
function mbdbps_next_prev($nextprev, $text, $series, $intro) {
global $post;
	$html_output = '';
	$seriesID = mbdbps_get_seriesID($series);
	if ($seriesID == '') {
		return $html_output;
	}	
	$posts = mbdbps_get_posts_list($seriesID);
	$found = null;
	foreach($posts as $one_post) {
			if ($one_post['ID'] == $post->ID) {
				$found = $one_post['order'];
			}
	}
	
	if ($found !== null) {
		
		if ($nextprev == 'next') {
			// make sure not last item if next
			$found++;
			if ($found >= count($posts)) {
				$found = null;
			}
		}
		if ($nextprev == 'prev') {
			// make sure no the first tiem
			if ($found == 0) {
				$found = null;
			} else {
				$found = $found - 1;
			}
		}	
		if ($found !== null) {
			$html_output .= '<div style="width:50%;display:inline">' . $text . ': <a href="' . $posts[$found]['link'] . '">' . $posts[$found]['title'] . '</a>';
			$html_output .= mbdbps_output_intro($intro, $posts[$found]['ID']);
			$html_output .= '</div>';
		}
	}
	return $html_output;
		
}


function mbdbps_shortcode_next($attr, $content) {
	$attr = shortcode_atts(array('intro' => 'no',
								'series' => ''), $attr);
	
	return mbdbps_next_prev('next', 'Next', $attr['series'], $attr['intro']);
}

function mbdbps_shortcode_prev($attr, $content) {
	$attr = shortcode_atts(array('intro' => 'no',
								'series' => ''), $attr);
								
	return mbdbps_next_prev('prev', 'Previous', $attr['series'], $attr['intro']);
}

function mbdbps_shortcode_series_name($attr, $content) {
	$attr = shortcode_atts(array('series' => ''), $attr);
	$seriesID = mbdbps_get_seriesID($attr['series']);
	if ($seriesID == '') {
		return '';
	}
	$seriesID = mbdbps_get_seriesID($attr['series']);
	if ($seriesID === null) {
		return '';
	}
	return mbdbps_get_series_name($seriesID);
	
}

function mbdps_shortcode_post_number($attr, $content) {
	$attr = shortcode_atts(array('series' => '',
								'postID' => '',
								'total' => 'no'), $attr);
	global $post;
	$seriesID = mbdbps_get_seriesID($attr['series']);						
	if ($seriesID == '') {
		return '';
	}
	$posts = mbdbps_get_posts_list($seriesID);
	$found = null;
	foreach($posts as $one_post) {
			if ($one_post['ID'] == $post->ID) {
				$found = $one_post['order'];
			}
	}
	
	if ($found !== null) {	
		$output_html = $found+1;
		if ($attr['total'] == 'yes') {
			$output_html .= ' of ' . count($posts);
		}
	} else {
		return '';
	}
	return $output_html;
	
								
}
