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

namespace SiteSEO\Actions\Front\Schemas;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooksFrontend;

class PrintHeadJsonSchema implements ExecuteHooksFrontend {
	public function hooks() {
		if (apply_filters('siteseo_old_social_accounts_jsonld_hook', false)) {
			return;
		}

		add_action('wp_head', [$this, 'render'], 2);
	}

	public function render() {
		/**
		 * Check if Social toggle is ON
		 *
		 * @since 5.3
		 * author Softaculous
		 */
		if (siteseo_get_toggle_option('social') !=='1') {
			return;
		}

		/**
		 * Check if is homepage
		 *
		 * @since 5.3
		 * author Softaculous
		 */
		if (!is_front_page()) {
			return;
		}

		if ('none' === siteseo_get_service('SocialOption')->getSocialKnowledgeType()) {
			return;
		}

		$jsons = siteseo_get_service('JsonSchemaGenerator')->getJsonsEncoded([
			'organization'
		]);

		echo wp_kses('<script type="application/ld+json">'.apply_filters('siteseo_schemas_organization_html', $jsons[0]).'</script>', ['script' => ['type' => true]]);
	}
}
