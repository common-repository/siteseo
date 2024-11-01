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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Get real preview + content analysis
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_do_real_preview()
{
	siteseo_check_ajax_referer('siteseo_real_preview_nonce');

	if (!current_user_can('edit_posts') || !is_admin()) {
		return;
	}
	
	$docs = siteseo_get_docs_links();

	// Get cookies
	if (isset($_COOKIE)) {
		$cookies = [];

		foreach ($_COOKIE as $name => $value) {
			if ('PHPSESSID' !== $name) {
				$cookies[] = new WP_Http_Cookie(['name' => $name, 'value' => $value]);
			}
		}
	}

	// Get post id
	if (isset($_GET['post_id'])) {
		$siteseo_get_the_id = siteseo_opt_get('post_id');
	}

	if ('yes' == get_post_meta($siteseo_get_the_id, '_siteseo_redirections_enabled', true)) {
		$data['title'] = __('A redirect is active for this URL. Turn it off to get the Google preview and content analysis.', 'siteseo');
	} else {
		//Get cookies
		if (isset($_COOKIE)) {
			$cookies = [];

			foreach ($_COOKIE as $name => $value) {
				if ('PHPSESSID' !== $name) {
					$cookies[] = new WP_Http_Cookie(['name' => $name, 'value' => $value]);
				}
			}
		}

		//Get post type
		if (isset($_GET['post_type'])) {
			$siteseo_get_post_type = siteseo_opt_get('post_type');
		} else {
			$siteseo_get_post_type = null;
		}

		//Origin
		if (isset($_GET['origin'])) {
			$siteseo_origin = siteseo_opt_get('origin');
		}

		//Tax name
		if (isset($_GET['tax_name'])) {
			$siteseo_tax_name = siteseo_opt_get('tax_name');
		}

		//Init
		$title		= '';
		$meta_desc	= '';
		$link		= '';
		$data		= [];

		//Save Target KWs
		if (! isset($_GET['is_elementor'])) {
			if (isset($_GET['siteseo_analysis_target_kw'])) {
				delete_post_meta($siteseo_get_the_id, '_siteseo_analysis_target_kw');
				update_post_meta($siteseo_get_the_id, '_siteseo_analysis_target_kw', siteseo_opt_get('siteseo_analysis_target_kw') );
			}
		}

		//Fix Elementor
		if (isset($_GET['is_elementor']) && true == $_GET['is_elementor']) {
			$_GET['siteseo_analysis_target_kw'] = get_post_meta($siteseo_get_the_id, '_siteseo_analysis_target_kw', true);
		}

		//DOM
		$dom					= new DOMDocument();
		$internalErrors			= libxml_use_internal_errors(true);
		$dom->preserveWhiteSpace = false;

		//Get source code
		$args = [
			'blocking'	=> true,
			'timeout'	 => 30,
			'sslverify'   => false,
		];

		if (isset($cookies) && ! empty($cookies)) {
			$args['cookies'] = $cookies;
		}
		$args = apply_filters('siteseo_real_preview_remote', $args);

		$data['title'] = $cookies;

		if ('post' == $siteseo_origin) { //Default: post type
			//Oxygen compatibility
			if (is_plugin_active('oxygen/functions.php') && function_exists('ct_template_output')) {
				$link = get_permalink((int) $siteseo_get_the_id);
				$link = add_query_arg('no_admin_bar', 1, $link);

				$response = wp_remote_get($link, $args);
				if (200 !== wp_remote_retrieve_response_code($response)) {
					$link = get_permalink((int) $siteseo_get_the_id);
					$response = wp_remote_get($link, $args);
				}
			} else {
				$custom_args = ['no_admin_bar' => 1];

				//Useful for Page / Theme builders
				$custom_args = apply_filters('siteseo_real_preview_custom_args', $custom_args);

				$link = add_query_arg('no_admin_bar', 1, get_preview_post_link((int) $siteseo_get_the_id, $custom_args));

				$link = apply_filters('siteseo_get_dom_link', $link, $siteseo_get_the_id);

				$response = wp_remote_get($link, $args);
			}
		} else { //Term taxonomy
			$link = get_term_link((int) $siteseo_get_the_id, $siteseo_tax_name);
			$response = wp_remote_get($link, $args);
		}

		//Check for error
		if (is_wp_error($response) || '404' == wp_remote_retrieve_response_code($response)) {
			$data['title'] = __('To get your Google snippet preview, publish your post!', 'siteseo');
		} elseif (is_wp_error($response) || '401' == wp_remote_retrieve_response_code($response)) {
			$data['title']				   = sprintf(__('Your site is protected by an authentication. <a href="%s" target="_blank">Fix this</a> <span class="dashicons dashicons-external"></span>', 'siteseo'), $docs['google_preview']['authentification']);
		} else {
			$response = wp_remote_retrieve_body($response);

			if ($dom->loadHTML('<?xml encoding="utf-8" ?>' . $response)) {
				if (is_plugin_active('oxygen/functions.php') && function_exists('ct_template_output')) {
					$data = get_post_meta($siteseo_get_the_id, '_siteseo_analysis_data', true) ? get_post_meta($siteseo_get_the_id, '_siteseo_analysis_data', true) : $data = [];

					if (! empty($data)) {
						$data = array_slice($data, 0, 3);
					}
				}

				$data['link_preview'] = $link;

				//Disable wptexturize
				add_filter('run_wptexturize', '__return_false');

				//Get post content (used for Words counter)
				$siteseo_get_the_content = get_post_field('post_content', $siteseo_get_the_id);
				$siteseo_get_the_content = apply_filters('siteseo_dom_analysis_get_post_content', $siteseo_get_the_content);

				//Cornerstone compatibility
				if (is_plugin_active('cornerstone/cornerstone.php')) {
					$siteseo_get_the_content = get_post_field('post_content', $siteseo_get_the_id);
				}

				//ThriveBuilder compatibility
				if (is_plugin_active('thrive-visual-editor/thrive-visual-editor.php') && empty($siteseo_get_the_content)) {
					$siteseo_get_the_content = get_post_meta($siteseo_get_the_id, 'tve_updated_post', true);
				}

				//Zion Builder compatibility
				if (is_plugin_active('zionbuilder/zionbuilder.php')) {
					$siteseo_get_the_content = $siteseo_get_the_content . get_post_meta($siteseo_get_the_id, '_zionbuilder_page_elements', true);
				}

				//BeTheme is activated
				$theme = wp_get_theme();
				if ('betheme' == $theme->template || 'Betheme' == $theme->parent_theme) {
					$siteseo_get_the_content = $siteseo_get_the_content . get_post_meta($siteseo_get_the_id, 'mfn-page-items-seo', true);
				}

				//Themify compatibility
				if (defined('THEMIFY_DIR') && method_exists('ThemifyBuilder_Data_Manager', '_get_all_builder_text_content')) {
					global $ThemifyBuilder;
					$builder_data = $ThemifyBuilder->get_builder_data($siteseo_get_the_id);
					$plain_text   = \ThemifyBuilder_Data_Manager::_get_all_builder_text_content($builder_data);
					$plain_text   = do_shortcode($plain_text);

					if ('' != $plain_text) {
						$siteseo_get_the_content = $plain_text;
					}
				}

				//Add WC product excerpt
				if ('product' == $siteseo_get_post_type) {
					$siteseo_get_the_content =  $siteseo_get_the_content . get_the_excerpt($siteseo_get_the_id);
				}

				$siteseo_get_the_content = apply_filters('siteseo_content_analysis_content', $siteseo_get_the_content, $siteseo_get_the_id);

				if (defined('WP_DEBUG') && WP_DEBUG === true) {
					$data['analyzed_content'] = $siteseo_get_the_content;
				}

				//Bricks compatibility
				if (defined('BRICKS_DB_EDITOR_MODE') && ('bricks' == $theme->template || 'Bricks' == $theme->parent_theme)) {
					$page_sections = get_post_meta($siteseo_get_the_id, BRICKS_DB_PAGE_CONTENT, true);
					$editor_mode   = get_post_meta($siteseo_get_the_id, BRICKS_DB_EDITOR_MODE, true);

					if (is_array($page_sections) && 'wordpress' !== $editor_mode) {
						$siteseo_get_the_content = Bricks\Frontend::render_data($page_sections);
					}
				}

				//Get Target Keywords
				if (isset($_GET['siteseo_analysis_target_kw']) && ! empty($_GET['siteseo_analysis_target_kw'])) {
					$data['target_kws'] = strtolower(siteseo_opt_get('siteseo_analysis_target_kw'));
					$siteseo_analysis_target_kw = array_filter(explode(',', strtolower(get_post_meta($siteseo_get_the_id, '_siteseo_analysis_target_kw', true))));

					$siteseo_analysis_target_kw = apply_filters( 'siteseo_content_analysis_target_keywords', $siteseo_analysis_target_kw, $siteseo_get_the_id );

					$data['target_kws_count'] = siteseo_get_service('CountTargetKeywordsUse')->getCountByKeywords($siteseo_analysis_target_kw, $siteseo_get_the_id);
				}

				$xpath = new DOMXPath($dom);

				//Title
				$list = $dom->getElementsByTagName('title');
				if ($list->length > 0) {
					$title		 = $list->item(0)->textContent;
					$data['title'] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($title)));
					if (isset($_GET['siteseo_analysis_target_kw']) && ! empty($_GET['siteseo_analysis_target_kw'])) {
						foreach ($siteseo_analysis_target_kw as $kw) {
							if (preg_match_all('#\b(' . $kw . ')\b#iu', $data['title'], $m)) {
								$data['meta_title']['matches'][$kw][] = $m[0];
							}
						}
					}
				}
				
				$redability_data = [];
				$redability_data = siteseo_do_redability_analysis($siteseo_get_the_content, $title);

				update_post_meta($siteseo_get_the_id, '_siteseo_readibility_data', $redability_data);

				//Meta desc
				$meta_description = $xpath->query('//meta[@name="description"]/@content');

				foreach ($meta_description as $key=>$mdesc) {
					$data['meta_desc'] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses(wp_strip_all_tags($mdesc->nodeValue))));
				}

				if (isset($_GET['siteseo_analysis_target_kw']) && ! empty($_GET['siteseo_analysis_target_kw'])) {
					if (! empty($meta_description)) {
						foreach ($meta_description as $meta_desc) {
							foreach ($siteseo_analysis_target_kw as $kw) {
								if (preg_match_all('#\b(' . $kw . ')\b#iu', $meta_desc->nodeValue, $m)) {
									$data['meta_description']['matches'][$kw][] = $m[0];
								}
							}
						}
					}
				}

				//OG:title
				$og_title = $xpath->query('//meta[@property="og:title"]/@content');

				if (! empty($og_title)) {
					$data['og_title']['count'] = count($og_title);
					foreach ($og_title as $key=>$mogtitle) {
						$data['og_title']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mogtitle->nodeValue)));
					}
				}

				//OG:description
				$og_desc = $xpath->query('//meta[@property="og:description"]/@content');

				if (! empty($og_desc)) {
					$data['og_desc']['count'] = count($og_desc);
					foreach ($og_desc as $key=>$mog_desc) {
						$data['og_desc']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mog_desc->nodeValue)));
					}
				}

				//OG:image
				$og_img = $xpath->query('//meta[@property="og:image"]/@content');

				if (! empty($og_img)) {
					$data['og_img']['count'] = count($og_img);
					foreach ($og_img as $key=>$mog_img) {
						$data['og_img']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mog_img->nodeValue)));
					}
				}

				//OG:url
				$og_url = $xpath->query('//meta[@property="og:url"]/@content');

				if (! empty($og_url)) {
					$data['og_url']['count'] = count($og_url);
					foreach ($og_url as $key=>$mog_url) {
						$url						= esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mog_url->nodeValue)));
						$data['og_url']['values'][] = $url;
						$url						= wp_parse_url($url);
						$data['og_url']['host']	 = $url['host'];
					}
				}

				//OG:site_name
				$og_site_name = $xpath->query('//meta[@property="og:site_name"]/@content');

				if (! empty($og_site_name)) {
					$data['og_site_name']['count'] = count($og_site_name);
					foreach ($og_site_name as $key=>$mog_site_name) {
						$data['og_site_name']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mog_site_name->nodeValue)));
					}
				}

				//Twitter:title
				$tw_title = $xpath->query('//meta[@name="twitter:title"]/@content');

				if (! empty($tw_title)) {
					$data['tw_title']['count'] = count($tw_title);
					foreach ($tw_title as $key=>$mtw_title) {
						$data['tw_title']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mtw_title->nodeValue)));
					}
				}

				//Twitter:description
				$tw_desc = $xpath->query('//meta[@name="twitter:description"]/@content');

				if (! empty($tw_desc)) {
					$data['tw_desc']['count'] = count($tw_desc);
					foreach ($tw_desc as $key=>$mtw_desc) {
						$data['tw_desc']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mtw_desc->nodeValue)));
					}
				}

				//Twitter:image
				$tw_img = $xpath->query('//meta[@name="twitter:image"]/@content');

				if (! empty($tw_img)) {
					$data['tw_img']['count'] = count($tw_img);
					foreach ($tw_img as $key=>$mtw_img) {
						$data['tw_img']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mtw_img->nodeValue)));
					}
				}

				//Twitter:image:src
				$tw_img = $xpath->query('//meta[@name="twitter:image:src"]/@content');

				if (! empty($tw_img)) {
					$count = null;
					if (! empty($data['tw_img']['count'])) {
						$count = $data['tw_img']['count'];
					}

					$data['tw_img']['count'] = count($tw_img) + $count;

					foreach ($tw_img as $key=>$mtw_img) {
						$data['tw_img']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mtw_img->nodeValue)));
					}
				}

				//Canonical
				$canonical = $xpath->query('//link[@rel="canonical"]/@href');

				foreach ($canonical as $key=>$mcanonical) {
					$data['canonical'] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mcanonical->nodeValue)));
				}

				foreach ($canonical as $key=>$mcanonical) {
					$data['all_canonical'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mcanonical->nodeValue)));
				}

				//h1
				$h1 = $xpath->query('//h1');
				if (! empty($h1)) {
					$data['h1']['nomatches']['count'] = count($h1);
					if (isset($_GET['siteseo_analysis_target_kw']) && ! empty($_GET['siteseo_analysis_target_kw'])) {
						foreach ($h1 as $heading1) {
							foreach ($siteseo_analysis_target_kw as $kw) {
								if (preg_match_all('#\b(' . $kw . ')\b#iu', $heading1->nodeValue, $m)) {
									$data['h1']['matches'][$kw][] = $m[0];
								}
							}
							$data['h1']['values'][] = esc_attr($heading1->nodeValue);
						}
					}
				}

				if (isset($_GET['siteseo_analysis_target_kw']) && ! empty($_GET['siteseo_analysis_target_kw'])) {
					//h2
					$h2 = $xpath->query('//h2');
					if (! empty($h2)) {
						foreach ($h2 as $heading2) {
							foreach ($siteseo_analysis_target_kw as $kw) {
								if (preg_match_all('#\b(' . $kw . ')\b#iu', $heading2->nodeValue, $m)) {
									$data['h2']['matches'][$kw][] = $m[0];
								}
							}
						}
					}

					//h3
					$h3 = $xpath->query('//h3');
					if (! empty($h3)) {
						foreach ($h3 as $heading3) {
							foreach ($siteseo_analysis_target_kw as $kw) {
								if (preg_match_all('#\b(' . $kw . ')\b#iu', $heading3->nodeValue, $m)) {
									$data['h3']['matches'][$kw][] = $m[0];
								}
							}
						}
					}

					//Keywords density
					if (! is_plugin_active('oxygen/functions.php') && ! function_exists('ct_template_output')) { //disable for Oxygen
						foreach ($siteseo_analysis_target_kw as $kw) {
							if (preg_match_all('#\b(' . $kw . ')\b#iu', stripslashes_deep(wp_strip_all_tags($siteseo_get_the_content)), $m)) {
								$data['kws_density']['matches'][$kw][] = $m[0];
							}
						}
					}

					//Keywords in permalink
					$post	= get_post($siteseo_get_the_id);
					$kw_slug = urldecode($post->post_name);

					if (is_plugin_active('permalink-manager-pro/permalink-manager.php')) {
						global $permalink_manager_uris;
						$kw_slug = urldecode($permalink_manager_uris[$siteseo_get_the_id]);
					}

					$kw_slug = str_replace('-', ' ', $kw_slug);

					if (isset($kw_slug)) {
						foreach ($siteseo_analysis_target_kw as $kw) {
							if (preg_match_all('#\b(' . remove_accents($kw) . ')\b#iu', strip_tags($kw_slug), $m)) {
								$data['kws_permalink']['matches'][$kw][] = $m[0];
							}
						}
					}
				}

				//Images
				/*Standard images*/
				$imgs = $xpath->query('//img');

				if (! empty($imgs) && null != $imgs) {
					//init
					$img_without_alt = [];
					$img_with_alt = [];
					foreach ($imgs as $img) {
						if ($img->hasAttribute('src')) {
							if (! preg_match_all('#\b(avatar)\b#iu', $img->getAttribute('class'), $m)) {//Exclude avatars from analysis
								if ($img->hasAttribute('width') || $img->hasAttribute('height')) {
									if ($img->getAttribute('width') > 1 || $img->getAttribute('height') > 1) {//Ignore files with width and heigh <= 1
										if ('' === $img->getAttribute('alt') || ! $img->hasAttribute('alt')) {//if alt is empty or doesn't exist
											$img_without_alt[] .= $img->getAttribute('src');
										} else {
											$img_with_alt[] .= $img->getAttribute('src');
										}
									}
								} elseif ('' === $img->getAttribute('alt') || ! $img->hasAttribute('alt')) {//if alt is empty or doesn't exist
									$img_src = download_url($img->getAttribute('src'));
									if (false === is_wp_error($img_src)) {
										if (filesize($img_src) > 100) {//Ignore files under 100 bytes
											$img_without_alt[] .= $img->getAttribute('src');
										} else {
											$img_with_alt[] .= $img->getAttribute('src');
										}
										@unlink($img_src);
									}
								}
							}
						}
						$data['img']['images']['without_alt'] = $img_without_alt;
						$data['img']['images']['with_alt'] = $img_with_alt;
					}
				}

				//Meta robots
				$meta_robots = $xpath->query('//meta[@name="robots"]/@content');
				if (! empty($meta_robots)) {
					foreach ($meta_robots as $key=>$value) {
						$data['meta_robots'][$key][] = esc_attr($value->nodeValue);
					}
				}

				//nofollow links
				$nofollow_links = $xpath->query("//a[contains(@rel, 'nofollow') and not(contains(@rel, 'ugc'))]");
				if (! empty($nofollow_links)) {
					foreach ($nofollow_links as $key=>$link) {
						if (! preg_match_all('#\b(cancel-comment-reply-link)\b#iu', $link->getAttribute('id'), $m) && ! preg_match_all('#\b(comment-reply-link)\b#iu', $link->getAttribute('class'), $m)) {
							$data['nofollow_links'][$key][$link->getAttribute('href')] = esc_attr($link->nodeValue);
						}
					}
				}
			}

			// outbound links
			$site_url	   = wp_parse_url(get_home_url(), PHP_URL_HOST);
			$outbound_links = $xpath->query("//a[not(contains(@href, '" . $site_url . "'))]");
			if (! empty($outbound_links)) {
				foreach ($outbound_links as $key=>$link) {
					if (! empty(wp_parse_url($link->getAttribute('href'), PHP_URL_HOST))) {
						$data['outbound_links'][$key][$link->getAttribute('href')] = esc_attr($link->nodeValue);
					}
				}
			}

			// Internal links
			$permalink = get_permalink((int) $siteseo_get_the_id);
			$args	  = [
				's'		 => $permalink,
				'post_type' => 'any',
			];
			$internal_links = new WP_Query($args);

			if ($internal_links->have_posts()) {
				$data['internal_links']['count'] = $internal_links->found_posts;

				while ($internal_links->have_posts()) {
					$internal_links->the_post();
					$data['internal_links']['links'][get_the_ID()] = [get_the_permalink() => get_the_title()];
				}
			}
			wp_reset_postdata();

			//Internal links for Oxygen Builder
			if (is_plugin_active('oxygen/functions.php') && function_exists('ct_template_output')) {
				$args	  = [
					'posts_per_page' => -1,
					'meta_query' => [
						[
							'key' => 'ct_builder_shortcodes',
							'value' => $permalink,
							'compare' => 'LIKE'
						]
					],
					'post_type' => 'any',
				];

				$internal_links = new WP_Query($args);

				if ($internal_links->have_posts()) {
					$data['internal_links']['count'] = $internal_links->found_posts;

					while ($internal_links->have_posts()) {
						$internal_links->the_post();
						$data['internal_links']['links'][get_the_ID()] = [get_the_permalink() => get_the_title()];
					}
				}
				wp_reset_postdata();
			}

			//Words Counter
			if (! is_plugin_active('oxygen/functions.php') && ! function_exists('ct_template_output')) { //disable for Oxygen
				if ('' != $siteseo_get_the_content) {
					$data['words_counter'] = preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u", normalize_whitespace(wp_strip_all_tags($siteseo_get_the_content)), $matches);

					if (! empty($matches[0])) {
						$words_counter_unique = count(array_unique($matches[0]));
					} else {
						$words_counter_unique = '0';
					}
					$data['words_counter_unique'] = $words_counter_unique;
				}
			}

			//Get schemas
			$json_ld = $xpath->query('//script[@type="application/ld+json"]');
			if (! empty($json_ld)) {
				foreach ($json_ld as $node) {
					$json = json_decode($node->nodeValue, true);
					if (isset($json['@type'])) {
						$data['json'][] = $json['@type'];
					}
				}
			}
		}

		libxml_use_internal_errors($internalErrors);
	}

	//Send data
	if (isset($data)) {
		//Oxygen builder
		if (get_post_meta($siteseo_get_the_id, '_siteseo_analysis_data_oxygen', true)) {
			$data2 = get_post_meta($siteseo_get_the_id, '_siteseo_analysis_data_oxygen', true);
			$data  = $data + $data2;
		}
		update_post_meta($siteseo_get_the_id, '_siteseo_analysis_data', $data);
	}

	//Re-enable QM
	remove_filter('user_has_cap', 'siteseo_disable_qm', 10, 3);

	// Return
	wp_send_json_success($data);
}
add_action('wp_ajax_siteseo_do_real_preview', 'siteseo_do_real_preview');


