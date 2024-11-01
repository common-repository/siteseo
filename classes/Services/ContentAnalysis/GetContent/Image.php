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

class Image
{
	public function getDataByXPath($xpath, $options)
	{
		$data = [];

		$items = $xpath->query('//img');

		foreach ($items as $key => $img) {
			if (! $img->hasAttribute('src')) {
				continue;
			}

			$result = preg_match_all('#\b(avatar)\b#iu', $img->getAttribute('class'), $matches);

			//Exclude avatars from analysis
			if ($result) {
				continue;
			}

			//Exclude images inferior to 1px
			if ($img->hasAttribute('width') || $img->hasAttribute('height')) {
				if ($img->getAttribute('width') <= 1 || $img->getAttribute('height') <= 1) {
					continue;
				}
			}

			//Exclude images inferirot to 100 bytes
			if ( ! function_exists( 'download_url' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			$img_src = download_url($img->getAttribute('src'));
			if (false === is_wp_error($img_src)) {
				if (filesize($img_src) < 100) {
					continue;
				}
				@unlink($img_src);
			}

			$data[$key]['src'] = $img->getAttribute('src');
			$data[$key]['alt'] =  $img->getAttribute('alt');
		}

		return array_values($data);
	}
}
