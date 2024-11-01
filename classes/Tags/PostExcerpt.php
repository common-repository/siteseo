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

class PostExcerpt implements GetTagValue {
	const NAME = 'post_excerpt';

	const ALIAS = ['wc_single_short_desc'];

	public static function getDescription() {
		return __('Post Excerpt', 'siteseo');
	}

	public function getValue($args = null) {
		$context = isset($args[0]) ? $args[0] : null;
		$value   = '';

		if ( ! $context) {
			return $value;
		}

		if (isset($context['is_404']) && ! $context['is_404'] && ! empty($context['post'])) {
			if (has_excerpt($context['post']->ID)) {
				$value = get_the_excerpt();
			}
		}

		if (empty($value) && isset($context['post']->ID)) {
			$content = get_post_field('post_content', $context['post']->ID, true);
			if ( ! empty($content)) {
				$value = $content;
			}
		}

		$value = wp_trim_words(
			esc_attr(
				stripslashes_deep(
					wp_filter_nohtml_kses(
						wp_strip_all_tags(
							strip_shortcodes($value),
							true
						)
					)
				)
			), siteseo_get_service('TagsToString')->getExcerptLengthForTags()
		);

		return apply_filters('siteseo_get_tag_post_excerpt_value', $value, $context);
	}
}
