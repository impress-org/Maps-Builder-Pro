<?php
/**
 * Add extra marker options markup in pro via "gmb_extra_marker_options" hook
 *
 * @package   gmb
 * @license   GPL-2.0+
 * @copyright 2016 WordImpress
 */
?>
<div class="marker-item" data-marker="mapicons" data-toggle="map-icons-row">
	<div class="marker-map-icons marker-preview">
		<img src="<?php echo GMB_PLUGIN_URL . 'assets/img/logo-mapicons.png'; ?>" class="default-marker"/>
	</div>
	<div class="marker-description"><?php _e( 'Map Icons', 'google-maps-builder' ); ?></div>
</div>
<div class="marker-item" data-marker="mapicons" data-toggle="templatic-icons-row">
	<div class="marker-map-icons marker-preview">
		<img src="<?php echo GMB_PLUGIN_URL . 'assets/img/templatic-icon.png'; ?>" class="templatic-marker"/>
	</div>
	<div class="marker-description"><?php _e( 'Templatic Icons', 'google-maps-builder' ); ?></div>
</div>
<div class="marker-item" data-marker="upload" data-toggle="marker-upload">
	<div class="marker-upload marker-preview">
		<span class="dashicons dashicons-upload"></span>
	</div>
	<div class="marker-description"><?php _e( 'Upload Marker', 'google-maps-builder' ); ?></div>
</div>
