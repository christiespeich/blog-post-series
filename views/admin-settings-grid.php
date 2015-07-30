<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

<form method="POST">
	<?php wp_nonce_field('mbdbps_admin_settings', '_mbdbsp_admin_settings_nonce'); ?>
	<div id="mbdbsp_series_div"><label for="_mbdbps_series"><?php _e('Series', MBDBPS_TEXT_DOMAIN); ?></label>
<select class="cmb2_select" name="_mbdbps_series" id="_mbdbps_series">	
	<?php
		echo mbdbsp_get_series_dropdown(false, false, $series);
	?>
	</select>
	<img id="mbdbps_loading_image" src="<?php echo plugins_url('../images/ajax-loader.gif', __FILE__ ) ?>">
	</div>
	<div id="mbdbsp_series_data_div">
	<div id="mbdbsp_series_name_div"><label for="_mbdbps_series_name"><?php _e('Series Name', MBDBPS_TEXT_DOMAIN); ?></label>
	
<input type="text" class="regular-text" name="_mbdbps_series_name" id="_mbdbps_series_name" value=""/></div>
<h3 class="cmb2-metabox-title"><?php _e('At the Beginning of Each Post in the Series', MBDBPS_TEXT_DOMAIN); ?> </h3>
<div id="mbdbps_top_text_div"><label for="_mbdbps_top_text"><?php _e('Display', MBDBPS_TEXT_DOMAIN); ?></label>
	<select class="cmb2_select" name="_mbdbps_top_text" id="_mbdbps_top_text">
		<option value="none"><?php _e('Nothing', MBDBPS_TEXT_DOMAIN); ?></option>
		<option value="full"><?php _e('Full List of Posts', MBDBPS_TEXT_DOMAIN); ?></option>
		<option value="nextprev"><?php _e('Only Next and Previous Posts', MBDBPS_TEXT_DOMAIN); ?></option>
	</select>
	</div>
	<div id="mbdbps_top_intro_div">
	<input type="checkbox" class="cmb2_checkbox" name="_mbdbps_top_intro" id="_mbdbps_top_intro"><?php _e('Include Intro Text', MBDBPS_TEXT_DOMAIN); ?>
</div>
<h3 class="cmb2-metabox-title"><?php _e('At the End of Each Post in the Series', MBDBPS_TEXT_DOMAIN); ?> </h3>
<div id="mbdbps_bottom_div"><label for="_mbdbps_bottom_text"><?php _e('Display', MBDBPS_TEXT_DOMAIN); ?></label>
	<select class="cmb2_select" name="_mbdbps_bottom_text" id="_mbdbps_bottom_text">
		<option value="none"><?php _e('Nothing', MBDBPS_TEXT_DOMAIN); ?></option>
		<option value="full"><?php _e('Full List of Posts', MBDBPS_TEXT_DOMAIN); ?></option>
		<option value="nextprev"><?php _e('Only Next and Previous Posts', MBDBPS_TEXT_DOMAIN); ?></option>
	</select>
	</div>
<div id="mbdbps_bottom_intro_div">
	<input type="checkbox" class="cmb2_checkbox" name="_mbdbps_bottom_intro" id="_mbdbps_bottom_intro"><?php _e('Include Intro Text', MBDBPS_TEXT_DOMAIN); ?>
</div>
<h3 class="cmb2-metabox-title"><?php _e('Posts in This Series (drag and drop to rearrange)', MBDBPS_TEXT_DOMAIN); ?></h3><ul id="mbdbps_post_grid"></ul>
</div>
	<input id="_mbdbps_submit" type="submit" value="<?php _e('Save', MBDBPS_TEXT_DOMAIN); ?>" class="button button-primary button-large">
</form>
