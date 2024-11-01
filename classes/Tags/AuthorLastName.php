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

class AuthorLastName implements GetTagValue {
	const NAME = 'author_last_name';

	public static function getDescription() {
		return __('Author Last Name', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = '';

		if ( ! $context) {
			return $value;
		}

		if ($context['is_single'] && isset($context['post']->post_author)) {
			$value	  = get_the_author_meta('last_name', $context['post']->post_author);
		}

		if ($context['is_author'] && is_int(get_queried_object_id())) {
			$user_info = get_userdata(get_queried_object_id());

			if (isset($user_info)) {
				$value = $user_info->last_name;
			}
		}

		$value = esc_attr($value);

		return apply_filters('siteseo_get_tag_author_last_name_value', $value, $context);
	}
}
