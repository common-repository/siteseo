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

use SiteSEO\Helpers\Metas\RobotSettings;

class RobotMeta
{
	protected function getKeyValue($meta){
		switch($meta){
			case '_siteseo_robots_index':
				return 'noindex';
			case '_siteseo_robots_follow':
				return 'nofollow';
			case '_siteseo_robots_archive':
				return 'noarchive';
			case '_siteseo_robots_snippet':
				return 'nosnippet';
			case '_siteseo_robots_imageindex':
				return 'noimageindex';
			case '_siteseo_robots_canonical':
				return 'canonical';
			case '_siteseo_robots_primary_cat':
				return 'primarycat';
			case '_siteseo_robots_breadcrumbs':
				return 'breadcrumbs';
		}

		return null;
	}

	/**
	 *
	 * @param array $context
	 * @return string|null
	 */
	public function getValue($context)
	{
		$data = [];

		$id = null;

		$callback = 'get_post_meta';
		if(isset($context['post'])){
			$id = $context['post']->ID;
		}
		else if(isset($context['term_id'])){
			$id = $context['term_id'];
			$callback = 'get_term_meta';
		}

		if(!$id){
			return $data;
		}

		$metas = RobotSettings::getMetaKeys($id);

		$data = [];
		foreach ($metas as $key => $value) {
			$name = $this->getKeyValue($value['key']);
			if($name === null){
				continue;
			}
			if ($value['use_default']) {
				$data[$name] = $value['default'];
			} else {
				$result = $callback($id, $value['key'], true);
				$data[$name] = 'checkbox' === $value['type'] ? ($result === true || $result === 'yes' ? true : false) : $result;
			}
		}

		return $data;
	}
}



