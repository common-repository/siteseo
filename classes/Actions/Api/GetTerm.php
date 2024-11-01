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

class GetTerm implements ExecuteHooks
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
		register_rest_route('siteseo/v1', '/terms/(?P<id>\d+)', [
			'methods'			 => 'GET',
			'callback'			=> [$this, 'processGet'],
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
	 * @param int $id
	 * @return array
	 */
	protected function getData($id, $taxonomy = 'category'){
		$context = siteseo_get_service('ContextPage')->buildContextWithCurrentId($id, ['type' => 'term','taxonomy' => $taxonomy])->getContext();

		$title = siteseo_get_service('TitleMeta')->getValue($context);
		$description = siteseo_get_service('DescriptionMeta')->getValue($context);

		$social = siteseo_get_service('SocialMeta')->getValue($context);
		$robots = siteseo_get_service('RobotMeta')->getValue($context);
		$redirections = siteseo_get_service('RedirectionMeta')->getValue($context);

		$canonical =  '';
		if(isset($robots['canonical'])){
			$canonical = $robots['canonical'];
			unset($robots['canonical']);
		}

		if(isset($robots['primarycat'])){
			unset($robots['primarycat']);
		}

		$breadcrumbs =  '';
		if(isset($robots['breadcrumbs'])){
			$breadcrumbs = $robots['breadcrumbs'];
			unset($robots['breadcrumbs']);
		}

		$data = [
			"title" => $title,
			"description" => $description,
			"canonical" => $canonical,
			"og" => $social['og'],
			"twitter" => $social['twitter'],
			"robots" => $robots,
			"breadcrumbs" => $breadcrumbs,
			"redirections" => $redirections
		];

		return apply_filters('siteseo_headless_get_post', $data, $id, $context);

	}

	/**
	 * @since 5.0.0
	 *
	 * @param \WP_REST_Request $request
	 */
	public function processGet(\WP_REST_Request $request)
	{
		$id	 = $request->get_param('id');
		$taxonomy = $request->get_param('taxonomy');
		if($taxonomy === null){
			$taxonomy = 'category';
		}

		$data = $this->getData($id, $taxonomy);

		return new \WP_REST_Response($data);
	}

}
