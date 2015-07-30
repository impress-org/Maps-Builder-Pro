/**
 *  Maps Directions
 *
 *  @description: Adds directions functionality to the maps builder
 *  @copyright: http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *  @since: 2.0
 */

var gmb_data;

(function ( $ ) {

	"use strict";
	var directionsService = new google.maps.DirectionsService();
	var directions_display,
		dirs_autocomplete,
		destination,
		destination_lat_field,
		destination_lng_field,
		destination_place_id,
		destination_count,
		destination_location_marker;

	$( window ).on( 'load', function () {

		directions_display = new google.maps.DirectionsRenderer();

		$( '.gmb-directions-autocomplete' ).each( function ( index, value ) {

			gmb_setup_autocomplete( $( this ) );

		} );

		$( '#gmb_directions_group_repeat' ).on( 'cmb2_add_row', function ( event, row ) {

			gmb_setup_autocomplete( $( row ).find( '.gmb-directions-autocomplete' ) );

		} );


	} );


	function gmb_setup_autocomplete( element ) {

		if ( typeof element[0] === 'undefined' ) {
			return false;
		}

		var dirs_autocomplete = new google.maps.places.Autocomplete( element[0] );
		dirs_autocomplete.bindTo( 'bounds', map );

		//Tame the enter key to not save the widget while using the dirs_autocomplete input
		google.maps.event.addDomListener( element[0], 'keydown', function ( e ) {
			if ( e.keyCode == 13 ) {
				e.preventDefault();
			}
		} );

		//Autocomplete event listener
		google.maps.event.addListener( dirs_autocomplete, 'place_changed', function () {

			//get place information
			destination = dirs_autocomplete.getPlace();

			destination_lat_field = $( element ).parents( '.gmb-destination-fieldset' ).find( '.gmb-directions-longitude' );
			destination_lng_field = $( element ).parents( '.gmb-destination-fieldset' ).find( '.gmb-directions-latitude' );
			destination_place_id = $( element ).parents( '.gmb-destination-fieldset' ).find( '.gmb-directions-place_id' );

			//set lat lng input values
			destination_lat_field.val( destination.geometry.location.lat() );
			destination_lng_field.val( destination.geometry.location.lng() );
			destination_place_id.val( destination.place_id );

			if ( !destination.geometry ) {
				alert( 'Error: Place not found!' );
				return;
			}

			window.map.setCenter( destination.geometry.location );
			add_destination_marker( window.map, destination.place_id );

		} );


	}

	/**
	 * Shows a Marker when Autocomplete search is used
	 * @param map
	 * @param place_id
	 */
	function add_destination_marker( map, place_id ) {

		var map_center = map.getCenter();

		//Marker for map
		destination_location_marker = new google.maps.Marker( {
			map      : map,
			title    : 'Map Icons',
			animation: google.maps.Animation.DROP,
			position : new google.maps.LatLng( map_center.lat(), map_center.lng() ),
			icon     : new google.maps.MarkerImage( gmb_data.plugin_url + "assets/img/temp-marker.png" ),
			zIndex   : google.maps.Marker.MAX_ZINDEX + 1,
			optimized: false
		} );


	}

	function calc_route() {
		var start = document.getElementById( 'start' ).value;
		var end = document.getElementById( 'end' ).value;
		var request = {
			origin     : start,
			destination: end,
			travelMode : google.maps.TravelMode.DRIVING
		};
		directionsService.route( request, function ( response, status ) {
			if ( status == google.maps.DirectionsStatus.OK ) {
				directionsDisplay.setDirections( response );
			}
		} );
	}

}( jQuery ));
