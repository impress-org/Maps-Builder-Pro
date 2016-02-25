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

		// MAP PREVIEW
		$preview_box = cmb2_get_metabox( array(
			'id'           => 'google_maps_preview_metabox',
			'title'        => __( 'Google Map Preview', $this->plugin_slug ),
			'object_types' => array( 'google_maps' ), // post type
			'context'      => 'normal', //  'normal', 'advanced', or 'side'
			'priority'     => 'high', //  'high', 'core', 'default' or 'low'
			'show_names'   => false, // Show field names on the left
		) );
		$preview_box->add_field( array(
			'name'    => __( 'Map Preview', $this->plugin_slug ),
			'id'      => $prefix . 'preview',
			'type'    => 'google_maps_preview',
			'default' => '',
		) );

		$this->marker_box->add_field(
			array(
				'name'              => __( 'Animate in Markers', $this->plugin_slug ),
				'desc'              => __( 'If you\'re adding a number of markers, you may want to drop them on the map consecutively rather than all at once.', $this->plugin_slug ),
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
				'name'              => __( 'Center Map upon Marker Click', $this->plugin_slug ),
				'desc'              => __( 'When a user clicks on a marker the map will be centered on the marker when this option is enabled.', $this->plugin_slug ),
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
				'name'              => __( 'Cluster Markers', $this->plugin_slug ),
				'desc'              => __( 'If enabled Maps Builder will intelligently create and manage per-zoom-level clusters for a large number of markers.', $this->plugin_slug ),
				'id'                => $prefix . 'marker_cluster',
				'type'              => 'multicheck',
				'options'           => array(
					'yes' => 'Yes, Enable'
				),
				'select_all_button' => false,
			)
		);

		$this->marker_box->add_group_field( $this->marker_box_group_field_id, array(
				'name'              => __( 'Marker Infowindow', $this->plugin_slug ),
				'desc'              => __( 'Would you like this marker\'s infowindow open by default on the map?', $this->plugin_slug ),
				'id'                => 'infowindow_open',
				'type'              => 'select',
				'default'           => 'closed',
				'options'           => array(
					'closed' => __( 'Closed by default', $this->plugin_slug ),
					'opened' => __( 'Opened by default', $this->plugin_slug )
				),
				'select_all_button' => false,
			)
		);

		// Directions
		$directions_box = cmb2_get_metabox( array(
			'id'           => 'google_maps_directions',
			'title'        => __( 'Directions', $this->plugin_slug ),
			'object_types' => array( 'google_maps' ), // post type
			'context'      => 'normal', //  'normal', 'advanced', or 'side'
			'priority'     => 'core', //  'high', 'core', 'default' or 'low'
			'show_names'   => true, // Show field names on the left
		) );
		$directions_box->add_field(
			array(
				'name'    => __( 'Directions Display', $this->plugin_slug ),
				'desc'    => __( 'How would you like to display the text directions on your website?', $this->plugin_slug ),
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
			'name'        => __( 'Direction Groups', $this->plugin_slug ),
			'id'          => $prefix . 'directions_group',
			'type'        => 'group',
			'description' => __( 'Add sets of directions below.', $this->plugin_slug ),
			'options'     => array(
				'group_title'   => __( 'Directions: {#}', 'cmb' ),
				'add_button'    => __( 'Add Directions', $this->plugin_slug ),
				'remove_button' => __( 'Remove Directions', $this->plugin_slug ),
				'sortable'      => false, // beta
			),
		) );
		$directions_box->add_group_field( $group_field_id, array(
			'name'       => __( 'Travel Mode', $this->plugin_slug ),
			'id'         => 'travel_mode',
			'type'       => 'select',
			'attributes' => array(
				'class' => 'gmb-travel-mode',
			),
			'options'    => array(
				'DRIVING'   => __( 'Driving', $this->plugin_slug ),
				'WALKING'   => __( 'Walking', $this->plugin_slug ),
				'BICYCLING' => __( 'Bicycling', $this->plugin_slug ),
				'TRANSIT'   => __( 'Transit', $this->plugin_slug ),
			),
		) );
		$directions_box->add_group_field( $group_field_id, array(
			'name'       => __( 'Destinations', $this->plugin_slug ),
			'id'         => 'point',
			'type'       => 'destination',
			'repeatable' => true,
			'options'    => array(
				'add_row_text'  => __( 'Add Destination', $this->plugin_slug ),
				'remove_button' => __( 'Remove Destination', $this->plugin_slug ),
				'sortable'      => false, // beta
			),
		) );

		$this->search_options->add_field(
			array(
				'name'              => __( 'Places Search', $this->plugin_slug ),
				'desc'              => __( 'Adds a search box to a map, using the Google Place Autocomplete feature. The search box will return a pick list containing a mix of places and predicted search terms.', $this->plugin_slug ),
				'id'                => $prefix . 'places_search',
				'type'              => 'multicheck',
				'options'           => array(
					'yes' => 'Yes, Enable Places Search'
				),
				'select_all_button' => false,
			)
		);


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
