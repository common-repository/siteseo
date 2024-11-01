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

namespace SiteSEO\Tags\WooCommerce;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetTagValue;

class PriceValidDate implements GetTagValue {
	const NAME = 'wc_price_valid_date';

	public static function getDescription() {
		return __('Product Date On Sale To', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		if ( ! siteseo_get_service('WooCommerceActivate')->isActive()) {
			return '';
		}

		$value = '';

		if ( ! $context) {
			return $value;
		}

		if ((is_singular(['product']) || $context['is_product']) && isset($context['post']->ID)) {
			$product	= wc_get_product($context['post']->ID);
			$date	   = $product->get_date_on_sale_to();
			if ($date) {
				$value	  = $date->date('m-d-Y');
			}
		}

		return apply_filters('siteseo_get_tag_wc_price_valid_date', $value, $context);
	}
}
