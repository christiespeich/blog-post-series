jQuery( document ).ready(function() {
	
	// bind the change event on all the drop down
	jQuery('#_mbdbps_series').bind('change', mbdbps_post_series_change);
	
	
	// set visibility of everything as needed
	mbdbps_post_series_change();
	
		
	
});

function mbdbps_post_series_change() {
	var post_series = jQuery('#_mbdbps_series').val();
	switch (post_series) {
		case '0':
			// if no series selected hide everything else
			jQuery('.cmb2-id--mbdbps-series').nextAll('div').hide();
			break;
		case '-1': 
			// only show the series name text box is Create New Series is selected
			// hide Comes After drop down if Create New Series is selected
			jQuery('.cmb2-id--mbdbps-series').nextAll('div').show();
			//jQuery('.cmb2-id--mbdbps-comes-after').hide();
			break;
		default:
			// show everything but New Series Name
			jQuery('.cmb2-id--mbdbps-series').nextAll('div').show();
			jQuery('.cmb2-id--mbdbps-new-series-name').hide();
			// clear out the comes after drop down and hide it
		//	jQuery('#_mbdbps_comes_after').empty().hide();
			// load the comes after drop down
		//	var data = {
		//		'action': 'load_post_series',
		//		'post_series': post_series,
		//		'post_id': jQuery('#post_ID').val(),
		//		'security': ajax_object.security
		//	};
		//	jQuery.post(ajax_object.ajax_url, data, mbdbps_get_posts_list);
	}
	
}

// function mbdbps_get_posts_list(response) {
	// var post_list = JSON.parse(response);
	// var comes_after = jQuery('#_mbdbps_comes_after');
	// for (post in post_list) {
		// if (!post_list.hasOwnProperty(post)) {
			// continue;
		// }
		// comes_after[0].add(new Option(post_list[post]['title'], post));
	// }
	// // show the drop down once it's populated
	// comes_after.show();
// }

