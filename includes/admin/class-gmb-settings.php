<?php

/**
 * CMB Theme Options
 * @version 0.1.0
 */
class Google_Maps_Builder_Settings extends Google_Maps_Builder_Core_Settings {



	/**
	 * Constructor
	 * @since 0.1.0
	 */
	public function __construct() {
		parent::__construct();

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
	 * Admin page markup. Mostly handled by CMB
	 * @since  0.1.0
	 */
	public function admin_page_display() {

		include( 'views/settings-page.php' );

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

	function add_plugin_meta_links( $meta, $file ) {

		if ( $file == GMB_PLUGIN_BASE ) {
			$meta[] = parent::add_plugin_meta_links( $meta, $file );
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

	/**
	 * Map Option Fields
	 * Defines the plugin option metabox and field configuration
	 * @since  1.0.0
	 * @return array
	 */
	public function map_option_fields() {
		self::$plugin_options = parent::map_option_fields();
		$prefix = $this->prefix();
		self::$plugin_options[ 'fields' ][] = array(
			'name'    => __( 'Map Language', $this->plugin_slug ),
			'id'      => $prefix . 'language',
			'type'    => 'select',
			'options' => gmb_get_map_languages(),
			'desc'    => __( 'The Google Maps API uses the user\'s browser preferred language setting when displaying textual information such as the names for controls, copyright notices, driving directions and labels on maps. In most cases, this is preferable; you usually do not wish to override the user\'s preferred language setting. However, if you wish to change the Maps API to ignore the browser\'s language setting and force it to display information in a particular language, you can configure that here.', $this->plugin_slug ),
		);
		self::$plugin_options[ 'fields' ] = array(
			'name'    => __( 'Signed-in Maps', $this->plugin_slug ),
			'id'      => $prefix . 'signed_in',
			'type'    => 'select',
			'options' => array(
				'disabled' => 'Disabled',
				'enabled'  => 'Enabled'
			),
			'desc'    => __( 'When you enable sign-in with Google Maps, the maps on your site will be tailored to your users. Users who are signed-in to their Google account will be able to save places for later viewing on the web or mobile devices. Places saved from the map will be attributed to your site. Notice: When sign-in is enabled, the default position and appearance of several controls will change.', $this->plugin_slug ),
		);

		return apply_filters( 'gmb_map_options_fields', self::$plugin_options );
	}

	/**
	 * General Option Fields
	 * Defines the plugin option metabox and field configuration
	 * @since  1.0.0
	 * @return array
	 */
	public function general_option_fields() {
		if ( empty( self::$plugin_options ) ) {
			self::$plugin_options = parent::general_option_fields();
		}

		$prefix = $this->prefix();

		self::$plugin_options[ 'fields' ][] = array(
			'name' => __( 'Mashup Metabox', $this->plugin_slug ),
			'id'   => $prefix . 'mashup_metabox',
			'desc' => __( 'Select which post types you would like to display the mashup metabox.', $this->plugin_slug ),
			'type' => 'multicheck_posttype',
		);

		return apply_filters( 'gmb_general_options_fields', self::$plugin_options );

	}

}
