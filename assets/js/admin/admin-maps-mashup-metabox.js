/**
 *  Mashups Metabox
 *
 *  @description: Adds functionality to the maps builder mashups metabox which appears on various post types as set by the user
 *  @copyright: http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *  @since: 2.0
 */

(function ( $ ) {

	"use strict";

	/**
	 * Kick it off on Doc Load
	 */
	$( document ).ready( function () {

		//Autocomplete
		set_mashup_autocomplete();


	} );


	/**
	 * Mashup Autocomplete
	 */
	function set_mashup_autocomplete() {

		var input = $( '#_gmb_mashup_autocomplete' ).get( 0 );

		var location_autocomplete = new google.maps.places.Autocomplete( input );
		//location_autocomplete.bindTo( 'bounds', map );

		google.maps.event.addListener( location_autocomplete, 'place_changed', function () {

			var place = location_autocomplete.getPlace();
			if ( !place.geometry ) {
				window.alert( "Autocomplete's returned place contains no geometry" );
				return false;
			}
			console.log(place);

			//Set field vars
			if ( place.geometry ) {
				$( '#_gmb_lat' ).val( place.geometry.location.lat() );
				$( '#_gmb_lng' ).val( place.geometry.location.lng() );
			}
			if ( place.formatted_address ) {
				$( '#_gmb_address' ).val( place.formatted_address );
			}
			if ( place.place_id ) {
				$( '#_gmb_place_id' ).val( place.place_id );
			}


		} );

		//Tame the enter key to not save the widget while using the autocomplete input
		google.maps.event.addDomListener( input, 'keydown', function ( e ) {
			if ( e.keyCode == 13 ) {
				e.preventDefault();
			}
		} );

	}

}( jQuery ));