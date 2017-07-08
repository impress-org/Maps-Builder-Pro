/**
 * Maps Builder JS.
 *
 * $current_user->IDFrontend form rendering
 */

(function ($, gmb) {


    /**
     * Create Mashup Marker.
     *
     * Loops through data and creates mashup markers.
     *
     * @param map
     * @param map_data
     */
    gmb.set_mashup_markers = function (map, map_data) {


        if (typeof map_data.mashup_markers === 'undefined' || !map_data.mashup_markers) {
            return false;
        }

        // Store the markers
        var markers = [];

        $(map_data.mashup_markers).each(function (index, mashup_value) {

            //Setup our vars
            var post_type = typeof mashup_value.post_type !== 'undefined' ? mashup_value.post_type : '';
            var taxonomy = typeof mashup_value.taxonomy !== 'undefined' ? mashup_value.taxonomy : '';
            var lat_field = typeof mashup_value.latitude !== 'undefined' ? mashup_value.latitude : '';
            var lng_field = typeof mashup_value.longitude !== 'undefined' ? mashup_value.longitude : '';
            var terms = typeof mashup_value.terms !== 'undefined' ? mashup_value.terms : '';

            var data = {
                action: 'get_mashup_markers',
                post_type: post_type,
                taxonomy: taxonomy,
                terms: terms,
                index: index,
                lat_field: lat_field,
                lng_field: lng_field
            };

            jQuery.post(map_data.ajax_url, data, function (response) {

                //Loop through marker data
                $.each(response, function (index, marker_data) {
                    //Set mashup markers
                    var marker = gmb.set_mashup_marker(map, data.index, marker_data, mashup_value, map_data);
                    if (marker instanceof Marker) {
                        // Add mashup marker to array with key equal to its post ID.
                        markers[marker.marker_data.id] = marker;
                    }
                });

                //Cluster?
                if (map_data.marker_cluster === 'yes') {
                    var markerCluster = new MarkerClusterer(map, markers);
                }

            }, 'json');

        });

    };

    /**
     * Set Mashup Marker
     *
     * @param map
     * @param mashup_index
     * @param marker_data
     * @param mashup_value
     * @param map_data
     * @returns {*}
     */
    gmb.set_mashup_marker = function (map, mashup_index, marker_data, mashup_value, map_data) {

        // Get latitude and longitude
        var lat = (typeof marker_data.latitude !== 'undefined' ? marker_data.latitude : '');
        var lng = (typeof marker_data.longitude !== 'undefined' ? marker_data.longitude : '');

        // Make sure we have latitude and longitude before creating the marker
        if (lat == '' || lng == '') {
            return false;
        }

        var title = (typeof marker_data.title !== 'undefined') ? marker_data.title : '';
        var address = (typeof marker_data.address !== 'undefined') ? marker_data.address : '';
        var marker_position = new google.maps.LatLng(lat, lng);

        var marker_icon = map_data.map_params.default_marker;
        var marker_label = '';

        //check for custom marker and label data
        var custom_marker_icon = (typeof mashup_value.marker !== 'undefined') ? mashup_value.marker : '';
        var custom_marker_img = (typeof mashup_value.marker_img !== 'undefined') ? mashup_value.marker_img : '';
        var included_marker_img = (typeof mashup_value.marker_included_img !== 'undefined') ? mashup_value.marker_included_img : '';

        //Plugin included marker image
        if (included_marker_img) {
            marker_icon = map_data.plugin_url + included_marker_img;
        } else if (custom_marker_img) {
            //Uploaded marker image
            marker_icon = custom_marker_img;
        } else if (custom_marker_icon.length > 0 && custom_marker_icon.length > 0) {
            //SVG Marker
            var custom_label = (typeof mashup_value.label !== 'undefined' ? mashup_value.label : '');
            marker_icon = eval('(' + custom_marker_icon + ')');
            marker_label = custom_label;
        }

        // Whether or not an individual marker displays its featured image is decided by the parent mashup's settings;
        // if it's set to "yes", then the image displays, else it doesn't.
        marker_data['featured_img'] = (mashup_value['featured_img'] === 'yes');

        // make and place map maker.
        var marker = new Marker({
            map: map,
            position: marker_position,
            marker_data: marker_data,
            icon: marker_icon,
            custom_label: marker_label
        });

        //Set click action for marker to open info_window
        google.maps.event.addListener(marker, 'click', function () {
            gmb.get_mashup_infowindow_content(map, marker, map_data);
        });

	    /**
	     * Adds custom event so marker can be manipulated before it is set.
	     *
	     * @since 2.1.2
	     * @author Tobias Malikowski tobias.malikowski@gmail.com
	     * @see http://api.jquery.com/trigger/
	     * @see http://api.jquery.com/on/
	     */
	    $( document ).trigger( 'gmb.set_mashup_marker', [marker, map, mashup_index, marker_data, mashup_value, map_data] );

        return marker;

    };

    /**
     * Get Mashup InfoWindow Content.
     *
     * @param map
     * @param marker
     * @param map_data
     */
    gmb.get_mashup_infowindow_content = function (map, marker, map_data) {

        map.info_window.close();

        var data = {
            action: 'get_mashup_marker_infowindow',
            marker_data: marker.marker_data
        };

        jQuery.post(map_data.ajax_url, data, function (response) {

            map.info_window.setContent(response.infowindow);
            map.info_window.updateContent_();
            map.info_window.open(map, marker, map_data);

            //Center markers on click option.
            //Timeout required to calculate height properly.
            if (map_data.marker_centered == 'yes') {
                window.setTimeout(function () {
                    map.info_window.panToView();
                }, 300);
            }

        }, 'json');

    };

    /**
     * Set Map Directions.
     *
     * @param map
     * @param map_data
     */
    gmb.set_map_directions = function (map, map_data) {

        //Setup destinations
        $(map_data.destination_markers).each(function (index, markers) {

            var directionsService = new google.maps.DirectionsService();
            var directionsDisplay = new google.maps.DirectionsRenderer();
            var directionsPanel = $('#directions-panel-' + map_data.id).find('.gmb-directions-panel-inner');

            //If no points skip
            if (typeof markers.point === 'undefined' || typeof markers.point[0] === 'undefined') {
                return false;
            }

            directionsDisplay.setMap(map);

            if (map_data.text_directions !== 'none') {
                directionsDisplay.setPanel($(directionsPanel).get(0));
            }

            //Origin (We first use address, if no address use lat/lng)
            var start_lat = markers.point[0].latitude;
            var start_lng = markers.point[0].longitude;
            var start_address = markers.point[0].address;
            var origin;
            if (start_address) {
                origin = start_address;
            } else {
                origin = start_lat + ',' + start_lng;
            }

            // Get the index of the max value, through the built in function inArray
            var point_index = parseInt(markers.point.length - 1, 10);
            if ('undefined' != markers.point[point_index]) {
                var end_lat = markers.point[point_index].latitude;
                var end_lng = markers.point[point_index].longitude;
                var end_address = markers.point[point_index].address;
            }

            var destination;
            if (end_address) {
                destination = end_address;
            } else {
                destination = end_lat + ',' + end_lng;
            }

            var travel_mode = (markers.travel_mode.length > 0) ? markers.travel_mode : 'DRIVING';
            var waypts = [];

            //Loop through interior elements (skipping first/last array items b/c they are origin/destinations)
            $(markers.point.slice(1, -1)).each(function (index, waypoint) {

                //Waypoint location (between origin/destination)
                var waypoint_lat = waypoint.latitude;
                var waypoint_lng = waypoint.longitude;
                var waypoint_address = waypoint.address;
                var waypoint_location;

                if (waypoint_address) {
                    waypoint_location = waypoint_address;
                } else {
                    waypoint_location = waypoint_lat + ',' + waypoint_lng;
                }
                waypts.push({
                    location: waypoint_location,
                    stopover: true
                });

            });

            var request = {
                origin: origin,
                destination: destination,
                waypoints: waypts,
                optimizeWaypoints: true,
                travelMode: google.maps.TravelMode[travel_mode]
            };

            directionsService.route(request, function (response, status) {

                if (status == google.maps.DirectionsStatus.OK) {

                    directionsDisplay.setOptions({preserveViewport: true}); //ensure users set lat/lng doesn't get all messed up
                    directionsDisplay.setDirections(response);

                }
            });

        }); //end foreach

        //Set directions toggle field for this map
        $('#directions-panel-' + map_data.id).find('.gmb-directions-toggle').on('click', function (e) {
            e.preventDefault();
            var dir_panel = $(this).parent('.gmb-directions-panel');
            if (dir_panel.hasClass('toggled')) {
                dir_panel.removeClass('toggled').animate({
                    right: '-50%'
                });
            } else {
                dir_panel.addClass('toggled').animate({
                    right: '0%'
                });
            }

        });

    };

    /**
     * Set Map Layers
     *
     * @param map
     * @param map_data
     */
    gmb.set_map_layers = function (map, map_data) {

        var trafficLayer = new google.maps.TrafficLayer();
        var transitLayer = new google.maps.TransitLayer();
        var bicycleLayer = new google.maps.BicyclingLayer();

        $(map_data.layers).each(function (index, value) {
            switch (value) {
                case 'traffic':
                    trafficLayer.setMap(map);
                    break;
                case 'transit':
                    transitLayer.setMap(map);
                    break;
                case 'bicycle':
                    bicycleLayer.setMap(map);
                    break;
            }
        });
    };

    /**
     * Set Places Search
     *
     * Adds a places search box that users search for place, addresses, establishments, etc.
     * @param map
     * @param map_data
     */
    gmb.set_map_places_search = function (map, map_data) {

        //Sanity check.
        if (map_data.places_search[0] !== 'yes') {
            return false;
        }

        var placeSearchWrap = $('#google-maps-builder-' + map_data.id).siblings('.places-search-wrap');
        var placeSearchInput = /** @type {HTMLInputElement} */(
            placeSearchWrap.find('#pac-input').get(0));

        //Adds the Places control at the map's center.
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(placeSearchWrap.get(0));

        var placeSearchAutocomplete = new google.maps.places.Autocomplete(placeSearchInput);
        placeSearchAutocomplete.bindTo('bounds', map);

        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
        });

        google.maps.event.addListener(placeSearchAutocomplete, 'place_changed', function () {
            marker.setVisible(false);
            var place = placeSearchAutocomplete.getPlace();

            if (!place.geometry) {
                window.alert("Autocomplete's returned place contains no geometry");
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }
            marker.setIcon(/** @type {google.maps.Icon} */({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            var info_window_content;
            if (place.name) {
                info_window_content = '<p class="place-title">' + place.name + '</p>';
            }
            info_window_content += gmb.set_place_content_in_info_window(place);
            map.info_window.setContent(info_window_content);
            map.info_window.updateContent_();
            map.info_window.open(map, marker, map_data);

            //Center markers on click option.
            //Timeout required to calculate height properly.
            if (map_data.marker_centered == 'yes') {
                window.setTimeout(function () {
                    map.info_window.panToView();
                }, 300);
            }


        });

        var placeTypes = $('#google-maps-builder-' + map_data.id).siblings('.places-search-wrap').find('#type-selector').get(0);

        // Sets a listener on a radio button to change the filter type on Places Autocomplete.
        function setupClickListener(id, placeTypes) {
            var radioButton = document.getElementById(id);
            google.maps.event.addDomListener(radioButton, 'click', function () {
                placeSearchAutocomplete.setTypes(placeTypes);
            });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);

    };


}(jQuery, window.MapsBuilder || ( window.MapsBuilder = {} )) );


