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

class RequestPreview
{
	public function getLinkRequest($id){
		$args = ['no_admin_bar' => 1];

		//Useful for Page / Theme builders
		$args = apply_filters('siteseo_real_preview_custom_args', $args);


		$link = add_query_arg('no_admin_bar', 1, get_preview_post_link((int) $id, $args));
		$link = apply_filters('siteseo_get_dom_link', $link, $id);

		return $link;
	}

	/**
	 * @param int $id
	 *
	 * @return string
	 */
	public function getDomById($id)
	{
		$args = [
			'redirection' => 2,
			'timeout'		 => 30,
			'sslverify'	   => false,
		];

		//Get cookies
		$cookies = [];
		if (isset($_COOKIE)) {
			foreach ($_COOKIE as $name => $value) {
				if ('PHPSESSID' !== $name) {
					$cookies[] = new \WP_Http_Cookie(['name' => $name, 'value' => $value]);
				}
			}
		}

		if (! empty($cookies)) {
			$args['cookies'] = $cookies;
		}

		$args = apply_filters('siteseo_real_preview_remote', $args);

		$link = $this->getLinkRequest($id);

		try {
			$response = wp_remote_get($link, $args);
			$body	 = wp_remote_retrieve_body($response);

			return $body;
		} catch (\Exception $e) {
			return null;
		}
	}
}
