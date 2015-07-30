jQuery( document ).ready(function() {
	window.mbdbps_unsaved_changes = false;
	
	// grab the initial series selection
	window.mbdbps_series_name = jQuery('#_mbdbps_series option:selected').text();
	window.mbdbps_series_ID = jQuery('#_mbdbps_series').val();
	
	// bind the change event on all the drop down
	jQuery('#_mbdbps_series').bind('change', mbdbps_post_series_change);
	
	// bind change events to all server-side inputs to set the unsaved_changes = true
	jQuery('#_mbdbps_series_name').bind('change', mbdbps_series_change);
	jQuery('#_mbdbps_bottom_text').bind('change', mbdbps_series_change);
	jQuery('#_mbdbps_bottom_intro').bind('change', mbdbps_series_change);
	jQuery('#_mbdbps_top_text').bind('change', mbdbps_series_change);
	jQuery('#_mbdbps_top_intro').bind('change', mbdbps_series_change);
	
	
	// bind a function to save the grid order via ajax
	jQuery('#_mbdbps_submit').bind('click', mbdbps_save);
	
	// make the grid sortable
	jQuery('#mbdbps_post_grid').sortable({
		opacity: 0.5,
		placeholder : 'ui-state-highlight',
		cursor: 'pointer',
		update: mbdbps_post_grid_update,
		deactivate: function () {
				window.unsaved_changes = true;
			}
	});
	
	// show the loading gif and hide the other elements and disable the button while ajax is loading
	jQuery('#mbdbps_loading_image').hide();
	jQuery('#mbdbps_loading_image').bind('ajaxStart', function(){
			jQuery(this).show();
			jQuery('#mbdbsp_series_data_div input, #mbdbsp_series_data_div select').prop('disabled', true); //hide();
			jQuery('#_mbdbps_submit').prop('disabled', true);
		}).bind('ajaxStop', function(){
			jQuery(this).hide();
		//	jQuery('#mbdbsp_series_data_div').show();
			jQuery('#mbdbsp_series_data_div input, #mbdbsp_series_data_div select').prop('disabled', false); //hide();
			jQuery('#_mbdbps_submit').prop('disabled', false);
		});

});

// the user has changed the series in the drop down
function mbdbps_post_series_change() {
	
	// if there are unsaved changes, warn the user
	if (window.unsaved_changes) {
		$response = confirm(localized_strings.message1 + ' ' + window.mbdbps_series_name + ' ' + localized_strings.message2 + ' ' + jQuery('#_mbdbps_series option:selected').text());
		if (!$response) {
			jQuery('#_mbdbps_series').val(window.mbdbps_series_ID);
			exit;
		} else {
			window.unsaved_changes = false;
		}
		
	}
	var post_series = jQuery('#_mbdbps_series').val();
	
	// grab the initial series selection
	window.mbdbps_series_name = jQuery('#_mbdbps_series option:selected').text();
	window.mbdbps_series_ID = jQuery('#_mbdbps_series').val();
	
	
	// set the text box to the name of the selected series
	jQuery('#_mbdbps_series_name').val(window.mbdbps_series_name);
	
	// load the grid via ajax
	var data = {
		'action': 'get_series_data',  // PHP function
		'series_id': jQuery('#_mbdbps_series').val(),
		'security': ajax_object.security
	};
	jQuery.post(ajax_object.ajax_url, data, mbdbps_load_posts_grid);  // JS function

}

// load the grid after the ajax is complete
function mbdbps_load_posts_grid(response) {
	var series_data = JSON.parse(response);
	
	// set top and bottomw inputs
	jQuery('#_mbdbps_top_text').val(series_data['top_text']);
	jQuery('#_mbdbps_bottom_text').val(series_data['bottom_text']);
	jQuery('#_mbdbps_top_intro').prop('checked', series_data['top_intro']);
	jQuery('#_mbdbps_bottom_intro').prop('checked', series_data['bottom_intro']);
	
	// remove any items in the grid
	jQuery('#mbdbps_post_grid li').remove();
	
	
	// add each item to the grid
	for (post in series_data['posts']) {
		if (!series_data['posts'].hasOwnProperty(post)) {
			continue;
		}
		jQuery('#mbdbps_post_grid').append('<li id="mbdbps_post_' + series_data['posts'][post]['ID'] + '" class="ui-state-default"><span class="ui-icon"></span>' + series_data['posts'][post]['title'] + '</li>');
	}
	
	// update the icons
	mbdbps_post_grid_update();
}

// an input has changed
function mbdbps_series_change() {
	window.unsaved_changes = true;
}

// update the icons in the grid
function mbdbps_post_grid_update() {
	// remove all the classes and add ui-icon on all of the items in the grid
	jQuery('#mbdbps_post_grid li span').removeClass().addClass('ui-icon');
	// add a down arrow to the first item
	jQuery('#mbdbps_post_grid li:first span').addClass('ui-icon-arrowthick-1-s');
	// add an up arrow to the last item
	jQuery('#mbdbps_post_grid li:last span').addClass('ui-icon-arrowthick-1-n');
	// add an up and down arrow to any non-first and non-last item
	jQuery('#mbdbps_post_grid li').not(':first').not(':last').children('span').addClass('ui-icon-arrowthick-2-n-s');
}

// save the sorted grid via ajax
function mbdbps_save() {
	var data = {
			'action': 'save_posts_grid',
			'series_id': jQuery('#_mbdbps_series').val(),
			'posts': jQuery('#mbdbps_post_grid').sortable('serialize'),
			'security': ajax_object.security
	};
	jQuery.post(ajax_object.ajax_url, data, mbdbps_after_save);
}
    
// function that's called after the save grid ajax
// not really anything to do...
function mbdbps_after_save() {
	
}
	