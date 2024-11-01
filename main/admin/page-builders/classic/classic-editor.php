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

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Classic editor related functions
 */

add_action( 'wp_enqueue_editor', 'siteseo_wp_tiny_mce' );
/**
 * Load extension for wpLink
 *
 * @param  string  $hook  Page hook name
 */
function siteseo_wp_tiny_mce( $hook ){
	$suffix = '';
	wp_enqueue_style( 'siteseo-classic', SITESEO_ASSETS_DIR . '/css/classic-editor' . $suffix . '.css' , [], SITESEO_VERSION );
	wp_enqueue_script( 'siteseo-classic', SITESEO_ASSETS_DIR . '/js/siteseo-classic-editor' . $suffix . '.js' , ['wplink'], SITESEO_VERSION, true );
	wp_localize_script( 'siteseo-classic', 'siteseoI18n', array(
		'sponsored' => __( 'Add <code>rel="sponsored"</code> attribute', 'siteseo' ),
		'nofollow'  => __( 'Add <code>rel="nofollow"</code> attribute', 'siteseo' ),
		'ugc'	   => __( 'Add <code>rel="UGC"</code> attribute', 'siteseo' ),
	) );
}
