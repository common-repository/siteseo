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

namespace SiteSEO\Thirds\ACF;

if ( ! defined('ABSPATH')) {
	exit;
}
class ContentAnalysisAcfFields
{
	protected $singleFieldTypes  = ['text', 'textarea', 'wysiwyg'];

	protected $complexFieldTypes = ['repeater', 'flexible_content', 'group'];

	/**
	 * @since 4.6.0
	 *
	 * @return array
	 */
	public function getSingleFieldTypes() {
		return apply_filters('siteseo_single_field_types_acf_analysis', $this->singleFieldTypes);
	}

	/**
	 * @since 4.6.0
	 *
	 * @return array
	 */
	public function getComplexeFieldTypes() {
		return apply_filters('siteseo_complex_field_types_acf_analysis', $this->complexFieldTypes);
	}

	/**
	 * @since 4.6.0
	 *
	 * @return string
	 *
	 * @param int $id
	 */
	public function addAcfContent($id) {
		if ( ! function_exists('get_field_objects')) {
			return '';
		}

		$fields = get_field_objects($id);

		$content = '';
		if ($fields) {
			foreach ($fields as $field) {
				$content .= $this->getFieldContent($field, $id);
			}
		}

		return $content;
	}

	/**
	 * @since 4.6.0
	 *
	 * @param int   $id
	 * @param mixed $field
	 *
	 * @return string
	 */
	public function getFieldContent($field, $id) {
		if (in_array($field['type'], $this->getSingleFieldTypes())) {
			return $field['value'] . ' ';
		} else {
			if ( ! in_array($field['type'], $this->getComplexeFieldTypes())) {
				return '';
			}
			if ( ! have_rows($field['name'], $id)) {
				return '';
			}

			$content = '';
			while (have_rows($field['name'], $id)) {
				$row = the_row();
				foreach ($row as $rowFieldKey => $rowField) {
					$subFieldArray = get_sub_field_object($rowFieldKey);
					if ($subFieldArray) {
						$content .= $this->getFieldContent($subFieldArray, $id);
					}
				}
			}

			return $content;
		}

		return '';
	}
}
