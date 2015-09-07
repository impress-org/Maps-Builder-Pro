<?php

/**
 * CMB Theme Options
 * @version 0.1.0
 */
class Google_Maps_Builder_Settings {


	/**
	 * Array of metaboxes/fields
	 * @var array
	 */
	protected static $plugin_options = array();

	public $plugin_slug;

	public $options_page;

	/**
	 * Option key, and option page slug
	 * @var string
	 */
	protected static $key = 'gmb_settings';


	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {

		$this->plugin_slug = Google_Maps_Builder()->get_plugin_slug();

		//Create Settings submenu
		add_action( 'admin_init', array( $this, 'mninit' ) );
		add_action( 'admin_menu', array( $this, 'add_page' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'wp_ajax_hide_welcome', array( $this, 'hide_welcome_callback' ) );

		//Add links/information to plugin row meta
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta_links' ), 10, 2 );
		add_filter( 'plugin_action_links', array( $this, 'add_plugin_page_links' ), 10, 2 );

		//Custom CMB2 Settings Fields
		add_action( 'cmb2_render_license_key', array( $this, 'gmb_license_key_callback' ), 10, 5 );
		add_action( 'cmb2_render_lat_lng_default', array( $this, 'cmb2_render_lat_lng_default' ), 10, 2 );
		add_action( 'plugins_loaded', array( $this, 'gmb_core_licensing' ) );
		add_filter( 'cmb2_get_metabox_form_format', array( $this, 'gmb_modify_cmb2_form_output' ), 10, 3 );

	}


	/**
	 * Core Licensing
	 */
	function gmb_core_licensing() {
		$core_license = new GMB_License( GMB_PLUGIN_BASE, 'Maps Builder Pro', GMB_VERSION, 'WordImpress', 'maps_builder_license_key' );
	}

	/**
	 * Register our setting to WP
	 * @since  0.1.0
	 */
	public function mninit() {

		register_setting( self::$key, self::$key );
	}

	/**
	 * Add menu options page
	 * @since 1.0.0
	 */
	public function add_page() {

		$this->options_page = add_submenu_page(
			'edit.php?post_type=google_maps',
			__( 'Maps Builder Settings', $this->plugin_slug ),
			__( 'Settings', $this->plugin_slug ),
			'manage_options',
			self::$key,
			array( $this, 'admin_page_display' )
		);

	}


	/**
	 * Hide the Settings welcome on click
	 *
	 * Sets a user meta key that once set
	 */
	public function hide_welcome_callback() {
		global $current_user;
		$user_id = $current_user->ID;
		add_user_meta( $user_id, 'gmb_hide_pro_welcome', 'true', true );
		wp_die(); // ajax call must die to avoid trailing 0 in your response
	}


	/**
	 * Admin page markup. Mostly handled by CMB
	 * @since  0.1.0
	 */
	public function admin_page_display() {

		include( 'views/settings-page.php' );

	}

	/**
	 * General Option Fields
	 * Defines the plugin option metabox and field configuration
	 * @since  1.0.0
	 * @return array
	 */
	public function general_option_fields() {

		// Only need to initiate the array once per page-load
		if ( ! empty( self::$plugin_options ) ) {
			return self::$plugin_options;
		}

		$prefix = 'gmb_';

		self::$plugin_options = array(
			'id'         => 'plugin_options',
			'show_on'    => array( 'key' => 'options-page', 'value' => array( self::$key, ), ),
			'show_names' => true,
			'fields'     => array(
				array(
					'name'    => __( 'Post Type Slug', $this->plugin_slug ),
					'desc'    => sprintf( __( 'Customize the default slug for the Maps Builder post type. %1$sResave (flush) permalinks%2$s after customizing.', $this->plugin_slug ), '<a href="' . esc_url( '/wp-admin/options-permalink.php' ) . '">"', '</a>' ),
					'default' => 'google-maps',
					'id'      => $prefix . 'custom_slug',
					'type'    => 'text_small'
				),
				array(
					'name'    => __( 'Menu Position', $this->plugin_slug ),
					'desc'    => sprintf( __( 'Set the menu position for Google Maps Builder. See the %1$smenu_position arg%2$s.', $this->plugin_slug ), '<a href="' . esc_url( 'http://codex.wordpress.org/Function_Reference/register_post_type#menu_position' ) . '" class="new-window" target="_blank">', '</a>' ),
					'default' => '21.3',
					'id'      => $prefix . 'menu_position',
					'type'    => 'text_small'
				),
				array(
					'name'    => __( 'Has Archive', $this->plugin_slug ),
					'id'      => $prefix . 'has_archive',
					'desc'    => sprintf( __( 'Controls the post type archive page. See <a href="%s">Resave (flush) permalinks</a> after customizing.', $this->plugin_slug ), esc_url( '/wp-admin/options-permalink.php' ) ),
					'type'    => 'radio_inline',
					'options' => array(
						'true'  => __( 'Yes', 'cmb' ),
						'false' => __( 'No', 'cmb' ),
					),
				),
				array(
					'name'    => __( 'Opening Map Builder', $this->plugin_slug ),
					'id'      => $prefix . 'open_builder',
					'desc'    => __( 'Do you want the Map Builder customizer to open by default when editing maps?', $this->plugin_slug ),
					'type'    => 'radio_inline',
					'options' => array(
						'true'  => __( 'Yes', 'cmb' ),
						'false' => __( 'No', 'cmb' ),
					),
				),
				array(
					'name' => __( 'Mashup Metabox', $this->plugin_slug ),
					'id'   => $prefix . 'mashup_metabox',
					'desc' => __( 'Select which post types you would like to display the mashup metabox.', $this->plugin_slug ),
					'type' => 'multicheck_posttype',
				),
			),
		);

		return apply_filters( 'gmb_general_options_fields', self::$plugin_options );

	}

	/**
	 * Map Option Fields
	 * Defines the plugin option metabox and field configuration
	 * @since  1.0.0
	 * @return array
	 */
	public function map_option_fields() {

		// Only need to initiate the array once per page-load
		if ( ! empty( self::$plugin_options ) ) {
			return self::$plugin_options;
		}

		$prefix = 'gmb_';

		self::$plugin_options = array(
			'id'         => 'plugin_options',
			'show_on'    => array( 'key' => 'options-page', 'value' => array( self::$key, ), ),
			'show_names' => true,
			'fields'     => array(
				array(
					'name' => __( 'Google Maps API Key', $this->plugin_slug ),
					'desc' => sprintf( __( 'The Google Maps JavaScript API v3 does not require an API key to function correctly. However, Google strongly encourages you to load the Maps API using an APIs Console key which allows you to monitor your Maps API usage. %1$sLearn how to obtain an API key%2$s.', $this->plugin_slug ), '<a href="' . esc_url( 'https://developers.google.com/maps/documentation/javascript/tutorial#api_key' ) . '" target="_blank" class="new-window">', '</a>' ),
					'id'   => $prefix . 'maps_api_key',
					'type' => 'text',
				),
				array(
					'name'           => __( 'Map Size', $this->plugin_slug ),
					'id'             => $prefix . 'width_height',
					'type'           => 'width_height',
					'width_std'      => '100',
					'width_unit_std' => '%',
					'height_std'     => '600',
					'lat_std'        => '32.7153292',
					'lng_std'        => '-117.15725509',
					'desc'           => '',
				),
				array(
					'name'    => __( 'Map Location', $this->plugin_slug ),
					'id'      => $prefix . 'lat_lng',
					'type'    => 'lat_lng_default',
					'lat_std' => '32.7153292',
					'lng_std' => '-117.15725509',
					'desc'    => '',
				),
				array(
					'name'    => __( 'Signed-in Maps', $this->plugin_slug ),
					'id'      => $prefix . 'signed_in',
					'type'    => 'select',
					'options' => array(
						'disabled' => 'Disabled',
						'enabled'  => 'Enabled'
					),
					'desc'    => __( 'When you enable sign-in with Google Maps, the maps on your site will be tailored to your users. Users who are signed-in to their Google account will be able to save places for later viewing on the web or mobile devices. Places saved from the map will be attributed to your site. Notice: When sign-in is enabled, the default position and appearance of several controls will change.', $this->plugin_slug ),
				),
				array(
					'name'    => __( 'Map Language', $this->plugin_slug ),
					'id'      => $prefix . 'language',
					'type'    => 'select',
					'options' => gmb_get_map_languages(),
					'desc'    => __( 'The Google Maps API uses the user\'s browser preferred language setting when displaying textual information such as the names for controls, copyright notices, driving directions and labels on maps. In most cases, this is preferable; you usually do not wish to override the user\'s preferred language setting. However, if you wish to change the Maps API to ignore the browser\'s language setting and force it to display information in a particular language, you can configure that here.', $this->plugin_slug ),
				)
			),
		);

		return apply_filters( 'gmb_map_options_fields', self::$plugin_options );

	}


	/**
	 * License Fields
	 * Defines the plugin option metabox and field configuration
	 * @since  1.0.0
	 * @return array
	 */
	public function license_fields() {

		// Only need to initiate the array once per page-load
		if ( ! empty( self::$plugin_options ) ) {
			return self::$plugin_options;
		}

		$prefix = 'gmb_';

		self::$plugin_options = array(
			'id'         => 'plugin_options',
			'give_title' => __( 'Give Licenses', 'give' ),
			'show_on'    => array( 'key' => 'options-page', 'value' => array( self::$key, ), ),
			'fields'     => apply_filters( 'gmb_settings_licenses', array()
			)
		);

		return apply_filters( 'gmb_general_options_fields', self::$plugin_options );

	}


	/**
	 * CMB Lat Lng
	 *
	 * Custom CMB field for Gmap latitude and longitude
	 *
	 * @param $field
	 * @param $meta
	 */
	function cmb2_render_lat_lng_default( $field, $meta ) {

		$meta = wp_parse_args(
			$meta, array(
				'geolocate_map' => 'yes',
				'latitude'      => '',
				'longitude'     => '',
			)
		);

		//Geolocate
		$output = '<div id="geolocate-wrap" class="clear">';
		$output .= '<label class="geocode-label size-label">' . __( 'Geolocate Position', $this->plugin_slug ) . ':</label>';
		$output .= '<div class="geolocate-radio-wrap size-labels-wrap">';
		$output .= '<label class="yes-label label-left"><input id="geolocate_map_yes" type="radio" name="' . $field->args['id'] . '[geolocate_map]" class="geolocate_map_radio radio-left" value="yes" ' . ( $meta['geolocate_map'] === 'yes' ? 'checked="checked"' : '' ) . '>' . __( 'Yes', $this->plugin_slug ) . '</label>';

		$output .= '<label class="no-label label-left"><input id="geolocate_map_no" type="radio" name="' . $field->args['id'] . '[geolocate_map]" class="geolocate_map_radio radio-left" value="no" ' . ( $meta['geolocate_map'] === 'no' ? 'checked="checked"' : '' ) . ' >' . __( 'No', $this->plugin_slug ) . '</label>';
		$output .= '</div>';
		$output .= '</div>';

		//lat_lng
		$output .= '<div id="lat-lng-wrap"><div class="coordinates-wrap clear">';
		$output .= '<div class="lat-lng-wrap lat-wrap clear"><span>' . __( 'Latitude', $this->plugin_slug ) . ': </span>
						<input type="text" class="regular-text latitude" name="' . $field->args['id'] . '[latitude]" id="' . $field->args['id'] . '-latitude" value="' . ( $meta['latitude'] ? $meta['latitude'] : $field->args['lat_std'] ) . '" /></div><div class="lat-lng-wrap lng-wrap clear"><span>' . __( 'Longitude', $this->plugin_slug ) . ': </span>
								<input type="text" class="regular-text longitude" name="' . $field->args['id'] . '[longitude]" id="' . $field->args['id'] . '-longitude" value="' . ( $meta['longitude'] ? $meta['longitude'] : $field->args['lng_std'] ) . '" />
								</div>';
		$output .= '<p class="small-desc">' . sprintf( __( 'For quick lat/lng lookup use <a href="%s" class="new-window"  target="_blank">this service</a>', $this->plugin_slug ), esc_url( 'http://www.latlong.net/' ) ) . '</p>';
		$output .= '</div><!-- /.search-coordinates-wrap -->';
		$output .= '</div>'; //end #geolocate-wrap
		$output .= '<p class="cmb2-metabox-description">' . __( 'When creating a new map the plugin will use your current longitude and latitude for the base location. If you see a blank space instead of the map, this is most likely because you have denied permission for location sharing. You may also specify a default longitude and latitude by turning off this option.', $this->plugin_slug ) . '</p>';


		echo $output;


	}


	/**
	 * Make public the protected $key variable.
	 * @since  0.1.0
	 * @return string  Option key
	 */
	public static function key() {
		return self::$key;
	}


	/**
	 * Add links to Plugin listings view
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	function add_plugin_page_links( $links, $file ) {

		if ( $file == GMB_PLUGIN_BASE ) {

			// Add Widget Page link to our plugin
			$settings_link = '<a href="edit.php?post_type=google_maps&page=' . self::$key . '" title="' . __( 'Visit the Google Maps Builder plugin settings page', $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>';

			array_unshift( $links, $settings_link );

		}

		return $links;
	}

	function add_plugin_meta_links( $meta, $file ) {

		if ( $file == GMB_PLUGIN_BASE ) {
			$meta[] = "<a href='http://wordpress.org/support/view/plugin-reviews/google-maps-builder' target='_blank' title='" . __( 'Rate Google Maps Builder on WordPress.org', $this->plugin_slug ) . "'>" . __( 'Rate Plugin', $this->plugin_slug ) . "</a>";
			$meta[] = '<a href="http://wordpress.org/support/plugin/google-maps-builder/" target="_blank" title="' . __( 'Get plugin support via the WordPress community', $this->plugin_slug ) . '">' . __( 'Support', $this->plugin_slug ) . '</a>';
			$meta[] = __( 'Thank You for using Google Maps Builder', $this->plugin_slug );
		}

		return $meta;
	}

	/**
	 * License Key Callback
	 *
	 * @description Registers the license field callback for EDD's Software Licensing
	 * @since       1.0
	 *
	 * @param array $field_object , $escaped_value, $object_id, $object_type, $field_type_object Arguments passed by CMB2
	 *
	 * @return void
	 */
	function gmb_license_key_callback( $field_object, $escaped_value, $object_id, $object_type, $field_type_object ) {

		$id                = $field_type_object->field->args['id'];
		$field_description = $field_type_object->field->args['desc'];
		$license_status    = get_option( $field_type_object->field->args['options']['is_valid_license_option'] );
		$field_classes     = 'regular-text gmb-license-field';

		if ( $license_status === 'valid' ) {
			$field_classes .= ' gmb-license-active';
		}

		$html = $field_type_object->input(
			array(
				'class' => $field_classes,
				'type'  => 'text'
			) );

		if ( $license_status === 'valid' ) {
			$html .= '<input type="submit" class="button-secondary gmb-license-deactivate" name="' . $id . '_deactivate" value="' . __( 'Deactivate License', 'give' ) . '"/>';
		}

		$html .= '<label for="give_settings[' . $id . ']"> ' . $field_description . '</label>';

		wp_nonce_field( $id . '-nonce', $id . '-nonce' );

		echo $html;
	}

	/**
	 * Modify CMB2 Default Form Output
	 *
	 * @param string @args
	 *
	 * @since 2.0
	 *
	 * @param $form_format
	 * @param $object_id
	 * @param $cmb
	 *
	 * @return string
	 */
	function gmb_modify_cmb2_form_output( $form_format, $object_id, $cmb ) {

		//only modify the give settings form
		if ( 'gmb_settings' == $object_id && 'plugin_options' == $cmb->cmb_id ) {

			return '<form class="cmb-form" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%2$s">%3$s<div class="gmb-submit-wrap"><input type="submit" name="submit-cmb" value="' . __( 'Save Settings', 'give' ) . '" class="button-primary"></div></form>';
		}

		return $form_format;

	}


}

/**
 * Wrapper function around cmb_get_option
 * @since  0.1.0
 *
 * @param  string $key Options array key
 *
 * @return mixed        Option value
 */
function gmb_get_option( $key = '' ) {
	return cmb2_get_option( Google_Maps_Builder_Settings::key(), $key );
}
