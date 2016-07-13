<?php

/**
 * Class Google_Maps_Builder_Settings
 */
class Google_Maps_Builder_Settings extends Google_Maps_Builder_Core_Settings {

	/**
	 * Google_Maps_Builder_Settings constructor.
	 */
	public function __construct() {

		parent::__construct();

		//Custom CMB2 Settings Fields
		add_action( 'cmb2_render_license_key', array( $this, 'gmb_license_key_callback' ), 10, 5 );
		add_action( 'cmb2_render_lat_lng_default', array( $this, 'cmb2_render_lat_lng_default' ), 10, 2 );
		add_action( 'admin_init', array( $this, 'gmb_core_licensing' ), 1 );
		add_filter( 'cmb2_get_metabox_form_format', array( $this, 'gmb_modify_cmb2_form_output' ), 10, 3 );

		//PRO only markup
		add_action( 'gmb_extra_marker_options', array( $this, 'pro_markers_options' ) );
		add_action( 'gmb_extra_markers', array( $this, 'pro_markers' ) );
		add_action( 'gmb_markers_before_save', array( $this, 'before_save' ) );

	}

	/**
	 * Core Licensing
	 */
	public function gmb_core_licensing() {
		if ( class_exists( 'GMB_License' ) && is_admin() ) {
			new GMB_License( GMB_PLUGIN_BASE, 'Maps Builder Pro', GMB_VERSION, 'WordImpress', 'maps_builder_license_key' );
		}
	}


	/**
	 * License Fields
	 *
	 * @description: Defines the plugin option metabox and field configuration
	 * @return array
	 */
	public function license_fields() {

		$prefix = $this->prefix();

		$this->plugin_options = array(
			'id'         => 'plugin_options',
			'show_on'    => array( 'key' => 'options-page', 'value' => array( self::$key, ), ),
			'show_names' => true,
			'fields'     => apply_filters( 'gmb_settings_licenses', array()
			)
		);

		return apply_filters( 'gmb_license_fields', $this->plugin_options );

	}

