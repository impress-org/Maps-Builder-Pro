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
		$this->plugin_slug = Google_Maps_Builder::instance()->get_plugin_slug();
		if( is_admin() ){
			new Google_Maps_Builder_Core_Admin_Scripts();
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_hooks' ) );
		}else{
			add_action( 'wp_enqueue_scripts', array( $this, 'font_end_hooks' ) );
			new Google_Maps_Builder_Core_Front_End_Scripts();

		}

	}

	/**
	 * Load additional admin scripts
	 *
	 * @since 2.1.0
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


		if ( ( $hook == 'post-new.php' || $hook == 'post.php' ) && 'google_maps' === $post->post_type ) {
			//pro only
			wp_register_script( $this->plugin_slug . '-admin-pro', $js_dir . 'admin-pro' . $suffix . '.js', array( 'jquery' ), GMB_VERSION );
			wp_enqueue_script( $this->plugin_slug . '-admin-pro' );


			//Directions
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

	/**
	 * Load additional front-end scripts
	 *
	 * @since 2.1.0
	 *
	 * @uses "enqueue_scripts"
	 *
	 */
	public function font_end_hooks(){
		$js_dir = GMB_PLUGIN_URL . 'assets/js/frontend/';
		$js_plugins = GMB_PLUGIN_URL . 'assets/js/plugins/';
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_register_script( 'google-maps-builder-plugin-script-pro', $js_dir . 'google-maps-builder' . $suffix . '.js', array( 'jquery' ), GMB_VERSION, true );
		wp_enqueue_script( 'google-maps-builder-plugin-script-pro' );

		wp_register_script( 'google-maps-builder-clusterer', $js_plugins . 'markerclusterer' . $suffix . '.js', array( 'jquery' ), GMB_VERSION, true );
		wp_enqueue_script( 'google-maps-builder-clusterer' );
	}


}//end class


