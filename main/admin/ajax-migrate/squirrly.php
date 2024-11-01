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

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Squirrly migration
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_squirrly_migration() {
	siteseo_check_ajax_referer('siteseo_squirrly_migrate_nonce');

	if (current_user_can(siteseo_capability('manage_options', 'migration')) && is_admin()) {
		if (isset($_POST['offset']) && isset($_POST['offset'])) {
			$offset = absint(siteseo_opt_post('offset'));
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'qss';
		$blog_id	= get_current_blog_id();

		$count_query = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE blog_id = %d", $blog_id), ARRAY_A);

		if ( ! empty($count_query)) {
			foreach ($count_query as $value) {
				$post_id = url_to_postid($value['URL']);

				if (0 != $post_id && ! empty($value['seo'])) {
					$seo = maybe_unserialize($value['seo']);

					if ('' != $seo['title']) { //Import title tag
						update_post_meta($post_id, '_siteseo_titles_title', $seo['title']);
					}
					if ('' != $seo['description']) { //Import description tag
						update_post_meta($post_id, '_siteseo_titles_desc', $seo['description']);
					}
					if ('' != $seo['og_title']) { //Import Facebook Title
						update_post_meta($post_id, '_siteseo_social_fb_title', $seo['og_title']);
					}
					if ('' != $seo['og_description']) { //Import Facebook Desc
						update_post_meta($post_id, '_siteseo_social_fb_desc', $seo['og_description']);
					}
					if ('' != $seo['og_media']) { //Import Facebook Image
						update_post_meta($post_id, '_siteseo_social_fb_img', $seo['og_media']);
					}
					if ('' != $seo['tw_title']) { //Import Twitter Title
						update_post_meta($post_id, '_siteseo_social_twitter_title', $seo['tw_title']);
					}
					if ('' != $seo['tw_description']) { //Import Twitter Desc
						update_post_meta($post_id, '_siteseo_social_twitter_desc', $seo['tw_description']);
					}
					if ('' != $seo['tw_media']) { //Import Twitter Image
						update_post_meta($post_id, '_siteseo_social_twitter_img', $seo['tw_media']);
					}
					if (1 === $seo['noindex']) { //Import noindex
						update_post_meta($post_id, '_siteseo_robots_index', 'yes');
					}
					if (1 === $seo['nofollow']) { //Import nofollow
						update_post_meta($post_id, '_siteseo_robots_follow', 'yes');
					}
					if ('' != $seo['canonical']) { //Import canonical
						update_post_meta($post_id, '_siteseo_robots_canonical', $seo['canonical']);
					}
				}
			}
			$offset = 'done';
		}
		$data		   = [];

		$data['offset'] = $offset;
		wp_send_json_success($data);
		exit();
	}
}
add_action('wp_ajax_siteseo_squirrly_migration', 'siteseo_squirrly_migration');
