<?php

/**
 * Google Maps Mashups
 *
 * Adds mashup functionality to Maps Builder Pro
 *
 * @package   Google_Maps_Builder_Admin
 * @author    WordImpress
 * @license   GPL-2.0+
 * @link      http://wordimpress.com
 * @copyright 2015 WordImpress
 */
class Google_Maps_Builder_Mashups_Builder {

	/**
	 * Go
	 * @since     2.0
	 */
	public function __construct() {

		$this->plugin_slug = Google_Maps_Builder()->get_plugin_slug();

		//Add metaboxes and fields to CPT
		add_action( 'cmb2_init', array( $this, 'mashup_builder_fields' ) );

		add_action( 'cmb2_render_select_taxonomies', array( $this, 'gmb_cmb_render_select_taxonomies' ), 10, 5 );
		add_action( 'cmb2_render_select_terms', array( $this, 'gmb_cmb_render_select_terms' ), 10, 5 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_mashup_builder_scripts' ) );
		add_action( 'wp_ajax_get_post_types_taxonomies', array( $this, 'get_post_types_taxonomies_callback' ) );
		add_action( 'wp_ajax_get_taxonomy_terms', array( $this, 'get_taxonomy_terms_callback' ) );
	}


	/**
	 * Enqueue Mashup Builder Scripts
	 *
	 * @param $hook
	 */
	public function enqueue_mashup_builder_scripts( $hook ) {
		global $post;
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		//Only enqueue scripts for CPT on post type screen
		if ( ( $hook == 'post-new.php' || $hook == 'post.php' ) && 'google_maps' === $post->post_type ) {
			wp_register_script( $this->plugin_slug . '-admin-mashups', GMB_PLUGIN_URL . 'assets/js/admin/admin-maps-mashups' . $suffix . '.js', array( 'jquery' ) );
			wp_enqueue_script( $this->plugin_slug . '-admin-mashups' );

			//			$ajax_array = array( 'ajax_url' => admin_url( 'admin-ajax.php' ) );
			//			wp_localize_script( $this->plugin_slug . '-admin-mashups', 'gmb_mashup', $ajax_array );

		}

	}


	/**
	 * Mashup Metabox Field
	 *
	 * Defines the Google Places CPT metabox and field configuration
	 *
	 * @since  2.0
	 * @return array
	 */
	public function mashup_builder_fields() {

		$prefix = 'gmb_';

		$mashup_metabox = cmb2_get_metabox( array(
			'id'           => 'google_maps_mashup_builder',
			'title'        => __( 'Map Markers from Posts (Mashups)', $this->plugin_slug ),
			'object_types' => array( 'google_maps' ), // post type
			'context'      => 'normal', //  'normal', 'advanced', or 'side'
			'priority'     => 'default', //  'high', 'core', 'default' or 'low'
			'show_names'   => true, // Show field names on the left
		) );
		$group_field_id = $mashup_metabox->add_field( array(
			'name'        => __( 'Mashup Group', $this->plugin_slug ),
			'id'          => $prefix . 'mashup_group',
			'type'        => 'group',
			'description' => __( 'Select the criteria for loading markers for this mashup group.', $this->plugin_slug ),
			'options'     => array(
				'group_title'   => __( 'Mashup: {#}', 'cmb' ),
				'add_button'    => __( 'Add Another Mashup', $this->plugin_slug ),
				'remove_button' => __( 'Remove Mashup', $this->plugin_slug ),
				'sortable'      => true, // beta
			),
		) );
		$mashup_metabox->add_group_field( $group_field_id, array(
			'name'        => __( 'Post Type', $this->plugin_slug ),
			'id'          => 'post_type',
			'description' => __( 'Select the post type containing your marker information.', $this->plugin_slug ),
			'row_classes' => 'gmb-mashup-post-type-field',
			'type'        => 'select_posttype',
		) );
		$mashup_metabox->add_group_field( $group_field_id, array(
			'name'        => __( 'Taxonomy Filter', $this->plugin_slug ),
			'id'          => 'taxonomy',
			'row_classes' => 'gmb-taxonomy-select-field',
			'description' => __( 'Select the taxonomies (if any) that you would like to filter by.', $this->plugin_slug ),
			'type'        => 'select_taxonomies',
		) );
		$mashup_metabox->add_group_field( $group_field_id, array(
			'name'        => __( 'Taxonomy Terms', $this->plugin_slug ),
			'id'          => 'terms',
			'row_classes' => 'gmb-terms-multicheck-field',
			'description' => __( 'Select the taxonomies (if any) that you would like to filter by.', $this->plugin_slug ),
			'type'        => 'select_terms',
		) );


	}


	/**
	 * Mashups Select Taxonomies
	 *
	 * @param $field
	 * @param $value
	 * @param $object_id
	 * @param $object_type
	 * @param $field_type_object
	 */
	public function gmb_cmb_render_select_taxonomies( $field, $value, $object_id, $object_type, $field_type_object ) {

		$group_data_array = maybe_unserialize( get_post_meta( $object_id, 'gmb_mashup_group', true ) );
		$post_type        = isset( $group_data_array[ $field->group->index ]['post_type'] ) ? $group_data_array[ $field->group->index ]['post_type'] : 'post';

		//First check to see if mashups post type field has been set
		if ( ! empty( $post_type ) ) {

			//Get taxonomies for CPT
			$taxonomies = get_object_taxonomies( $post_type, 'objects' );
			$options    = '';

			//Do we have taxonomies?
			if ( $taxonomies ) {
				foreach ( $taxonomies as $taxonomy ) {
					$options .= $field_type_object->select_option( array(
						'label'   => $taxonomy->labels->name,
						'value'   => $taxonomy->name,
						'checked' => $value == $taxonomy->name,
					) );
				}
			} else {
				$options .= $field_type_object->select_option( array(
					'label' => __( 'No taxonomies found', $this->plugin_slug ),
					'value' => 'none'
				) );
			}
			//Output taxonomies select
			echo $field_type_object->select( array(
				'options'      => $options,
				'autocomplete' => 'off'
			) );

		}


	}

	/**
	 * Mashups Select Terms
	 *
	 * @param $field
	 * @param $value
	 * @param $object_id
	 * @param $object_type
	 * @param $field_type_object
	 */
	public function gmb_cmb_render_select_terms( $field, $value, $object_id, $object_type, $field_type_object ) {

		$group_data_array = maybe_unserialize( get_post_meta( $object_id, 'gmb_mashup_group', true ) );
		$post_type        = $group_data_array[ $field->group->index ]['post_type'];
		$taxonomy         = $group_data_array[ $field->group->index ]['taxonomy'];
		$output           = '';

		//Get Terms
		$args['taxonomy'] = isset( $taxonomy ) ? $taxonomy : '';
		$args             = wp_parse_args( $args, array( 'taxonomy' => 'category' ) );
		$taxonomy         = $args['taxonomy'];

		$terms = (array) get_terms( $taxonomy, $args );


		//First check to see if mashups post type field has been set
		if ( ! empty( $post_type ) && ! empty( $terms ) ) {

			$output .= '<ul class="cmb2-checkbox-list cmb2-list">';

			$output .= $this->gmb_get_terms_checklist( $terms, $field->group->index, $value );

			$output .= '</ul><p class="cmb2-metabox-description">' . __( 'Select the taxonomies (if any) that you would like to filter by.', $this->plugin_slug ) . '</p>';


		} else {
			$output = '<p class="no-terms">' . __( 'No terms found for this taxonomy', $this->plugin_slug ) . '</p>';
		}


		echo $output;


	}


	/**
	 * AJAX Taxonomies Callback
	 *
	 * @description Used to query taxonomies and taxonomy terms
	 *
	 * @since       2.0
	 */
	function get_post_types_taxonomies_callback() {

		//Set Vars
		$post_type           = isset( $_POST['post_type'] ) ? $_POST['post_type'] : '';
		$repeater_index      = isset( $_POST['index'] ) ? $_POST['index'] : '';
		$taxonomies          = get_object_taxonomies( $post_type, 'objects' );
		$i                   = 0;
		$tax_terms           = '';
		$response            = '';
		$response['options'] = '';

		//Do we have taxonomies?
		if ( $taxonomies ) {

			//Create taxonomy options
			foreach ( $taxonomies as $taxonomy ) {
				//Set term query var on last loop through
				if ( $i == 0 ) {
					$tax_terms = $taxonomy->name;
				}
				$response['options'] .= '<option value="' . $taxonomy->name . '">' . $taxonomy->labels->name . '</option>';
				$i ++;
			}
			$response['status'] = 'taxonomies found';

			//Get terms multicheck list for taxonomy and send to JS
			$args['taxonomy']            = isset( $tax_terms ) ? $tax_terms : '';
			$args                        = wp_parse_args( $args, array( 'taxonomy' => 'category' ) );
			$taxonomy                    = $args['taxonomy'];
			$terms                       = (array) get_terms( $taxonomy, $args );
			$response['terms_checklist'] = $this->gmb_get_terms_checklist( $terms, $repeater_index, '' );


		} else {

			$response['options'] = '<option>' . __( 'No taxonomies found', $this->plugin_slug ) . '</option>';
			$response['status']  = 'none';

		}

		echo json_encode( $response );

		wp_die();

	}


	/**
	 * AJAX Taxonomies Callback
	 */
	function get_taxonomy_terms_callback() {

		//Set Vars
		$repeater_index = isset( $_POST['index'] ) ? $_POST['index'] : '';
		$taxonomy       = isset( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : '';
		$response       = '';
		$terms          = (array) get_terms( $taxonomy );

		//Do we have taxonomies?
		if ( $terms ) {

			//Get terms multicheck list for taxonomy and send to JS
			$response['terms_checklist'] = $this->gmb_get_terms_checklist( $terms, $repeater_index, '' );

		} else {

			$response['terms_checklist'] = '<li>' . __( 'No terms found for this taxonomy', $this->plugin_slug ) . '</li>';
			$response['status']          = 'none';

		}

		echo json_encode( $response );

		wp_die();

	}


	/**
	 * Get Terms Checklist
	 *
	 *
	 * @param $terms array - a list of terms to loop through
	 * @param $index int - the index of this repeater group
	 * @param $value string - default value to match up if any
	 *
	 * @return string
	 */
	public function gmb_get_terms_checklist( $terms, $index, $value ) {
		$output = '';
		$i      = 0;

		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {

				$output .= '<li>
							<input type="checkbox" class="cmb2-option" name="gmb_mashup_group[' . $index . '][terms][]" id="gmb_mashup_group_' . $index . '_terms' . $i . '" value="' . $term->term_id . '" ' . ( is_array( $value ) && in_array( $term->term_id, $value ) ? 'checked="checked"' : '' ) . '>
							<label for="gmb_mashup_group_' . $index . '_terms' . $i . '">' . $term->name . '</label>
							</li>';

				$i ++;
			}
		} //end foreach

		return $output;

	}

} //end class

new Google_Maps_Builder_Mashups_Builder();