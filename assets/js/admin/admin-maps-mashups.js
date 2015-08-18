/**
 *  Mashup Functionality
 *
 *  @description: Adds directions functionality to the maps builder
 *  @copyright: http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *  @since: 2.0
 */
//var gmb_mashup;

(function ( $ ) {

	"use strict";

	/**
	 * Kick it off on Window Load
	 */
	$( document ).ready( function () {

		//Start it off
		gmb_mashup_init();

		//When a new row is added re-initialize
		//$('#cmb2-metabox-google_maps_mashup_builder' ).find('.cmb-add-group-row' ).on('click', function(){
		//	gmb_mashup_init();
		//});

	} );

	/**
	 * Initialize
	 */
	function gmb_mashup_init() {
		toggle_mashup_taxonomies();
		toggle_mashup_terms();
	}

	/**
	 * Toggle Mashup Taxonomies
	 *
	 * @description Fires when the taxonomy filter select is toggled
	 */
	function toggle_mashup_taxonomies() {

		//Post Type select on Change
		$( 'body' ).on( 'change', '.gmb-mashup-post-type-field select', function () {

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

		} );

		//Taxonomy Filter
		$( 'body' ).on( 'change', '.cmb-type-select-taxonomies select', function () {

			var select = $( this );
			var this_value = select.val();
			var repeater_index = select.parents( '.cmb-repeatable-grouping' ).data( 'iterator' );
			var terms_filter_wrap = $( this ).parents( '.cmb-repeatable-grouping' ).find( '.gmb-terms-multicheck-field' );

			//Hide terms wrap
			terms_filter_wrap.hide();

			var data = {
				action   : 'get_taxonomy_terms',
				taxonomy : this_value,
				index    : repeater_index
			};
console.log(data);
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post( ajaxurl, data, function ( response ) {

				var data = jQuery.parseJSON( response );

				console.log(data);
				terms_filter_wrap.find( '.cmb2-checkbox-list' ).empty().html( data.terms_checklist );
				terms_filter_wrap.show();


			} );

		} );

	}


	function toggle_mashup_terms() {

		$( '.gmb-mashup-post-type-field' ).find( 'select' ).on( 'change', function () {


		} );

	}


}( jQuery ));