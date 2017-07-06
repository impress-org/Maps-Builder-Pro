<?php

/**
 * Google Maps Mashups
 *
 * Adds mashup metaboxes to user selected post types with Maps Builder Pro
 *
 * @package   Google_Maps_Builder_Admin
 * @author    WordImpress
 * @license   GPL-2.0+
 * @link      http://wordimpress.com
 * @copyright 2015 WordImpress
 */

/**
 * Class Google_Maps_Builder_Mashups_Metabox
 */
class Google_Maps_Builder_Mashups_Metabox {

	/**
	 * @var
	 */
	public $enabled_post_types;

	/**
	 * Google_Maps_Builder_Mashups_Metabox constructor.
	 *
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since 2.0.0
	 * @since 2.1.2 Added CMB2 hooks to delete transients on save.
	 */
	public function __construct() {

		// Add metaboxes and fields to CPT.
		add_action( 'cmb2_init', array( $this, 'mashup_metabox_fields' ) );
		add_action( 'cmb2_render_google_mashup_geocoder', array( $this, 'cmb2_render_google_mashup_geocoder' ), 10, 2 );
		add_filter( 'cmb2_sanitize_google_mashup_geocoder', array(
			$this,
			'cmb2_sanitize_google_mashup_geocoder'
		), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_mashup_scripts' ) );

		// Check if location fields have changed on save.
		add_action( 'cmb2_save_field__gmb_lat', array( $this, 'maybe_delete_transient' ), 10, 3 );
		add_action( 'cmb2_save_field__gmb_lng', array( $this, 'maybe_delete_transient' ), 10, 3 );
		add_action( 'cmb2_save_field__gmb_address', array( $this, 'maybe_delete_transient' ), 10, 3 );
		add_action( 'cmb2_save_field__gmb_place_id', array( $this, 'maybe_delete_transient' ), 10, 3 );

	}

	/**
	 * Mashup Metabox Field
	 *
	 * Defines the Google Places CPT metabox and field configuration
	 *
	 * @since  2.0
	 *
	 * @return array|mixed
	 */
	public function mashup_metabox_fields() {

		$this->enabled_post_types = gmb_get_option( 'gmb_mashup_metabox' );

		//Sanity check
		if ( $this->enabled_post_types === false ) {
			return;
		}

		// This prefix is used in CMB2 hooks when deleting transients!
		$prefix = '_gmb_';

		//Output metabox on appropriate CPTs
		$preview_box = cmb2_get_metabox( array(
			'id'           => 'google_maps_mashup_metabox',
			'title'        => __( 'Maps Builder Pro Mashup', 'google-maps-builder' ),
			'object_types' => $this->enabled_post_types, // post type
			'context'      => 'side', //  'normal', 'advanced', or 'side'
			'priority'     => 'core', //  'high', 'core', 'default' or 'low'
			'show_names'   => true, // Show field names on the left
		) );
		$preview_box->add_field( array(
			'id'      => $prefix . 'mashup_autocomplete',
			'type'    => 'google_mashup_geocoder',
			'after'   => '<div class="gmb-toggle-fields-wrap"><a href="#" class="gmb-toggle-fields"><span class="dashicons dashicons-arrow-down"></span>' . __( 'View Location Fields', 'google-maps-builder' ) . '</a></div>',
			'default' => '',
		) );

		$preview_box->add_field( array(
			'name'       => __( 'Marker Latitude', 'google-maps-builder' ),
			'before_row' => '<div class="gmb-toggle">',
			'id'         => $prefix . 'lat',
			'type'       => 'text',
		) );
		$preview_box->add_field( array(
			'name' => __( 'Marker Longitude', 'google-maps-builder' ),
			'id'   => $prefix . 'lng',
			'type' => 'text',
		) );
		$preview_box->add_field( array(
			'name' => __( 'Address', 'google-maps-builder' ),
			'id'   => $prefix . 'address',
			'type' => 'text',
		) );

		$preview_box->add_field( array(
			'name'      => __( 'Marker Place ID', 'google-maps-builder' ),
			'id'        => $prefix . 'place_id',
			'type'      => 'text',
			'after_row' => '</div>',//Closes .gmb-toggle
		) );
	}

	/**
	 * Enqueue Mashup Scripts
	 *
	 * @param $hook
	 *
	 * @return mixed
	 */
	public function enqueue_mashup_scripts( $hook ) {

		if ( $this->is_mashup_metabox_enabled() == false ) {
			return false;
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$apikey = gmb_get_option( 'gmb_maps_api_key' );

		//Only enqueue on post edit screens
		if ( $hook === 'post.php' || $hook === 'post-new.php' ) {

			//Load Google Maps API on this CPT
			wp_register_script( 'google-maps-builder-admin-gmaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . $apikey, array( 'jquery' ) );
			wp_enqueue_script( 'google-maps-builder-admin-gmaps' );

			//Register our mashup metabox JS
			wp_register_script( 'google-maps-builder-admin-mashups-script', GMB_PLUGIN_URL . 'assets/js/admin/admin-maps-mashup-metabox' . $suffix . '.js', array(
				'jquery'
			) );
			wp_enqueue_script( 'google-maps-builder-admin-mashups-script' );

			//Localize
			wp_localize_script( 'google-maps-builder-admin-mashups-script', 'gmb_mashup_data', array(
				'i18n' => array(
					'api_key_required'         => sprintf( __( '%1$sGoogle API Error:%2$s Please include your Google Maps API key in the %3$splugin settings%5$s to start using the plugin. An API key with Maps and Places APIs enabled is now required due to recent changes by Google. Getting an API key is free and easy. %4$sView Documentation%5$s', 'google-maps-builder' ), '<strong>', '</strong>', '<a href="' . esc_url( admin_url( 'edit.php?post_type=google_maps&page=gmb_settings' ) ) . '">', '<a href="https://wordimpress.com/documentation/maps-builder-pro/creating-maps-api-key/" target="_blank" class="new-window">', '</a>' )
				)
			) );

			wp_register_style( 'google-maps-builder-admin-mashups-style', GMB_PLUGIN_URL . 'assets/css/gmb-mashup-metabox.css' );
			wp_enqueue_style( 'google-maps-builder-admin-mashups-style' );
		}


	}

	/**
	 * Is Mashup Metabox Enabled
	 *
	 * Conditional
	 * @return bool
	 */
	public function is_mashup_metabox_enabled() {

		$current_screen = get_current_screen();

		//False if not enabled or array (sanity check)
		if ( empty( $this->enabled_post_types ) || ! is_array( $this->enabled_post_types ) ) {
			return false;
		}

		//False if not enabled on this post type
		if ( ! isset( $current_screen->post_type ) || ! in_array( $current_screen->post_type, $this->enabled_post_types ) ) {
			return false;
		}

		//Bail if this isn't the post type in should be enabled on either
		return true;

	}


	/**
	 * Custom Google Autocomplete for Mashup
	 *
	 * @since  2.0
	 *
	 * @param $field
	 * @param $meta
	 *
	 * @return array
	 */
	function cmb2_render_google_mashup_geocoder( $field, $meta ) {

		$meta = wp_parse_args(
			$meta, array(
				'geocode'     => '',
				'geocode_set' => '',
			)
		);

		$output = '<div class="autocomplete-wrap" ' . ( $meta['geocode_set'] == '1' ? 'style="display:none;"' : '' ) . '>';

		$output .= '<label for="' . $field->args( 'id' ) . '">' . __( 'Add Location', 'google-maps-builder' ) . '</label>';
		$output .= '<input type="text" name="' . $field->args( 'id' ) . '[geocode]" id="' . $field->args( 'id' ) . '" value="" class="search-autocomplete" />';
		$output .= '<input type="hidden" name="' . $field->args( 'id' ) . '[geocode_set]" id="' . $field->args( 'id' ) . '" value="' . $meta['geocode_set'] . '" class="search-autocomplete-set" />';
		$output .= '<p class="autocomplete-description"> ' . __( 'Enter the name of a point of interest, address, or establishment above or manually set the fields below.', 'google-maps-builder' ) . '</p>';
		$output .= '</div>';//autocomplete-wrap
		$output .= '<div class="gmb-autocomplete-notice"' . ( $meta['geocode_set'] !== '1' ? 'style="display:none;"' : '' ) . '><p>' . __( 'Location set for this post', 'google-maps-builder' ) . '</p><a href="#" class="gmb-reset-autocomplete button button-small">' . __( 'Reset', 'google-maps-builder' ) . '</a>';
		$output .= '</div>';

		echo $output;

	}


	/**
	 * Sanitize Mashup Metabox
	 *
	 * $current_user->IDClears out meta_key transient if it doesn't contain new metakey
	 * @since      2.0
	 */
	function cmb2_sanitize_google_mashup_geocoder() {

		global $post;
		$existing_transient = get_transient( $post->post_type . '_meta_keys' );

		if ( $existing_transient === false ) {
			return;
		}

		if ( ! in_array( '_gmb_lat', $existing_transient ) || ! in_array( '_gmb_lng', $existing_transient ) ) {
			delete_transient( $post->post_type . '_meta_keys' );
		}

	}

	/**
	 * Deletes mash-up transient if a location field has been updated.
	 *
	 * When a location field is updated, transients that store mash-up data
	 * for that post type must be deleted. Otherwise the mash-up markers will
	 * not reflect the updated fields on the map.
	 *
	 * @since 2.1.2
	 *
	 * @param bool              $updated Whether the metadata update action occurred.
	 * @param string            $action  Action performed. Could be "repeatable", "updated", or "removed".
	 * @param CMB2_Field object $field   This field object
	 */
	function maybe_delete_transient( $updated, $action, $field ) {
		if ( $updated ) {
			global $wpdb;
			$post_type = get_post_type( $field->object_id );

			// Delete all mash-up transients associated with this post type.
			$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s", '%gmb_mashup_' . $post_type . '%' ) );

			// Unhook actions from other location fields. No need to check them.
			$this->unhook_maybe_delete_transient();
		}
	}

	/**
	 * Unhooks actions after mash-up transient has been deleted.
	 *
	 * Once a transient is deleted, there is no need to continue checking
	 * whether other location fields have changed.
	 *
	 * @since 2.1.2
	 */
	function unhook_maybe_delete_transient() {
		remove_action( 'cmb2_save_field__gmb_lat', array( $this, 'maybe_delete_transient' ) );
		remove_action( 'cmb2_save_field__gmb_lng', array( $this, 'maybe_delete_transient' ) );
		remove_action( 'cmb2_save_field__gmb_address', array( $this, 'maybe_delete_transient' ) );
		remove_action( 'cmb2_save_field__gmb_place_id', array( $this, 'maybe_delete_transient' ) );
	}


} //end class

new Google_Maps_Builder_Mashups_Metabox();