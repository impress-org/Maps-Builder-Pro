(function ($, gmb) {

    /**
     * Custom Snazzy Maps
     *
     * @description Sets a custom snazzy map from JS
     * @since 2.0
     */
    gmb.set_custom_snazzy_map = function () {

        var custom_theme_json = $('#gmb_theme_json');

        //Sanity check
        if (custom_theme_json.val() === '') {
            return;
        }

        try {
            var custom_theme_json_val = $.parseJSON(custom_theme_json.val());
            map.setOptions({
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                styles: eval(custom_theme_json_val)
            });
        }
        catch (err) {
            alert('Invalid JSON');
            custom_theme_json.val('').focus();
        }
    };

    /**
     * Sets the Map Theme
     *
     * @description Uses Snazzy Maps JSON arrow to set the colors for the map
     * @since 1.0
     */
    gmb.set_map_theme = function () {

        var preset_theme = $('#gmb_theme');
        var map_theme_input_val = parseInt(preset_theme.val());
        var map_type_select_field = $('#gmb_type');
        var custom_theme_json_wrap = $('.cmb2-id-gmb-theme-json');
        var custom_theme_json = $('#gmb_theme_json');

        //"Set a Custom Snazzy Map" button click
        $('.custom-snazzy-toggle').on('click', function (e) {
            e.preventDefault();
            preset_theme.val('custom');
            custom_theme_json_wrap.show();
            custom_theme_json.val('').focus();
            gmb.set_custom_snazzy_map();
        });

        //On Snazzy Map textfield value change
        custom_theme_json.on('change', function () {
            gmb.set_custom_snazzy_map();
        });

        //Sanity check to see if none
        if (preset_theme.val() !== 'none') {
            map_type_select_field.val('RoadMap');
        }
        //Snazzy maps select set to none
        if (preset_theme.val() === 'none') {
            custom_theme_json.val(''); //clear value from custom JSON field
        }
        //Custom snazzy map
        else if (preset_theme.val() === 'custom') {
            custom_theme_json_wrap.show();
            gmb.set_custom_snazzy_map();
        }
        //Preconfigured snazzy map
        else {
            custom_theme_json_wrap.hide();
            //AJAX to get JSON data for Snazzy
            $.getJSON(gmb_data.snazzy, function (data) {

                $.each(data, function (index) {

                    if (data[index].id === map_theme_input_val) {
                        map_theme_input_val = eval(data[index].json);
                        $('#gmb_theme_json').val(data[index].json);
                    }

                });

                map.setOptions({
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    styles: map_theme_input_val
                });

            });

        }
    };

    /**
     * Marker Index
     *
     * @description Helper function that returns the appropriate index for the repeatable group
     * @returns {Number}
     */
    gmb.get_marker_index = function () {

        var marker_repeatable = $('#gmb_markers_group_repeat');
        var marker_repeatable_group = marker_repeatable.find(' div.cmb-repeatable-grouping');
        var marker_add_row_btn = marker_repeatable.find('.cmb-add-group-row.button');

        //Create a new marker repeatable meta group
        var index = parseInt(marker_repeatable_group.last().attr('data-iterator'));
        var existing_vals = marker_repeatable_group.first().find('input,textarea').val();

        //Ensure appropriate index is used for marker
        if (existing_vals && index === 0) {
            marker_add_row_btn.trigger('click');
            index = 1;
        } else if (index !== 0) {
            marker_add_row_btn.trigger('click');
            //recount rows
            index = parseInt(marker_repeatable.find(' div.cmb-repeatable-grouping').last().attr('data-iterator'));
        }

        return index;
    };


}(jQuery, window.MapsBuilderAdmin || ( window.MapsBuilderAdmin = {} )) );


