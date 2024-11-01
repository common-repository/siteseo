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

namespace SiteSEO\Actions\Api\Options;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooks;

class ProSettings implements ExecuteHooks {
	/**
	 * Current user ID
	 *
	 * @var int
	 */
	private $current_user = '';

	public function hooks() {
		$this->current_user = wp_get_current_user()->ID;
		add_action('rest_api_init', [$this, 'register']);
	}

	/**
	 * @since 5.5
	 *
	 * @return boolean
	 */
	public function permissionCheck(\WP_REST_Request $request) {
		$nonce = $request->get_header('x-wp-nonce');
		if ( ! wp_verify_nonce($nonce, 'wp_rest')) {
			return false;
		}

		if ( ! user_can( $this->current_user, 'manage_options' )) {
			return false;
		}

		return true;
	}

	/**
	 * @since 5.5
	 *
	 * @return void
	 */
	public function register() {
		register_rest_route('siteseo/v1', '/options/pro-settings', [
			'methods'			 => 'GET',
			'callback'			=> [$this, 'processGet'],
			'permission_callback' => [$this, 'permissionCheck'],
		]);
	}

	/**
	 * @since 5.5
	 */
	public function processGet(\WP_REST_Request $request) {
		$options  = get_option('siteseo_pro_option_name');

		if (empty($options)) {
			return;
		}

		$data = [];

		foreach($options as $key => $value) {
			$data[$key] = $value;
		}

		return new \WP_REST_Response($data);
	}
}
