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

class SearchUrl
{
	public function searchByPostName($value) {
		global $wpdb;

		$limit   = apply_filters('siteseo_search_url_result_limit', 50);
		if($limit > 200){
			$limit = 200;
		}

		$postTypes = siteseo_get_service('WordPressData')->getPostTypes();

		$postTypes = array_map(function($v) {
			return "'" . esc_sql($v) . "'";
		}, array_keys($postTypes));

		
		$data = $wpdb->get_results($wpdb->prepare("
			SELECT p.id, p.post_title
			FROM $wpdb->posts p
			WHERE (
				p.post_name LIKE %s
				OR p.post_title LIKE %s
			)
			AND p.post_status = 'publish'
			AND p.post_type IN (%s)
			LIMIT %d", '%' . $value . '%', '%' . $value . '%', implode(',',$postTypes), $limit), ARRAY_A); 

		foreach ($data as $key => $value) {
			$data[$key]['guid'] = get_permalink($value['id']);
		}
		return $data;
	}
}
