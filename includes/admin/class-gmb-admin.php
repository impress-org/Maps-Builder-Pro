<?php

/**
 * Google Maps Admin
 *
 * The admin is considered the single post view where you build maps
 *
 * @package   Google_Maps_Builder_Admin
 * @author    WordImpress
 * @license   GPL-2.0+
 * @link      http://wordimpress.com
 * @copyright 2015 WordImpress
 */
class Google_Maps_Builder_Admin extends Google_Maps_Builder_Core_Admin {

	/**
	 * Defines the Google Places CPT metabox and field configuration
	 * @since  1.0.0
	 * @return array
	 */
	public function cpt2_metaboxes_fields() {

		parent::cpt2_metaboxes_fields();
		$prefix          = 'gmb_';
		$default_options = $this->get_default_map_options();

		$this->marker_box->add_field(
			array(
				'name'              => __( 'Animate in Markers', 'google-maps-builder' ),
				'desc'              => __( 'If you\'re adding a number of markers, you may want to drop them on the map consecutively rather than all at once.', 'google-maps-builder' ),
				'id'                => $prefix . 'marker_animate',
				'type'              => 'multicheck',
				'options'           => array(
					'yes' => 'Yes, Enable'
				),
				'select_all_button' => false,
			)
		);
		$this->marker_box->add_field(
			array(
				'name'              => __( 'Center Map upon Marker Click', 'google-maps-builder' ),
				'desc'              => __( 'When a user clicks on a marker the map will be centered on the marker when this option is enabled.', 'google-maps-builder' ),
				'id'                => $prefix . 'marker_centered',
				'type'              => 'multicheck',
				'options'           => array(
					'yes' => 'Yes, Enable'
				),
				'default' => 'yes',
				'select_all_button' => false,
			)
		);
		$this->marker_box->add_field(
			array(
				'name'              => __( 'Cluster Markers', 'google-maps-builder' ),
				'desc'              => __( 'If enabled Maps Builder will intelligently create and manage per-zoom-level clusters for a large number of markers.', 'google-maps-builder' ),
				'id'                => $prefix . 'marker_cluster',
				'type'              => 'multicheck',
				'options'           => array(
					'yes' => 'Yes, Enable'
				),
				'select_all_button' => false,
			)
		);

		$this->marker_box->add_group_field( $this->marker_box_group_field_id, array(
				'name'              => __( 'Marker Infowindow', 'google-maps-builder' ),
				'desc'              => __( 'Would you like this marker\'s infowindow open by default on the map?', 'google-maps-builder' ),
				'id'                => 'infowindow_open',
				'type'              => 'select',
				'default'           => 'closed',
				'options'           => array(
					'closed' => __( 'Closed by default', 'google-maps-builder' ),
					'opened' => __( 'Opened by default', 'google-maps-builder' )
				),
				'select_all_button' => false,
			)
		);

		// Directions
		$directions_box = cmb2_get_metabox( array(
			'id'           => 'google_maps_directions',
			'title'        => __( 'Directions', 'google-maps-builder' ),
			'object_types' => array( 'google_maps' ), // post type
			'context'      => 'normal', //  'normal', 'advanced', or 'side'
			'priority'     => 'core', //  'high', 'core', 'default' or 'low'
			'show_names'   => true, // Show field names on the left
		) );
		$directions_box->add_field(
			array(
				'name'    => __( 'Directions Display', 'google-maps-builder' ),
				'desc'    => __( 'How would you like to display the text directions on your website?', 'google-maps-builder' ),
				'id'      => $prefix . 'text_directions',
				'type'    => 'select',
				'default' => 'overlay',
				'options' => array(
					'none'    => __( 'No text directions', 'cmb' ),
					'overlay' => __( 'Display in overlay panel', 'cmb' ),
					'below'   => __( 'Display below map', 'cmb' ),
				),
			)
		);
		$group_field_id = $directions_box->add_field( array(
			'name'        => __( 'Direction Groups', 'google-maps-builder' ),
			'id'          => $prefix . 'directions_group',
			'type'        => 'group',
			'description' => __( 'Add sets of directions below.', 'google-maps-builder' ),
			'options'     => array(
				'group_title'   => __( 'Directions: {#}', 'cmb' ),
				'add_button'    => __( 'Add Directions', 'google-maps-builder' ),
				'remove_button' => __( 'Remove Directions', 'google-maps-builder' ),
				'sortable'      => false, // beta
			),
		) );
		$directions_box->add_group_field( $group_field_id, array(
			'name'       => __( 'Travel Mode', 'google-maps-builder' ),
			'id'         => 'travel_mode',
			'type'       => 'select',
			'attributes' => array(
				'class' => 'gmb-travel-mode',
			),
			'options'    => array(
				'DRIVING'   => __( 'Driving', 'google-maps-builder' ),
				'WALKING'   => __( 'Walking', 'google-maps-builder' ),
				'BICYCLING' => __( 'Bicycling', 'google-maps-builder' ),
				'TRANSIT'   => __( 'Transit', 'google-maps-builder' ),
			),
		) );
		$directions_box->add_group_field( $group_field_id, array(
			'name'       => __( 'Destinations', 'google-maps-builder' ),
			'id'         => 'point',
			'type'       => 'destination',
			'repeatable' => true,
			'options'    => array(
				'add_row_text'  => __( 'Add Destination', 'google-maps-builder' ),
				'remove_button' => __( 'Remove Destination', 'google-maps-builder' ),
				'sortable'      => false, // beta
			),
		) );

		$this->search_options->add_field(
			array(
				'name'              => __( 'Places Search', 'google-maps-builder' ),
				'desc'              => __( 'Adds a search box to a map, using the Google Place Autocomplete feature. The search box will return a pick list containing a mix of places and predicted search terms.', 'google-maps-builder' ),
				'id'                => $prefix . 'places_search',
				'type'              => 'multicheck',
				'options'           => array(
					'yes' => 'Yes, Enable Places Search'
				),
				'select_all_button' => false,
			)
		);

		//Snazzy maps.
		$this->display_options->add_field( array(
			'name'    => __( 'Map Theme', 'google-maps-builder' ),
			'desc'    => sprintf( __( 'Set optional preconfigured <a href="%1s" class="snazzy-link new-window"  target="_blank">Snazzy Maps</a> styles by selecting from the dropdown above or use your own style.', 'google-maps-builder' ), esc_url( 'http://snazzymaps.com' ) ) . '<br><a href="#" class="button button-small custom-snazzy-toggle">' . __( 'Set a Custom Snazzy Map', 'google-maps-builder' ) . '</a>',			'id'      => $prefix . 'theme',
			'type'    => 'select',
			'default' => 'none',
			'options' => apply_filters( 'gmb_snazzy_maps', array(
				'none'   => __( 'None', 'google-maps-builder' ),
				'custom' => __( 'Custom', 'google-maps-builder' ),
				'68'     => __( 'Aqua', 'google-maps-builder' ),
				'73'     => __( 'A Dark World', 'google-maps-builder' ),
				'42'     => __( 'Apple Maps-esque', 'google-maps-builder' ),
				'35'     => __( 'Avocado World', 'google-maps-builder' ),
				'23'     => __( 'Bates Green', 'google-maps-builder' ),
				'43'     => __( 'Bentley', 'google-maps-builder' ),
				'74'     => __( 'Becomeadinosaur', 'google-maps-builder' ),
				'79'     => __( 'Black and White', 'google-maps-builder' ),
				'28'     => __( 'Bluish', 'google-maps-builder' ),
				'11'     => __( 'Blue', 'google-maps-builder' ),
				'60'     => __( 'Blue Gray', 'google-maps-builder' ),
				'61'     => __( 'Blue Essence', 'google-maps-builder' ),
				'25'     => __( 'Blue water', 'google-maps-builder' ),
				'67'     => __( 'Blueprint', 'google-maps-builder' ),
				'66'     => __( 'Blueprint (No Labels)', 'google-maps-builder' ),
				'17'     => __( 'Bright & Bubbly', 'google-maps-builder' ),
				'45'     => __( 'Candy Colours', 'google-maps-builder' ),
				'63'     => __( 'Caribbean Mountain', 'google-maps-builder' ),
				'77'     => __( 'Clean Cut', 'google-maps-builder' ),
				'30'     => __( 'Cobalt', 'google-maps-builder' ),
				'80'     => __( 'Cool Grey', 'google-maps-builder' ),
				'6'      => __( 'Countries', 'google-maps-builder' ),
				'9'      => __( 'Chilled', 'google-maps-builder' ),
				'32'     => __( 'Deep Green', 'google-maps-builder' ),
				'56'     => __( 'Esperanto', 'google-maps-builder' ),
				'36'     => __( 'Flat Green', 'google-maps-builder' ),
				'53'     => __( 'Flat Map', 'google-maps-builder' ),
				'82'     => __( 'Grass is Greener', 'google-maps-builder' ),
				'5'      => __( 'Greyscale', 'google-maps-builder' ),
				'20'     => __( 'Gowalla', 'google-maps-builder' ),
				'48'     => __( 'Hard edges', 'google-maps-builder' ),
				'76'     => __( 'HashtagNineNineNine', 'google-maps-builder' ),
				'21'     => __( 'Hopper', 'google-maps-builder' ),
				'69'     => __( 'Holiday', 'google-maps-builder' ),
				'46'     => __( 'Homage to Toner', 'google-maps-builder' ),
				'24'     => __( 'Hot Pink', 'google-maps-builder' ),
				'41'     => __( 'Hints of Gold', 'google-maps-builder' ),
				'81'     => __( 'Ilustracao', 'google-maps-builder' ),
				'7'      => __( 'Icy Blue', 'google-maps-builder' ),
				'33'     => __( 'Jane Iredale', 'google-maps-builder' ),
				'71'     => __( 'Jazzygreen', 'google-maps-builder' ),
				'65'     => __( 'Just places', 'google-maps-builder' ),
				'59'     => __( 'Light Green', 'google-maps-builder' ),
				'29'     => __( 'Light Monochrome', 'google-maps-builder' ),
				'37'     => __( 'Lunar Landscape', 'google-maps-builder' ),
				'44'     => __( 'MapBox', 'google-maps-builder' ),
				'2'      => __( 'Midnight Commander', 'google-maps-builder' ),
				'57'     => __( 'Military Flat', 'google-maps-builder' ),
				'10'     => __( 'Mixed', 'google-maps-builder' ),
				'83'     => __( 'Muted Blue', 'google-maps-builder' ),
				'47'     => __( 'Nature', 'google-maps-builder' ),
				'34'     => __( 'Neon World', 'google-maps-builder' ),
				'13'     => __( 'Neutral Blue', 'google-maps-builder' ),
				'62'     => __( 'Night vision', 'google-maps-builder' ),
				'64'     => __( 'Old Dry Mud', 'google-maps-builder' ),
				'22'     => __( 'Old Timey', 'google-maps-builder' ),
				'1'      => __( 'Pale Dawn', 'google-maps-builder' ),
				'39'     => __( 'Paper', 'google-maps-builder' ),
				'78'     => __( 'Pink & Blue', 'google-maps-builder' ),
				'3'      => __( 'Red Alert', 'google-maps-builder' ),
				'31'     => __( 'Red Hues', 'google-maps-builder' ),
				'18'     => __( 'Retro', 'google-maps-builder' ),
				'51'     => __( 'Roadtrip At Night', 'google-maps-builder' ),
				'54'     => __( 'RouteXL', 'google-maps-builder' ),
				'75'     => __( 'Shade of Green', 'google-maps-builder' ),
				'38'     => __( 'Shades of Grey', 'google-maps-builder' ),
				'27'     => __( 'Shift Worker', 'google-maps-builder' ),
				'58'     => __( 'Simple Labels', 'google-maps-builder' ),
				'52'     => __( 'Souldisco', 'google-maps-builder' ),
				'12'     => __( 'Snazzy Maps', 'google-maps-builder' ),
				'19'     => __( 'Subtle', 'google-maps-builder' ),
				'49'     => __( 'Subtle Green', 'google-maps-builder' ),
				'15'     => __( 'Subtle Grayscale', 'google-maps-builder' ),
				'55'     => __( 'Subtle Grayscale Map', 'google-maps-builder' ),
				'50'     => __( 'The Endless Atlas', 'google-maps-builder' ),
				'4'      => __( 'Tripitty', 'google-maps-builder' ),
				'72'     => __( 'Transport for London', 'google-maps-builder' ),
				'8'      => __( 'Turquoise Water', 'google-maps-builder' ),
				'16'     => __( 'Unimposed Topography', 'google-maps-builder' ),
				'70'     => __( 'Unsaturated Browns', 'google-maps-builder' ),
				'14'     => __( 'Vintage', 'google-maps-builder' ),
				'26'     => __( 'Vintage Blue', 'google-maps-builder' ),
				'40'     => __( 'Vitamin C', 'google-maps-builder' ),
			) )
		) );

	}

	/**
	 * Add places search to output
	 *
	 * @since 2.1.0
	 *
	 * @param $output
	 *
	 * @return string
	 */
	function places_search( $output ){
		//Places search
		ob_start();
		include Google_Maps_Builder()->engine->get_google_maps_template( 'places-search.php' );
		$output .= ob_get_clean();
		$output .= '<div class="warning-message wpgp-message"></div>';

		return $output;
	}





}