function siteseo_do_redability_analysis($post, $title){

	$data = [];

	// These are power words specifically for headlines.
	// These are not hard rules, but they are perceived to have a higher CTR if used in the heading.
	$power_words = ['exclusive', 'revealed', 'secrets', 'ultimate', 'proven', 'essential', 'unleashed', 'discover', 'breakthrough', 'shocking', 'insider', 'elite', 'uncovered', 'powerful', 'guaranteed', 'transformative', 'instant', 'revolutionary', 'unbelievable', 'top', 'best', 'must-have', 'limited', 'special', 'rare', 'unique', 'unprecedented', 'premium', 'urgent', 'exclusive', 'today', 'now', 'latest', 'new', 'free', 'bonus', 'offer', 'sensational', 'astonishing', 'incredible', 'jaw-dropping', 'unmissable', 'essential', 'critical', 'vital', 'pivotal', 'game-changer', 'spotlight', 'trending', 'hot', 'popular', 'featured', 'special', 'limited-time', 'hurry', 'last chance', 'countdown'];
	
	if(!empty($title)){
		// Checking power words.
		$title_words = explode(' ', strtolower($title));

		$present_power_words = array_intersect($title_words, $power_words);

		if(!empty($present_power_words)){
			$data['power_words'] = $present_power_words;
		}

		// Checking number in the Title
		if(preg_match('/\s\d+\s/', preg_quote($title), $number)){
			$data['number_found'] = $number[0];
		}
	}
	
	// We are cheching paragarph lenght too.
	if(!isset($data['paragraph_length'])){
		$data['paragraph_length'] = 0;
	}
	
	if(!empty($post)){
		preg_match_all('/<p>.*<\/p>/U', $post, $paragraphs);
		
		foreach($paragraphs[0] as $paragraph){
			$paragraph = normalize_whitespace(wp_strip_all_tags($paragraph));
			
			$data['paragraph_length'] += substr_count($paragraph, ' ') + 1; // updating paragraph length
			siteseo_analyse_passive_voice($paragraph, $data);
		}
	}

	return $data;
}

