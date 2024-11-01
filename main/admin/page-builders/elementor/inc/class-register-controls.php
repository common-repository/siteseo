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

namespace SiteSeoElementorAddon;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Register_Controls {
	use \SiteSeoElementorAddon\Singleton;

	/**
	 * Initialize class
	 *
	 * @return  void
	 */
	private function _initialize() {
		add_action( 'elementor/controls/register', [ $this, 'register_controls' ] );
	}

	/**
	 * Register controls
	 *
	 * @return  void
	 */
	public function register_controls( $controls_manager ) {
		$controls_manager->register( new \SiteSeoElementorAddon\Controls\Social_Preview_Control() );
		$controls_manager->register( new \SiteSeoElementorAddon\Controls\Text_Letter_Counter_Control() );
		$controls_manager->register( new \SiteSeoElementorAddon\Controls\Content_Analysis_Control() );
		if ( is_plugin_active( 'siteseo-pro/siteseo-pro.php' ) ) {
			$controls_manager->register( new \SiteSeoElementorAddon\Controls\Google_Suggestions_Control() );
		}
	}
}
