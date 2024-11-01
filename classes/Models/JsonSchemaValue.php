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

namespace SiteSEO\Models;

if ( ! defined('ABSPATH')) {
	exit;
}

/**
 * @abstract
 */
abstract class JsonSchemaValue implements GetJsonFromFile {
	abstract protected function getName();

	/**
	 * @since 4.5.0
	 *
	 * @param string $file
	 * @param mixed  $name
	 *
	 * @return string
	 */
	public function getJson() {
		$file = apply_filters('siteseo_get_json_from_file', sprintf('%s/%s.json', SITESEO_TEMPLATE_JSON_SCHEMAS, $this->getName(), '.json'));

		if ( ! file_exists($file)) {
			return '';
		}

		$json = file_get_contents($file);

		return $json;
	}

	/**
	 * @since 4.5.0
	 *
	 * @param string
	 *
	 * @return array
	 */
	public function getArrayJson() {
		$json = $this->getJson();
		try {
			$data = json_decode($json, true);

			return apply_filters('siteseo_schema_get_array_json', $data, $this->getName());
		} catch (\Exception $th) {
			return [];
		}
	}

	/**
	 * @since 4.5.0
	 *
	 * @param array $data
	 *
	 * @return array|string
	 */
	public function renderJson($data) {
		return wp_json_encode($data);
	}

	/**
	 * @since 4.5.0
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function cleanValues($data) {
		return apply_filters('siteseo_schema_clean_values', $data, $this->getName());
	}
}