	/**
	 * Add Plugin Meta Links
	 *
	 * @description: Adds links to the plugin listing page in wp-admin
	 *
	 * @param $meta
	 * @param $file
	 *
	 * @return array
	 */
	function add_plugin_meta_links( $meta, $file ) {

		if ( $file == GMB_PLUGIN_BASE ) {
			$meta[] = "<a href='http://wordpress.org/support/view/plugin-reviews/google-maps-builder' target='_blank' title='" . __( 'Rate Google Maps Builder on WordPress.org', $this->plugin_slug ) . "'>" . __( 'Rate Plugin', $this->plugin_slug ) . "</a>";
			$meta[] = '<a href="https://wordimpress.com/support/" target="_blank" title="' . __( 'Have an active license? Get priority support from WordImpress.', $this->plugin_slug ) . '">' . __( 'Support', $this->plugin_slug ) . '</a>';
			$meta[] = "<a href='https://wordimpress.com/documentation/maps-builder-pro/' target='_blank' title='" . __( 'View the plugin documentation', $this->plugin_slug ) . "'>" . __( 'Documentation', $this->plugin_slug ) . "</a>";

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
		$license_status    = isset( $field_type_object->field->args['options']['is_valid_license_option'] ) ? get_option( $field_type_object->field->args['options']['is_valid_license_option'] ) : '';
		$field_classes     = 'regular-text gmb-license-field';

		if ( $license_status === 'valid' ) {
			$field_classes .= ' gmb-license-active';
		}

		$html = $field_type_object->input(
			array(
				'class' => $field_classes,
				'type'  => $license_status == 'valid' ? 'password' : 'text'
			)
		);

		//Valid License
		if ( $license_status == 'valid' ) {
			$html .= '<input type="submit" class="button-secondary gmb-license-deactivate" name="' . $id . '_deactivate" value="' . __( 'Deactivate License', 'give' ) . '"/>';
		} else {
			//This license is not valid so delete it
			gmb_delete_option( $id );
		}

		$html .= '<label for="give_settings[' . $id . ']"> ' . $field_description . '</label>';

		wp_nonce_field( $id . '-nonce', $id . '-nonce' );

		echo $html;

	}

	/**
	 * Map Option Fields
	 *
	 * @description: Defines the plugin option metabox and field configuration
	 * @since  1.0.0
	 * @return array
	 */
	public function map_option_fields() {
		$this->plugin_options             = parent::map_option_fields();
		$prefix                           = $this->prefix();
		$this->plugin_options['fields'][] = array(
			'name'    => __( 'Map Language', $this->plugin_slug ),
			'id'      => $prefix . 'language',
			'type'    => 'select',
			'options' => gmb_get_map_languages(),
			'desc'    => __( 'The Google Maps API uses the user\'s browser preferred language setting when displaying textual information such as the names for controls, copyright notices, driving directions and labels on maps. In most cases, this is preferable; you usually do not wish to override the user\'s preferred language setting. However, if you wish to change the Maps API to ignore the browser\'s language setting and force it to display information in a particular language, you can configure that here.', $this->plugin_slug ),
		);
		$this->plugin_options['fields'][] = array(
			'name'    => __( 'Signed-in Maps', $this->plugin_slug ),
			'id'      => $prefix . 'signed_in',
			'type'    => 'select',
			'options' => array(
				'disabled' => 'Disabled',
				'enabled'  => 'Enabled'
			),
			'desc'    => __( 'When you enable sign-in with Google Maps, the maps on your site will be tailored to your users. Users who are signed-in to their Google account will be able to save places for later viewing on the web or mobile devices. Places saved from the map will be attributed to your site. Notice: When sign-in is enabled, the default position and appearance of several controls will change.', $this->plugin_slug ),
		);

		return apply_filters( 'gmb_map_options_fields', $this->plugin_options );
	}

	/**
	 * General Option Fields
	 *
	 * Defines the plugin option metabox and field configuration
	 * @since  1.0.0
	 * @return array
	 */
	public function general_option_fields() {

		$this->plugin_options = parent::general_option_fields();

		$prefix = $this->prefix();

		$this->plugin_options['fields'][] = array(
			'name' => __( 'Mashup Metabox', $this->plugin_slug ),
			'id'   => $prefix . 'mashup_metabox',
			'desc' => __( 'Select which post types you would like to display the mashup metabox.', $this->plugin_slug ),
			'type' => 'multicheck_posttype',
		);

		return apply_filters( 'gmb_general_options_fields', $this->plugin_options );

	}

	/**
	 * Add pro-only markers in markers partial
	 *
	 * @uses "gmb_extra_markers" action
	 */
	public function pro_markers_options() {
		gmb_include_view( 'admin/views/pro-markers-options.php', false, $this->view_data() );
	}

	/**
	 * Add pro-only markers in markers partial
	 *
	 * @uses "gmb_extra_markers" action
	 */
	public function pro_markers() {
		gmb_include_view( 'admin/views/pro-markers.php', false, $this->view_data() );
	}

	/**
	 * Add additional markup before save in markers partial
	 *
	 * @uses "gmb_markers_before_save" action
	 */
	public function before_save() {
		gmb_include_view( 'admin/views/markers-before-save.php', false, $this->view_data() );
	}

	/**
	 * Markup for settings tab switcher
	 *
	 * @param $active_tab
	 *
	 * @uses "gmb_settings_tabs" action
	 */
	public function settings_tabs( $active_tab ) {
		parent::settings_tabs( $active_tab );

		gmb_include_view( 'admin/views/pro-settings-tabs.php', false, $this->view_data( $this->tab_settings( $active_tab ), true ) );
	}

	/**
	 * Handle main data for the settings page
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */
	protected function settings_page_data() {

		$data = array(
			'welcome'        => sprintf( '%1s Maps Builder <em>Pro</em> %s', __( 'Welcome To', 'maps-builder-pro' ), Google_Maps_Builder()->meta['Version'] ),
			'sub_heading'    => $this->sub_heading(),
			'license_fields' => $this->license_fields(),
		);

		return $this->view_data( $data, true );
	}

	/**
	 * Sub heading markup for settings page
	 *
	 * @since 2.1.0
	 *
	 * @return string
	 */
	protected function sub_heading() {
		$out = __( 'Thank you for upgrading to the Maps Builder Pro', 'google-maps-pro' );
		$out .= sprintf( __( 'As a Pro active license holder you receive %3$spriority support%2$s, awesome plugin features, and thoroughly written plugin %1$sdocumentation%2$s. We hope you enjoy using the Pro plugin version!', 'google-maps-pro' ), '<a href="https://wordimpress.com/documentation/maps-builder-pro/" target="_blank">', '</a>', '<a href="https://wordimpress.com/support/forum/maps-builder-pro" target="_blank">' );

		return $out;

	}
}
