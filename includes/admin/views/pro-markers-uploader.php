<?php
/**
 * Adds the Uploader
 *
 * $current_user->IDAdds the upload html via "gmb_markers_before_save" hook
 *
 * @package   gmb
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 WordImpress
 */
?>
<div class="marker-icon-row marker-upload clear">
	<h3><?php _e( 'Step 2: Upload or Select a Marker Icon', 'google-maps-builder' ); ?></h3>

	<div class="gmb-marker-image-wrap clear">
		<div class="gmb-image-preview"></div>
		<input class="gmb-upload-button button" onclick="gmb_upload_marker.uploader(); return false;" type="button" value="<?php _e( 'Upload or Select a Marker Image', 'google-maps-builder' ); ?>">
	</div>
</div>