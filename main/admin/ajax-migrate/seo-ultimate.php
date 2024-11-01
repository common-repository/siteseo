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
/* SEO Ultimate migration
* @since 3.8.2
* @author Softaculous
*/
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_seo_ultimate_migration() {
	siteseo_check_ajax_referer('siteseo_seo_ultimate_migrate_nonce');

	if (current_user_can(siteseo_capability('manage_options', 'migration')) && is_admin()) {
		if (isset($_POST['offset']) && isset($_POST['offset'])) {
			$offset = absint(siteseo_opt_post('offset'));
		}

		global $wpdb;

		$total_count_posts = (int) $wpdb->get_var("SELECT count(*) FROM {$wpdb->posts}");

		$increment = 200;
		global $post;

		if ($offset > $total_count_posts) {
			$offset = 'done';
			wp_reset_query();
		} else {
			$args = [
				'posts_per_page' => $increment,
				'post_type'	  => 'any',
				'post_status'	=> 'any',
				'offset'		 => $offset,
			];

			$su_query = get_posts($args);

			if ($su_query) {
				foreach ($su_query as $post) {
					if ('' != get_post_meta($post->ID, '_su_title', true)) { //Import title tag
						update_post_meta($post->ID, '_siteseo_titles_title', get_post_meta($post->ID, '_su_title', true));
					}
					if ('' != get_post_meta($post->ID, '_su_description', true)) { //Import meta desc
						update_post_meta($post->ID, '_siteseo_titles_desc', get_post_meta($post->ID, '_su_description', true));
					}
					if ('' != get_post_meta($post->ID, '_su_og_title', true)) { //Import Facebook Title
						update_post_meta($post->ID, '_siteseo_social_fb_title', get_post_meta($post->ID, '_su_og_title', true));
					}
					if ('' != get_post_meta($post->ID, '_su_og_description', true)) { //Import Facebook Desc
						update_post_meta($post->ID, '_siteseo_social_fb_desc', get_post_meta($post->ID, '_su_og_description', true));
					}
					if ('' != get_post_meta($post->ID, '_su_og_image', true)) { //Import Facebook Image
						update_post_meta($post->ID, '_siteseo_social_fb_img', get_post_meta($post->ID, '_su_og_image', true));
					}
					if ('1' == get_post_meta($post->ID, '_su_meta_robots_noindex', true)) { //Import Robots NoIndex
						update_post_meta($post->ID, '_siteseo_robots_index', 'yes');
					}
					if ('1' == get_post_meta($post->ID, '_su_meta_robots_nofollow', true)) { //Import Robots NoFollow
						update_post_meta($post->ID, '_siteseo_robots_follow', 'yes');
					}
				}
			}
			$offset += $increment;
		}
		$data		   = [];
		$data['offset'] = $offset;

		$data['total'] = $total_count_posts;

		if ($offset >= $total_count_posts) {
			$data['count'] = $total_count_posts;
		} else {
			$data['count'] = $offset;
		}

		wp_send_json_success($data);
		exit();
	}
}
add_action('wp_ajax_siteseo_seo_ultimate_migration', 'siteseo_seo_ultimate_migration');
