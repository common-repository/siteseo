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

namespace SiteSEO\Services\Sitemap\Render;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

class Single {
	const NAME_SERVICE = 'SitemapRenderSingle';

	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	protected function hooksWPMLCompatibility() {
		add_filter('siteseo_sitemaps_single_query', function ($args) {
			global $sitepress, $sitepress_settings;

			$sitepress_settings['auto_adjust_ids'] = 0;
			remove_filter('terms_clauses', [$sitepress, 'terms_clauses']);
			remove_filter('category_link', [$sitepress, 'category_link_adjust_id'], 1);

			return $args;
		});

		add_filter('wpml_get_home_url', 'siteseo_remove_wpml_home_url_filter', 20, 5);
		add_action('the_post', function ($post) {
			$language = apply_filters('wpml_element_language_code', null, [
				  'element_id'   => $post->ID,
				  'element_type' => 'page',
			  ]);
			do_action('wpml_switch_language', $language);
		});


		add_filter('siteseo_sitemaps_single_url', function($url, $post) {
			//Exclude custom canonical from sitemaps
			if (get_post_meta($post->ID, '_siteseo_robots_canonical', true) && get_permalink( $post->ID) !== get_post_meta($post->ID, '_siteseo_robots_canonical', true)) {
				return null;
			}

			//If noindex, continue to next post
			if (get_post_meta($post->ID, '_siteseo_robots_index', true) ==='yes') {
				return null;
			}

			//Exclude hidden languages
			//@credits WPML compatibility team
			if (function_exists('icl_object_id') && defined('ICL_SITEPRESS_VERSION')) { //WPML
				global $sitepress, $sitepress_settings;

				// Check that at least ID is set in post object.
				if ( ! isset( $post->ID ) ) {
					return $url;
				}

				// Get list of hidden languages.
				$hidden_languages = $sitepress->get_setting( 'hidden_languages', array() );

				// If there are no hidden languages return original URL.
				if ( empty( $hidden_languages ) ) {
					return $url;
				}

				// Get language information for post.
				$language_info = $sitepress->post_translations()->get_element_lang_code( $post->ID );

				// If language code is one of the hidden languages return null to skip the post.
				if ( in_array( $language_info, $hidden_languages, true ) ) {
					return null;
				}
			}

			return $url;
		}, 10, 2);
	}

	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	public function render() {
		if ( ! function_exists('siteseo_get_service')) {
			return;
		}

		siteseo_get_service('SitemapHeaders')->printHeaders();

		//Remove primary category
		remove_filter('post_link_category', 'siteseo_titles_primary_cat_hook', 10, 3);

		$this->hooksWPMLCompatibility();

		ob_start();
		include_once SITESEO_TEMPLATE_SITEMAP_DIR . '/single.php';
		$xml = ob_get_contents();
		ob_end_clean();

		echo apply_filters('siteseo_sitemaps_xml_single', $xml); //phpcs:ignore
	}
}
