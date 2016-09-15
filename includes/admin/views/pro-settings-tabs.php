<?php
/**
 * Pro Settings Tabs
 *
 * @package   Google_Maps_Builder
 * @author    Devin Walker <devin@wordimpress.com>
 * @license   GPL-2.0+
 * @link      http://wordimpress.com
 * @copyright 2016 WordImpress, Devin Walker
 */
?>
<a href="?post_type=google_maps&page=<?php echo $key; ?>&tab=license" class="nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active' : ''; ?>">
	<?php _e( 'License', 'google-maps-builder' ); ?>
</a>