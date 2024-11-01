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

namespace SiteSEO\Helpers;

if ( ! defined('ABSPATH')) {
	exit;
}

abstract class ContentAnalysis {
	public static function getData() {
		$data = [
			'all_canonical'=> [
				'title'  => __('Canonical URL', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'schemas'=> [
				'title'  => __('Structured data types', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'old_post'=> [
				'title'  => __('Last modified date', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'words_counter'=> [
				'title'  => __('Words counter', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'keywords_density'=> [
				'title'  => __('Keywords density', 'siteseo'),
				'impact' => null,
				'desc'   => null,
			],
			'keywords_permalink'=> [
				'title'  => __('Keywords in permalink', 'siteseo'),
				'impact' => null,
				'desc'   => null,
			],
			'headings'=> [
				'title'  => __('Headings', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'meta_title'=> [
				'title'  => __('Meta title', 'siteseo'),
				'impact' => null,
				'desc'   => null,
			],
			'meta_desc'=> [
				'title'  => __('Meta description', 'siteseo'),
				'impact' => null,
				'desc'   => null,
			],
			'social'=> [
				'title'  => __('Social meta tags', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'robots'=> [
				'title'  => __('Meta robots', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'img_alt'=> [
				'title'  => __('Alternative texts of images', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'nofollow_links'=> [
				'title'  => __('NoFollow Links', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'outbound_links'=> [
				'title'  => __('Outbound Links', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
			'internal_links'=> [
				'title'  => __('Internal Links', 'siteseo'),
				'impact' => 'good',
				'desc'   => null,
			],
		];

		return apply_filters('siteseo_get_content_analysis_data', $data);
	}
	
	public static function getReadibilityData(){
		$data = [
			'passive_voice'=> [
				'title'  => __('Passive Voice', 'siteseo'),
				'impact' => 'low',
				'desc'   => null,
			],
			'paragraph_length'=> [
				'title'  => __('Paragraph Length', 'siteseo'),
				'impact' => 'low',
				'desc'   => '',
			],
			/* 'consecutive_word' => [
				'title' => __('Consecutive Word', 'siteseo'),
				'impact' => 'low',
				'desc' => null,
			], */
			'power_words' => [
				'title' => __('Power Word in title', 'siteseo'),
				'impact' => 'low',
				'desc' => null,
			],
			'number_found' => [
				'title' => __('Number in title', 'siteseo'),
				'impact' => 'low',
				'desc' => null,
			],
		];

		return apply_filters('siteseo_get_readibility_analysis_data', $data);
	}
}
