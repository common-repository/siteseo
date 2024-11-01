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

namespace SiteSEO\Services;

if ( ! defined('ABSPATH')) {
	exit;
}

class VariablesToString {
	const REGEX = "#\[\[(.*?)\]\]#";

	/**
	 * @since 4.5.0
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	public function getVariables($string) {
		if ( ! is_string($string)) {
			return [];
		}

		preg_match_all(self::REGEX, $string, $matches);

		return $matches;
	}

	/**
	 * @since 4.5.0
	 *
	 * @param function $variable
	 * @param array	$context
	 *
	 * @return void
	 */
	public function getValueFromContext($variable, $context= []) {
		if ( ! array_key_exists($variable, $context)) {
			return '';
		}

		return $context[$variable];
	}

	/**
	 * @since 4.5.0
	 *
	 * @param string $string
	 * @param mixed  $context
	 *
	 * @return string
	 */
	public function replace($string, $context = []) {
		$variables = $this->getVariables($string);

		if ( ! array_key_exists(1, $variables)) {
			return $string;
		}

		foreach ($variables[1] as $key => $variable) {
			$value  = $this->getValueFromContext($variable, $context);

			$string = str_replace($variables[0][$key], $value, $string);
		}

		return $string;
	}

	/**
	 * @since 4.5.0
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	protected function removeDataEmpty($data) {
		return array_filter($data);
	}

	/**
	 * @since 4.5.0
	 *
	 * @param array $data
	 * @param array $context
	 * @param mixed $options
	 *
	 * @return array
	 */
	public function replaceDataToString($data, $context = [], $options = []) {
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$data[$key] = $this->replaceDataToString($value, $context, $options);
			} else {
				$data[$key] = $this->replace($value, $context);
			}
		}

		if (isset($options['remove_empty']) && $options['remove_empty']) {
			$data = $this->removeDataEmpty($data);
		}

		return $data;
	}
}
