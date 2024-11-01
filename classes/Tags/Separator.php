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

class Separator implements GetTagValue {
	const NAME = 'sep';

	const DEFAULT_SEPARATOR = '-';

	public static function getDescription() {
		return __('Separator', 'siteseo');
	}

	public function getValue($args = null) {
		$context   = isset($args[0]) ? $args[0] : null;

		$separator = siteseo_get_service('TitleOption')->getSeparator();
		if (empty($separator)) {
			$separator = self::DEFAULT_SEPARATOR;
		}

		return apply_filters('siteseo_get_tag_separator_value', $separator, $context);
	}
}
