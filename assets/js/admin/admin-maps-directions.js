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

	var directionsDisplay = [];
	var directionsService = new google.maps.DirectionsService();
	var dirs_autocomplete,
		destination,
		destination_lat_field,
		destination_lng_field,
		destination_place_id,
		destination_address,
		destination_count,
		destination_location_marker;

	/**
	 * Kick it off on Window Load
	 */
	$( window ).on( 'load', function () {

		//Calculate the route here
		calc_routes();

		//Setup autocomplete for each input
		$( '.gmb-directions-autocomplete' ).each( function ( index, value ) {
			gmb_setup_autocomplete( $( this ) );
		} );

		//Destination added
		$( '#gmb_directions_group_repeat' ).on( 'cmb2_add_row', function ( event, row ) {
			gmb_setup_autocomplete( $( row ).find( '.gmb-directions-autocomplete' ) );
		} );

		//Destination row removed
		$( '.cmb-type-destination' ).on( 'cmb2_remove_row', function ( event, row ) {
			calc_routes();
		} );

		//Destination row removed
		$( 'body' ).on( 'change', '.gmb-travel-mode', function ( event, row ) {
			calc_routes();
		} );


	} );


	/**
	 * Setup Directions Autocomplete Field
	 *
	 * @param element
	 * @returns {boolean}
	 */
	function gmb_setup_autocomplete( element ) {

		if ( typeof element[0] === 'undefined' ) {
			return false;
		}

		var dirs_autocomplete = new google.maps.places.Autocomplete( element[0] );
		dirs_autocomplete.bindTo( 'bounds', map );

		//Tame the enter key to not save the post while using the dirs_autocomplete input
		google.maps.event.addDomListener( element[0], 'keydown', function ( e ) {
			if ( e.keyCode == 13 ) {
				e.preventDefault();
			}
		} );

		//Autocomplete event listener
		google.maps.event.addListener( dirs_autocomplete, 'place_changed', function () {

			//get place information
			destination = dirs_autocomplete.getPlace();

			//Set appropriate field object vars
			destination_lat_field = $( element ).parents( '.gmb-destination-fieldset' ).find( '.gmb-directions-latitude' );
			destination_lng_field = $( element ).parents( '.gmb-destination-fieldset' ).find( '.gmb-directions-longitude' );
			destination_place_id = $( element ).parents( '.gmb-destination-fieldset' ).find( '.gmb-directions-place_id' );
			destination_address = $( element ).parents( '.gmb-destination-fieldset' ).find( '.gmb-directions-address' );

			//set values
			destination_lat_field.val( destination.geometry.location.lat() );
			destination_lng_field.val( destination.geometry.location.lng() );
			destination_place_id.val( destination.place_id );
			destination_address.val( destination.formatted_address );

			if ( !destination.geometry ) {
				alert( 'Error: Place not found!' );
				return;
			}

			calc_routes();

		} );


	}


	/**
	 * Calculate Route
	 */
	function calc_routes() {

		//Loop through Directions group
		$( '#gmb_directions_group_repeat' ).find( '.cmb-repeatable-grouping' ).each( function ( index, value ) {

			if ( directionsDisplay[index] ) {
				directionsDisplay[index].setMap( null );
			}

			//Setup
			directionsDisplay[index] = new google.maps.DirectionsRenderer();
			directionsDisplay[index].setMap( window.map );
			var repeatable_row = $( this ).find( '.cmb-repeat-row' );

			//Get origin
			var start_lat = repeatable_row.first().find( '.gmb-directions-latitude[data-iterator="0"]' ).val();
			var start_lng = repeatable_row.first().find( '.gmb-directions-longitude[data-iterator="0"]' ).val();
			var start_address = repeatable_row.first().find( '.gmb-directions-address[data-iterator="0"]' ).val();
			var origin;
			if ( start_address ) {
				origin = start_address;
			} else {
				origin = start_lat + ',' + start_lng;
			}

			//Get Destination
			var end_lat = repeatable_row.last().find( '.gmb-directions-latitude' ).val();
			var end_lng = repeatable_row.last().find( '.gmb-directions-longitude' ).val();
			var end_address = repeatable_row.last().find( '.gmb-directions-address' ).val();
			var final_destination;
			if ( start_address ) {
				final_destination = end_address;
			} else {
				final_destination = end_lat + ',' + end_lng;
			}

			var travel_mode = $( this ).find( '.gmb-travel-mode' ).val();
			var waypts = [];

			//Next Loop through interior destionations (not first or last) to get waypoints
			repeatable_row.not( ':first' ).not( ':last' ).each( function ( index, value ) {

				var waypoint_address = $( this ).find( '.gmb-directions-address' ).val();
				var waypoint_lat = $( this ).find( '.gmb-directions-latitude' ).val();
				var waypoint_lng = $( this ).find( '.gmb-directions-longitude' ).val();
				var waypoint_location;

				if ( waypoint_address ) {
					waypoint_location = waypoint_address;
				} else {
					waypoint_location = waypoint_lat + ',' + waypoint_lng;
				}

				waypts.push( {
					location: waypoint_location,
					stopover: true
				} );

			} );


			//Directions Request
			var request = {
				origin           : origin,
				destination      : final_destination,
				waypoints        : waypts,
				optimizeWaypoints: true,
				travelMode       : google.maps.TravelMode[travel_mode]
			};

			directionsService.route( request, function ( response, status ) {

				if ( status == google.maps.DirectionsStatus.OK ) {

					directionsDisplay[index].setOptions( {preserveViewport: true} ); //ensure users set lat/lng doesn't get all messed u
					directionsDisplay[index].setDirections( response ); //Set dem directions

				}
			} );

		} );
	}


}( jQuery ));