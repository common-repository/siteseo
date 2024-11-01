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

namespace SiteSEO\JsonSchemas;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Models\GetJsonData;
use SiteSEO\Models\JsonSchemaValue;

class Image extends JsonSchemaValue implements GetJsonData {
	const NAME = 'image';

	protected function getName() {
		return self::NAME;
	}

	/**
	 * @since 4.6.0
	 *
	 * @param array $context
	 *
	 * @return string|array
	 */
	public function getJsonData($context = null) {
		$data = $this->getArrayJson();

		return apply_filters('siteseo_get_json_data_image', $data);
	}
}