function siteseo_analyse_passive_voice($paragraph, &$data){
	
	if(empty($paragraph)){
		return;
	}

	$sentences = explode('.', $paragraph);
	$passive_count = 0;

	if(!isset($data['passive_voice']['passive_sentences'])){
		$data['passive_voice']['passive_sentences'] = 0;
	}
	
	if(!isset($data['passive_voice']['total_sentences'])){
		$data['passive_voice']['total_sentences'] = 0;
	}

	if(empty($sentences)){
		return;
	}

	foreach($sentences as $sentence){
		if(empty($sentence)){
			continue;
		}

		$sentence = normalize_whitespace($sentence);
		$is_passive = siteseo_sentence_is_passive($sentence);
		
		if($is_passive == true){
			$passive_count++;
		}
	}

	$data['passive_voice']['passive_sentences'] += $passive_count;
	$data['passive_voice']['total_sentences'] += count($sentences);
}

function siteseo_sentence_is_passive($sentence){
	$be_words = ['am', 'is', 'are', 'was', 'were', 'be', 'being', 'been'];

	// TODO: We can check if "en" ending words are a comman pattern too, then we will remove the en ending words too from here.
	$past_particles = ['gone' ,'done' ,'seen' ,'taken' ,'eaten' ,'written' ,'driven' ,'spoken' ,'broken' ,'chosen' ,'fallen' ,'forgotten' ,'forgiven' ,'hidden' ,'known' ,'grown' ,'drawn' ,'flown' ,'thrown' ,'blown' ,'shown' ,'worn' ,'sworn' ,'torn' ,'woken' ,'begun' ,'sung' ,'run' ,'swum' ,'shaken' ,'given' ,'proven' ,'ridden' ,'risen' ,'shone' ,'shot' ,'fought' ,'thought' ,'bought' ,'brought' ,'caught' ,'taught' ,'built' ,'felt' ,'kept' ,'slept' ,'left' ,'lost' ,'meant' ,'met' ,'read' ,'sold' ,'sent' ,'spent' ,'stood' ,'understood' ,'won' ,'held' ,'told' ,'heard' ,'paid' ,'laid' ,'said' ,'found' ,'made' ,'learned' ,'put'];
	
	if(empty($sentence)){
		return false;
	}
	
	$words = explode(' ', $sentence);

	for($i = 0; $i < count($words); $i++){
		// Checking if we have a be word
		if(!in_array($words[$i], $be_words)){
			continue;
		}

		// If be word is there then need to check if next one is past particle with mostly ends with ed.
		if(strpos($words[$i+1], 'ed') != strlen($words[$i+1]) - 2){
			if(!in_array($words[$i+1], $past_particles)){
				continue;
			}
		}

		return true;
	}

	return false;
}

