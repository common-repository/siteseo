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

namespace SiteSEO\Actions\Front;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooksFrontend;
use SiteSEO\ManualHooks\Thirds\WooCommerce\WooCommerceAnalytics;

class GoogleAnalytics implements ExecuteHooksFrontend {
	/**
	 * @since 4.4.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action('siteseo_google_analytics_html', [$this, 'analytics'], 10, 1);
	}

	public function analytics($echo) {
		
		$google_analytics = siteseo_get_service('GoogleAnalyticsOption');
		
		if ('' != $google_analytics->getGA4() && '1' == $google_analytics->getEnableOption()) {
			if (siteseo_get_service('WooCommerceActivate')->isActive()) {
				$woocommerceAnalyticsHook = new WooCommerceAnalytics();
				$woocommerceAnalyticsHook->hooks();
			}
		}
	}
}
