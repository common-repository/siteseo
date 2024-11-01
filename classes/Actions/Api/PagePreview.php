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

class PagePreview implements ExecuteHooks
{
	public function hooks()
	{
		add_action('rest_api_init', [$this, 'register']);
	}

	/**
	 * @since 5.0.0
	 *
	 * @return void
	 */
	public function register()
	{
		register_rest_route('siteseo/v1', '/posts/(?P<id>\d+)/page-preview', [
			'methods'			 => 'GET',
			'callback'			=> [$this, 'preview'],
			'args'				=> [
				'id' => [
					'validate_callback' => function ($param, $request, $key) {
						return is_numeric($param);
					},
				],
			],
			'permission_callback' => '__return_true',
		]);
	}

	/**
	 * @since 5.0.0
	 */
	public function preview(\WP_REST_Request $request)
	{
		$apiHeader = new ApiHeader();
		$apiHeader->hooks();

		$id   = (int) $request->get_param('id');
		$str  = siteseo_get_service('RequestPreview')->getDomById($id);
		$data = siteseo_get_service('DomFilterContent')->getData($str, $id);
		if (defined('WP_DEBUG') && WP_DEBUG) {
			$data['analyzed_content'] = siteseo_get_service('DomAnalysis')->getPostContentAnalyze($id);
			$data['analyzed_content_id'] = $id;
		}

		$data['analysis_target_kw'] = [
			'value' => array_filter(explode(',', strtolower(get_post_meta($id, '_siteseo_analysis_target_kw', true))))
		];

		return new \WP_REST_Response($data);
	}
}
