/**
 * Google Maps CPT Handling
 *
 *
 */

(function ( $ ) {

	"use strict";


	$( document ).on( 'ready', function () {

		//Import Markers
		$( '#gmb_maps' ).on( 'change', function () {
			var map_value = $( this ).val();
			var csv_upload = $( '.csv-upload' );
			if ( map_value !== '0' ) {
				csv_upload.show();
			} else {
				csv_upload.hide();
			}

		} );


	} );


}( jQuery ));

