<?php
/**
 * Add extra marker markup in pro before save button via "gmb_markers_before_save" hook
 *
 * @package   gmb
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 WordImpress
 */
?>
<div class="marker-icon-row marker-upload clear">
	<h3><?php _e( 'Step 2: Upload or Select a Marker Icon', $plugin_slug ); ?></h3>

	<div class="gmb-marker-image-wrap clear">
		<div class="gmb-image-preview"></div>
		<input class="gmb-upload-button button" onclick="gmb_upload_marker.uploader(); return false;" type="button" value="Upload or Select a Marker Image">
	</div>


</div>
