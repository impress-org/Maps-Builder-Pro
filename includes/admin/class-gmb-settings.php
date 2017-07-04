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

		//Pro Markers
		add_action( 'gmb_extra_marker_options', array( $this, 'pro_markers_options' ) );
		add_action( 'gmb_maps_icons_markers_list_after', array( $this, 'pro_markers_default' ) );
		add_action( 'gmb_markers_before_save', array( $this, 'pro_markers_maps_icons' ) );
		add_action( 'gmb_markers_before_save', array( $this, 'pro_markers_templatic' ) );
		add_action( 'gmb_markers_before_save', array( $this, 'pro_markers_uploader' ) );

	}

	/**
	 * Core Licensing
	 */
	public function gmb_core_licensing() {
		if ( class_exists( 'GMB_License' ) ) {
			new GMB_License( GMB_PLUGIN_BASE, 'Maps Builder Pro', GMB_VERSION, 'WordImpress', 'maps_builder_license_key' );
		}
	}


	/**
	 * License Fields
	 *
	 * $current_user->IDDefines the plugin option metabox and field configuration
	 * @return array
	 */
	public function license_fields() {

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
	 * $current_user->IDAdds links to the plugin listing page in wp-admin
	 *
	 * @param $meta
	 * @param $file
	 *
	 * @return array
	 */
	function add_plugin_meta_links( $meta, $file ) {

		if ( $file == GMB_PLUGIN_BASE ) {
			$meta[] = "<a href='http://wordpress.org/support/view/plugin-reviews/google-maps-builder' target='_blank' title='" . __( 'Rate Google Maps Builder on WordPress.org', 'google-maps-builder' ) . "'>" . __( 'Rate Plugin', 'google-maps-builder' ) . "</a>";
			$meta[] = '<a href="https://wordimpress.com/support/" target="_blank" title="' . __( 'Have an active license? Get priority support from WordImpress.', 'google-maps-builder' ) . '">' . __( 'Support', 'google-maps-builder' ) . '</a>';
			$meta[] = "<a href='https://wordimpress.com/documentation/maps-builder-pro/' target='_blank' title='" . __( 'View the plugin documentation', 'google-maps-builder' ) . "'>" . __( 'Documentation', 'google-maps-builder' ) . "</a>";

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
	 * $current_user->IDDefines the plugin option metabox and field configuration
	 * @since  1.0.0
	 * @return array
	 */
	public function map_option_fields() {
		$this->plugin_options             = parent::map_option_fields();
		$prefix                           = $this->prefix();
		$this->plugin_options['fields'][] = array(
			'name'    => __( 'Map Language', 'google-maps-builder' ),
			'id'      => $prefix . 'language',
			'type'    => 'select',
			'options' => gmb_get_map_languages(),
			'desc'    => __( 'The Google Maps API uses the user\'s browser preferred language setting when displaying textual information such as the names for controls, copyright notices, driving directions and labels on maps. In most cases, this is preferable; you usually do not wish to override the user\'s preferred language setting. However, if you wish to change the Maps API to ignore the browser\'s language setting and force it to display information in a particular language, you can configure that here.', 'google-maps-builder' ),
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
			'name' => __( 'Mashup Metabox', 'google-maps-builder' ),
			'id'   => $prefix . 'mashup_metabox',
			'desc' => __( 'Select which post types you would like to display the mashup metabox.', 'google-maps-builder' ),
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
	 * @uses "gmb_markers_before_save" action
	 */
	public function pro_markers_default() {
		gmb_include_view( 'admin/views/pro-markers-default-icons.php', false, $this->view_data() );
	}

	/**
	 * Add Additional Pro-only Maps Icons Markers
	 *
	 * $current_user->IDAdds additional icons to the ul list in the admin markers modal
	 *
	 * @uses "gmb_maps_icons_markers_list_after" action
	 */
	public function pro_markers_maps_icons() {
		gmb_include_view( 'admin/views/pro-markers-maps-icons.php', false, $this->view_data() );
	}

	/**
	 * Adds Templatic Pro Markers
	 *
	 * @uses "gmb_markers_before_save" action
	 */
	public function pro_markers_templatic() {
		gmb_include_view( 'admin/views/pro-markers-templatic.php', false, $this->view_data() );
	}


	/**
	 * Adds the Uploader HTML element
	 *
	 * @uses "gmb_markers_before_save" action
	 */
	public function pro_markers_uploader() {
		gmb_include_view( 'admin/views/pro-markers-uploader.php', false, $this->view_data() );
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
		$out = __( 'Thank you for upgrading to Maps Builder Pro.', 'google-maps-builder' ) . ' ';
		$out .= sprintf( __( 'As a Pro active license holder you receive <a href="%1$s" target="_blank">priority support</a>, awesome plugin features, and thoroughly written plugin <a href="%2$s" target="_blank">documentation</a>. We hope you enjoy using the Pro plugin version!', 'google-maps-builder' ), 'https://wordimpress.com/documentation/maps-builder-pro/', 'https://wordimpress.com/support/' );

		return $out;

	}
}
