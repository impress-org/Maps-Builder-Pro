/**
 * Maps Builder JS
 *
 * @description: Frontend form rendering
 */
var gmb_data;

(function ( $ ) {
	"use strict";
	var map;
	var places_service;
	var place;
	var info_window;
	var directionsDisplay = [];
	var search_markers = [];
	var info_window_args = {
		map                : map,
		shadowStyle        : 0,
		padding            : 0,
		backgroundColor    : 'rgb(255, 255, 255)',
		borderRadius       : 3,
		arrowSize          : 15,
		minHeight          : 100,
		maxHeight          : 355,
		minWidth           : 220,
		maxWidth           : 355,
		borderWidth        : 0,
		disableAutoPan     : true,
		backgroundClassName: 'gmb-infobubble'
	};

	/*
	 * Global load function for other plugins / themes to use
	 *
	 * ex: google_maps_builder_load( object );
	 */
	window.google_maps_builder_load = function ( map_canvas ) {
		if ( !$( map_canvas ).hasClass( 'google-maps-builder' ) ) {
			return 'invalid Google Maps Builder';
		}
		initialize_map( map_canvas );
	};

	$( document ).ready( function () {

		var google_maps = $( '.google-maps-builder' );
		/*
		 * Loop through maps and initialize
		 */
		google_maps.each( function ( index, value ) {

			initialize_map( $( google_maps[index] ) );

		} );

		// fix for bootstrap tabs
		$( 'a[data-toggle="tab"]' ).on( 'shown.bs.tab', function ( e ) {
			var panel = $( e.target ).attr( 'href' );
			load_hidden_map( panel );
		} );
		//Beaver Builder Tabs
		$( '.fl-tabs-label' ).on( 'click', function ( e ) {
			var panel = $( '.fl-tabs-panel-content.fl-tab-active' ).get( 0 );
			load_hidden_map( panel );
		} );
		//Tabby Tabs:
		$( '.responsive-tabs__list__item' ).on( 'click', function ( e ) {
			var panel = $( '.responsive-tabs__panel--active' ).get( 0 );
			load_hidden_map( panel );
		} );

	} );

	/**
	 * Map Init After the fact
	 *
	 * @description Good for tabs / ajax - pass in wrapper div class/id
	 * @since 2.0
	 */
	function load_hidden_map( parent ) {
		var google_hidden_maps = $( parent ).find( '.google-maps-builder' );
		if ( !google_hidden_maps.length ) {
			return;
		}
		google_hidden_maps.each( function ( index, value ) {
			//google.maps.event.trigger( map, 'resize' ); //TODO: Ideally we'd resize the map rather than reinitialize for faster performance, but that requires a bit of rewrite in how the plugin works
			initialize_map( $( google_hidden_maps[index] ) );
		} );
	}

	/**
	 * Map Intialize
	 *
	 * Sets up and configures the Google Map
	 *
	 * @param map_canvas
	 */
	function initialize_map( map_canvas ) {
		//info_window - Contains the place's information and content
		info_window = new InfoBubble( info_window_args );

		var map_id = $( map_canvas ).data( 'map-id' );
		var map_data = gmb_data[map_id];
		var latitude = (map_data.map_params.latitude) ? map_data.map_params.latitude : '32.713240';
		var longitude = (map_data.map_params.longitude) ? map_data.map_params.longitude : '-117.159443';
		var map_options = {
			center: new google.maps.LatLng( latitude, longitude ),
			zoom  : parseInt( map_data.map_params.zoom ),
			styles: [
				{
					stylers: [
						{visibility: 'simplified'}
					]
				},
				{
					elementType: 'labels', stylers: [
					{visibility: 'off'}
				]
				}
			]
		};
		map = new google.maps.Map( map_canvas[0], map_options );
		places_service = new google.maps.places.PlacesService( map );

		set_map_options( map, map_data );
		set_map_theme( map, map_data );
		set_map_markers( map, map_data, info_window );
		set_mashup_markers( map, map_data );
		set_map_directions( map, map_data );
		set_map_layers( map, map_data );
		set_map_places_search( map, map_data );

		//Display places?
		if ( map_data.places_api.show_places === 'yes' ) {
			perform_places_search( map, map_data, info_window );
		}


	} //end initialize_map


	/**
	 * Set Map Theme
	 *
	 * @description: Sets up map theme
	 *
	 */
	function set_map_theme( map, map_data ) {

		var map_type = map_data.map_theme.map_type.toUpperCase();
		var map_theme = map_data.map_theme.map_theme_json;


		//Custom (Snazzy) Theme
		if ( map_type === 'ROADMAP' && map_theme !== 'none' ) {

			map.setOptions( {
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				styles   : eval( map_theme )
			} );

		} else {
			//standard theme
			map.setOptions( {
				mapTypeId: google.maps.MapTypeId[map_type],
				styles   : false
			} );

		}


	}

	/**
	 * Set Map Options
	 *
	 * Sets up map controls
	 *
	 */
	function set_map_options( map, map_data ) {

		//Zoom control
		var zoom_control = map_data.map_controls.zoom_control.toLowerCase();

		if ( zoom_control == 'none' ) {
			map.setOptions( {
				zoomControl: false
			} );
		} else {
			map.setOptions( {
				zoomControl       : true,
				zoomControlOptions: {
					style: google.maps.ZoomControlStyle[zoom_control]
				}
			} );
		}

		//Mouse Wheel Zoom
		var mouse_zoom = map_data.map_controls.wheel_zoom.toLowerCase();
		if ( mouse_zoom === 'none' ) {
			map.setOptions( {
				scrollwheel: false
			} );
		} else {
			map.setOptions( {
				scrollwheel: true
			} );
		}

		//Pan Control
		var pan = map_data.map_controls.pan_control.toLowerCase();
		if ( pan === 'none' ) {
			map.setOptions( {
				panControl: false
			} );
		} else {
			map.setOptions( {
				panControl: true
			} );
		}


		//Street View Control
		var street_view = map_data.map_controls.street_view.toLowerCase();
		if ( street_view === 'none' ) {
			map.setOptions( {
				streetViewControl: false
			} );
		} else {
			map.setOptions( {
				streetViewControl: true
			} );
		}

		//Map Double Click
		var double_click_zoom = map_data.map_controls.double_click_zoom.toLowerCase();
		if ( double_click_zoom === 'none' ) {
			map.setOptions( {
				disableDoubleClickZoom: true
			} );
		} else {
			map.setOptions( {
				disableDoubleClickZoom: false
			} );
		}

		//Map Draggable
		var draggable = map_data.map_controls.draggable.toLowerCase();
		if ( draggable === 'none' ) {
			map.setOptions( {
				draggable: false
			} );
		} else {
			map.setOptions( {
				draggable: true
			} );
		}

	}

	/**
	 * Set Map Markers
	 */
	function set_map_markers( map, map_data, info_window ) {

		var map_markers = map_data.map_markers;
		var markers = [];

		//Loop through repeatable field of markers
		$( map_markers ).each( function ( index, marker_data ) {

			// Make sure we have latitude and longitude before creating the marker
			if ( marker_data.lat == '' || marker_data.lng == '' ) {
				return;
			}

			var marker_label = '';

			//check for custom marker and label data
			var marker_icon = map_data.map_params.default_marker; //Default marker icon here

			//Marker Image Icon
			if ( marker_data.marker_img ) {
				marker_icon = marker_data.marker_img;
			}
			//SVG Icon
			else if ( (typeof marker_data.marker !== 'undefined' && marker_data.marker.length > 0) && (typeof marker_data.label !== 'undefined' && marker_data.label.length > 0) ) {
				marker_icon = eval( "(" + marker_data.marker + ")" );
				marker_label = marker_data.label
			}

			//Default marker args
			var marker_args = {
				position    : new google.maps.LatLng( marker_data.lat, marker_data.lng ),
				map         : map,
				zIndex      : index,
				icon        : marker_icon,
				custom_label: marker_label
			};

			//Is sign in enabled? And, do we have a place ID for this marker location?
			if ( marker_data.place_id && map_data.signed_in_option === 'enabled' ) {

				//Remove unnecessary array params
				delete marker_args.position;

				//Add Proper Params
				marker_args.place = {
					location: {lat: parseFloat( marker_data.lat ), lng: parseFloat( marker_data.lng )},
					placeId : marker_data.place_id
				};
				marker_args.attribution = {
					source: map_data.site_name,
					webUrl: map_data.site_url
				};

			}

			//Marker for map
			var location_marker = new Marker( marker_args );
			markers.push( location_marker );

			location_marker.setVisible( true );

			google.maps.event.addListener( location_marker, 'click', function () {
				set_info_window_content( marker_data, info_window );
				info_window.open( map, location_marker );
				info_window.updateContent_();
			} );

			//Opened by default?
			if ( typeof marker_data.infowindow_open !== 'undefined' && marker_data.infowindow_open == 'opened' ) {
				google.maps.event.addListenerOnce( map, 'idle', function () {

					info_window.setContent( '<div id="infobubble-content" class="loading"></div>' );
					set_info_window_content( marker_data, info_window );
					info_window.open( map, location_marker );
					info_window.updateContent_();
				});
			}

		} ); //end $.each()

		//Cluster?
		if ( map_data.marker_cluster === 'yes' ) {
			var markerCluster = new MarkerClusterer( map, markers );
		}


	}

	/**
	 * Set Infowindow Content
	 *
	 * @description: Queries to get Google Place Details information
	 *
	 * @param marker_data
	 * @param info_window
	 */
	function set_info_window_content( marker_data, info_window ) {

		var info_window_content;

		//place name
		if ( marker_data.title ) {
			info_window_content = '<p class="place-title">' + marker_data.title + '</p>';
		}

		if ( marker_data.description ) {
			info_window_content += '<div class="place-description">' + marker_data.description + '</div>';
		}

		//Does this marker have a place_id
		if ( marker_data.place_id && marker_data.hide_details !== 'on' ) {

			var request = {
				key    : gmb_data.api_key,
				placeId: marker_data.place_id
			};

			//Get details from Google on this place
			places_service.getDetails( request, function ( place, status ) {

				if ( status == google.maps.places.PlacesServiceStatus.OK ) {

					info_window_content += set_place_content_in_info_window( place );
					info_window.setContent( info_window_content ); //set marker content
					info_window.updateContent_();

				}
			} );
		} else {
			info_window.setContent( info_window_content ); //set marker content
			info_window.updateContent_();

		}


	}


	/**
	 * info_window Content for Place Details
	 *
	 * This marker contains more information about the place
	 *
	 * @param place
	 */
	function set_place_content_in_info_window( place ) {

		var info_window_content;

		//additional info wrapper
		info_window_content = '<div class="marker-info-wrapper">';

		//place address
		info_window_content += ((place.formatted_address) ? '<div class="place-address">' + place.formatted_address + '</div>' : '' );

		//place phone
		info_window_content += ((place.formatted_phone_number) ? '<div class="place-phone">' + place.formatted_phone_number + '</div>' : '' );

		//place website
		info_window_content += ((place.website) ? '<div class="place-website"><a href="' + place.website + '" target="_blank" rel="nofollow" title="Click to visit the ' + place.name + ' website">Website</a></div>' : '' );

		//rating
		if ( place.rating ) {
			info_window_content += '<div class="rating-wrap clear">' +
				'<p class="numeric-rating">' + place.rating + '</p>' +
				'<div class="star-rating-wrap">' +
				'<div class="star-rating-size" style="width:' + (65 * place.rating / 5) + 'px;"></div>' +
				'</div>' +
				'</div>'
		}

		//close wrapper
		info_window_content += '</div>';

		return info_window_content;

	}

	/**
	 * Google Places Nearby Search
	 */
	function perform_places_search( map, map_data, info_window ) {

		var map_center = map.getCenter();
		var types_array = map_data.places_api.search_places;

		//remove existing markers
		for ( var i = 0; i < search_markers.length; i++ ) {
			search_markers[i].setMap( null );
		}
		search_markers = [];

		//Check if any place types are selected
		if ( types_array.length > 0 ) {

			//perform search request
			var request = {
				key     : gmb_data.api_key,
				location: new google.maps.LatLng( map_center.lat(), map_center.lng() ),
				types   : types_array,
				radius  : map_data.places_api.search_radius
			};
			places_service.nearbySearch( request, function ( results, status, pagination ) {

				var i = 0;
				var result;

				//setup new markers
				if ( status == google.maps.places.PlacesServiceStatus.OK ) {

					//place new markers
					for ( i = 0; result = results[i]; i++ ) {
						create_search_result_marker( map, results[i], info_window );
					}

					//show all pages of results @see: http://stackoverflow.com/questions/11665684/more-than-20-results-by-pagination-with-google-places-api
					if ( pagination.hasNextPage ) {
						pagination.nextPage();
					}

				}

			} );
		}

	}


	/**
	 * Create Search Result Marker
	 *
	 * Used with Places Search to place markers on map
	 * @param map
	 * @param place
	 * @param info_window
	 */
	function create_search_result_marker( map, place, info_window ) {

		var search_marker = new google.maps.Marker( {
			map: map
		} );
		//setup marker icon
		search_marker.setIcon( /** @type {google.maps.Icon} */({
			url       : place.icon,
			size      : new google.maps.Size( 24, 24 ),
			origin    : new google.maps.Point( 0, 0 ),
			anchor    : new google.maps.Point( 17, 34 ),
			scaledSize: new google.maps.Size( 24, 24 )
		}) );

		search_marker.setPosition( place.geometry.location );
		search_marker.setVisible( true );

		google.maps.event.addListener( search_marker, 'click', function () {

			info_window.close();
			info_window.setContent( '<div class="gmb-infobubble loading"></div>' );

			var marker_data = {
				title   : place.name,
				place_id: place.place_id
			};

			set_info_window_content( marker_data, info_window );
			info_window.open( map, search_marker );

		} );

		search_markers.push( search_marker )

	}

	/**
	 * Create Mashup Marker
	 *
	 * Loops through data and creates mashup markers
	 * @param map
	 * @param map_data
	 */
	function set_mashup_markers( map, map_data ) {

		if ( typeof map_data.mashup_markers === 'undefined' || !map_data.mashup_markers ) {
			return false;
		}

		// Store the markers
		var markers = [];

		$( map_data.mashup_markers ).each( function ( index, mashup_value ) {

			//Setup our vars
			var post_type = typeof mashup_value.post_type !== 'undefined' ? mashup_value.post_type : '';
			var taxonomy = typeof mashup_value.taxonomy !== 'undefined' ? mashup_value.taxonomy : '';
			var lat_field = typeof mashup_value.latitude !== 'undefined' ? mashup_value.latitude : '';
			var lng_field = typeof mashup_value.longitude !== 'undefined' ? mashup_value.longitude : '';
			var terms = typeof mashup_value.terms !== 'undefined' ? mashup_value.terms : '';

			var data = {
				action   : 'get_mashup_markers',
				post_type: post_type,
				taxonomy : taxonomy,
				terms    : terms,
				index    : index,
				lat_field: lat_field,
				lng_field: lng_field
			};

			jQuery.post( map_data.ajax_url, data, function ( response ) {

				//Loop through marker data
				$.each( response, function ( index, marker_data ) {
					var marker = set_mashup_marker( map, data.index, marker_data, mashup_value, map_data );
					if ( marker instanceof Marker ) {
						markers.push( marker );
					}
				} );

				//Cluster?
				if ( map_data.marker_cluster === 'yes' ) {
					var markerCluster = new MarkerClusterer( map, markers );
				}

			}, 'json' );

		} );

	}


	/**
	 *
	 * Set Mashup Marker
	 *
	 * @param map
	 * @param mashup_index
	 * @param marker_data
	 * @param mashup_value
	 * @param map_data
	 * @returns {*}
	 */
	function set_mashup_marker( map, mashup_index, marker_data, mashup_value, map_data ) {

		// Get latitude and longitude
		var lat = (typeof marker_data.latitude !== 'undefined' ? marker_data.latitude : '');
		var lng = (typeof marker_data.longitude !== 'undefined' ? marker_data.longitude : '');

		// Make sure we have latitude and longitude before creating the marker
		if ( lat == '' || lng == '' ) {
			return false;
		}

		var title = (typeof marker_data.title !== 'undefined' ? marker_data.title : '');
		var address = (typeof marker_data.address !== 'undefined' ? marker_data.address : '');
		var marker_position = new google.maps.LatLng( lat, lng );

		var marker_icon = map_data.map_params.default_marker;
		var marker_label = '';

		//check for custom marker and label data
		var custom_marker_icon = (typeof mashup_value.marker !== 'undefined' ? mashup_value.marker : '');
		var custom_marker_img = (typeof mashup_value.marker_img !== 'undefined' ? mashup_value.marker_img : '');

		if ( custom_marker_img ) {
			marker_icon = custom_marker_img;
		} else if ( custom_marker_icon.length > 0 && custom_marker_icon.length > 0 ) {
			var custom_label = (typeof mashup_value.label !== 'undefined' ? mashup_value.label : '');
			marker_icon = eval( "(" + custom_marker_icon + ")" );
			marker_label = custom_label;
		}

		// make and place map maker.
		var marker = new Marker( {
			map         : map,
			position    : marker_position,
			marker_data : marker_data,
			icon        : marker_icon,
			custom_label: marker_label
		} );

		//Set click action for marker to open infowindow
		google.maps.event.addListener( marker, 'click', function () {
			get_mashup_infowindow_content( marker, map_data );
		} );

		return marker;

	}


	/**
	 * Get Mashup Infowindow Content
	 *
	 * @param marker
	 * @param map_data
	 */
	function get_mashup_infowindow_content( marker, map_data ) {

		info_window.setContent( '<div class="gmb-infobubble loading"></div>' );

		info_window.open( map, marker );

		var data = {
			action      : 'get_mashup_marker_infowindow',
			marker_data : marker.marker_data,
			featured_img: marker.featured_img
		};

		jQuery.post( map_data.ajax_url, data, function ( response ) {

			info_window.setContent( response.infowindow );
			info_window.updateContent_();

		}, 'json' );
	}


	/**
	 * Set Map Directions
	 *
	 * @param map
	 * @param map_data
	 */
	function set_map_directions( map, map_data ) {

		//Setup destinations
		$( map_data.destination_markers ).each( function ( index, markers ) {

			var directionsService = new google.maps.DirectionsService();
			var directionsDisplay = new google.maps.DirectionsRenderer();
			var directionsPanel = $( '#directions-panel-' + map_data.id ).find( '.gmb-directions-panel-inner' );

			//If no points skip
			if ( typeof markers.point === 'undefined' || typeof markers.point[0] === 'undefined' ) {
				return false;
			}

			directionsDisplay.setMap( map );

			if ( map_data.text_directions !== 'none' ) {
				directionsDisplay.setPanel( $( directionsPanel ).get( 0 ) );
			}

			//Origin (We first use address, if no address use lat/lng)
			var start_lat = markers.point[0].latitude;
			var start_lng = markers.point[0].longitude;
			var start_address = markers.point[0].address;
			var origin;
			if ( start_address ) {
				origin = start_address;
			} else {
				origin = start_lat + ',' + start_lng;
			}

			// Get the index of the max value, through the built in function inArray
			var end_lat = markers.point[markers.point.length - 1].latitude;
			var end_lng = markers.point[markers.point.length - 1].longitude;
			var end_address = markers.point[markers.point.length - 1].address;
			var destination;
			if ( end_address ) {
				destination = end_address;
			} else {
				destination = end_lat + ',' + end_lng;
			}

			var travel_mode = (markers.travel_mode.length > 0) ? markers.travel_mode : 'DRIVING';
			var waypts = [];

			//Loop through interior elements (skipping first/last array items b/c they are origin/destinations)
			$( markers.point.slice( 1, -1 ) ).each( function ( index, waypoint ) {

				//Waypoint location (between origin/destination)
				var waypoint_lat = waypoint.latitude;
				var waypoint_lng = waypoint.longitude;
				var waypoint_address = waypoint.address;
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

			var request = {
				origin           : origin,
				destination      : destination,
				waypoints        : waypts,
				optimizeWaypoints: true,
				travelMode       : google.maps.TravelMode[travel_mode]
			};

			directionsService.route( request, function ( response, status ) {

				if ( status == google.maps.DirectionsStatus.OK ) {

					directionsDisplay.setOptions( {preserveViewport: true} ); //ensure users set lat/lng doesn't get all messed up
					directionsDisplay.setDirections( response );

				}
			} );

		} ); //end foreach

		//Set directions toggle field for this map
		$( '#directions-panel-' + map_data.id ).find( '.gmb-directions-toggle' ).on( 'click', function ( e ) {
			e.preventDefault();
			var dir_panel = $( this ).parent( '.gmb-directions-panel' );
			if ( dir_panel.hasClass( 'toggled' ) ) {
				dir_panel.removeClass( 'toggled' ).animate( {
					right: '-50%'
				} );
			} else {
				dir_panel.addClass( 'toggled' ).animate( {
					right: '0%'
				} );
			}

		} );

	}


	/**
	 * Set Map Layers
	 *
	 * @param map
	 * @param map_data
	 */
	function set_map_layers( map, map_data ) {

		var trafficLayer = new google.maps.TrafficLayer();
		var transitLayer = new google.maps.TransitLayer();
		var bicycleLayer = new google.maps.BicyclingLayer();

		$( map_data.layers ).each( function ( index, value ) {
			switch ( value ) {
				case 'traffic':
					trafficLayer.setMap( map );
					break;
				case 'transit':
					transitLayer.setMap( map );
					break;
				case 'bicycle':
					bicycleLayer.setMap( map );
					break;
			}
		} );
	}

	/**
	 * Set Places Search
	 *
	 * @description Adds a places search box that users search for place, addresses, estiblishments, etc.
	 * @param map
	 * @param map_data
	 */
	function set_map_places_search( map, map_data ) {

		//sanity check
		if ( map_data.places_search[0] !== 'yes' ) {
			return false;
		}

		var placeSearchWrap = $( '#google-maps-builder-' + map_data.id ).siblings( '.places-search-wrap' );

		var placeSearchInput = /** @type {HTMLInputElement} */(
			placeSearchWrap.find( '#pac-input' ).get( 0 ));
		var placeTypes = $( '#google-maps-builder-' + map_data.id ).siblings( '.places-search-wrap' ).find( '#type-selector' ).get( 0 );

		map.controls[google.maps.ControlPosition.TOP_CENTER].push( placeSearchWrap.get( 0 ) );

		var placeSearchAutocomplete = new google.maps.places.Autocomplete( placeSearchInput );
		placeSearchAutocomplete.bindTo( 'bounds', map );

		var infowindow = new InfoBubble();
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

			var info_window_content;
			if ( place.name ) {
				info_window_content = '<p class="place-title">' + place.name + '</p>';
			}
			info_window_content += set_place_content_in_info_window( place );
			infowindow.setContent( info_window_content ); //set marker content
			infowindow.open( map, marker );

		} );

		// Sets a listener on a radio button to change the filter type on Places
		// Autocomplete.
		function setupClickListener( id, placeTypes ) {
			var radioButton = document.getElementById( id );
			google.maps.event.addDomListener( radioButton, 'click', function () {
				placeSearchAutocomplete.setTypes( placeTypes );
			} );
		}

		setupClickListener( 'changetype-all', [] );
		setupClickListener( 'changetype-address', ['address'] );
		setupClickListener( 'changetype-establishment', ['establishment'] );
		setupClickListener( 'changetype-geocode', ['geocode'] );

	}

}( jQuery ));