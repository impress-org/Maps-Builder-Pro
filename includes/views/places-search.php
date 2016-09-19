<?php
/**
 *  googlemapsbuilder.dev - places-search.php
 *
 * @description:
 * @copyright  : http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since      : 1.0.0
 * @created    : 8/1/2015
 */ ?>
<div id="places-search" class="places-search-wrap">
	<input id="pac-input" class="controls" type="text"
	       placeholder="<?php _e( 'Enter a location', 'google-maps-builder' ); ?>">

	<div id="type-selector" class="controls">
		<input type="radio" name="type" id="changetype-all" checked="checked">
		<label for="changetype-all"><?php _e( 'All', 'google-maps-builder' ); ?></label>

		<input type="radio" name="type" id="changetype-establishment">
		<label for="changetype-establishment"><?php _e( 'Establishments', 'google-maps-builder' ); ?></label>

		<input type="radio" name="type" id="changetype-address">
		<label for="changetype-address"><?php _e( 'Addresses', 'google-maps-builder' ); ?></label>

		<input type="radio" name="type" id="changetype-geocode">
		<label for="changetype-geocode"><?php _e( 'Geocodes', 'google-maps-builder' ); ?></label>
	</div>
</div>
