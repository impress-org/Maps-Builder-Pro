<?php
/**
 *  Markers
 *
 * @description: Appears in modal
 * @since      :
 * @created    : 4/29/14
 */
?>

<div id="marker-icon-modal" class="white-popup">
	<div class="inner-modal">
		<button type="button" class="gmb-modal-close">&times;</button>
		<div class="marker-description-wrap clear">
			<h3><?php _e( 'Customize Map Marker', $this->plugin_slug ); ?></h3>

			<p><?php _e( 'Customize your Google Maps markers by selecting a marker graphic and icon. Integration made possible from the excellent Maps Icon library.', $this->plugin_slug ); ?></p>
		</div>

		<div class="marker-row clear">
			<h3><?php _e( 'Step 1: Select a Marker Type', $this->plugin_slug ); ?></h3>

			<div class="marker-item" data-marker="MAP_PIN">
				<div class="marker-svg">
					<svg version="1.0" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 100 165" enable-background="new 0 0 100 165" xml:space="preserve"><path fill="#428BCA" d="M50,0C22.382,0,0,21.966,0,49.054C0,76.151,50,165,50,165s50-88.849,50-115.946C100,21.966,77.605,0,50,0z"></path>
				</svg>
				</div>
				<div class="marker-description"><?php _e( 'Map Pin', $this->plugin_slug ); ?></div>
			</div>
			<div class="marker-item" data-marker="SQUARE_PIN">
				<div class="marker-svg">
					<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="40px" height="40px" viewBox="0 0 100 120" enable-background="new 0 0 100 120" xml:space="preserve"><polygon fill="#428BCA" points="100,0 0,0 0,100 36.768,100 50.199,119.876 63.63,100 100,100 "></polygon></svg>
				</div>
				<div class="marker-description"><?php _e( 'Square Pin', $this->plugin_slug ); ?></div>
			</div>
			<div class="marker-item" data-marker="default">
				<div class="marker-default">
					<img src="<?php echo apply_filters( 'gmb_default_marker', GMB_PLUGIN_URL . 'assets/img/default-marker.png' ); ?>" class="default-marker" />
				</div>
				<div class="marker-description"><?php _e( 'Default', $this->plugin_slug ); ?></div>
			</div>
			<div class="marker-item" data-marker="mapicons">
				<div class="marker-map-icons">
					<img src="<?php echo apply_filters( 'gmb_default_marker', GMB_PLUGIN_URL . 'assets/img/logo-mapicons.png' ); ?>" class="default-marker" />
				</div>
				<div class="marker-description"><?php _e( 'Map Icons', $this->plugin_slug ); ?></div>
			</div>
			<div class="marker-item" data-marker="upload">
				<div class="marker-svg">
					<img src="<?php echo apply_filters( 'gmb_default_marker', GMB_PLUGIN_URL . 'assets/img/logo-mapicons.png' ); ?>" class="default-marker" />
				</div>
				<div class="marker-description"><?php _e( 'Upload Marker', $this->plugin_slug ); ?></div>
			</div>

		</div>

		<div class="marker-icon-row map-svg-icons clear">

			<div class="marker-icon-color-wrap clear">
				<div class="marker-color-picker-wrap">
					<input type="text" name="color" id="color" value="#428BCA" class="color-picker marker-color" />
				</div>
				<p class="color-desc"><?php _e( 'Customize the marker color?', $this->plugin_slug ); ?></p>
			</div>

			<h3><?php _e( 'Step 2: Select a Marker Icon', $this->plugin_slug ); ?></h3>

			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-art-gallery"></span>
					art-gallery
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-campground"></span>
					campground
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-bank"></span>
					bank
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-hair-care"></span>
					hair-care
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-gym"></span>
					gym
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-point-of-interest"></span>
					point-of-interest
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-post-box"></span>
					post-box
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-post-office"></span>
					post-office
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-university"></span>
					university
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-beauty-salon"></span>
					beauty-salon
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-atm"></span>
					atm
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-rv-park"></span>
					rv-park
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-school"></span>
					school
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-library"></span>
					library
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-spa"></span>
					spa
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-route"></span>
					route
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-postal-code"></span>
					postal-code
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-stadium"></span>
					stadium
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-postal-code-prefix"></span>
					postal-code-prefix
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-museum"></span>
					museum
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-finance"></span>
					finance
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-natural-feature"></span>
					natural-feature
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-funeral-home"></span>
					funeral-home
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-cemetery"></span>
					cemetery
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-park"></span>
					park
				</div>
			</div>
			<div class="icon">
				<div class="icon-inner">
					<span class="map-icon-lodging"></span>
					lodging
				</div>
			</div>


			<div class="marker-label-color-wrap clear">
				<div class="marker-color-picker-wrap">
					<input type="text" name="color" id="color" class="color-picker label-color" value="#444444" /></div>
				<p class="color-desc"><?php _e( 'Customize the icon color?', $this->plugin_slug ); ?></p>
			</div>


		</div>
		<!--/.marker-icon-row -->

		<div class="marker-icon-row map-icons-row gmb-hidden clear">
			<ul class="map-icons-list">
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/accessdenied.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/airport.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/anniversary.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/apartment-3.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/audio.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/bank_euro.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/bar.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/bar_coktail.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/bicycle_shop.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/bigcity.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/blank_black.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/blank_black.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/blank_blue.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/blank_green.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/blank_grey.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/blank_pink.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/blank_red.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/blank_turquoise.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/blank_white.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/blank_yellow.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/bread.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/bus.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/busstop.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/bustour.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/camping-2.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/car.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/casino.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/caution.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/cctv.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/cinema.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/cloudy.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/coffee.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/comment-map-icon.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/communitycentre.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/condominium.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/construction.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/conveniencestore.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/conversation-map-icon.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/cycling.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/dancinghall.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/daycare.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/direction_down.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/direction_left.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/direction_right.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/direction_up.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/direction_upthenleft.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/direction_upthenright.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/disability.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/downloadicon.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/drinkingwater.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/entrance.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/factory.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/farm-2.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/fastfood.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/female.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/ferry.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/fillingstation.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/finish.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/fire.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/firstaid.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/flag-export.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/flowers.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/forest2.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/gifts.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/grocery.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/group.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/harbor.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/home.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/hospital-2.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/hospital-building.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/hostel_0star.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/hotel_0star.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/house.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/information.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/kids.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/lake.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_00.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_01.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_02.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_03.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_04.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_05.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_06.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_07.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_08.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_09.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/number_10.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_a.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_b.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_c.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_d.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_e.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_f.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_g.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_h.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_i.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_j.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_k.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_l.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_m.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_n.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_o.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_p.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_q.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_r.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_s.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_t.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_u.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_v.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_w.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_x.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_y.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/letter_z.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/lodging_0star.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/loveinterest.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/male.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/mall.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/medicine.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/motel-2.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/motorcycle.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/mountains.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/movierental.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/music_classical.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/music_live.png' ?>" alt=""></a>
				</li>

				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/parking.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/parkinggarage.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/party.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/photo.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/pin-export.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/pinother.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/pizzaria.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/planecrash.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/police2.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/postal.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/quadrifoglio.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/radiation.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/recycle.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/regroup.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/repair.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/restaurant.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/revolt.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/sandwich.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/school.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/shooting2.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/shootingrange.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/sight-2.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/smiley_happy.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/smiley_neutral.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/smiley_sad.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/snowy.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/sportutilityvehicle.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/star.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/stop.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/strike.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/sunny.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/supermarket.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/swimming2.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/symbol_excla.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/symbol_inter.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/taxiway.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/telephone.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/text.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/theater.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/toilets.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/toilets_disability.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/tools.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/train.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/truck3.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/tweet.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/university.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/video.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/villa.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/wifi.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/winebar.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/workoffice.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/zoo.png' ?>" alt=""></a>
				</li>
				<li>
					<a href="#" class="maps-icon"><img src="<?php echo GMB_PLUGIN_URL . 'assets/img/maps-icons-collection/zoom.png' ?>" alt=""></a>
				</li>
			</ul>
		</div>

		<div class="save-marker-icon clear gmb-hidden">
			<p class="save-text"><?php _e( 'Marker is ready to be set.', $this->plugin_slug ); ?></p>
			<button class="button button-primary button-large save-marker-button" data-marker="" data-marker-color="#428BCA" data-label="" data-label-color="#FFFFFF" data-marker-index="">Set Marker</button>
		</div>

	</div>
</div>