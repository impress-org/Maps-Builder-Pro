
(function ( $, gmb ) {

        /**
         * Custom Snazzy Maps
         *
         * @description Sets a custom snazzy map from JS
         * @since 2.0
         */
        gmb.set_custom_snazzy_map = function() {

            var custom_theme_json = $( '#gmb_theme_json' );

            //Sanity check
            if ( custom_theme_json.val() === '' ) {
                return;
            }

            try {
                var custom_theme_json_val = $.parseJSON( custom_theme_json.val() );
                map.setOptions( {
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    styles   : eval( custom_theme_json_val )
                } );
            }
            catch ( err ) {
                alert( 'Invalid JSON' );
                custom_theme_json.val( '' ).focus();
            }
        };

        /**
         * Sets the Map Theme
         *
         * @description Uses Snazzy Maps JSON arrow to set the colors for the map
         * @since 1.0
         */
        gmb.set_map_theme = function() {

            var preset_theme = $( '#gmb_theme' );
            var map_theme_input_val = parseInt( preset_theme.val() );
            var map_type_select_field = $( '#gmb_type' );
            var custom_theme_json_wrap = $( '.cmb2-id-gmb-theme-json' );
            var custom_theme_json = $( '#gmb_theme_json' );

            //"Set a Custom Snazzy Map" button click
            $( '.custom-snazzy-toggle' ).on( 'click', function ( e ) {
                e.preventDefault();
                preset_theme.val( 'custom' );
                custom_theme_json_wrap.show();
                custom_theme_json.val( '' ).focus();
                gmb.set_custom_snazzy_map();
            } );

            //On Snazzy Map textfield value change
            custom_theme_json.on( 'change', function () {
                gmb.set_custom_snazzy_map();
            } );

            //Sanity check to see if none
            if ( preset_theme.val() !== 'none' ) {
                map_type_select_field.val( 'RoadMap' );
            }
            //Snazzy maps select set to none
            if ( preset_theme.val() === 'none' ) {
                custom_theme_json.val( '' ); //clear value from custom JSON field
            }
            //Custom snazzy map
            else if ( preset_theme.val() === 'custom' ) {
                custom_theme_json_wrap.show();
                gmb.set_custom_snazzy_map();
            }
            //Preconfigured snazzy map
            else {
                custom_theme_json_wrap.hide();
                //AJAX to get JSON data for Snazzy
                $.getJSON( gmb_data.snazzy, function ( data ) {

                    $.each( data, function ( index ) {

                        if ( data[index].id === map_theme_input_val ) {
                            map_theme_input_val = eval( data[index].json );
                            $( '#gmb_theme_json' ).val( data[index].json );
                        }

                    } );

                    map.setOptions( {
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        styles   : map_theme_input_val
                    } );

                } );

            }
        };

    /**
     *  Add Markers
     *
     * @description This is the marker that first displays on load for the main location or place
     *
     * @param map
     */
    gmb.add_markers = function( map ) {

        gmb.clear_main_markers();
        var time = 500;
        var markers = [];
        var cluster_markers = $( '#gmb_marker_cluster1' ).prop( 'checked' );

        //Loop through repeatable field of markers
        $( "#gmb_markers_group_repeat" ).find( '.cmb-repeatable-grouping' ).each( function ( index ) {

            var marker_icon = gmb_data.default_marker;
            var marker_label = '';

            //check for custom marker and label data
            var custom_marker_icon = $( '#gmb_markers_group_' + index + '_marker' ).val();
            var custom_marker_img = $( '#gmb_markers_group_' + index + '_marker_img' ).val();

            if ( custom_marker_img ) {
                marker_icon = custom_marker_img;
            } else if ( custom_marker_icon.length > 0 && custom_marker_icon.length > 0 ) {
                var custom_label = $( '#gmb_markers_group_' + index + '_label' ).val();
                marker_icon = eval( "(" + custom_marker_icon + ")" );
                marker_label = custom_label;
            }

            var marker_lat = parseFloat( $( '#gmb_markers_group_' + index + '_lat' ).val() );
            var marker_lng = parseFloat( $( '#gmb_markers_group_' + index + '_lng' ).val() );
            var place_id = $( '#gmb_markers_group_' + index + '_place_id' ).val();
            var position = new google.maps.LatLng( marker_lat, marker_lng );

            //Default marker args
            var marker_args = {
                position    : position,
                map         : map,
                zIndex      : index,
                icon        : marker_icon,
                custom_label: marker_label
            };

            //Is sign in enabled? And, do we have a place ID for this marker location?
            if ( place_id && gmb_data.signed_in_option === 'enabled' ) {

                //Remove unnecessary array params
                delete marker_args.position;

                //Add Proper Params
                marker_args.place = {
                    location: {lat: marker_lat, lng: marker_lng},
                    placeId : place_id
                };
                marker_args.attribution = {
                    source: gmb_data.site_name,
                    webUrl: gmb_data.site_url
                };

            }

            //Marker for map
            var location_marker = new Marker( marker_args );
            markers.push( location_marker );

            location_marker.setVisible( true );

            //Set click action for marker to open infowindow
            google.maps.event.addListener( location_marker, 'click', function () {
                gmb.get_info_window_content( index, location_marker );
            } );

            time += 500;

            //Remove row button/icon also removes icon (CMB2 buttons)
            $( '#gmb_markers_group_' + index + '_title' ).parents( '.cmb-repeatable-grouping' ).find( '.cmb-remove-group-row' ).each( function () {
                google.maps.event.addDomListener( $( this )[0], 'click', function () {
                    var index = $( this ).parents( '.cmb-repeatable-grouping' ).data( 'index' );
                    //close info window and remove marker
                    info_bubble.close();
                    location_marker.setVisible( false );
                } );
            } );

        } ); //end $.each()

        //Cluster?
        if ( cluster_markers === true ) {
            var markerCluster = new MarkerClusterer( map, markers );
        }

    };

    /**
     * Marker Index
     *
     * @description Helper function that returns the appropriate index for the repeatable group
     * @returns {Number}
     */
    gmb.get_marker_index = function() {

        var marker_repeatable = $( '#gmb_markers_group_repeat' );
        var marker_repeatable_group = marker_repeatable.find( ' div.cmb-repeatable-grouping' );
        var marker_add_row_btn = marker_repeatable.find( '.cmb-add-group-row.button' );

        //Create a new marker repeatable meta group
        var index = parseInt( marker_repeatable_group.last().attr( 'data-iterator' ) );
        var existing_vals = marker_repeatable_group.first().find( 'input,textarea' ).val();

        //Ensure appropriate index is used for marker
        if ( existing_vals && index === 0 ) {
            marker_add_row_btn.trigger( 'click' );
            index = 1;
        } else if ( index !== 0 ) {
            marker_add_row_btn.trigger( 'click' );
            //recount rows
            index = parseInt( marker_repeatable.find( ' div.cmb-repeatable-grouping' ).last().attr( 'data-iterator' ) );
        }

        return index;
    };


    gmb.set_map_theme = function() {
        var preset_theme = $( '#gmb_theme' );
        var map_theme_input_val = parseInt( preset_theme.val() );
        var map_type_select_field = $( '#gmb_type' );
        var custom_theme_json_wrap = $( '.cmb2-id-gmb-theme-json' );
        var custom_theme_json = $( '#gmb_theme_json' );

        //"Set a Custom Snazzy Map" button click
        $( '.custom-snazzy-toggle' ).on( 'click', function ( e ) {
            e.preventDefault();
            preset_theme.val( 'custom' );
            custom_theme_json_wrap.show();
            custom_theme_json.val( '' ).focus();
            set_custom_snazzy_map();
        } );

        //On Snazzy Map textfield value change
        custom_theme_json.on( 'change', function () {
            set_custom_snazzy_map();
        } );

        //Sanity check to see if none
        if ( preset_theme.val() !== 'none' ) {
            map_type_select_field.val( 'RoadMap' );
        }
        //Snazzy maps select set to none
        if ( preset_theme.val() === 'none' ) {
            custom_theme_json.val( '' ); //clear value from custom JSON field
        }
        //Custom snazzy map
        else if ( preset_theme.val() === 'custom' ) {
            custom_theme_json_wrap.show();
            set_custom_snazzy_map();
        }
        //Preconfigured snazzy map
        else {
            custom_theme_json_wrap.hide();
            //AJAX to get JSON data for Snazzy
            $.getJSON( gmb_data.snazzy, function ( data ) {

                $.each( data, function ( index ) {

                    if ( data[index].id === map_theme_input_val ) {
                        map_theme_input_val = eval( data[index].json );
                        $( '#gmb_theme_json' ).val( data[index].json );
                    }

                } );

                map.setOptions( {
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    styles   : map_theme_input_val
                } );

            } );

        }

    };


}( jQuery, window.MapsBuilderAdmin || ( window.MapsBuilderAdmin = {} ) ) );


