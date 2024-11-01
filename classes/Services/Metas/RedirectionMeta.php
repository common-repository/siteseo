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

use SiteSEO\Helpers\Metas\RedirectionSettings;

class RedirectionMeta
{
	protected function getKeyValue($meta){
		switch($meta){
			case '_siteseo_redirections_enabled':
				return 'enabled';
			case '_siteseo_redirections_logged_status':
				return 'status';
			case '_siteseo_redirections_type':
				return 'type';
			case '_siteseo_redirections_value':
				return 'value';
		}

		return null;
	}

	protected function getMetaValue($callback, $id, $meta){
		switch($callback){
			case 'get_post_meta':
				return get_post_meta($id, $meta, true);
			case 'get_term_meta':
				return get_term_meta($id, $meta, true);
		}
	}

	public function getPostMetaType($postId){
		return $this->getMetaValue('get_post_meta', $postId, '_siteseo_redirections_type');
	}

	public function getPostMetaStatus($postId){
		return $this->getMetaValue('get_post_meta', $postId, '_siteseo_redirections_logged_status');
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

		$metas = RedirectionSettings::getMetaKeys($id);

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



