<?php
/*
* SiteSEO
* https://siteseo.io/
* (c) SiteSEO Team <support@siteseo.io>
*/

/*
Copyright 2016 - 2024 - Benjamin Denis  (email : contact@seopress.org)
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

if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * AMP Compatibility - wp action hook
 *
 * @since 5.9.0
 *
 * @return void
 */
add_action('wp', 'siteseo_amp_compatibility_wp', 0);
function siteseo_amp_compatibility_wp() {
	if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
		wp_dequeue_script( 'siteseo-accordion' );

		remove_filter( 'siteseo_google_analytics_html', 'siteseo_google_analytics_js', 10);

		remove_action('wp_enqueue_scripts', 'siteseo_google_analytics_ecommerce_js', 20, 1);

		remove_action('wp_enqueue_scripts', 'siteseo_google_analytics_cookies_js', 20, 1);

		remove_action( 'wp_head', 'siteseo_load_google_analytics_options', 0 );
	}
}

/**
 * AMP Compatibility - wp_head action hook
 *
 * @since 5.9.0
 *
 * @return void
 */
add_action('wp_head', 'siteseo_amp_compatibility_wp_head', 0);
function siteseo_amp_compatibility_wp_head() {
	if ( function_exists( 'amp_is_request' ) && amp_is_request() ) {
		wp_dequeue_script( 'siteseo-accordion' );
	}
}