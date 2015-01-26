<?php
/*
Plugin Name: Logarithmic Pagination
Plugin URI: http://www.k308.de/wp/logarithmic-pagination
Description: Plugin that inserts logarithmic pagination into archive pages.
Version: 0.1.3
Author: Jonas Heitzer
Author URI: http://blog.k308.de
License: GPL2

Copyright 2013  Jonas Heitzer  (email : jonas@k308.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

// Define plugin constants
define( 'WPLOPA_TOKEN', true ) ;
$plugin = plugin_basename( __FILE__ );

// Init
function wp_lopa_init() {
	$opts = array(
		'lopa_show_css'        => 'Y',
		'lopa_show_links'      => 9,
		'lopa_show_before'     => false,
		'lopa_show_after'      => false,
		'lopa_show_prevnext'   => 'Y',
		'lopa_before_id'       => 'pages_top',
		'lopa_after_id'        => 'pages_bottom',
		'lopa_prev_text'       => __( 'Previous', 'wp_lopa' ),
		'lopa_next_text'       => __( 'Next', 'wp_lopa' ),
		'lopa_switch_author'   => false,
		'lopa_switch_category' => false,
		'lopa_switch_date'     => false,
		'lopa_switch_home'     => false,
		'lopa_switch_search'   => false,
		'lopa_switch_tag'      => false,
	);
	foreach ( $opts as $key => $value ) {
		update_option( $key, $value );
	}

}
register_activation_hook( __FILE__, 'wp_lopa_init' );

// Check if we a re supposed to show pagination. If yes hooks into loop_start & loop_end.
function lopa_load_plugin() {
	global $wp_query, $post, $page;

	include 'inc/pagination.php';

	// Is it the homepage and is the homepage selected in the settings?
	if ( is_front_page() && is_home() && 'Y' == get_option( 'lopa_switch_home' ) )
		$switch_home = true;

	// Is it a date archive and date archives selected in the settings?
	if ( is_date() && 'Y' == get_option( 'lopa_switch_date' ) )
		$switch_date = true;

	// Is it the search-results and are they selected in the settings?
	if ( is_search() && 'Y' == get_option( 'lopa_switch_search' ) )
		$switch_search = true;

	// Is it a category archive and are they selected in the settings?
	if ( is_category() && 'Y' == get_option( 'lopa_switch_category' ) )
		$switch_category = true;

	// Is it a tag archive and are they selected in the settings?
	if ( is_tag() && 'Y' == get_option( 'lopa_switch_tag' ) )
		$switch_tag = true;

	// Is it an author archive and are they selected in the settings?
	if ( is_author() && 'Y' == get_option( 'lopa_switch_author' ) )
		$switch_author = true;

	// Is there more than 1 page, should we show pagination and are we actually at a point where we are supposed to show it.
	if ( $wp_query->max_num_pages > 1 && ( $switch_author || $switch_date || $switch_tag || $switch_home || $switch_search || $switch_category ) && did_action( 'wp_enqueue_scripts' ) == 1 ) {
		add_action( 'loop_start', 'wp_lopa_before', 0 );
		add_action( 'loop_end', 'wp_lopa_after', 0 );

		// Check if default css is enabled
		if ( get_option( 'lopa_show_css' ) == 'Y' )
			$show_css = true;

		// If not disabled in the Settings, show this default style
		if ( $show_css ) {
			wp_lopa_load_stylesheet();
		}

	}

}

// Load localization
function wp_lopa_lang() {
	load_plugin_textdomain( 'wp-lopa', false, basename(dirname(__FILE__)) . '/languages/' );
}
add_action( 'plugins_loaded', 'wp_lopa_lang' );

// If we are on the Dashboard, please include the settings page. If not load call the above function to check wether to show pagination or not.
if ( is_admin() ) {
	include 'inc/settings.php';
} else {
	add_action('wp_head', 'lopa_load_plugin');
}

?>