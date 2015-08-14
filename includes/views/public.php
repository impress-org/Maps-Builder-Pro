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

$map_width = isset( $visual_info['width'] ) ? $visual_info['width'] : '100';
$map_width .= isset( $visual_info['map_width_unit'] ) ? $visual_info['map_width_unit'] : '%';
$map_height = isset( $visual_info['height'] ) ? $visual_info['height'] : '500';
$map_height .= isset( $visual_info['map_height_unit'] ) ? $visual_info['map_height_unit'] : 'px';

?>

<div class="google-maps-builder-wrap">

	<div id="google-maps-builder-<?php echo $atts['id']; ?>" class="google-maps-builder" <?php echo ! empty( $atts['id'] ) ? ' data-map-id="' . $atts['id'] . '"' : '">Error: NO MAP ID'; ?> style="width:<?php echo $map_width; ?>; height:<?php echo $map_height; ?>"></div>

	<div id="directions-panel-<?php echo $atts['id']; ?>" class="gmb-directions-panel panel-<?php echo $text_directions; ?>">
		<div class="gmb-directions-toggle"><span class="gmb-directions-icon"><span class="gmb-hide-text"><?php _e( 'Toggle Directions', $this->plugin_slug ); ?></span></span></div>
		<div class="gmb-directions-panel-inner"></div>
	</div>

	<?php
	if ( isset( $localized_data[ $post->ID ]['places_search'][0] ) && $localized_data[ $post->ID ]['places_search'][0] === 'yes' ) {
		include $this->get_google_maps_template( 'places-search.php' );
	}
	?>

</div>