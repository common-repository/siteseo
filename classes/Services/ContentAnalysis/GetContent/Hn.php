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

namespace SiteSEO\Services\ContentAnalysis\GetContent;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

class Hn {
	public function getDataByXPath($xpath, $options) {
		$data = [];
		if ( ! isset($options['hn'])) {
			return $data;
		}

		$items = $xpath->query(sprintf('//%s', $options['hn']));

		foreach ($items as $key => $item) {
			$data[] = $item->nodeValue;
		}

		return $data;
	}
}