// Analysis every 15 seconds
function siteseo_do_realtime_analysis(){

	// Security check
	siteseo_check_ajax_referer('siteseo_realtime_nonce');

	if(!current_user_can('edit_posts') || !is_admin()){
		return;
	}

	$data = [];

	$post_content = !empty($_POST['post_content']) ? wp_kses_post(wp_unslash($_POST['post_content'])) : '';
	$post_id = (int) siteseo_opt_post('post_id');
	$post_origin = siteseo_opt_post('post_origin');
	$post_type = siteseo_opt_post('post_type');
	$post_title  = siteseo_opt_post('post_title');
	$post_tax = siteseo_opt_post('post_tax');
	$post_slug = siteseo_opt_post('post_slug');
	$meta = siteseo_opt_post('meta');
	$keywords_str = strtolower(siteseo_opt_post('keywords'));
	$keywords = [$keywords_str];
	$h1_title = $post_title; // We use title of the page as h1

	// In case we are setting a custom title using SiteSEO metabox then
	// we will need to consider that as the title for the Analysis.
	if(!empty($meta['title'])){
		$post_title = $meta['title'];
		update_post_meta($post_id , '_siteseo_titles_title', $meta['title']);
	}
	
	if(strpos($keywords_str, ',') !== FALSE){
		$keywords = explode(',', $keywords_str);
	}

	$keywords = apply_filters('siteseo_content_analysis_target_keywords', $keywords, $post_id);
	$post_content = apply_filters('siteseo_dom_analysis_get_post_content', $post_content);

	// Zion Builder compatibility
	if(is_plugin_active('zionbuilder/zionbuilder.php')){
		$post_content .= get_post_meta($post_id, '_zionbuilder_page_elements', true);
	}

	// BeTheme is activated
	$theme = wp_get_theme();
	if('betheme' == $theme->template || 'Betheme' == $theme->parent_theme){
		$post_content .= get_post_meta($post_id, 'mfn-page-items-seo', true);
	}

	// Themify compatibility
	if(defined('THEMIFY_DIR') && method_exists('ThemifyBuilder_Data_Manager', '_get_all_builder_text_content')){
		global $ThemifyBuilder;
		$builder_data = $ThemifyBuilder->get_builder_data($post_id);
		$plain_text = \ThemifyBuilder_Data_Manager::_get_all_builder_text_content($builder_data);
		$plain_text = do_shortcode($plain_text);

		if('' != $plain_text){
			$post_content = $plain_text;
		}
	}

	// Add WC product excerpt
	if('product' == $post_type){
		$post_content .= get_the_excerpt($post_id);
	}

	$post_content = apply_filters('siteseo_content_analysis_content', $post_content, $post_id);

	$data['target_kws_count'] = siteseo_get_service('CountTargetKeywordsUse')->getCountByKeywords($keywords, $post_id);

	$data['words_counter'] = preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u", normalize_whitespace(wp_strip_all_tags($post_content)), $matches);
	
	if(!empty($matches[0])){
		$words_counter_unique = count(array_unique($matches[0]));
	} else {
		$words_counter_unique = '0';
	}
	$data['words_counter_unique'] = $words_counter_unique;
		
	// Checkinng the post slug
	if(!empty($keywords)){
		// Keyword density
		foreach($keywords as $kw){
			if (preg_match_all('#\b(' . $kw . ')\b#iu', stripslashes_deep(wp_strip_all_tags($post_content)), $m)) {
				$data['kws_density']['matches'][$kw][] = $m[0];
			}
		}
		
		if (is_plugin_active('permalink-manager-pro/permalink-manager.php')) {
			global $permalink_manager_uris;
			$post_slug = urldecode($permalink_manager_uris[$post_id]);
		}

		$post_slug = str_replace('-', ' ', $post_slug);

		if(isset($post_slug)){
			foreach($keywords as $kw){
				if (preg_match_all('#\b(' . remove_accents($kw) . ')\b#iu', strip_tags($post_slug), $m)){
					$data['kws_permalink']['matches'][$kw][] = $m[0];
				}
			}
		}
	}

	//Title
	if(!empty($post_title)){
		$data['title'] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($post_title)));
		if(!empty($keywords)){
			foreach($keywords as $kw){
				if(preg_match_all('#\b(' . $kw . ')\b#iu', $data['title'], $m)){
					$data['meta_title']['matches'][$kw][] = $m[0];
				}
			}
		}
	}

	//Meta desc
	if(!empty($meta) && !empty($meta['description'])){
		$data['meta_desc'] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses(wp_strip_all_tags($meta['description']))));
		update_post_meta($post_id , '_siteseo_titles_desc', $meta['description']);
		
		if(!empty($keywords) && !empty($data['meta_desc'])){
			foreach($keywords as $kw){
				if(preg_match_all('#\b(' . $kw . ')\b#iu', $data['meta_desc'], $m)){
					$data['meta_description']['matches'][$kw][] = $m[0];
				}
			}
		}
	}

	if('1' == siteseo_get_service('SocialOption')->getSocialFacebookOg()){
		//OG:title
		if(!empty($meta) && !empty($meta['og_title'])){
			$data['og_title']['count'] = 1;
			$data['og_title']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($meta['og_title'])));
		}

		//OG:description
		if(!empty($meta) && !empty($meta['og_title'])){
			$data['og_desc']['count'] = 1;
			$data['og_desc']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($meta['og_title'])));
		}

		//OG:image
		if(!empty($meta) && !empty($meta['og_img'])){
			$data['og_img']['count'] = 1;
			$data['og_img']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($meta['og_img'])));
		}
	}
	
	if('1' == siteseo_get_service('SocialOption')->getSocialTwitterCard()){	
		//Twitter:title
		if (!empty($meta) && !empty($meta['tw_title'])){
			$data['tw_title']['count'] = 1;
			$data['tw_title']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($meta['tw_title'])));
		}

		//Twitter:description
		if (!empty($meta) && !empty($meta['tw_desc'])) {
			$data['tw_desc']['count'] = 1;
			$data['tw_desc']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($meta['tw_desc'])));
		}

		//Twitter:image
		if (!empty($meta) && !empty($meta['tw_img'])) {
			$data['tw_img']['count'] = 1;
			$data['tw_img']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($meta['tw_img'])));
		}
	}

	// h1
	preg_match_all('/<h1[^>]*>.*<\/h1[^>]*>/iU', $post_content, $h1);

	// We are setting post title to be the h1 as post title is supposed to be a h1.
	if(empty($h1)){
		$h1 = [];
	}
	if(empty($h1[0])){
		$h1[0] = [];
	}

	array_unshift($h1[0], $h1_title); // We need our post title to be shown as the first h1.

	if(!empty($h1) && !empty($h1[0])){
		$data['h1']['nomatches']['count'] = count($h1[0]);

		if(!empty($keywords)){
			foreach($h1[0] as $heading1){
				if(empty($heading1)){
					continue;
				}

				foreach($keywords as $kw){
					if(preg_match_all('#\b(' . $kw . ')\b#iu', $heading1, $m)){
						$data['h1']['matches'][$kw][] = $m[0];
					}
				}
				$data['h1']['values'][] = esc_attr(wp_strip_all_tags($heading1));
			}
		}
	}
	
	//DOM
	$dom = new DOMDocument();
	$internalErrors = libxml_use_internal_errors(true);
	$dom->preserveWhiteSpace = false;

	if($dom->loadHTML('<?xml encoding="utf-8" ?>' . $post_content)){
		$xpath = new DOMXPath($dom);

		if(!empty($keywords)){
			//h2
			$h2 = $xpath->query('//h2');
			if(!empty($h2)){
				foreach ($h2 as $heading2) {
					foreach ($keywords as $kw) {
						if (preg_match_all('#\b(' . $kw . ')\b#iu', $heading2->nodeValue, $m)) {
							$data['h2']['matches'][$kw][] = $m[0];
						}
					}
				}
			}

			//h3
			$h3 = $xpath->query('//h3');
			if(!empty($h3)){
				foreach($h3 as $heading3){
					foreach($keywords as $kw){
						if(preg_match_all('#\b(' . $kw . ')\b#iu', $heading3->nodeValue, $m)){
							$data['h3']['matches'][$kw][] = $m[0];
						}
					}
				}
			}
		}
		
		//Images
		/*Standard images*/
		$imgs = $xpath->query('//img');

		if(!empty($imgs) && null != $imgs){
			//init
			$img_without_alt = [];
			$img_with_alt = [];
			
			foreach($imgs as $img){
				if ($img->hasAttribute('src')) {
					if (! preg_match_all('#\b(avatar)\b#iu', $img->getAttribute('class'), $m)) {//Exclude avatars from analysis

						if(!empty($img->hasAttribute('alt')) && !empty($img->getAttribute('alt'))){
							$img_with_alt[] .= $img->getAttribute('src');
						} elseif ('' === $img->getAttribute('alt') || ! $img->hasAttribute('alt')) {//if alt is empty or doesn't exist
							$img_src = download_url($img->getAttribute('src'));

							if (false === is_wp_error($img_src)) {
								if (filesize($img_src) > 100) {//Ignore files under 100 bytes
									$img_without_alt[] .= $img->getAttribute('src');
								} else {
									$img_with_alt[] .= $img->getAttribute('src');
								}
								@unlink($img_src);
							}
						}
					}
				}
				$data['img']['images']['without_alt'] = $img_without_alt;
				$data['img']['images']['with_alt'] = $img_with_alt;
			}
		}
		
		//nofollow links
		$nofollow_links = $xpath->query("//a[contains(@rel, 'nofollow') and not(contains(@rel, 'ugc'))]");
		if (! empty($nofollow_links)) {
			foreach ($nofollow_links as $key => $link) {
				if (! preg_match_all('#\b(cancel-comment-reply-link)\b#iu', $link->getAttribute('id'), $m) && ! preg_match_all('#\b(comment-reply-link)\b#iu', $link->getAttribute('class'), $m)) {
					$data['nofollow_links'][$key][$link->getAttribute('href')] = esc_attr($link->nodeValue);
				}
			}
		}
		
		// outbound links
		$site_url = wp_parse_url(get_home_url(), PHP_URL_HOST);
		$outbound_links = $xpath->query("//a[not(contains(@href, '" . $site_url . "'))]");
		if(! empty($outbound_links)){
			foreach ($outbound_links as $key=>$link) {
				if (! empty(wp_parse_url($link->getAttribute('href'), PHP_URL_HOST))) {
					$data['outbound_links'][$key][$link->getAttribute('href')] = esc_attr($link->nodeValue);
				}
			}
		}

		// Cleaning
		$xpath = null;
		$dom = null;
	}
	
	// Internal links
	$permalink = get_permalink((int) $post_id);
	$args = [
		's' => $permalink,
		'post_type' => 'any',
	];
	$internal_links = new WP_Query($args);

	if($internal_links->have_posts()){
		$data['internal_links']['count'] = $internal_links->found_posts;

		while($internal_links->have_posts()){
			$internal_links->the_post();
			$data['internal_links']['links'][get_the_ID()] = [get_the_permalink() => get_the_title()];
		}
	}

	//Get source code
	$args = [
		'blocking' => true,
		'timeout' => 30,
		'sslverify' => false,
	];

	$args = apply_filters('siteseo_real_preview_remote', $args);

	if('post' == $post_origin){ //Default: post type
		// Oxygen compatibility
		if(is_plugin_active('oxygen/functions.php') && function_exists('ct_template_output')){
			$link = get_permalink((int) $post_id);
			$link = add_query_arg('no_admin_bar', 1, $link);

			$response = wp_remote_get($link, $args);
			if(200 !== wp_remote_retrieve_response_code($response)){
				$link = get_permalink((int) $post_id);
				$response = wp_remote_get($link, $args);
			}
		} else {
			$custom_args = ['no_admin_bar' => 1];

			//Useful for Page / Theme builders
			$custom_args = apply_filters('siteseo_real_preview_custom_args', $custom_args);

			$link = add_query_arg('no_admin_bar', 1, get_preview_post_link((int) $post_id, $custom_args));

			$link = apply_filters('siteseo_get_dom_link', $link, $post_id);

			$response = wp_remote_get($link, $args);
		}
	} else { //Term taxonomy
		$link = get_term_link((int) $post_id, $post_tax);
		$response = wp_remote_get($link, $args);
	}

	if(is_wp_error($response) || '404' == wp_remote_retrieve_response_code($response)){
		$data['title'] = __('The post is returning 404 error, publish your post!', 'siteseo');
	}elseif (is_wp_error($response) || '401' == wp_remote_retrieve_response_code($response)){
		$data['title'] = __('Your site is protected by an authentication.');
	}else{
		$body = wp_remote_retrieve_body($response);

		//DOM
		$dom = new DOMDocument();
		$internalErrors = libxml_use_internal_errors(true);
		$dom->preserveWhiteSpace = false;

		if($dom->loadHTML('<?xml encoding="utf-8" ?>' . $body)){
			$data['link_preview'] = $link;

			//Disable wptexturize
			add_filter('run_wptexturize', '__return_false');

			$xpath = new DOMXPath($dom);

			//OG:url
			$og_url = $xpath->query('//meta[@property="og:url"]/@content');

			if (! empty($og_url)) {
				$data['og_url']['count'] = count($og_url);
				foreach($og_url as $key => $mog_url){
					$url = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mog_url->nodeValue)));
					$data['og_url']['values'][] = $url;
					$url = wp_parse_url($url);
					$data['og_url']['host'] = $url['host'];
				}
			}

			//OG:site_name
			$og_site_name = $xpath->query('//meta[@property="og:site_name"]/@content');

			if(!empty($og_site_name)){
				$data['og_site_name']['count'] = count($og_site_name);
				foreach($og_site_name as $mog_site_name){
					$data['og_site_name']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mog_site_name->nodeValue)));
				}
			}

			//Twitter:image:src
			$tw_img = $xpath->query('//meta[@name="twitter:image:src"]/@content');

			if(!empty($tw_img)){
				$count = null;
				if(!empty($data['tw_img']['count'])){
					$count = $data['tw_img']['count'];
				}

				$data['tw_img']['count'] = count($tw_img) + $count;

				foreach($tw_img as $mtw_img){
					$data['tw_img']['values'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mtw_img->nodeValue)));
				}
			}

			//Canonical
			$canonical = $xpath->query('//link[@rel="canonical"]/@href');
			if(!empty($canonical)){
				foreach ($canonical as $mcanonical) {
					$data['canonical'] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mcanonical->nodeValue)));
				}

				foreach ($canonical as $mcanonical) {
					$data['all_canonical'][] = esc_attr(stripslashes_deep(wp_filter_nohtml_kses($mcanonical->nodeValue)));
				}
			}

			// Meta robots
			$meta_robots = $xpath->query('//meta[@name="robots"]/@content');
			if(!empty($meta_robots)){
				foreach($meta_robots as $key=>$value){
					$data['meta_robots'][$key][] = esc_attr($value->nodeValue);
				}
			}

			//Get schemas
			$json_ld = $xpath->query('//script[@type="application/ld+json"]');
			if (! empty($json_ld)) {
				foreach ($json_ld as $node) {
					$json = json_decode($node->nodeValue, true);
					if (isset($json['@type'])) {
						$data['json'][] = $json['@type'];
					}
				}
			}
		}
	}
	
	libxml_use_internal_errors($internalErrors);

	update_post_meta($post_id, '_siteseo_analysis_data', $data);

	$analyzes = siteseo_get_service('GetContentAnalysis')->getAnalyzes(get_post($post_id));
	$html_response = siteseo_get_service('RenderContentAnalysis')->render($analyzes, $data, false);
	
	$acceptable_svg = [
		'svg' => [
			'role' => true,
			'aria-hidden' => true,
			'focusable' => true,
			'width' => true,
			'height' => true,
			'viewbox' => true,
			'version' => true,
			'xmlns' => true
		],
		'circle' => [
			'id' => true,
			'r' => true,
			'cx' => true,
			'cy' => true,
			'fill' => true,
			'stroke-dasharray' => true,
			'stroke-dashoffset' => true
		]
	];
	
	$allowed_html = wp_kses_allowed_html('post');
	$allowed_html = array_merge($allowed_html, $acceptable_svg);

	wp_send_json(['html' => wp_kses($html_response, $allowed_html), 'success' => true]);
	
}
add_action('wp_ajax_siteseo_realtime_analysis', 'siteseo_do_realtime_analysis');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Flush permalinks
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_flush_permalinks()
{
	siteseo_check_ajax_referer('siteseo_flush_permalinks_nonce');
	if (current_user_can(siteseo_capability('manage_options', 'flush')) && is_admin()) {
		flush_rewrite_rules(false);
		exit();
	}
}
add_action('wp_ajax_siteseo_flush_permalinks', 'siteseo_flush_permalinks');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Dashboard toggle features
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_toggle_features()
{
	siteseo_check_ajax_referer('siteseo_toggle_features_nonce');

	if (current_user_can(siteseo_capability('manage_options', 'dashboard')) && is_admin()) {
		if (isset($_POST['feature']) && isset($_POST['feature_value'])) {
			$siteseo_toggle_options					= get_option('siteseo_toggle');
			$siteseo_toggle_options[siteseo_opt_post('feature')] = siteseo_opt_post('feature_value');
			update_option('siteseo_toggle', $siteseo_toggle_options, 'yes', false);
		}
		exit();
	}
}
add_action('wp_ajax_siteseo_toggle_features', 'siteseo_toggle_features');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Dashboard drag and drop features
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_dnd_features()
{
	check_ajax_referer('siteseo_dnd_features_nonce');
	if (current_user_can(siteseo_capability('manage_options', 'dashboard')) && is_admin()) {
		if (isset($_POST['order']) && !empty($_POST['order'])) {
			$cards_order = get_option('siteseo_dashboard_option_name');

			$cards_order['cards_order'] = siteseo_opt_post('order');

			update_option('siteseo_dashboard_option_name', $cards_order);
		}
	}

	wp_send_json_success();
}
add_action('wp_ajax_siteseo_dnd_features', 'siteseo_dnd_features');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Dashboard News Panel
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_news()
{
	siteseo_check_ajax_referer('siteseo_news_nonce');
	if (current_user_can(siteseo_capability('manage_options', 'dashboard')) && is_admin()) {
		if (isset($_POST['news_max_items'])) {
			$siteseo_dashboard_option_name = get_option('siteseo_dashboard_option_name');
			$siteseo_dashboard_option_name['news_max_items']  = intval(siteseo_opt_post('news_max_items'));
			update_option('siteseo_dashboard_option_name', $siteseo_dashboard_option_name, false);
		}
		exit();
	}
}
add_action('wp_ajax_siteseo_news', 'siteseo_news');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Dashboard Display Panel
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_display()
{
	siteseo_check_ajax_referer('siteseo_display_nonce');
	if (current_user_can(siteseo_capability('manage_options', 'dashboard')) && is_admin()) {
		//Notifications Center
		if (isset($_POST['notifications_center'])) {
			$siteseo_advanced_option_name = get_option('siteseo_advanced_option_name');

			if ('1' == $_POST['notifications_center']) {
				$siteseo_advanced_option_name['appearance_notifications'] = siteseo_opt_post('notifications_center');
			} else {
				unset($siteseo_advanced_option_name['appearance_notifications']);
			}

			update_option('siteseo_advanced_option_name', $siteseo_advanced_option_name, false);
		}
		//News Panel
		if (isset($_POST['news_center'])) {
			$siteseo_advanced_option_name = get_option('siteseo_advanced_option_name');

			if ('1' == $_POST['news_center']) {
				$siteseo_advanced_option_name['appearance_news'] = siteseo_opt_post('news_center');
			} else {
				unset($siteseo_advanced_option_name['appearance_news']);
			}

			update_option('siteseo_advanced_option_name', $siteseo_advanced_option_name, false);
		}
		//Tools Panel
		if (isset($_POST['tools_center'])) {
			$siteseo_advanced_option_name = get_option('siteseo_advanced_option_name');

			if ('1' == $_POST['tools_center']) {
				$siteseo_advanced_option_name['appearance_seo_tools'] = siteseo_opt_post('tools_center');
			} else {
				unset($siteseo_advanced_option_name['appearance_seo_tools']);
			}

			update_option('siteseo_advanced_option_name', $siteseo_advanced_option_name, false);
		}
		exit();
	}
}
add_action('wp_ajax_siteseo_display', 'siteseo_display');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Dashboard hide notices
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_hide_notices()
{
	siteseo_check_ajax_referer('siteseo_hide_notices_nonce');

	if (current_user_can(siteseo_capability('manage_options', 'dashboard')) && is_admin()) {
		if (isset($_POST['notice']) && isset($_POST['notice_value'])) {
			$siteseo_notices_options = get_option('siteseo_notices');
			$siteseo_notices_options[siteseo_opt_post('notice')] = siteseo_opt_post('notice_value');
			update_option('siteseo_notices', $siteseo_notices_options, 'yes', false);
		}
		exit();
	}
}
add_action('wp_ajax_siteseo_hide_notices', 'siteseo_hide_notices');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Regenerate Video XML Sitemap
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_video_xml_sitemap_regenerate()
{
	siteseo_check_ajax_referer('siteseo_video_regenerate_nonce');

	if (current_user_can(siteseo_capability('manage_options', 'migration')) && is_admin()) {
		if (isset($_POST['offset']) && isset($_POST['offset'])) {
			$offset = absint(siteseo_opt_post('offset'));
		}

		$cpt = ['any'];
		$sitemap_post_types_list = siteseo_get_service('SitemapOption')->getPostTypesList();
		if ($sitemap_post_types_list) {
			unset($cpt[0]);
			foreach ($sitemap_post_types_list as $cpt_key => $cpt_value) {
				foreach ($cpt_value as $_cpt_key => $_cpt_value) {
					if ('1' == $_cpt_value) {
						$cpt[] = $cpt_key;
					}
				}
			}

			$cpt = array_map(function($item) {
				return "'" . esc_sql($item) . "'";
			}, $cpt);

			$cpt_string = implode(",", $cpt);
		}

		global $wpdb;
		$total_count_posts = (int) $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM {$wpdb->posts} WHERE post_status IN ('pending', 'draft', 'publish', 'future') AND post_type IN ( %s ) ", $cpt_string));

		$increment = 1;
		global $post;

		if ($offset > $total_count_posts) {
			wp_reset_query();
			$count_items = $total_count_posts;
			$offset = 'done';
		} else {
			$args = [
				'posts_per_page' => $increment,
				'post_type'	  => $cpt,
				'post_status'	=> ['pending', 'draft', 'publish', 'future'],
				'offset'		 => $offset,
			];

			$video_query = get_posts($args);

			if ($video_query) {
				foreach ($video_query as $post) {
					siteseo_pro_video_xml_sitemap($post->ID, $post);
				}
			}
			$offset += $increment;
		}
		$data		   = [];

		$data['total'] = $total_count_posts;

		if ($offset >= $total_count_posts) {
			$data['count'] = $total_count_posts;
		} else {
			$data['count'] = $offset;
		}

		$data['offset'] = $offset;

		//Clear cache
		delete_transient( '_siteseo_sitemap_ids_video' );

		wp_send_json_success($data);
		exit();
	}
}
add_action('wp_ajax_siteseo_video_xml_sitemap_regenerate', 'siteseo_video_xml_sitemap_regenerate');

