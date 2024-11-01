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

namespace SiteSEO\Services\ContentAnalysis\GetContent;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

class InternalLinks
{
	public function getDataByXPath($xpath, $options)
	{
		$data = [];

		$permalink = get_permalink((int) $options['id']);

		$args	  = [
			's'		 => $permalink,
			'post_type' => 'any',
		];
		$items = new \WP_Query($args);

		if ($items->have_posts()) {
			while ($items->have_posts()) {
				$items->the_post();
				$post_type_object = get_post_type_object(get_post_type());
				$data[] = [
					"id" => get_the_ID(),
					"edit_post_link" => admin_url(sprintf($post_type_object->_edit_link . '&action=edit', get_the_ID())),
					"url" => get_the_permalink(),
					"value" => get_the_title()
				];
			}
		}

		wp_reset_postdata();

		//Internal links for Oxygen Builder
		if (is_plugin_active('oxygen/functions.php') && function_exists('ct_template_output')) {
			$args	  = [
				'posts_per_page' => -1,
				'meta_query' => [
					[
						'key' => 'ct_builder_shortcodes',
						'value' => $permalink,
						'compare' => 'LIKE'
					]
				],
				'post_type' => 'any',
			];

			$items = new \WP_Query($args);

			if ($items->have_posts()) {
				while ($items->have_posts()) {
					$items->the_post();
					$post_type_object = get_post_type_object(get_post_type());
					$data[] = [
						"id" => get_the_ID(),
						"edit_post_link" => admin_url(sprintf($post_type_object->_edit_link . '&action=edit', get_the_ID())),
						"url" => get_the_permalink(),
						"value" => get_the_title()
					];
				}
			}
			wp_reset_postdata();
		}

		return $data;
	}
}
