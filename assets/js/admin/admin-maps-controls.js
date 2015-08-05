/**
 *  Maps Directions
 *
 *  @description: Adds directions functionality to the maps builder
 *  @copyright: http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *  @since: 2.0
 */

var gmb_data;
var gmb_upload_marker;
var trafficLayer = new google.maps.TrafficLayer();
var transitLayer = new google.maps.TransitLayer();
var bicycleLayer = new google.maps.BicyclingLayer();
var placeSearchAutocomplete;

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
		$( '.cmb2-id-gmb-layers input:checkbox' ).each( function () {
			set_map_layers( $( this ) );
		} );

		//Places Search
		var places_search_control = $( '.cmb2-id-gmb-places-search input' );
		places_search_control.on( 'change', function () {
			toggle_map_places_search_field( $( this ) );
		} );

		toggle_map_places_search_field( places_search_control );
		
		//Initialize Magnific/Modal Functionality Too
		$( 'body' ).on( 'click', '.gmb-magnific-inline', function ( e ) {
			e.preventDefault();
			var target = '.' + $( this ).data( 'target' ); //target element class name

			//Modal in modal?
			//We can't have a magnific inside magnific so CSS3 modal it is
			if ( $.magnificPopup.instance.isOpen === true ) {
				console.log( target );
				//Open CSS modal
				$( target ).before( '<div class="modal-placeholder"></div>' ) // Save a DOM "bookmark"
					.removeClass( 'mfp-hide' ) //ensure it's visible
					.appendTo( '.magnific-builder #poststuff' ); // Move the element to container

				//Add close functionality
				$( target ).on( 'click', function ( e ) {
					//only on overlay
					if ( $( e.target ).hasClass( 'white-popup' ) ) {
						// Move back out of container
						$( this )
							.addClass( 'mfp-hide' ) //ensure it's hidden
							.appendTo( '.modal-placeholder' )  // Move it back to it's proper location
							.unwrap(); // Remove the placeholder
					}
				} );
				//Close button
				$( '.gmb-modal-close' ).on( 'click', function () {
					$( target )
						.addClass( 'mfp-hide' ) //ensure it's hidden
						.appendTo( '.modal-placeholder' )  // Move it back to it's proper location
						.unwrap(); // Remove the placeholder
				} );


			}
			//Normal modal open
			else {
				$.magnificPopup.open( {
					items   : {
						src : $( target ),
						type: 'inline'
					},
					midClick: true
				} );
			}
		} );

		//Custom marker modal uploader
		gmb_upload_marker = {

			// Call this from the upload button to initiate the upload frame.
			uploader: function () {

				//@TODO: i18n
				var frame = wp.media( {
					title   : 'Set an Custom Marker Icon',
					multiple: false,
					library : {type: 'image'},
					button  : {text: 'Set Marker'}
				} );

				// Handle results from media manager.
				frame.on( 'close', function () {
					var attachments = frame.state().get( 'selection' ).toJSON();
					gmb_upload_marker.render( attachments[0] );
				} );

				frame.open();
				return false;
			},

			// Output Image preview
			render: function ( attachment ) {

				$( '.gmb-image-preview' ).prepend( gmb_upload_marker.imgHTML( attachment ) );
				$( '.gmb-image-preview' ).html( gmb_upload_marker.imgHTML( attachment ) );
				$( '.gmb-image-preview' ).show();
				$( '.save-marker-icon' ).slideDown(); //slide down save button
				$( '.save-marker-button' ).data( 'marker-image', attachment.url ); //slide down save button

			},

			// Render html for the image.
			imgHTML    : function ( attachment ) {
				var img_html = '<img src="' + attachment.url + '" ';
				img_html += 'width="' + attachment.width + '" ';
				img_html += 'height="' + attachment.height + '" ';
				if ( attachment.alt != '' ) {
					img_html += 'alt="' + attachment.alt + '" ';
				}
				img_html += '/>';
				return img_html;
			},
			// User wants to remove the avatar
			removeImage: function ( widget_id_string ) {
				$( "#" + widget_id_string + 'attachment_id' ).val( '' );
				$( "#" + widget_id_string + 'imageurl' ).val( '' );
				$( "#" + widget_id_string + 'preview img' ).remove();
				$( "#" + widget_id_string + 'preview a' ).hide();
			}

		};


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

	/**
	 * Toggle Places Search Field
	 * @descrition: Adds and removes the places search field from the map preview
	 * @param input
	 */
	function toggle_map_places_search_field( input ) {

		//Setup search or Toggle show/hide?
		if ( typeof placeSearchAutocomplete === 'undefined' && input.prop( 'checked' ) === true ) {
			set_map_places_search_field(); //hasn't been setup yet, so set it up
			$( '#places-search' ).show();
		} else if ( input.prop( 'checked' ) === true && typeof placeSearchAutocomplete === 'object' ) {
			$( '#places-search' ).show();
		} else {
			$( '#places-search' ).hide();
		}

	}

	/**
	 * Set up Places Search Field
	 *
	 * @description Creates the Google Map custom control with autocomplete enabled
	 *
	 */
	function set_map_places_search_field() {
		var input = /** @type {HTMLInputElement} */(
			document.getElementById( 'pac-input' ));

		var types = document.getElementById( 'type-selector' );
		map.controls[google.maps.ControlPosition.TOP_CENTER].push( document.getElementById( 'places-search' ) );

		placeSearchAutocomplete = new google.maps.places.Autocomplete( input );
		placeSearchAutocomplete.bindTo( 'bounds', map );

		var infowindow = new google.maps.InfoWindow();
		var marker = new google.maps.Marker( {
			map        : map,
			anchorPoint: new google.maps.Point( 0, -29 )
		} );

		google.maps.event.addListener( placeSearchAutocomplete, 'place_changed', function () {
			infowindow.close();
			marker.setVisible( false );
			var place = placeSearchAutocomplete.getPlace();
			if ( !place.geometry ) {
				window.alert( "Autocomplete's returned place contains no geometry" );
				return;
			}

			// If the place has a geometry, then present it on a map.
			if ( place.geometry.viewport ) {
				map.fitBounds( place.geometry.viewport );
			} else {
				map.setCenter( place.geometry.location );
				map.setZoom( 17 );  // Why 17? Because it looks good.
			}
			marker.setIcon( /** @type {google.maps.Icon} */({
				url       : place.icon,
				size      : new google.maps.Size( 71, 71 ),
				origin    : new google.maps.Point( 0, 0 ),
				anchor    : new google.maps.Point( 17, 34 ),
				scaledSize: new google.maps.Size( 35, 35 )
			}) );
			marker.setPosition( place.geometry.location );
			marker.setVisible( true );

			var address = '';
			if ( place.address_components ) {
				address = [
					(place.address_components[0] && place.address_components[0].short_name || ''),
					(place.address_components[1] && place.address_components[1].short_name || ''),
					(place.address_components[2] && place.address_components[2].short_name || '')
				].join( ' ' );
			}

			infowindow.setContent( '<div><strong>' + place.name + '</strong><br>' + address );
			infowindow.open( map, marker );
		} );

		// Sets a listener on a radio button to change the filter type on Places
		// Autocomplete.
		function setupClickListener( id, types ) {
			var radioButton = document.getElementById( id );
			google.maps.event.addDomListener( radioButton, 'click', function () {
				placeSearchAutocomplete.setTypes( types );
			} );
		}

		setupClickListener( 'changetype-all', [] );
		setupClickListener( 'changetype-address', ['address'] );
		setupClickListener( 'changetype-establishment', ['establishment'] );
		setupClickListener( 'changetype-geocode', ['geocode'] );

		//Tame the enter key to not save the widget while using the autocomplete input
		google.maps.event.addDomListener( input, 'keydown', function ( e ) {
			if ( e.keyCode == 13 ) {
				e.preventDefault();
			}
		} );

	}


}( jQuery ));