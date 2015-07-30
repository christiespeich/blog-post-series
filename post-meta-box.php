<?php
/************************************
	META BOX
************************************/
add_action( 'cmb2_init', 'mbdbps_init_post_meta_box' );
function mbdbps_init_post_meta_box() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_mbdbps_';

	$post_meta_box = new_cmb2_box( array(
		'id'            => $prefix . 'post_meta_box',
		'title'         => __( 'Blog Post Series', MBDBPS_TEXT_DOMAIN ),
		'object_types'  => array( 'post', ), // Post type
		 'context'    => 'normal',
		 'priority'   => 'high',
		 'show_names' => true, // Show field names on the left
		
		
	) );

	$post_meta_box->add_field( array(
		'name'       => __( 'Series', MBDBPS_TEXT_DOMAIN ),
		'id'         => $prefix . 'series',
		'type'       => 'select',
		'options'	=> mbdbps_get_series_list(),
		
	) );	
	
	$post_meta_box->add_field( array(
		'name'       => __( 'Series Name', MBDBPS_TEXT_DOMAIN ),
		'id'         => $prefix . 'new_series_name',
		'type'       => 'text',
		
	) );
	
	$post_meta_box->add_field( array(
		'name'       => __( 'Intro', MBDBPS_TEXT_DOMAIN ),
		'id'         => $prefix . 'intro',
		'type'       => 'wysiwyg',
	) );
}
	

/**********************************************************
	METABOX SAVING
**********************************************************/
add_action( 'cmb2_override__mbdbps_series_meta_save', 'mbdbps_series_save', 3, 4);
function mbdbps_series_save($override, $a, $args, $field_obj ) {
	
	
	global $post;
	$new_series = $a['value'];
	$mbdbps_series = get_option('mbdbps_series');
	
	// remove from any series already in
	if (isset($mbdbps_series) && $mbdbps_series != '' && $mbdbps_series != null) {
		foreach ($mbdbps_series as $seriesID => $series) {
			if (array_key_exists('posts', $mbdbps_series[$seriesID])) {
				$keys = array_keys($mbdbps_series[$seriesID]['posts'], $post->ID);
				foreach ($keys as $key) {
					// remove the post
					unset($mbdbps_series[$seriesID]['posts'][$key]);
				}
				// renumber the indices
				$mbdbps_series[$seriesID]['posts'] = array_values($mbdbps_series[$seriesID]['posts']);
			}
		}
	}
	update_option('mbdbps_series', $mbdbps_series);
	
	if ($new_series == '-1') {
		$new_series_ID = mbdbps_uniqueID_generator('');
		// TODO: sanitize
		$new_series_name = $_REQUEST['_mbdbps_new_series_name'];
		$new_series_slug = sanitize_title($new_series_name);
		$mbdbps_series[$new_series_ID]['title'] = $new_series_name;
		$mbdbps_series[$new_series_ID]['slug'] = $new_series_slug;
		$mbdbps_series[$new_series_ID]['posts'][] = $post->ID;
		update_option('mbdbps_series', $mbdbps_series);
		update_post_meta($post->ID, '_mbdbps_series', $new_series_ID);
		// override the saving of this field
		return true;
	}
	
	// if not CREATE NEW SERIES, save the post at the end of the order
	if ($new_series != '0') {
		$mbdbps_series[$new_series]['posts'][] = $post->ID;
		update_option('mbdbps_series', $mbdbps_series);
		// if added to an existing series, DON'T override
		return null;
	
	}
	
	// if series = 0 then there should be no series data saved
	if ($new_series == '0') {
		delete_post_meta($post->ID, '_mbdbps_series');
		// override. Don't save a 0 in the database
		return true;
	}
	

	
}

add_action( 'cmb2_override__mbdbps_new_series_name_meta_save', 'mbdbps_new_series_name_save', 3, 4);
function mbdbps_new_series_name_save($override, $a, $args, $field_obj ) {
	// override -- don't save it at all!
	return true;
}

/*************************************************************************
	columns
**************************************************************************/

// Add to our admin_init function
add_filter('manage_post_posts_columns', 'mbdbps_add_post_columns');
function mbdbps_add_post_columns($columns) {
    $columns['mbdbps_series'] = 'Series';
    return $columns;
}

// Add to our admin_init function
add_action('manage_posts_custom_column', 'mbdbps_render_post_columns', 10, 2);
function mbdbps_render_post_columns($column_name, $id) {
    switch ($column_name) {
		case 'mbdbps_series':
			$seriesID = get_post_meta($id, '_mbdbps_series', true);
			$mbdbps_series = get_option('mbdbps_series');
			if (array_key_exists($seriesID, $mbdbps_series)) {
				if (array_key_exists('title', $mbdbps_series[$seriesID])) {
					echo $mbdbps_series[$seriesID]['title'];
					$orderID = array_search($id, $mbdbps_series[$seriesID]['posts']);
					if ($orderID !== false) {
						echo '<br>';
						echo sprintf('Post %d of %d', $orderID+1, count($mbdbps_series[$seriesID]['posts']));
					}
				} else {
					echo 'None';
				}
			} else {
				echo 'None';
			}
			break;
	}
}
/*
// Add to our admin_init function
add_action('quick_edit_custom_box',  'mbdbps_add_quick_edit', 10, 2);
function mbdbps_add_quick_edit($column_name, $post_type) {
    if ($column_name != 'mbdbps_series') return;
    ?>
    <fieldset class="inline-edit-col-left">
    <div class="inline-edit-col">
        <span class="title">Series</span>
        <input type="hidden" name="mbdbps_series_noncename" id="mbdbps_series_noncename" value="" />
		<select name='_mbdbps_series' id='_mbdbps_series'>
        <?php // Get all widget sets
			$series_list = mbdbps_get_series_list();
			foreach ($series_list as $seriesID => $series) {
				echo "<option class='widget-option' value='{$seriesID}'>{$series}</option>\n";
			}
		
            
        ?>
        </select>
    </div>
    </fieldset>
    <?php
}
*/