function siteseo_create_robots(){
	siteseo_check_ajax_referer('siteseo_admin_nonce');
	
	if(!current_user_can('manage_options')){
		wp_send_json_error(__('You do not have required permission to create robots.txt file.', 'siteseo'));
	}

	ob_start();
	do_robots();
	$robots_txt = ob_get_clean();
	
	$is_public = absint(get_option('blog_public'));
	$robots_txt = apply_filters('robots_txt', $robots_txt, $is_public);
	
	if(file_put_contents(ABSPATH . 'robots.txt', $robots_txt)){
		wp_send_json_success(__('Successfully create the robots.txt file', 'siteseo'));
	}

	wp_send_json_error();
}
add_action('wp_ajax_siteseo_create_robots', 'siteseo_create_robots');

function siteseo_update_robots(){
	siteseo_check_ajax_referer('siteseo_admin_nonce');
	
	if(!current_user_can('manage_options')){
		wp_send_json_error(__('You do not have required permission to edit this file.', 'siteseo'));
	}
	
	$robots_txt = '';
	if(!empty($_POST['robots'])){
		$robots_txt = sanitize_textarea_field(wp_unslash($_POST['robots']));
	}

	if(empty($robots_txt)){
		wp_send_json_error(__('You have supplied empty robots rules', 'siteseo'));
	}
	
	if(!is_writable(ABSPATH . 'robots.txt')){
		wp_send_json_error(__('robots.txt file is not writable', 'siteseo'));
	}
	
	if(file_put_contents(ABSPATH . 'robots.txt', $robots_txt)){
		wp_send_json_success(__('Successfully update the robots.txt file', 'siteseo'));
	}

	wp_send_json_error(__('Unable to update the robots.txt file', 'siteseo'));
	
}

