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

namespace SiteSEO\Services\ContentAnalysis;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

class CountTargetKeywordsUse
{
	public function getCountByKeywords($targetKeywords, $postId = null)
	{
		if(empty($targetKeywords)){
			return [];
		}

		$targetKeywords = array_map('trim', $targetKeywords);

		global $wpdb;


		$query = "SELECT post_id, meta_value
		FROM {$wpdb->postmeta}
		WHERE meta_key = '_siteseo_analysis_target_kw'
		AND meta_value LIKE %s";

		$data = [];

		foreach ($targetKeywords as $key => $keyword) {
			$rows = $wpdb->get_results($wpdb->prepare($query, "%$keyword%"), ARRAY_A);
			$data[] = [
				"key" => $keyword,
				"rows" => array_values(array_filter(array_map(function($row) use ($keyword, $postId) {
					$values = array_map('trim', explode(',', $row['meta_value']));

					if(!in_array($keyword, $values, true) || $postId === $row['post_id']){
						return null;
					}

					return $row['post_id'];
				}, $rows)))
			];
		}

		return $data;

	}
}

