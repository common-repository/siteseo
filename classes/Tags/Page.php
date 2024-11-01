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

namespace SiteSEO\Tags;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class Page implements GetTagValue {
	const NAME = 'page';

	public static function getDescription() {
		return __('Page number with context', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		global $wp_query;

		$value = '';

		if ( ! $context) {
			return $value;
		}

		if (isset($wp_query->max_num_pages)) {
			if ($context['paged'] > 1) {
				$currentPage = get_query_var('paged');
			} else {
				$currentPage = 1;
			}

			$value = sprintf(__('Page %d of %2$d', 'siteseo'), $currentPage, $wp_query->max_num_pages);
		}

		return apply_filters('siteseo_get_tag_page_value', $value, $context);
	}
}
