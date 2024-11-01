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

namespace SiteSEO\Services\Social;

if ( ! defined('ABSPATH')) {
	exit;
}

class TwitterImageOptionMeta {

	public function getUrl(){
		if (function_exists('is_shop') && is_shop()) {
			$value = get_post_meta(get_option('woocommerce_shop_page_id'), '_siteseo_social_twitter_img', true);
		} else {
			$value = get_post_meta(get_the_ID(), '_siteseo_social_twitter_img', true);
		}

		return $value;
	}

	public function getAttachmentId(){
		if (function_exists('is_shop') && is_shop()) {
			$value = get_post_meta(get_option('woocommerce_shop_page_id'), '_siteseo_social_twitter_img_attachment_id', true);
		} else {
			$value = get_post_meta(get_the_ID(), '_siteseo_social_twitter_img_attachment_id', true);
		}

		return $value;

	}

}
