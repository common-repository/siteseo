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

namespace SiteSEO\Services\Options;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Constants\Options;

class ToggleOption {
	/**
	 * @since 4.3.0
	 *
	 * @return array
	 */
	public function getOption() {
		return get_option(Options::KEY_TOGGLE_OPTION);
	}

	/**
	 * @since 4.3.0
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function searchOptionByKey($key) {
		$data = $this->getOption();

		if (empty($data)) {
			return null;
		}

		$keyComposed = sprintf('toggle-%s', $key);
		if ( ! isset($data[$keyComposed])) {
			return null;
		}

		return $data[$keyComposed];
	}

	/**
	 * @since 4.4.0
	 *
	 * @return string
	 */
	public function getToggleLocalBusiness() {
		return $this->searchOptionByKey('local-business');
	}

	public function getToggleGoogleNews(){
		return $this->searchOptionByKey('news');
	}

	public function getToggleInspectUrl(){
		return $this->searchOptionByKey('inspect-url');
	}

	/**
	 * @since 6.4.0
	 *
	 * @return string
	 */
	public function getToggleAi(){
		return $this->searchOptionByKey('ai');
	}
	
	public function getToggleWhiteLabel(){
		if (is_network_admin() || is_multisite()) {
			return $this->searchOptionByKey('white-label', true);
		}

		return $this->searchOptionByKey('white-label');
	}
}
