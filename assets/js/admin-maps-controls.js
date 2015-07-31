/**
 *  Maps Directions
 *
 *  @description: Adds directions functionality to the maps builder
 *  @copyright: http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *  @since: 2.0
 */

var gmb_data;

var trafficLayer = new google.maps.TrafficLayer();
var transitLayer = new google.maps.TransitLayer();
var bicycleLayer = new google.maps.BicyclingLayer();

(function ( $ ) {

	"use strict";

	/**
	 * Kick it off on Window Load
	 */
	$( window ).on( 'load', function () {

		//Layers
		$( '.cmb2-id-gmb-layers input' ).on( 'change', function () {
			set_map_layers( $( this ) );
		} );

		//Loop through layers
		$('.cmb2-id-gmb-layers input:checkbox' ).each(function(){
			set_map_layers( $( this ) );
		});

	} );

	/**
	 * Set Map Layers
	 *
	 * @description Toggles various layers on and off
	 * @param layer obj
	 */
	function set_map_layers( layer ) {

		if ( layer ) {
			var this_val = layer.val();
		} else {
			return false;
		}

		var checked = layer.prop( 'checked' );


		switch ( this_val ) {

			case 'traffic':


				if ( !checked ) {
					trafficLayer.setMap( null );
				} else {
					trafficLayer.setMap( window.map );
				}
				break;
			case 'transit':


				if ( !checked ) {
					transitLayer.setMap( null );
				} else {
					transitLayer.setMap( window.map );
				}
				break;

			case 'bicycle':


				if ( !checked ) {
					bicycleLayer.setMap( null );
				} else {
					bicycleLayer.setMap( window.map );
				}
				break;

		}

	}
}( jQuery ));
