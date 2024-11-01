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

namespace SiteSEO\Services\Metas;

if (! defined('ABSPATH')) {
	exit;
}

class TitleMeta
{
	/**
	 *
	 * @param array $context
	 * @return string|null
	 */
	public function getValue($context)
	{

		$value = null;
		if(isset($context['post'])){
			$id = $context['post']->ID;
			$value = get_post_meta($id, '_siteseo_titles_title', true);
		}

		if(isset($context['term_id'])){
			$id = $context['term_id'];
			$value = get_term_meta($id, '_siteseo_titles_title', true);
		}

		if($value === null){
			return $value;
		}

		return siteseo_get_service('TagsToString')->replace($value, $context);

	}
}


