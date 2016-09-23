<?php
/**
 *  CMB2 Custom Select field for CPTs.
 *
 * @author     : WordImpress
 * @copyright  : http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since      : 2.0
 */

/**
 * Render CPT Post Type.
 *
 * @param $field
 * @param $escaped_value
 * @param $object_id
 * @param $object_type
 * @param $field_type_object CMB2_Types
 */
function gmb_cmb_render_select_post_type( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {

	if ( version_compare( CMB2_VERSION, '2.2.2', '>=' ) ) {
		$field_type_object->type = new CMB2_Type_Select( $field_type_object );
	}

	$cpts    = get_post_types();
	$options = '';

	//Remove known plugin CPTs.
	unset( $cpts['acf'] );
	unset( $cpts['apto_sort'] );
	unset( $cpts['fl-builder-template'] );
	unset( $cpts['google_maps'] );
	unset( $cpts['nav_menu_item'] );
	unset( $cpts['revision'] );
	unset( $cpts['edd_log'] );
	unset( $cpts['edd_discount'] );
	unset( $cpts['deprecated_log'] );

	if ( $cpts ) {
		foreach ( $cpts as $cpt ) {

			$cpt_object = get_post_type_object( $cpt );

			$options .= $field_type_object->select_option( array(
				'label'   => $cpt_object->labels->name . ' (' . $cpt . ')',
				'value'   => $cpt,
				'checked' => $escaped_value == $cpt,
			) );
		}
	}

	echo $field_type_object->select( array( 'options' => $options ) );

}

add_action( 'cmb2_render_select_post_type', 'gmb_cmb_render_select_post_type', 10, 5 );