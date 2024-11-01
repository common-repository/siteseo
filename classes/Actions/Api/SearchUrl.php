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

namespace SiteSEO\Actions\Api;

if (! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooks;
use SiteSEO\ManualHooks\ApiHeader;

class SearchUrl implements ExecuteHooks
{
	public function hooks()
	{
		add_action('rest_api_init', [$this, 'register']);
	}

	/**
	 *
	 * @return void
	 */
	public function register()
	{
		register_rest_route('siteseo/v1', '/search-url', [
			'methods'			 => 'GET',
			'callback'			=> [$this, 'process'],
			'permission_callback' => '__return_true',
		]);
	}

	public function process(\WP_REST_Request $request)
	{

		$url = $request->get_param('url');

		$data = siteseo_get_service('SearchUrl')->searchByPostName($url);

		return new \WP_REST_Response($data);
	}
}