add_action('wp_ajax_siteseo_update_robots', 'siteseo_update_robots');

function siteseo_update_htaccess() {
	siteseo_check_ajax_referer('siteseo_admin_nonce');

	if (!current_user_can('manage_options')) {
		wp_send_json_error(__('You do not have required permission to edit this file.', 'siteseo'));
	}

	$htaccess_enable = isset($_POST['htaccess_enable']) ? intval(sanitize_text_field(wp_unslash($_POST['htaccess_enable']))) : 0;
	$htaccess_rules = isset($_POST['htaccess_code']) ? sanitize_textarea_field(wp_unslash($_POST['htaccess_code'])) : '';

	if(empty($htaccess_enable)){
		wp_send_json_error(__('Please accept the warning first before proceeding with saving the htaccess', 'siteseo'));
	}

	$htaccess_file = ABSPATH . '.htaccess';
	$backup_file = ABSPATH . '.htaccess_backup.siteseo';
	
	if(!is_writable($htaccess_file)){
		wp_send_json_error(__('.htaccess file is not writable so the ', 'siteseo'));
	}

	// Backup .htaccess file
	if(!copy($htaccess_file, $backup_file)){
		wp_send_json_error(__('Failed to create backup of .htaccess file.', 'siteseo'));
	}

	// Update the .htaccess file
	if(file_put_contents($htaccess_file, $htaccess_rules) === false){
		wp_send_json_error(__('Failed to update .htaccess file.', 'siteseo'));
	}

	$response = wp_remote_get(site_url());
	$response_code = wp_remote_retrieve_response_code($response);
	
	// Restore the backup if something goes wrong.
	if($response_code > 299){
		copy($backup_file, $htaccess_file);
		wp_send_json_error(__('There was a syntax error in the htaccess rules you provided as the response to your website with the new htaccess gave response code of', 'siteseo') . ' ' . $response_code);
	}

	wp_send_json_success(__('Successfully updated .htaccess file', 'siteseo'));
}

add_action('wp_ajax_siteseo_update_htaccess', 'siteseo_update_htaccess');

require_once __DIR__ . '/ajax-migrate/smart-crawl.php';
require_once __DIR__ . '/ajax-migrate/seopressor.php';
require_once __DIR__ . '/ajax-migrate/slim-seo.php';
require_once __DIR__ . '/ajax-migrate/platinum.php';
require_once __DIR__ . '/ajax-migrate/wpseo.php';
require_once __DIR__ . '/ajax-migrate/premium-seo-pack.php';
require_once __DIR__ . '/ajax-migrate/wp-meta-seo.php';
require_once __DIR__ . '/ajax-migrate/seo-ultimate.php';
require_once __DIR__ . '/ajax-migrate/squirrly.php';
require_once __DIR__ . '/ajax-migrate/seo-framework.php';
require_once __DIR__ . '/ajax-migrate/yoast.php';
require_once __DIR__ . '/export/csv.php';
