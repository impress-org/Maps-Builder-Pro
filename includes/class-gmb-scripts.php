<?php
/**
 * Scripts
 *
 * @package     GMB
 * @subpackage  Functions
 * @copyright   Copyright (c) 2015, WordImpress
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Google_Maps_Builder_Scripts
 */
class Google_Maps_Builder_Scripts extends Google_Maps_Builder_Core_Scripts_Init {

	/**
	 * Enqueue admin scripts that need to run late.
	 *
	 * @since 2.1.0
	 *
	 * @uses "admin_enqueue_scripts
	 *
	 * @param $hook
	 */
	public function admin_late( $hook ) {
		global $post;
		$js_dir = GMB_PLUGIN_URL . 'assets/js/admin/';
		$suffix = $this->paths->suffix();
		if ( ( $hook == 'post-new.php' || $hook == 'post.php' ) && 'google_maps' === $post->post_type ) {
			//Pro only.
			wp_register_script( 'google-maps-builder-admin-pro', $js_dir . 'admin-pro' . $suffix . '.js', array( 'jquery' ), GMB_VERSION );
			wp_enqueue_script( 'google-maps-builder-admin-pro' );
		}
	}

	/**
	 * Load additional admin scripts.
	 *
	 * @since 2.1.0
	 *
	 * @uses "admin_enqueue_scripts"
	 *
	 * @param $hook
	 */
	public function admin_hooks( $hook ) {
		global $post;
		$js_dir     = GMB_PLUGIN_URL . 'assets/js/admin/';
		$js_plugins = GMB_PLUGIN_URL . 'assets/js/plugins/';
		$suffix     = $this->paths->suffix();

		if ( ( $hook == 'post-new.php' || $hook == 'post.php' ) && 'google_maps' === $post->post_type ) {
			//Directions
			wp_register_script( 'google-maps-builder-admin-map-directions', $js_dir . 'admin-maps-directions' . $suffix . '.js', array( 'jquery' ), GMB_VERSION );
			wp_enqueue_script( 'google-maps-builder-admin-map-directions' );

			//mashups
			wp_register_script( 'google-maps-builder-admin-maps-mashups', $js_dir . 'admin-maps-mashups' . $suffix . '.js', array( 'jquery' ), GMB_VERSION );
			wp_enqueue_script( 'google-maps-builder-admin-maps-mashups' );

			//Marker Clustering
			wp_register_script( 'google-maps-builder-admin-map-marker-clustering', $js_plugins . 'markerclusterer' . $suffix . '.js', array( 'jquery' ), GMB_VERSION );
			wp_enqueue_script( 'google-maps-builder-admin-map-marker-clustering' );
		}

		//Import/Export Scripts
		if ( $hook == 'google_maps_page_gmb_import_export' ) {
			wp_register_script( 'google-maps-builder-admin-import-export', $js_dir . 'admin-import-export' . $suffix . '.js', array( 'jquery' ), GMB_VERSION );
			wp_enqueue_script( 'google-maps-builder-admin-import-export' );


		}

	}

	/**
	 * Load additional front-end scripts
	 *
	 * @since 2.1.0
	 *
	 * @uses "enqueue_scripts"
	 *
	 */
	public function front_end_hooks() {
		$js_plugins = GMB_PLUGIN_URL . 'assets/js/plugins/';
		$suffix     = $this->paths->suffix();

		wp_register_script( 'google-maps-builder-clusterer', $js_plugins . 'markerclusterer' . $suffix . '.js', array( 'jquery' ), GMB_VERSION, true );
		wp_enqueue_script( 'google-maps-builder-clusterer' );
	}

	/**
	 * Enqueue front-end scripts that need to run late
	 *
	 * @since 2.1.0
	 *
	 * @uses "wp_enqueue_scripts
	 *
	 * @param $hook
	 */
	public function front_end_late( $hook ) {
		$js_dir = GMB_PLUGIN_URL . 'assets/js/frontend/';
		$suffix = $this->paths->suffix();
		wp_register_script( 'google-maps-builder-plugin-script-pro', $js_dir . 'google-maps-builder' . $suffix . '.js', array( 'jquery' ), GMB_VERSION, true );
		wp_enqueue_script( 'google-maps-builder-plugin-script-pro' );

	}


}//end class



