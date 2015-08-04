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
	var dirs_autocomplete,
		destination,
		destination_lat_field,
		destination_lng_field,
		destination_place_id,
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

			//Set appropriate field vars
			destination_lat_field = $( element ).parents( '.gmb-destination-fieldset' ).find( '.gmb-directions-latitude' );
			destination_lng_field = $( element ).parents( '.gmb-destination-fieldset' ).find( '.gmb-directions-longitude' );
			destination_place_id = $( element ).parents( '.gmb-destination-fieldset' ).find( '.gmb-directions-place_id' );

			//set lat lng input values
			destination_lat_field.val( destination.geometry.location.lat() );
			destination_lng_field.val( destination.geometry.location.lng() );
			destination_place_id.val( destination.place_id );

			if ( !destination.geometry ) {
				alert( 'Error: Place not found!' );
				return;
			}

			//Add new row

			//$( element ).parents( '.cmb-type-destination' ).find( '.cmb-add-row-button' ).trigger( 'click' );

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

			var directionsService = new google.maps.DirectionsService();
			directionsDisplay[index] = new google.maps.DirectionsRenderer();
			directionsDisplay[index].setMap( window.map );


			//Next loop through the groups within
			var repeatable_row = $( this ).find( '.cmb-repeat-row' );
			var start_lat = repeatable_row.first().find( '.gmb-directions-latitude[data-iterator="0"]' ).val();
			var start_lng = repeatable_row.first().find( '.gmb-directions-longitude[data-iterator="0"]' ).val();

			var end_lat = repeatable_row.last().find( '.gmb-directions-latitude' ).val();
			var end_lng = repeatable_row.last().find( '.gmb-directions-longitude' ).val();
			var travel_mode = $( this ).find( '.gmb-travel-mode' ).val();
			var waypts = [];

			repeatable_row.not( ':first' ).not( ':last' ).each( function ( index, value ) {

				var waypoint_lat = $( this ).find( '.gmb-directions-latitude' ).val();
				var waypoint_lng = $( this ).find( '.gmb-directions-longitude' ).val();

				waypts.push( {
					location: waypoint_lat + ',' + waypoint_lng,
					stopover: true
				} );

			} );

			var request = {
				origin           : start_lat + ',' + start_lng,
				destination      : end_lat + ',' + end_lng,
				waypoints        : waypts,
				optimizeWaypoints: true,
				travelMode       : google.maps.TravelMode[travel_mode]
			};

			directionsService.route( request, function ( response, status ) {

				if ( status == google.maps.DirectionsStatus.OK ) {

					//directionsDisplay[index].setOptions( {preserveViewport: true} );
					directionsDisplay[index].setDirections( response );

					var route = response.routes[0];
					//var summaryPanel = document.getElementById( 'directions_panel' );
					//summaryPanel.innerHTML = '';
					//// For each route, display summary information.
					//for ( var i = 0; i < route.legs.length; i++ ) {
					//	var routeSegment = i + 1;
					//	summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment + '</b><br>';
					//	summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
					//	summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
					//	summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
					//}
				}
			} );

		} );
	}


}( jQuery ));