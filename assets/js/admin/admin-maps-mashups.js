/**
 *  Admin Mashup Functionality
 *
 *  @description: Adds directions functionality to the maps builder
 *  @copyright: http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *  @since: 2.0
 */
window.GMB_Mashups = (function ( window, document, $, undefined ) {
	'use strict';

	var app = {};

	var address;
	var lat;
	var lng;
	var marker;
	var markers = [];

	app.cache = function () {

	};

	app.init = function () {
		app.cache();

		//Post Type select on Change
		$( 'body' ).on( 'change', '.gmb-mashup-post-type-field select', app.toggle_mashup_taxonomies );
		$( 'body' ).on( 'change', '.cmb-type-select-taxonomies select', app.toggle_mashup_terms );
		$( 'body' ).on( 'click', '.gmb-load-mashup', app.toggle_mashup_loading );

		//Refresh button
		$( 'body' ).on( 'change', '.cmb-repeatable-grouping select, .cmb-repeatable-grouping input:not(hidden)', function () {

			$( this ).parents( '.cmb-repeatable-grouping' ).find( '.gmb-load-mashup' ).addClass( 'button-primary' ).removeAttr( 'disabled' );

		} );

	};


	/**
	 * Toggle Mashup Taxonomies
	 *
	 * @description Fires when the post types select is toggled
	 */
	app.toggle_mashup_taxonomies = function () {

		var select = $( this );
		var this_value = select.val();
		var repeater_index = select.parents( '.cmb-repeatable-grouping' ).data( 'iterator' );
		var taxonomy_filter_wrap = $( this ).parents( '.gmb-mashup-post-type-field' ).next( '.gmb-taxonomy-select-field' );
		var taxonomy_filter = taxonomy_filter_wrap.find( 'select' );
		var terms_filter_wrap = $( this ).parents( '.gmb-mashup-post-type-field' ).nextAll( '.gmb-terms-multicheck-field' );

		//Hide terms filter
		taxonomy_filter_wrap.hide();
		terms_filter_wrap.hide();

		//AJAX data to send to and trigger PHP
		var data = {
			action   : 'get_post_types_taxonomies',
			post_type: this_value,
			index    : repeater_index
		};
		//Go AJAX go
		jQuery.post( ajaxurl, data, function ( response ) {

			//We expect JSON back
			var data = jQuery.parseJSON( response );

			//Update taxonomy filter dropdown with our new options
			taxonomy_filter.empty().html( data.options );
			//show it
			taxonomy_filter_wrap.show();

			//Show taxonomy's terms
			if ( data.status !== 'none' ) {

				terms_filter_wrap.find( '.cmb2-checkbox-list' ).empty().html( data.terms_checklist );
				terms_filter_wrap.show();

			}

		} );

	};

	/**
	 * Toggle Mashup Terms
	 *
	 * @description Fires when the taxonomies select is toggled
	 */
	app.toggle_mashup_terms = function () {

		//Taxonomy Filter
		var select = $( this );
		var this_value = select.val();
		var repeater_index = select.parents( '.cmb-repeatable-grouping' ).data( 'iterator' );
		var terms_filter_wrap = $( this ).parents( '.cmb-repeatable-grouping' ).find( '.gmb-terms-multicheck-field' );


		//Hide terms wrap
		terms_filter_wrap.hide();

		var data = {
			action  : 'get_taxonomy_terms',
			taxonomy: this_value,
			index   : repeater_index
		};

		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post( ajaxurl, data, function ( response ) {

			var data = jQuery.parseJSON( response );

			terms_filter_wrap.find( '.cmb2-checkbox-list' ).empty().html( data.terms_checklist );
			terms_filter_wrap.show();

		} );


	};

	/**
	 * Marker Loading
	 *
	 * @param e event
	 */
	app.toggle_mashup_loading = function ( e ) {

		e.preventDefault();
		var submit_button = $( this );
		var repeater_index = submit_button.parents( '.cmb-repeatable-grouping' ).data( 'iterator' );
		var post_type = $( '#gmb_mashup_group_' + repeater_index + '_post_type' ).val();
		var taxonomy = $( '#gmb_mashup_group_' + repeater_index + '_taxonomy' ).val();

		var terms = [];
		$( '.cmb2-id-gmb-mashup-group-' + repeater_index + '-terms' ).find( 'input[type="checkbox"]:checked' ).each( function ( i ) {
			terms[i] = $( this ).val();
		} );

		var data = {
			action   : 'get_mashup_markers',
			post_type: post_type,
			taxonomy : taxonomy,
			terms    : terms,
			index    : repeater_index
		};

		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.post( ajaxurl, data, function ( response ) {

			//Sanity check
			if ( typeof response.error !== 'undefined' ) {
				alert( response.error );
				return false;
			}

			//First clear markers
			app.clear_mashup_markers( data.index );
			//Then create new index for this array
			markers[data.index] = [];

			//Loop through marker data
			$.each( response, function ( index, value ) {
				app.set_mashup_marker( data.index, value );
			} );

			//Set mashup as configured
			submit_button.prev( '#mashup_configured' ).val( true );
			submit_button.removeClass( 'button-primary' ).attr( 'disabled', 'disabled' );


		}, 'json' );


	};


	/**
	 * Set Mashup Marker
	 *
	 * @param mashup_index
	 * @param marker_data
	 */
	app.set_mashup_marker = function ( mashup_index, marker_data ) {

		address = (typeof marker_data.all_custom._gmb_address !== 'undefined' ? marker_data.all_custom._gmb_address[0] : '');
		lat = (typeof marker_data.all_custom._gmb_lat !== 'undefined' ? marker_data.all_custom._gmb_lat[0] : '');
		lng = (typeof marker_data.all_custom._gmb_lng !== 'undefined' ? marker_data.all_custom._gmb_lng[0] : '');

		// make and place map maker.
		marker = new google.maps.Marker( {
			map     : window.map,
			position: new google.maps.LatLng( lat, lng )
		} );

		markers[mashup_index].push( marker ); //Add to markers array

	};


	/**
	 * Clear Mashup Markers
	 *
	 * @param mashup_index
	 */
	app.clear_mashup_markers = function ( mashup_index ) {

		//Only clear if there are markers
		if ( typeof markers[mashup_index] === 'undefined' ) {
			return;
		}

		//Loop through and clear
		for ( var i = 0; i < markers[mashup_index].length; i++ ) {
			markers[mashup_index][i].setMap( null );
		}

	};


	//Get it started
	$( document ).ready( app.init );

	return app;

})( window, document, jQuery );