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

if(!defined('ABSPATH')){
	die('HACKING ATTEMPT!');
}

// Check global settings
if(!function_exists('siteseo_titles_single_cpt_noindex_option')){
	function siteseo_titles_single_cpt_noindex_option(){
		global $post;
		$siteseo_get_current_cpt = get_post_type($post);

		$options = get_option('siteseo_titles_option_name');
		if(! empty($options) && isset($options['titles_single_titles'][$siteseo_get_current_cpt]['noindex'])) {
			return $options['titles_single_titles'][$siteseo_get_current_cpt]['noindex'];
		}
	}
}

if (! function_exists('siteseo_titles_single_cpt_nofollow_option')) {
	function siteseo_titles_single_cpt_nofollow_option(){
		global $post;
		$siteseo_get_current_cpt = get_post_type($post);

		$options = get_option('siteseo_titles_option_name');
		if (! empty($options) && isset($options['titles_single_titles'][$siteseo_get_current_cpt]['nofollow'])){
			return $options['titles_single_titles'][$siteseo_get_current_cpt]['nofollow'];
		}
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Display metabox in Custom Post Type
///////////////////////////////////////////////////////////////////////////////////////////////////
function siteseo_titles_single_cpt_date_option(){
	global $post;
	$siteseo_get_current_cpt = get_post_type($post);

	$options = get_option('siteseo_titles_option_name');
	if (! empty($options) && isset($options['titles_single_titles'][$siteseo_get_current_cpt]['date'])) {
		return $options['titles_single_titles'][$siteseo_get_current_cpt]['date'];
	}
}

function siteseo_display_date_snippet(){
	if (siteseo_titles_single_cpt_date_option()) {
		return '<div class="snippet-date">' . get_the_modified_date('M j, Y') . ' - </div>';
	}
}

function siteseo_metaboxes_init(){
	global $typenow;
	global $pagenow;

	$data_attr			 = [];
	$data_attr['data_tax'] = '';
	$data_attr['termId']   = '';

	if ('post-new.php' == $pagenow || 'post.php' == $pagenow) {
		$data_attr['current_id'] = get_the_id();
		$data_attr['origin']	 = 'post';
		$data_attr['title']	  = get_the_title($data_attr['current_id']);
	} elseif ('term.php' == $pagenow || 'edit-tags.php' == $pagenow) {
		global $tag;
		$data_attr['current_id'] = $tag->term_id;
		$data_attr['termId']	 = $tag->term_id;
		$data_attr['origin']	 = 'term';
		$data_attr['data_tax']   = $tag->taxonomy;
		$data_attr['title']	  = $tag->name;
	}

	$data_attr['isHomeId'] = get_option('page_on_front');
	if ('0' === $data_attr['isHomeId']) {
		$data_attr['isHomeId'] = '';
	}

	return $data_attr;
}

function siteseo_display_seo_metaboxe(){
	
	add_action('add_meta_boxes', 'siteseo_init_metabox');
	function siteseo_init_metabox(){
		
		$metaboxe_position = siteseo_get_service('AdvancedOption')->getAppearanceMetaboxePosition();
		
		if(empty($metaboxe_position)){
			$metaboxe_position = 'default';
		}

		$siteseo_get_post_types = siteseo_get_service('WordPressData')->getPostTypes();

		$siteseo_get_post_types = apply_filters('siteseo_metaboxe_seo', $siteseo_get_post_types);

		if (! empty($siteseo_get_post_types) && ! siteseo_get_service('EnqueueModuleMetabox')->canEnqueue()) {
			foreach ($siteseo_get_post_types as $key => $value) {
				add_meta_box('siteseo_cpt', __('SiteSEO', 'siteseo'), 'siteseo_cpt', $key, 'normal', $metaboxe_position);
			}
		}
		
		add_meta_box('siteseo_cpt', __('SiteSEO', 'siteseo'), 'siteseo_cpt', 'siteseo_404', 'normal', $metaboxe_position);
	}

}

function siteseo_cpt($post){
	global $typenow, $wp_version, $siteseo;
	$prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
	wp_nonce_field(plugin_basename(__FILE__), 'siteseo_cpt_nonce');

	//init
	$disabled = [];

	wp_enqueue_script('siteseo-cpt-tabs-js', SITESEO_ASSETS_DIR . '/js/siteseo-metabox' . $prefix . '.js', ['jquery-ui-tabs', 'jquery-ui-autocomplete'], SITESEO_VERSION);
		
	if ('siteseo_404' != $typenow) {
		wp_enqueue_script('jquery-ui-accordion');
		
		//Tagify
		wp_enqueue_script('siteseo-tagify-js', SITESEO_ASSETS_DIR . '/js/tagify.min.js', ['jquery'], SITESEO_VERSION, true);
		wp_register_style('siteseo-tagify', SITESEO_ASSETS_DIR . '/css/tagify.min.css', [], SITESEO_VERSION);
		wp_enqueue_style('siteseo-tagify');

		//Register Google Snippet Preview / Content Analysis JS
		wp_enqueue_script('siteseo-cpt-counters-js', SITESEO_ASSETS_DIR . '/js/siteseo-counters' . $prefix . '.js', ['jquery', 'jquery-ui-tabs', 'jquery-ui-accordion'], SITESEO_VERSION, true);

		//If Gutenberg ON
		if (function_exists('get_current_screen')) {
			$get_current_screen = get_current_screen();
			if (isset($get_current_screen->is_block_editor)) {
				if ($get_current_screen->is_block_editor) {
					wp_enqueue_script('siteseo-block-editor-js', SITESEO_ASSETS_DIR . '/js/siteseo-block-editor' . $prefix . '.js', ['jquery', 'wp-data', 'react', 'wp-edit-post', 'wp-element', 'wp-plugins'], SITESEO_VERSION, true);
					if ( version_compare( $wp_version, '5.8', '>=' ) ) {
						wp_enqueue_script( 'siteseo-primary-category-js', SITESEO_URL_PUBLIC . '/editor/primary-category-select/index.js', ['wp-hooks'], SITESEO_VERSION, true);
					}
				}
			}
		}

		wp_enqueue_script('siteseo-cpt-video-sitemap-js', SITESEO_ASSETS_DIR . '/js/siteseo-sitemap-video' . $prefix . '.js', ['jquery', 'jquery-ui-accordion'], SITESEO_VERSION);

		$siteseo_real_preview = [
			'siteseo_nonce'		 => wp_create_nonce('siteseo_real_preview_nonce'), // @deprecated 4.4.0
			'siteseo_real_preview'  => admin_url('admin-ajax.php'), // @deprecated 4.4.0
			'i18n'				   => ['progress'  => __('Analysis in progress...', 'siteseo')],
			'ajax_url'			   => admin_url('admin-ajax.php'),
			'get_preview_meta_title' => wp_create_nonce('get_preview_meta_title'),
			'realtime_nonce' => wp_create_nonce('siteseo_realtime_nonce'),
		];
		wp_localize_script('siteseo-cpt-counters-js', 'siteseoAjaxRealPreview', $siteseo_real_preview);

		wp_enqueue_script('siteseo-media-uploader-js', SITESEO_ASSETS_DIR . '/js/siteseo-media-uploader' . $prefix . '.js', ['jquery'], SITESEO_VERSION, false);
		wp_enqueue_media();
	}
	
	$metabox_data = [];
	$metabox_data = siteseo_metabox_data('post');

	require_once dirname(dirname(__FILE__)) . '/admin-dyn-variables-helper.php'; //Dynamic variables

	require_once dirname(__FILE__) . '/admin-metaboxes-form.php'; //Metaboxe HTML
	
	siteseo_metabox_form_html($metabox_data);

	do_action('siteseo_seo_metabox_init');
}

add_action('save_post', 'siteseo_save_metabox', 10, 2);
function siteseo_save_metabox($post_id, $post){
	//Nonce
	if (! isset($_POST['siteseo_cpt_nonce']) || ! wp_verify_nonce(siteseo_opt_post('siteseo_cpt_nonce'), plugin_basename(__FILE__))) {
		return $post_id;
	}

	//Post type object
	$post_type = get_post_type_object($post->post_type);

	//Check permission
	if (! current_user_can($post_type->cap->edit_post, $post_id)) {
		return $post_id;
	}

	if ('attachment' !== get_post_type($post_id)) {
		$analysis_tabs = [];
		$analysis_tabs = json_decode(siteseo_opt_post('analysis_tabs'), true);

		if(!empty($analysis_tabs) && is_array($analysis_tabs) && in_array('title-settings', $analysis_tabs)){
			if(!empty($_POST['siteseo_titles_title'])){
				update_post_meta($post_id, '_siteseo_titles_title', siteseo_opt_post('siteseo_titles_title'));
			} else {
				delete_post_meta($post_id, '_siteseo_titles_title');
			}
			if(!empty($_POST['siteseo_titles_desc'])){
				update_post_meta($post_id, '_siteseo_titles_desc', siteseo_opt_post('siteseo_titles_desc'));
			} else {
				delete_post_meta($post_id, '_siteseo_titles_desc');
			}
		}
		if(!empty($analysis_tabs) && is_array($analysis_tabs) && in_array('advanced-settings', $analysis_tabs)){
			if(isset($_POST['siteseo_robots_index'])){
				update_post_meta($post_id, '_siteseo_robots_index', 'yes');
			} else {
				delete_post_meta($post_id, '_siteseo_robots_index');
			}
			if (isset($_POST['siteseo_robots_follow'])) {
				update_post_meta($post_id, '_siteseo_robots_follow', 'yes');
			} else {
				delete_post_meta($post_id, '_siteseo_robots_follow');
			}
			if (isset($_POST['siteseo_robots_imageindex'])) {
				update_post_meta($post_id, '_siteseo_robots_imageindex', 'yes');
			} else {
				delete_post_meta($post_id, '_siteseo_robots_imageindex');
			}
			if (isset($_POST['siteseo_robots_archive'])) {
				update_post_meta($post_id, '_siteseo_robots_archive', 'yes');
			} else {
				delete_post_meta($post_id, '_siteseo_robots_archive');
			}
			if (isset($_POST['siteseo_robots_snippet'])) {
				update_post_meta($post_id, '_siteseo_robots_snippet', 'yes');
			} else {
				delete_post_meta($post_id, '_siteseo_robots_snippet');
			}
			if (!empty($_POST['siteseo_robots_canonical'])) {
				update_post_meta($post_id, '_siteseo_robots_canonical', siteseo_opt_post('siteseo_robots_canonical'));
			} else {
				delete_post_meta($post_id, '_siteseo_robots_canonical');
			}
			if (!empty($_POST['siteseo_robots_primary_cat'])) {
				update_post_meta($post_id, '_siteseo_robots_primary_cat', siteseo_opt_post('siteseo_robots_primary_cat'));
			} else {
				delete_post_meta($post_id, '_siteseo_robots_primary_cat');
			}
		}

		if(!empty($analysis_tabs) && is_array($analysis_tabs) && in_array('social-settings', $analysis_tabs)){
			//Facebook
			if (!empty($_POST['siteseo_social_fb_title'])) {
				update_post_meta($post_id, '_siteseo_social_fb_title', siteseo_opt_post('siteseo_social_fb_title'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_fb_title');
			}
			if (!empty($_POST['siteseo_social_fb_desc'])) {
				update_post_meta($post_id, '_siteseo_social_fb_desc', siteseo_opt_post('siteseo_social_fb_desc'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_fb_desc');
			}
			if (!empty($_POST['siteseo_social_fb_img'])) {
				update_post_meta($post_id, '_siteseo_social_fb_img', siteseo_opt_post('siteseo_social_fb_img'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_fb_img');
			}
			if (!empty($_POST['siteseo_social_fb_img_attachment_id']) && !empty($_POST['siteseo_social_fb_img'])) {
				update_post_meta($post_id, '_siteseo_social_fb_img_attachment_id', siteseo_opt_post('siteseo_social_fb_img_attachment_id'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_fb_img_attachment_id');
			}
			if (!empty($_POST['siteseo_social_fb_img_width']) && !empty($_POST['siteseo_social_fb_img'])) {
				update_post_meta($post_id, '_siteseo_social_fb_img_width', siteseo_opt_post('siteseo_social_fb_img_width'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_fb_img_width');
			}
			if (!empty($_POST['siteseo_social_fb_img_height']) && !empty($_POST['siteseo_social_fb_img'])) {
				update_post_meta($post_id, '_siteseo_social_fb_img_height', siteseo_opt_post('siteseo_social_fb_img_height'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_fb_img_height');
			}

			//Twitter
			if (!empty($_POST['siteseo_social_twitter_title'])) {
				update_post_meta($post_id, '_siteseo_social_twitter_title', siteseo_opt_post('siteseo_social_twitter_title'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_twitter_title');
			}
			if (!empty($_POST['siteseo_social_twitter_desc'])) {
				update_post_meta($post_id, '_siteseo_social_twitter_desc', siteseo_opt_post('siteseo_social_twitter_desc'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_twitter_desc');
			}
			if (!empty($_POST['siteseo_social_twitter_img'])) {
				update_post_meta($post_id, '_siteseo_social_twitter_img', siteseo_opt_post('siteseo_social_twitter_img'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_twitter_img');
			}
			if (!empty($_POST['siteseo_social_twitter_img_attachment_id']) && !empty($_POST['siteseo_social_twitter_img'])) {
				update_post_meta($post_id, '_siteseo_social_twitter_img_attachment_id', siteseo_opt_post('siteseo_social_twitter_img_attachment_id'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_twitter_img_attachment_id');
			}
			if (!empty($_POST['siteseo_social_twitter_img_width']) && !empty($_POST['siteseo_social_twitter_img'])) {
				update_post_meta($post_id, '_siteseo_social_twitter_img_width', siteseo_opt_post('siteseo_social_twitter_img_width'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_twitter_img_width');
			}
			if (!empty($_POST['siteseo_social_twitter_img_height']) && !empty($_POST['siteseo_social_twitter_img'])) {
				update_post_meta($post_id, '_siteseo_social_twitter_img_height', siteseo_opt_post('siteseo_social_twitter_img_height'));
			} else {
				delete_post_meta($post_id, '_siteseo_social_twitter_img_height');
			}
		}

		if(!empty($analysis_tabs) && is_array($analysis_tabs) && in_array('redirect', $analysis_tabs)){
			if (isset($_POST['siteseo_redirections_type'])) {
				update_post_meta($post_id, '_siteseo_redirections_type', siteseo_opt_post('siteseo_redirections_type'));
			}
			if (!empty($_POST['siteseo_redirections_value'])) {
				update_post_meta($post_id, '_siteseo_redirections_value', siteseo_opt_post('siteseo_redirections_value'));
			} else {
				delete_post_meta($post_id, '_siteseo_redirections_value');
			}
			if (isset($_POST['siteseo_redirections_param'])) {
				update_post_meta($post_id, '_siteseo_redirections_param', siteseo_opt_post('siteseo_redirections_param'));
			}
			if (isset($_POST['siteseo_redirections_enabled'])) {
				update_post_meta($post_id, '_siteseo_redirections_enabled', 'yes');
			} else {
				delete_post_meta($post_id, '_siteseo_redirections_enabled', '');
			}
			if (isset($_POST['siteseo_redirections_enabled_regex'])) {
				update_post_meta($post_id, '_siteseo_redirections_enabled_regex', 'yes');
			} else {
				delete_post_meta($post_id, '_siteseo_redirections_enabled_regex');
			}
			if (isset($_POST['siteseo_redirections_logged_status'])) {
				update_post_meta($post_id, '_siteseo_redirections_logged_status', siteseo_opt_post('siteseo_redirections_logged_status'));
			} else {
				delete_post_meta($post_id, '_siteseo_redirections_logged_status');
			}
		}

		if (did_action('elementor/loaded')) {
			$elementor = get_post_meta($post_id, '_elementor_page_settings', true);

			if (! empty($elementor)) {
				if (isset($_POST['siteseo_titles_title'])) {
					$elementor['_siteseo_titles_title'] = siteseo_opt_post('siteseo_titles_title');
				}
				if (isset($_POST['siteseo_titles_desc'])) {
					$elementor['_siteseo_titles_desc'] = siteseo_opt_post('siteseo_titles_desc');
				}
				if (isset($_POST['siteseo_robots_index'])) {
					$elementor['_siteseo_robots_index'] = 'yes';
				} else {
					$elementor['_siteseo_robots_index'] = '';
				}
				if (isset($_POST['siteseo_robots_follow'])) {
					$elementor['_siteseo_robots_follow'] = 'yes';
				} else {
					$elementor['_siteseo_robots_follow'] = '';
				}
				if (isset($_POST['siteseo_robots_imageindex'])) {
					$elementor['_siteseo_robots_imageindex'] = 'yes';
				} else {
					$elementor['_siteseo_robots_imageindex'] = '';
				}
				if (isset($_POST['siteseo_robots_archive'])) {
					$elementor['_siteseo_robots_archive'] = 'yes';
				} else {
					$elementor['_siteseo_robots_archive'] = '';
				}
				if (isset($_POST['siteseo_robots_snippet'])) {
					$elementor['_siteseo_robots_snippet'] = 'yes';
				} else {
					$elementor['_siteseo_robots_snippet'] = '';
				}
				if (isset($_POST['siteseo_robots_canonical'])) {
					$elementor['_siteseo_robots_canonical'] = siteseo_opt_post('siteseo_robots_canonical');
				}
				if (isset($_POST['siteseo_robots_primary_cat'])) {
					$elementor['_siteseo_robots_primary_cat'] = siteseo_opt_post('siteseo_robots_primary_cat');
				}
				if (isset($_POST['siteseo_social_fb_title'])) {
					$elementor['_siteseo_social_fb_title'] = siteseo_opt_post('siteseo_social_fb_title');
				}
				if (isset($_POST['siteseo_social_fb_desc'])) {
					$elementor['_siteseo_social_fb_desc'] = siteseo_opt_post('siteseo_social_fb_desc');
				}
				if (isset($_POST['siteseo_social_fb_img'])) {
					$elementor['_siteseo_social_fb_img'] = siteseo_opt_post('siteseo_social_fb_img');
				}
				if (isset($_POST['siteseo_social_twitter_title'])) {
					$elementor['_siteseo_social_twitter_title'] = siteseo_opt_post('siteseo_social_twitter_title');
				}
				if (isset($_POST['siteseo_social_twitter_desc'])) {
					$elementor['_siteseo_social_twitter_desc'] = siteseo_opt_post('siteseo_social_twitter_desc');
				}
				if (isset($_POST['siteseo_social_twitter_img'])) {
					$elementor['_siteseo_social_twitter_img'] = siteseo_opt_post('siteseo_social_twitter_img');
				}
				if (isset($_POST['siteseo_redirections_type'])) {
					$elementor['_siteseo_redirections_type'] = siteseo_opt_post('siteseo_redirections_type');
				}
				if (isset($_POST['siteseo_redirections_value'])) {
					$elementor['_siteseo_redirections_value'] = siteseo_opt_post('siteseo_redirections_value');
				}
				if (isset($_POST['siteseo_redirections_param'])) {
					$elementor['_siteseo_redirections_param'] = siteseo_opt_post('siteseo_redirections_param');
				}
				if (isset($_POST['siteseo_redirections_enabled'])) {
					$elementor['_siteseo_redirections_enabled'] = 'yes';
				} else {
					$elementor['_siteseo_redirections_enabled'] = '';
				}
				update_post_meta($post_id, '_elementor_page_settings', $elementor);
			}
		}

		do_action('siteseo_seo_metabox_save', $post_id, $seo_tabs);
	}
}

function siteseo_content_analysis($post){
	$prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
	wp_nonce_field(plugin_basename(__FILE__), 'siteseo_content_analysis_nonce');

	//Tagify
	wp_enqueue_script('siteseo-tagify-js-1', SITESEO_ASSETS_DIR . '/js/tagify.min.js', ['jquery'], SITESEO_VERSION, true);
	wp_register_style('siteseo-tagify-1', SITESEO_ASSETS_DIR . '/css/tagify.min.css', [], SITESEO_VERSION);
	wp_enqueue_style('siteseo-tagify-1');

	wp_enqueue_script('siteseo-cpt-counters-js', SITESEO_ASSETS_DIR . '/js/siteseo-counters' . $prefix . '.js', ['jquery', 'jquery-ui-tabs', 'jquery-ui-accordion', 'jquery-ui-autocomplete'], SITESEO_VERSION);
	$siteseo_real_preview = [
		'siteseo_nonce' => wp_create_nonce('siteseo_real_preview_nonce'),
		'siteseo_real_preview' => admin_url('admin-ajax.php'),
		'i18n' => ['progress' => __('Analysis in progress...', 'siteseo')],
		'ajax_url' => admin_url('admin-ajax.php'),
		'get_preview_meta_title' => wp_create_nonce('get_preview_meta_title'),
		'realtime_nonce' => wp_create_nonce('siteseo_realtime_nonce'),
	];
	wp_localize_script('siteseo-cpt-counters-js', 'siteseoAjaxRealPreview', $siteseo_real_preview);

	$siteseo_inspect_url = [
		'siteseo_nonce' => wp_create_nonce('siteseo_inspect_url_nonce'),
		'siteseo_inspect_url' => admin_url('admin-ajax.php'),
	];
	wp_localize_script('siteseo-cpt-counters-js', 'siteseoAjaxInspectUrl', $siteseo_inspect_url);
	
	$metabox_data = [];

	$metabox_data['analysis_target_kw'] = get_post_meta($post->ID, '_siteseo_analysis_target_kw', true);
	$metabox_data['analysis_data'] = get_post_meta($post->ID, '_siteseo_analysis_data', true);
	$metabox_data['readibility_data'] = get_post_meta($post->ID, '_siteseo_readibility_data', true);
	$metabox_data['meta_title'] = get_post_meta($post->ID, '_siteseo_titles_title', true);
	$metabox_data['meta_desc'] = get_post_meta($post->ID, '_siteseo_titles_desc', true);
	
	$title_options = get_option('siteseo_titles_option_name', []);

	if(siteseo_titles_single_cpt_noindex_option() || !empty($title_options['titles_noindex']) || true === post_password_required($post->ID)){
		$metabox_data['robots_index'] = 'yes';
	} else {
		$metabox_data['robots_index'] = get_post_meta($post->ID, '_siteseo_robots_index', true);
	}

	if(post_password_required($post->ID) === true || !empty($title_options['titles_noindex']) || siteseo_titles_single_cpt_noindex_option()){
		$metabox_data['robots_index'] = 'yes';
	} else {
		$metabox_data['robots_index'] = get_post_meta($post->ID, '_siteseo_robots_index', true);
	}

	if(!empty($title_options['titles_nofollow']) || siteseo_titles_single_cpt_nofollow_option()){
		$metabox_data['robots_follow'] = 'yes';
	} else {
		$metabox_data['robots_follow'] = get_post_meta($post->ID, '_siteseo_robots_follow', true);
	}

	if(!empty($title_options['titles_noarchive'])){
		$metabox_data['robots_archive'] = 'yes';
	} else {
		$metabox_data['robots_archive'] = get_post_meta($post->ID, '_siteseo_robots_archive', true);
	}

	if(!empty($title_options['titles_nosnippet'])){
		$metabox_data['robots_snippet'] = 'yes';
	} else {
		$metabox_data['robots_snippet'] = get_post_meta($post->ID, '_siteseo_robots_snippet', true);
	}

	if(!empty($title_options['titles_noimageindex'])){
		$metabox_data['robots_imageindex'] = 'yes';
	} else {
		$metabox_data['robots_imageindex'] = get_post_meta($post->ID, '_siteseo_robots_imageindex', true);
	}

	require_once dirname(__FILE__) . '/metabox-content-analysis.php'; //Metaboxe HTML
	
	siteseo_content_analysis_tab($metabox_data);
}

add_action('save_post', 'siteseo_save_ca_metabox', 10, 2);
function siteseo_save_ca_metabox($post_id, $post){
	//Nonce
	if (! isset($_POST['siteseo_content_analysis_nonce']) || ! wp_verify_nonce(siteseo_opt_post('siteseo_content_analysis_nonce'), plugin_basename(__FILE__))) {
		return $post_id;
	}

	//Post type object
	$post_type = get_post_type_object($post->post_type);

	//Check permission
	if (! current_user_can($post_type->cap->edit_post, $post_id)) {
		return $post_id;
	}

	if ('attachment' !== get_post_type($post_id)) {
		if (isset($_POST['siteseo_analysis_target_kw'])) {
			update_post_meta($post_id, '_siteseo_analysis_target_kw', siteseo_opt_post('siteseo_analysis_target_kw'));
		}

		if (did_action('elementor/loaded')) {
			$elementor = get_post_meta($post_id, '_elementor_page_settings', true);

			if (! empty($elementor)) {
				if (isset($_POST['siteseo_analysis_target_kw'])) {
					$elementor['_siteseo_analysis_target_kw'] = siteseo_opt_post('siteseo_analysis_target_kw');
				}
				update_post_meta($post_id, '_elementor_page_settings', $elementor);
			}
		}
	}
}

//Save metabox values in elementor
add_action('save_post', 'siteseo_update_elementor_fields', 999, 2);
function siteseo_update_elementor_fields($post_id, $post){
	do_action('siteseo/page-builders/elementor/save_meta', $post_id);
}

if(is_user_logged_in()){
	if(is_super_admin()){
		$siteseo->display_seo_metaboxe = 1;
		$siteseo->display_ca_metaboxe = 1;
	}else{
		global $wp_roles;
		$user = wp_get_current_user();
		//Get current user role
		if (isset($user->roles) && is_array($user->roles) && ! empty($user->roles)) {
			$siteseo_user_role = current($user->roles);
			
			$siteseo_options = get_option('siteseo_advanced_option_name');

			//If current user role matchs values from Security settings then apply -- SEO Metaboxe
			if (!empty($siteseo_options) && isset($siteseo_options['siteseo_advanced_security_metaboxe_role']) && array_key_exists($siteseo_user_role, $siteseo_options['siteseo_advanced_security_metaboxe_role'])) {
				//do nothing
			} else {
				$siteseo->display_seo_metaboxe = 1;
			}

			//If current user role matchs values from Security settings then apply -- SEO Content Analysis
			if (!empty($siteseo_options) && isset($siteseo_options['security_metaboxe_ca_role']) && array_key_exists($siteseo_user_role, $siteseo_options['security_metaboxe_ca_role'])) {
				//do nothing
			} else {
				$siteseo->display_ca_metaboxe = 1;
			}
		}
	}
	
	if(!empty($siteseo->display_seo_metaboxe)){
		siteseo_display_seo_metaboxe();
	}
	
}

function siteseo_metabox_data($post_type){
	global $post;

	$metabox_data = [];
	$metabox_data['title'] = $post->post_title;
	$metabox_data['excerpt'] = $post->post_excerpt;

	// Getting the first paragraph of the post
	if(empty($metabox_data['excerpt'])){
		$post_seperated = get_extended($post->post_content);

		if(!empty($post_seperated['main'])){
			$metabox_data['excerpt'] = wp_strip_all_tags($post_seperated['main']);
		}
	}

	$metabox_data['meta_title'] = get_post_meta($post->ID, '_siteseo_titles_title', true);
	$metabox_data['meta_desc'] = get_post_meta($post->ID, '_siteseo_titles_desc', true);
	$metabox_data['robots_canonical'] = get_post_meta($post->ID, '_siteseo_robots_canonical', true);
	$metabox_data['robots_primary_cat'] = get_post_meta($post->ID, '_siteseo_robots_primary_cat', true);
	$metabox_data['fb_title'] = get_post_meta($post->ID, '_siteseo_social_fb_title', true);
	$metabox_data['fb_desc'] = get_post_meta($post->ID, '_siteseo_social_fb_desc', true);
	$metabox_data['fb_img'] = get_post_meta($post->ID, '_siteseo_social_fb_img', true);
	$siteseo_social_fb_img_attachment_id	= get_post_meta($post->ID, '_siteseo_social_fb_img_attachment_id', true);
	$siteseo_social_fb_img_width		= get_post_meta($post->ID, '_siteseo_social_fb_img_width', true);
	$siteseo_social_fb_img_height		= get_post_meta($post->ID, '_siteseo_social_fb_img_height', true);
	$metabox_data['x_title'] = get_post_meta($post->ID, '_siteseo_social_twitter_title', true);
	$metabox_data['x_desc'] = get_post_meta($post->ID, '_siteseo_social_twitter_desc', true);
	$metabox_data['x_img'] = get_post_meta($post->ID, '_siteseo_social_twitter_img', true);
	$siteseo_social_twitter_img_attachment_id = get_post_meta($post->ID, '_siteseo_social_twitter_img_attachment_id', true);
	$siteseo_social_twitter_img_width	= get_post_meta($post->ID, '_siteseo_social_twitter_img_width', true);
	$siteseo_social_twitter_img_height	= get_post_meta($post->ID, '_siteseo_social_twitter_img_height', true);
	$metabox_data['redirections_enabled'] = get_post_meta($post->ID, '_siteseo_redirections_enabled', true);
	$metabox_data['redirections_enabled_regex']	= get_post_meta($post->ID, '_siteseo_redirections_enabled_regex', true);
	$metabox_data['redirections_logged_status']	= get_post_meta($post->ID, '_siteseo_redirections_logged_status', true);
	$metabox_data['redirections_type'] = get_post_meta($post->ID, '_siteseo_redirections_type', true);
	$metabox_data['redirections_value'] = get_post_meta($post->ID, '_siteseo_redirections_value', true);
	$metabox_data['redirections_param'] = get_post_meta($post->ID, '_siteseo_redirections_param', true);

	$title_options = get_option('siteseo_titles_option_name', []);
	$metabox_data['disabled_robots'] = [
		'robots_index' => '',
		'robots_follow' => '',
		'archive' => '',
		'snippet' => '',
		'imageindex' => '',
	];

	if(post_password_required($post->ID) === true || !empty($title_options['titles_noindex']) || siteseo_titles_single_cpt_noindex_option()){
		$metabox_data['robots_index'] = 'yes';
		$metabox_data['disabled_robots']['robots_index'] = 'disabled';
	} else {
		$metabox_data['robots_index'] = get_post_meta($post->ID, '_siteseo_robots_index', true);
	}

	if(!empty($title_options['titles_nofollow']) || siteseo_titles_single_cpt_nofollow_option()){
		$metabox_data['robots_follow'] = 'yes';
		$metabox_data['disabled_robots']['robots_follow'] = 'disabled';
	} else {
		$metabox_data['robots_follow'] = get_post_meta($post->ID, '_siteseo_robots_follow', true);
	}

	if(!empty($title_options['titles_noarchive'])){
		$metabox_data['robots_archive'] = 'yes';
		$metabox_data['disabled_robots']['archive'] = 'disabled';
	} else {
		$metabox_data['robots_archive'] = get_post_meta($post->ID, '_siteseo_robots_archive', true);
	}

	if(!empty($title_options['titles_nosnippet'])){
		$metabox_data['robots_snippet'] = 'yes';
		$metabox_data['disabled_robots']['snippet'] = 'disabled';
	} else {
		$metabox_data['robots_snippet'] = get_post_meta($post->ID, '_siteseo_robots_snippet', true);
	}

	if(!empty($title_options['titles_noimageindex'])){
		$metabox_data['robots_imageindex'] = 'yes';
		$metabox_data['disabled_robots']['imageindex'] = 'disabled';
	} else {
		$metabox_data['robots_imageindex'] = get_post_meta($post->ID, '_siteseo_robots_imageindex', true);
	}

	return $metabox_data;
}
