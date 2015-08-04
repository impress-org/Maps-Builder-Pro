<?php
/**
 *  googlemapsbuilder.dev - places-search.php
 *
 * @description:
 * @copyright  : http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since      : 1.0.0
 * @created    : 8/1/2015
 */

//Places search
$output .= '<div id="places-search" class="places-search-wrap"><input id="pac-input" class="controls" type="text"
    placeholder="' . __( 'Enter a location', $this->plugin_slug ) . '">
<div id="type-selector" class="controls">
  <input type="radio" name="type" id="changetype-all" checked="checked">
  <label for="changetype-all">' . __( 'All', $this->plugin_slug ) . '</label>

  <input type="radio" name="type" id="changetype-establishment">
  <label for="changetype-establishment">' . __( 'Establishments', $this->plugin_slug ) . '</label>

  <input type="radio" name="type" id="changetype-address">
  <label for="changetype-address">' . __( 'Addresses', $this->plugin_slug ) . '</label>

  <input type="radio" name="type" id="changetype-geocode">
  <label for="changetype-geocode">' . __( 'Geocodes', $this->plugin_slug ) . '</label>
</div> </div>';

echo $output;