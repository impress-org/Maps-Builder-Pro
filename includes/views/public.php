<?php
/**
 * Represents the view for the public-facing component
 *
 * @package   Google_Maps_Builder
 * @author    Devin Walker <devin@wordimpress.com>
 * @license   GPL-2.0+
 * @link      http://wordimpress.com
 * @copyright 2015 WordImpress, Devin Walker
 */
?>

<div class="google-maps-builder-wrap">

	<div id="google-maps-builder-<?php echo $atts['id']; ?>" class="google-maps-builder" <?php echo ! empty( $atts['id'] ) ? ' data-map-id="' . $atts['id'] . '"' : '">Error: NO MAP ID'; ?> style="width:<?php echo $visual_info['width'] . $visual_info['map_width_unit']; ?>; height:<?php echo $visual_info['height']; ?>px"></div>

	<div id="directions-panel-<?php echo $atts['id']; ?>" class="gmb-directions-panel"></div>

	<?php
	if(isset($localized_data[$post->ID]['places_search'][0]) && $localized_data[$post->ID]['places_search'][0] === 'yes') {
		include $this->get_google_maps_template( 'places-search.php' );
	}
	?>

</div>