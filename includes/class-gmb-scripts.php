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


class Google_Maps_Builder_Scripts  {

	/**
	 * Plugin slug
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	protected $plugin_slug;

	/**
	 * Load scripts by context
	 *
	 * @since 2.0.0
	 */
	public function __construct(){
		if( is_admin() ){
			$obj = new Google_Maps_Builder_Core_Admin_Scripts();
			//@todo MAKE PRO ONLY
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_hooks' ) );
		}else{
			$obj = new Google_Maps_Builder_Core_Front_End_Scripts();
		}
		$this->plugin_slug = $obj->get_plugin_slug();

	}

	/**
	 * Load additional admin scripts
	 *
	 * @todo MAKE PRO ONLY
	 *
	 * @uses "admin_enqueue_scripts"
	 *
	 * @param $hook
	 */
	public function admin_hooks( $hook ){
		global $post;
		$js_dir = GMB_PLUGIN_URL . 'assets/js/admin/';
		$js_plugins = GMB_PLUGIN_URL . 'assets/js/plugins/';
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		//Directions
		if ( ( $hook == 'post-new.php' || $hook == 'post.php' ) && 'google_maps' === $post->post_type ) {
			wp_register_script( $this->plugin_slug . '-admin-map-directions', $js_dir . 'admin-maps-directions' . $suffix . '.js', array( 'jquery' ), GMB_VERSION );
			wp_enqueue_script( $this->plugin_slug . '-admin-map-directions' );
			//Marker Clustering
			wp_register_script( $this->plugin_slug . '-admin-map-marker-clustering', $js_plugins . 'markerclusterer' . $suffix . '.js', array( 'jquery' ), GMB_VERSION );
			wp_enqueue_script( $this->plugin_slug . '-admin-map-marker-clustering' );
		}

		//Import/Export Scripts
		if ( $hook == 'google_maps_page_gmb_import_export' ) {
			wp_register_script( $this->plugin_slug . '-admin-import-export', $js_dir . 'admin-import-export' . $suffix . '.js', array( 'jquery' ), GMB_VERSION );
			wp_enqueue_script( $this->plugin_slug . '-admin-import-export' );


		}

	}


}//end class


