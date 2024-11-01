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

namespace SiteSEO\Constants;

if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Options {
	/**
	 * @since 4.3.0
	 *
	 * @var string
	 */
	const KEY_OPTION_SITEMAP = 'siteseo_xml_sitemap_option_name';

	/**
	 * @since 4.3.0
	 *
	 * @var string
	 */
	const KEY_OPTION_TITLE = 'siteseo_titles_option_name';

	/**
	 * @since 4.5.0
	 *
	 * @var string
	 */
	const KEY_OPTION_SOCIAL = 'siteseo_social_option_name';

	/**
	 * @since 4.6.0
	 *
	 * @var string
	 */
	const KEY_OPTION_ADVANCED = 'siteseo_advanced_option_name';

	/**
	 * @since 6.0.0
	 *
	 * @var string
	 */
	const KEY_OPTION_NOTICE = 'siteseo_notices';

	/**
	 * @since 4.5.0
	 *
	 * @var string
	 */
	const KEY_TOGGLE_OPTION = 'siteseo_toggle';

	/**
	 * @since 5.8.0
	 *
	 * @var string
	 */
	const KEY_OPTION_GOOGLE_ANALYTICS = 'siteseo_google_analytics_option_name';
}
