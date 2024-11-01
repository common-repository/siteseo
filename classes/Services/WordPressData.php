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

namespace SiteSEO\Services;

if ( ! defined('ABSPATH')) {
	exit;
}

class WordPressData {
	public function getPostTypes( $return_all = false, $args = array() ) {
		global $wp_post_types;

		$default_args = [
			'show_ui' => true,
			'public'  => true,
		];

		$args = wp_parse_args( $args, $default_args );

		if ( '' === $args['public'] ) {
			unset( $args['public'] );
		}

		$post_types = get_post_types($args, 'objects', 'and');

		if ( ! $return_all ) {
			unset(
				$post_types['attachment'],
				$post_types['siteseo_rankings'],
				$post_types['siteseo_backlinks'],
				$post_types['siteseo_404'],
				$post_types['elementor_library'],
				$post_types['customer_discount'],
				$post_types['cuar_private_file'],
				$post_types['cuar_private_page'],
				$post_types['ct_template']
			);
		}

		$post_types = apply_filters( 'siteseo_post_types', $post_types, $return_all, $args );

		return $post_types;
	}

	public function getTaxonomies($with_terms = false, $return_all = false) {
		$args = [
			'show_ui' => true,
			'public'  => true,
		];
		$args = apply_filters('siteseo_get_taxonomies_args', $args);

		$output	 = 'objects'; // or objects
		$operator   = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies($args, $output, $operator);

		if ( ! $return_all ) {
			unset(
				$taxonomies['siteseo_bl_competitors']
			);
		}

		$taxonomies = apply_filters( 'siteseo_get_taxonomies_list', $taxonomies, $return_all );

		if ( ! $with_terms) {
			return $taxonomies;
		}

		foreach ($taxonomies as $_tax_slug => &$_tax) {
			$_tax->terms = get_terms(['taxonomy' => $_tax_slug]);
		}

		return $taxonomies;
	}
}
