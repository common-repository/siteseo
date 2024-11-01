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

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

global $wp_version;
$hook_name = version_compare( $wp_version, '5.8' ) >= 0 ? 'block_categories_all' : 'block_categories';
add_filter( $hook_name, 'siteseo_register_block_categories' );
/**
 * Declares a new category
 *
 * @param   array  $categories  Existing categories
 * @return  array  $categories
 */
function siteseo_register_block_categories( $categories ) {
	return array_merge(
		$categories,[
			[
				'slug'  => 'siteseo',
				'title' => __( 'SiteSEO', 'siteseo' ),
			],
		]
	);
}

/**
 * Register blocks
 */
add_action( 'init', 'siteseo_register_blocks', 1000 );
function siteseo_register_blocks() {
	require_once __DIR__ . '/blocks/faq/block.php';
	require_once __DIR__ . '/blocks/sitemap/block.php';

	// FAQ Block
	siteseo_register_block_faq();

	// Sitemap Block
	register_block_type( __DIR__ . '/blocks/sitemap', [
		'render_callback' => 'siteseo_sitemap_block',
		'attributes' => [
			'postTypes' => [
				'type'	=> 'array',
				'default' => []
			],
			'isSiteMapEnabled' => [
				'type'	=> 'boolean',
				'default' => ( '1' == siteseo_get_toggle_option( 'xml-sitemap' ) ) && ( '1' == siteseo_get_service('SitemapOption')->getHtmlEnable() )
			],
			'optionsPageUrl' => [
				'type'	=> 'string',
				'default' => add_query_arg( 'page', 'siteseo-xml-sitemap', admin_url( 'admin.php' ) )
			],
			'fontSize'		=> [ 'type' => 'string' ],
			'backgroundColor' => [ 'type' => 'string' ],
			'style'		   => [ 'type' => 'object' ],
			'textColor'	   => [ 'type' => 'string' ],
			'gradient'		=> [ 'type' => 'string' ],
			'className'	   => [ 'type' => 'string' ],
		]
	]);
	wp_set_script_translations( 'siteseo/sitemap', 'siteseo' );

	$settings = get_option('siteseo_advanced_option_name', []);
	
	
	if(!empty($settings['breadcrumbs_enable'])){
		// Register Breadcrumbs block
		register_block_type(SITESEO_DIR_PATH . '/main/public/editor/blocks/breadcrumbs/build', [
			'render_callback' => 'siteseo_gutenberg_breadcrumbs',
			'attributes' => [
				'hideHome' => [
					'type' => 'boolean',
					'default' => (!empty($settings) && !empty($settings['breadcrumbs_home']) ? true : false),
				],
				'homeLabel' => [
					'type'    => 'string',
					'default' => (!empty($settings) && !empty($settings['breadcrumb_home_label']) ? esc_html($settings['breadcrumb_home_label']) : esc_html__('Home', 'siteseo')),
				],
				'seperator' => [
					'type' => 'string',
					'default' => function_exists('siteseo_breadcrumbs_seperator') ? siteseo_breadcrumbs_seperator() : '/',
				],
				'prefix' => [
					'type' => 'string',
					'default' => (!empty($settings) && !empty($settings['breadcrumb_prefix']) ? esc_html($settings['breadcrumb_prefix']) : ''),
				],
			]
		]);
		wp_set_script_translations('siteseo/breadcrumbs', 'siteseo');
	}
}

function siteseo_gutenberg_breadcrumbs(){
	return sprintf('<div %s>%s</div>', get_block_wrapper_attributes(), siteseo_render_breadcrumbs());
}