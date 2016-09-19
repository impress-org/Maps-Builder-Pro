(function ($, gmb) {

    /**
     * Custom Snazzy Maps.
     *
     * Sets a custom snazzy map from JS.
     *
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

}(jQuery, window.MapsBuilderAdmin || ( window.MapsBuilderAdmin = {} )) );


