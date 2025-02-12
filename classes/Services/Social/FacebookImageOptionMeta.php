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

class FacebookImageOptionMeta {

	public function getUrl(){
		if (function_exists('is_shop') && is_shop()) {
			$value = get_post_meta(get_option('woocommerce_shop_page_id'), '_siteseo_social_fb_img', true);
		} else {
			$value = get_post_meta(get_the_ID(), '_siteseo_social_fb_img', true);
		}

		if(empty($value) &&  '1' === siteseo_get_service('SocialOption')->getSocialFacebookImgDefault() ){
			$options = get_option('siteseo_social_option_name');
			$value = isset($options['social_facebook_img']) ? $options['social_facebook_img'] : null;
		}

		return $value;
	}

	public function getAttachmentId(){
		if (function_exists('is_shop') && is_shop()) {
			$value = get_post_meta(get_option('woocommerce_shop_page_id'), '_siteseo_social_fb_img_attachment_id', true);
		} else {
			$value = get_post_meta(get_the_ID(), '_siteseo_social_fb_img_attachment_id', true);
		}

		if(empty($value) &&  '1' === siteseo_get_service('SocialOption')->getSocialFacebookImgDefault() && empty(get_post_meta(get_the_ID(), '_siteseo_social_fb_img', true)) ){
			$options = get_option('siteseo_social_option_name');
			$value = isset($options['siteseo_social_facebook_img_attachment_id']) ? $options['siteseo_social_facebook_img_attachment_id'] : null;
		}

		return $value;

	}


	public function getMetasBy($strategy = 'url'){

		if($strategy === 'url'){
			$url = $this->getUrl();

			if(empty($url)){
				return '';
			}

			return $this->getMetasByUrl($url);
		}

		else if($strategy === 'id'){
			$id = $this->getAttachmentId();

			if(empty($id) || $id === null){
				return $this->getMetasBy('url');
			}

			return $this->getMetasStringByAttachmentId($id);
		}

		return '';
	}

	public function getMetasByUrl($url){
		$str = '';
		if (!function_exists('attachment_url_to_postid')) {
			return $str;
		}

		$postId = attachment_url_to_postid($url);

		if(empty($postId) && !empty($url)){
			return $this->getMetasStringByUrl($url);
		}

		return $this->getMetasStringByAttachmentId($postId);
	}


	public function getMetasStringByUrl($url){
		$str = '';

		//OG:IMAGE
		$str = '';
		$str .= '<meta property="og:image" content="' . esc_attr($url) . '" />';
		$str .= "\n";

		//OG:IMAGE:SECURE_URL IF SSL
		if (is_ssl()) {
			$str .= '<meta property="og:image:secure_url" content="' . esc_attr($url) . '" />';
			$str .= "\n";
		}

		return $str;

	}

	public function getMetasStringByAttachmentId($postId){
		$str = '';

		$imageSrc = wp_get_attachment_image_src($postId, 'full');

		if(empty($imageSrc)){
			return $str;
		}

		$url = $imageSrc[0];

		//If cropped image
		if (0 != $postId) {
			$dir  = wp_upload_dir();
			$path = $url;
			if (0 === strpos($path, $dir['baseurl'] . '/')) {
				$path = substr($path, strlen($dir['baseurl'] . '/'));
			}

			if (preg_match('/^(.*)(\-\d*x\d*)(\.\w{1,})/i', $path, $matches) && function_exists('attachment_url_to_postid')) {
				$url	 = $dir['baseurl'] . '/' . $matches[1] . $matches[3];
				$postId = attachment_url_to_postid($url);
			}
		}


		//OG:IMAGE
		$str = '';
		$str .= '<meta property="og:image" content="' . $url . '" />';
		$str .= "\n";

		//OG:IMAGE:SECURE_URL IF SSL
		if (is_ssl()) {
			$str .= '<meta property="og:image:secure_url" content="' . $url . '" />';
			$str .= "\n";
		}

		//OG:IMAGE:WIDTH + OG:IMAGE:HEIGHT
		if ( ! empty($imageSrc)) {
			$str .= '<meta property="og:image:width" content="' . $imageSrc[1] . '" />';
			$str .= "\n";
			$str .= '<meta property="og:image:height" content="' . $imageSrc[2] . '" />';
			$str .= "\n";
		}

		//OG:IMAGE:ALT
		$alt = get_post_meta($postId, '_wp_attachment_image_alt', true);
		if (!empty($alt)) {
			$str .= '<meta property="og:image:alt" content="' . esc_attr(get_post_meta($postId, '_wp_attachment_image_alt', true)) . '" />';
			$str .= "\n";
		}

		return $str;

	}
}
