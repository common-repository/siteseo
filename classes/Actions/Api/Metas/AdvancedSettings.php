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

namespace SiteSEO\Actions\Api\Metas;

if (! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooks;

class AdvancedSettings implements ExecuteHooks
{
	public function hooks() {
		register_post_meta( '', '_siteseo_robots_primary_cat',
			[
				'show_in_rest' => true,
				'single'	   => true,
				'type'		 => 'string',
				'auth_callback' => [$this, 'meta_auth']
			]
		);
	}

	/**
	 * Auth callback is required for protected meta keys
	 *
	 * @param   bool	$allowed
	 * @param   string  $meta_key
	 * @param   int	 $id
	 * @return  bool	$allowed
	 */
	public function meta_auth( $allowed, $meta_key, $id ) {
		return current_user_can( 'edit_posts', $id );
	}
}
