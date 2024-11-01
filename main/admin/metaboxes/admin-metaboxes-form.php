<?php
/*
* SiteSEO
* https://siteseo.io/
* (c) SiteSEO Team <support@siteseo.io>
*/

if(!defined('ABSPATH')){
	die('HACKING ATTEMPT!');
}

function siteseo_metabox_form_html(&$metabox_data){
	global $siteseo, $post, $pagenow, $typenow;

	$data_attr = [];
	$data_attr['data_tax'] = '';
	$data_attr['termId'] = '';

	if('post-new.php' == $pagenow || 'post.php' == $pagenow){
		$data_attr['current_id'] = get_the_id();
		$data_attr['origin'] = 'post';
		$data_attr['title'] = get_the_title($data_attr['current_id']);
	} elseif('term.php' == $pagenow || 'edit-tags.php' == $pagenow){
		global $tag;
		$data_attr['current_id'] = $tag->term_id;
		$data_attr['termId'] = $tag->term_id;
		$data_attr['origin'] = 'term';
		$data_attr['data_tax'] = $tag->taxonomy;
		$data_attr['title'] = $tag->name;
	}

	$data_attr['isHomeId'] = get_option('page_on_front');
	if($data_attr['isHomeId'] === '0'){
		$data_attr['isHomeId'] = '';
	}

	// Static Data
	$home_url = home_url();
	$parsed_url = parse_url($home_url);
	$host_uri = $parsed_url['host'];
	$social_placeholder = SITESEO_ASSETS_DIR . '/img/social-placeholder.png';

	$metabox_tag_drop_kses = [
		'button' => [
			'class' => true,
			'type' => true,
		],
		'span' => [
			'class' => true,
		],
		'div' => [
			'class' => true,
			'style' => true,
		],
		'input' => [
			'type' => true,
			'class' => true,
			'name' => true,
			'spellcheck' => true,
			'placeholder' => true,
		],
		'ul' => true,
		'li' => [
			'class' => true,
			'data-*' => true,
			'tabindex' => true,
		]
	];

	$siteseo_metabox_tabs = [
		'content-analysis' => __('Content Analysis', 'siteseo')
	];
		
	if($typenow != 'siteseo_404'){		
		$siteseo_metabox_tabs['title-settings'] = __('Title', 'siteseo');
		$siteseo_metabox_tabs['social-settings'] = __('Social', 'siteseo');
		$siteseo_metabox_tabs['advanced-settings'] = __('Advanced', 'siteseo');
	}

	$siteseo_metabox_tabs['redirect'] = __('Redirects', 'siteseo');

echo '<div id="siteseo-metabox-wrapper" class="siteseo-metabox-wrapper">
<div class="siteseo-metabox-tabs" data-home-id="'.esc_attr($data_attr['isHomeId']).'" data-term-id="'.esc_attr($data_attr['termId']).'" data_id="'.esc_attr($data_attr['current_id']).'" data_origin="'.esc_attr($data_attr['origin']).'" data_tax="'.esc_attr($data_attr['data_tax']).'">';

	foreach($siteseo_metabox_tabs as $siteseo_metabox_tab => $siteseo_metabox_tab_title){
		$selected_metabox_tab = '';

		// We don't want to show the content analysis to everyone.
		if(empty($siteseo->display_ca_metaboxe) && $siteseo_metabox_tab === 'content-analysis'){
			continue;
		}

		if($siteseo_metabox_tab === 'content-analysis'){
			$selected_metabox_tab = 'siteseo-metabox-tab-label-active';
		}

		if(empty($siteseo->display_ca_metaboxe) && $siteseo_metabox_tab === 'title-settings'){
			$selected_metabox_tab = 'siteseo-metabox-tab-label-active';
		}			
		
		
		echo '<div class="siteseo-metabox-tab-label '.esc_attr($selected_metabox_tab).'" data-tab="siteseo-metabox-tab-'.esc_attr($siteseo_metabox_tab).'">'.esc_html($siteseo_metabox_tab_title).'</div>';
	}
	
$home_url = home_url();
$parsed_home_url = parse_url($home_url);

$meta_desc_percentage = '1';
if(!empty($metabox_data['meta_desc'])){
	$meta_desc_percentage = (strlen($metabox_data['meta_desc'])/160)*100;
} elseif(!empty($metabox_data['excerpt'])){
	$meta_desc_percentage = (strlen($metabox_data['excerpt'])/160)*100;
}

if(intval($meta_desc_percentage) > 100){
	$meta_desc_percentage = '100';
}

$meta_title_percentage = '1';
if(!empty($metabox_data['meta_title'])){
	$meta_title_percentage = (strlen($metabox_data['meta_title'])/60)*100;
} else if(!empty($metabox_data['title'])){
	$meta_title_percentage = (strlen($metabox_data['title'])/60)*100;
}

if(intval($meta_title_percentage) > 100){
	$meta_title_percentage = '100';
}

echo '</div>';
if(!empty($siteseo->display_ca_metaboxe)){
echo '<div class="siteseo-sidebar-tabs siteseo-sidebar-tabs-opened"><span>'.esc_html__('Content Analysis', 'siteseo').'</span><span class="siteseo-sidebar-tabs-arrow"><span class="dashicons dashicons-arrow-down-alt2"></span></span></div>
<div class="siteseo-metabox-tab-content-analysis siteseo-metabox-tab" style="display:block;">';
	siteseo_content_analysis($post);
echo'</div>';
}

echo '
<div class="siteseo-sidebar-tabs '.(empty($siteseo->display_ca_metaboxe) ? 'siteseo-sidebar-tabs-opened' : '').'"><span>'.esc_html__('Title', 'siteseo').'</span><span class="siteseo-sidebar-tabs-arrow"><span class="dashicons dashicons-arrow-down-alt2"></span></span></div>
<div class="siteseo-metabox-tab-title-settings siteseo-metabox-tab" style="'.(empty($siteseo->display_ca_metaboxe) ? 'display:block;' : '').'">
<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label>'.esc_html__('Search Preview','siteseo').'</label>
	</div>
	<div class="siteseo-metabox-search-preview">
		<div class="siteseo-search-preview-toggle">
			<span id="siteseo-metabox-search-pc" style="display:none">'.esc_html__('Show Desktop version', 'siteseo').'</span>
			<span id="siteseo-metabox-search-mobile">'.esc_html__('Show Mobile version', 'siteseo').'</span>
		</div>
		<div class="siteseo-search-preview-desktop">
			<div class="siteseo-search-preview-metadata">
				<div style="background-color: #e2eeff; border: 1px solid #e2eeff; height:28px; width:28px; padding: 3px; border-radius: 50px; display:flex; align-items:center; justify-content:center;">
				<svg focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#0060f0"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"></path></svg>
				</div>
				<div class="siteseo-search-preview-metadata-link">
					<div>'.esc_url($parsed_home_url['host']).'</div>
					<div><cite>'.esc_url(home_url()).'</cite></div>
				</div>
				<div>
				<svg focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path></svg>
				</div>
			</div>
			<h3>'.(!empty($metabox_data['meta_title']) ? esc_html(siteseo_resolve_text($metabox_data['meta_title'])) : (!empty($metabox_data['title']) ? esc_html($metabox_data['title']) : 'Post Title here')).'</h3>
			<div class="siteseo-search-preview-description">
				'.(!empty($metabox_data['meta_desc']) ? esc_html(siteseo_resolve_text($metabox_data['meta_desc'])) : (!empty($metabox_data['excerpt']) ? esc_html($metabox_data['excerpt']) : 'Post description')).'
			</div>
			
		</div>
	</div>
</div>
<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label for="siteseo_titles_title_meta">'.esc_html__('Title').'</label>
	</div>
	<div class="siteseo-metabox-input-wrap">
		<div class="siteseo-metabox-tags">
			<button type="button" class="siteseo-metabox-tag" data-tag="%%post_title%%"><span class="dashicons dashicons-plus"></span> Post Title</button>
			<button type="button" class="siteseo-metabox-tag" data-tag="%%sitetitle%%"><span class="dashicons dashicons-plus"></span> Site Title</button>
			<button type="button" class="siteseo-metabox-tag" data-tag="%%sep%%"><span class="dashicons dashicons-plus"></span> Seperator</button>
			'.wp_kses(siteseo_render_dyn_variables('tag-title siteseo-metabox-tag'), $metabox_tag_drop_kses).'
		</div>
		<input type="text" id="siteseo_titles_title_meta" class="siteseo_titles_title_meta" name="siteseo_titles_title" placeholder="'.(!empty($metabox_data['title']) ? esc_attr($metabox_data['title']) : esc_html__('Enter title for this post', 'siteseo')).'" value="'.(!empty($metabox_data['meta_title']) ? esc_html($metabox_data['meta_title']) : '').'"/>
		<div class="siteseo-metabox-limits">
			<span class="siteseo-metabox-limits-meter"><span style="width:'.esc_attr($meta_title_percentage).'%"></span></span>
			<span class="siteseo-metabox-limits-numbers"><em>'.esc_html(strlen($metabox_data['meta_title'])).'</em> out of 60 max recommended characters</span>
		</div>
	</div>
</div>
<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label for="siteseo_titles_desc_meta">'.esc_html__('Meta Description').'</label>
	</div>
	<div class="siteseo-metabox-input-wrap">
		<div class="siteseo-metabox-tags">
			<button type="button" class="siteseo-metabox-tag" data-tag="%%post_excerpt%%"><span class="dashicons dashicons-plus"></span> Post Excerpt</button>
			'.wp_kses(siteseo_render_dyn_variables('tag-description siteseo-metabox-tag'), $metabox_tag_drop_kses).'
		</div>
		<textarea id="siteseo_titles_desc_meta" class="siteseo_titles_desc_meta" name="siteseo_titles_desc" rows="2" placeholder="'.esc_html__('Enter description for this post', 'siteseo').'">'.(!empty($metabox_data['meta_desc']) ? esc_html($metabox_data['meta_desc']) : '').'</textarea>
		<div class="siteseo-metabox-limits">
			<span class="siteseo-metabox-limits-meter"><span style="width:'.esc_attr($meta_desc_percentage).'%"></span></span>
			<span class="siteseo-metabox-limits-numbers"><em>'.esc_html(strlen($metabox_data['meta_desc'])).'</em> out of 160 max recommended characters</span>
		</div>
	</div>
</div>
</div>

<div class="siteseo-sidebar-tabs"><span>'.esc_html__('Social', 'siteseo').'</span><span class="siteseo-sidebar-tabs-arrow"><span class="dashicons dashicons-arrow-down-alt2"></span></span></div>
<div class="siteseo-metabox-tab-social-settings siteseo-metabox-tab">
	<div class="siteseo-metabox-subtabs">
		<div class="siteseo-metabox-tab-label siteseo-metabox-tab-label-active" data-tab="siteseo-metabox-tab-fb-settings">Facebook</div>
		<div class="siteseo-metabox-tab-label" data-tab="siteseo-metabox-tab-x-settings">X(Twitter)</div>
	</div>
	<div class="siteseo-metabox-tab-fb-settings siteseo-metabox-tab" style="display:block;">
	<div class="siteseo-metabox-option-wrap">
		<div class="siteseo-metabox-label-wrap">
			<label>'.esc_html__('Preview','siteseo').'</label>
		</div>
		<div class="siteseo-metabox-fb-preview">
			<div class="siteseo-metabox-fb-image">
				<img src="'.(!empty($metabox_data['fb_img']) ? esc_url($metabox_data['fb_img']) : esc_url($social_placeholder)).'" alt="Facebook preview" load="lazy"/>
			</div>
			<div class="siteseo-metabox-fb-data">
				<div class="siteseo-metabox-fb-host">'.(!empty($host_uri) ? esc_html($host_uri) : '').'</div>
				<div class="siteseo-metabox-fb-title">'.(!empty($metabox_data['fb_title']) ? esc_html($metabox_data['fb_title']) : esc_html($metabox_data['meta_title'])).'</div>
				<div class="siteseo-metabox-fb-desc">'.(!empty($metabox_data['fb_desc']) ? esc_html($metabox_data['fb_desc']) : esc_html($metabox_data['meta_desc'])).'</div>
			</div>
		</div>
	</div>
	<div class="siteseo-metabox-option-wrap">
		<div class="siteseo-metabox-label-wrap">
			<label for="siteseo_social_fb_title_meta">'.esc_html__('Facebook Title', 'siteseo').'</label>
		</div>
		<div class="siteseo-metabox-input-wrap">
			<input type="text" id="siteseo_social_fb_title_meta" name="siteseo_social_fb_title" placeholder="'.(!empty($metabox_data['meta_title']) ? esc_html($metabox_data['meta_title']) : '').'" value="'.(!empty($metabox_data['fb_title']) ? esc_attr($metabox_data['fb_title']) : '').'" />
		</div>
	</div>

	<div class="siteseo-metabox-option-wrap">
		<div class="siteseo-metabox-label-wrap">
			<label for="siteseo_social_fb_desc_meta">'.esc_html__('Facebook description', 'siteseo').'</label>
		</div>
		<div class="siteseo-metabox-input-wrap">
			<textarea id="siteseo_social_fb_desc_meta" name="siteseo_social_fb_desc" rows="2" placeholder="'.(!empty($metabox_data['meta_desc']) ? esc_html($metabox_data['meta_desc']) : '').'">'.(!empty($metabox_data['fb_desc']) ? esc_html($metabox_data['fb_desc']) : '').'</textarea>
		</div>
	</div>
	<div class="siteseo-metabox-option-wrap">
		<div class="siteseo-metabox-label-wrap">
			<label for="siteseo_social_fb_img_meta">'.esc_html__('Facebook Thumbnail', 'siteseo').'</label>
		</div>
		<div class="siteseo-metabox-input-wrap">
			<span style="color:red; font-weight:bold; display:none;"></span>
			<input type="text" id="siteseo_social_fb_img_meta" name="siteseo_social_fb_img" class="siteseo_social_fb_img_meta" placeholder="'.esc_html__('Enter URL of the Image you want to be shown as the Facebook image', 'siteseo').'" value="'.(!empty($metabox_data['fb_img']) ? esc_url($metabox_data['fb_img']) : '').'"/>
			<p class="description">'.esc_html__('Minimum size: 200x200px, ideal ratio 1.91:1, 8Mb max. (eg: 1640x856px or 3280x1712px for retina screens).', 'siteseo').'</p>
			<input type="hidden" name="siteseo_social_fb_img_attachment_id" id="siteseo_social_fb_img_attachment_id" class="siteseo_social_fb_img_attachment_id" value="">
			<input type="hidden" name="siteseo_social_fb_img_width" id="siteseo_social_fb_img_width" class="siteseo_social_fb_img_width" value="">
			<input type="hidden" name="siteseo_social_fb_img_height" id="siteseo_social_fb_img_height" class="siteseo_social_fb_img_height" value="">
			<button class="components-button is-secondary" id="siteseo_social_fb_img_upload">Upload Image</button>
		</div>
	</div>
	</div>

	<div class="siteseo-metabox-tab-x-settings siteseo-metabox-tab">
	<div class="siteseo-metabox-option-wrap">
		<div class="siteseo-metabox-label-wrap">
			<label>'.esc_html__('Preview','siteseo').'</label>
		</div>
		<div>
		<div class="siteseo-metabox-x-preview">
			<div class="siteseo-metabox-x-image">
				<img src="'.(!empty($metabox_data['x_img']) ? esc_url($metabox_data['x_img']) : esc_url($social_placeholder)).'" alt="X preview" load="lazy"/>
			</div>
			<div class="siteseo-metabox-x-data">
				<div class="siteseo-metabox-x-title">'.(!empty($metabox_data['x_title']) ? esc_html($metabox_data['x_title']) : esc_html($metabox_data['meta_title'])).'</div>
			</div>
		</div>
		<div class="siteseo-metabox-x-host">From '.(!empty($host_uri) ? esc_html($host_uri) : '').'</div>
		</div>
	</div>
	<div class="siteseo-metabox-option-wrap">
		<div class="siteseo-metabox-label-wrap">
			<label for="siteseo_social_twitter_title_meta">'.esc_html__('X Title', 'siteseo').'</label>
		</div>
		<div class="siteseo-metabox-input-wrap">
			<input type="text" id="siteseo_social_twitter_title_meta" name="siteseo_social_twitter_title" placeholder="'.(!empty($metabox_data['meta_title']) ? esc_html($metabox_data['meta_title']) : '').'" value="'.(!empty($metabox_data['x_title']) ? esc_attr($metabox_data['x_title']) : '').'" />
		</div>
	</div>
	<div class="siteseo-metabox-option-wrap">
		<div class="siteseo-metabox-label-wrap">
			<label for="siteseo_social_twitter_desc_meta">'.esc_html__('X description', 'siteseo').'</label>
		</div>
		<div class="siteseo-metabox-input-wrap">
			<textarea id="siteseo_social_twitter_desc_meta" name="siteseo_social_twitter_desc" rows="2" placeholder="'.(!empty($metabox_data['meta_desc']) ? esc_html($metabox_data['meta_desc']) : '').'">'.(!empty($metabox_data['fb_desc']) ? esc_attr($metabox_data['x_desc']) : '').'</textarea>
		</div>
	</div>
	<div class="siteseo-metabox-option-wrap">
		<div class="siteseo-metabox-label-wrap">
			<label for="siteseo_social_twitter_img_meta">'.esc_html__('X Thumbnail', 'siteseo').'</label>
		</div>
		<div class="siteseo-metabox-input-wrap">
			<span style="color:red; font-weight:bold; display:none;"></span>
			<input type="text" id="siteseo_social_twitter_img_meta" name="siteseo_social_twitter_img" placeholder="'.esc_html__('Enter URL of the Image you want to be shown as the X image', 'siteseo').'" value="'.(!empty($metabox_data['x_img']) ? esc_attr($metabox_data['x_img']) : '').'" />
			<p class="description">'.esc_html__('Minimum size: 144x144px (300x157px with large card enabled), ideal ratio 1:1 (2:1 with large card), 5Mb max.', 'siteseo').'</p>
			<input type="hidden" name="siteseo_social_twitter_img_attachment_id" id="siteseo_social_twitter_img_attachment_id" class="siteseo_social_twitter_img_attachment_id" value="">
			<input type="hidden" name="siteseo_social_twitter_img_width" id="siteseo_social_twitter_img_width" class="siteseo_social_twitter_img_width" value="">
			<input type="hidden" name="siteseo_social_twitter_img_height" id="siteseo_social_twitter_img_height" class="siteseo_social_twitter_img_height" value="">
			<button class="components-button is-secondary" id="siteseo_social_twitter_img_upload">Upload Image</button>
		</div>
	</div>
	</div>
</div>

<div class="siteseo-sidebar-tabs"><span>'.esc_html__('Advanced', 'siteseo').'</span><span class="siteseo-sidebar-tabs-arrow"><span class="dashicons dashicons-arrow-down-alt2"></span></span></div>
<div class="siteseo-metabox-tab-advanced-settings siteseo-metabox-tab">
<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label for="siteseo_social_twitter_img_meta">'.esc_html__('Meta Robots Settings', 'siteseo').'</label>
		<p class="description">'. wp_kses_post(sprintf(__('You cannot uncheck a checkbox? This is normal, and it\'s most likely defined in the <a href="%s">global settings of the plugin.</a>', 'siteseo'), esc_url(admin_url('admin.php?page=siteseo-titles#tab=tab_siteseo_titles_single')))).'</p>
	</div>
	<div class="siteseo-metabox-input-wrap">';
		
	$robots_options = [
		'siteseo_robots_index_meta' => [
			'desc' => __('Do not display this page in search engine results / Sitemaps', 'siteseo'),
			'short' => 'noindex',
			'name' => 'siteseo_robots_index',
			'checked' => $metabox_data['robots_index'],
			'disabled' => $metabox_data['disabled_robots']['robots_index']
		],
		'siteseo_robots_follow_meta' => [
			'desc' => __('Do not follow links for this page', 'siteseo'),
			'short' => 'nofollow',
			'name' => 'siteseo_robots_follow',
			'checked' => $metabox_data['robots_follow'],
			'disabled' => $metabox_data['disabled_robots']['robots_follow']
		],
		'siteseo_robots_imageindex_meta' => [
			'desc' => __('Do not index images for this page', 'siteseo'),
			'short' => 'noimageindex',
			'name' => 'siteseo_robots_imageindex',
			'checked' => $metabox_data['robots_imageindex'],
			'disabled' => $metabox_data['disabled_robots']['imageindex']
		],
		'siteseo_robots_archive_meta' => [
			'desc' => __('Do not display a "Cached" link in the Google search results', 'siteseo'),
			'short' => 'noarchive',
			'name' => 'siteseo_robots_archive',
			'checked' => $metabox_data['robots_archive'],
			'disabled' => $metabox_data['disabled_robots']['archive']
		],
		'siteseo_robots_snippet_meta' => [
			'desc' => __('Do not display a description in search results for this page', 'siteseo'),
			'short' => 'nosnippet',
			'name' => 'siteseo_robots_snippet',
			'checked' => $metabox_data['robots_snippet'],
			'disabled' => $metabox_data['disabled_robots']['snippet']
		]
	];
	
	foreach($robots_options as $robots_id => $robots_option){
		$checked = '';
		if(!empty($robots_option['checked'])){
			$checked = 'checked';
		}
		
		$disabled = '';
		if(!empty($robots_option['disabled'])){
			$disabled = 'disabled';
			$robots_option['name'] = '';
		}

		echo '<label for="'.esc_attr($robots_id).'" style="display:block; margin-bottom:5px;">
			<input type="checkbox" value="yes" id="'.esc_attr($robots_id).'" class="siteseo-metabox-robots-options" name="'.esc_attr($robots_option['name']).'" '.esc_attr($checked).' '.esc_attr($disabled).'/>
			'.esc_html($robots_option['desc']).' ('.esc_html($robots_option['short']).')
		</label>';
	}
	
	echo '</div>
</div>
<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label for="siteseo_robots_canonical_meta">'.esc_html__('Canonical URL', 'siteseo').'</label>
	</div>
	<div class="siteseo-metabox-input-wrap">
		<input id="siteseo_robots_canonical_meta" type="text" name="siteseo_robots_canonical" placeholder="'.esc_url(get_the_permalink()).'" value="'.(!empty($metabox_data['robots_canonical']) ? esc_html($metabox_data['robots_canonical']) : '').'">
	</div>
</div>';

if(!empty($pagenow) && !empty($typenow) && ($pagenow == 'post.php' || $pagenow == 'post-new.php') && ($typenow == 'post' || $typenow == 'product')){

	$categories = (object)[];
	if($typenow == 'product'){
		$categories = get_the_terms($post, 'product_cat');
	} else {
		$categories = get_categories();
	}
	
	if(!empty($categories) && !is_wp_error($categories)){
		echo '<div class="siteseo-metabox-option-wrap">
		<div class="siteseo-metabox-label-wrap">
			<label for="siteseo_robots_canonical_meta">'.esc_html__('Select a primary category', 'siteseo').'</label>
		</div>
		<div class="siteseo-metabox-input-wrap">
			<select id="siteseo_robots_primary_cat" name="siteseo_robots_primary_cat">';
				foreach($categories as $category){
					$selected = '';
					if(!empty($metabox_data['robots_primary_cat']) && $metabox_data['robots_primary_cat'] == $category->term_id){
						$selected = 'selected';
					}

					echo '<option value="'.esc_attr($category->term_id).'" '.esc_attr($selected).'>'.esc_html($category->name).'</option>'; 
				}
			echo '</select>
		</div>
	</div>';
	}

}
echo '</div>

<div class="siteseo-sidebar-tabs"><span>'.esc_html__('Redirects', 'siteseo').'</span><span class="siteseo-sidebar-tabs-arrow"><span class="dashicons dashicons-arrow-down-alt2"></span></span></div>
<div class="siteseo-metabox-tab-redirect siteseo-metabox-tab">
<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label for="siteseo_redirections_enabled_meta">'.esc_html__('Enable redirection', 'siteseo').'</label>
	</div>
	<div class="siteseo-metabox-input-wrap">
		<input id="siteseo_redirections_enabled_meta" type="checkbox" name="siteseo_redirections_enabled" value="yes" '.(!empty($metabox_data['redirections_enabled']) ? 'checked' : '').'>
	</div>
</div>
<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label for="siteseo_redirections_enabled_meta">'.esc_html__('Login status', 'siteseo').'</label>
	</div>
	<div class="siteseo-metabox-input-wrap">
		<select name="siteseo_redirections_logged_status" id="siteseo_redirections_logged_status">
			<option value="both" '.(!empty($metabox_data['redirections_logged_status']) && $metabox_data['redirections_logged_status'] == 'both' ? 'selected' : '').'>'.esc_html__('All', 'siteseo').'</option>
			<option value="only_logged_in" '.(!empty($metabox_data['redirections_logged_status']) && $metabox_data['redirections_logged_status'] == 'only_logged_in' ? 'selected' : '').'>'.esc_html__('Only when logged In', 'siteseo').'</option>
			<option value="only_not_logged_in" '.(!empty($metabox_data['redirections_logged_status']) && $metabox_data['redirections_logged_status'] == 'only_not_logged_in' ? 'selected' : '').'>'.esc_html__('Only when not logged in', 'siteseo').'</option>
		</select>
	</div>
</div>
<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label for="siteseo_redirections_type">'.esc_html__('Redirection Type', 'siteseo').'</label>
	</div>
	<div class="siteseo-metabox-input-wrap">
		<select name="siteseo_redirections_type" id="siteseo_redirections_type">
			<option value="301" '.(!empty($metabox_data['redirections_type']) && $metabox_data['redirections_type'] == '301' ? 'selected' : '').'>'.esc_html__('301 Moved Permanently', 'siteseo').'</option>
			<option value="302" '.(!empty($metabox_data['redirections_type']) && $metabox_data['redirections_type'] == '302' ? 'selected' : '').'>'.esc_html__('302 Found / Moved Temporarily', 'siteseo').'</option>
			<option value="307" '.(!empty($metabox_data['redirections_type']) && $metabox_data['redirections_type'] == '307' ? 'selected' : '').'>'.esc_html__('307 Moved Temporarily', 'siteseo').'</option>';
			if($typenow === 'siteseo_404'){
				echo '<option value="410" '.(!empty($metabox_data['redirections_type']) && $metabox_data['redirections_type'] == '410' ? 'selected' : '').'>'.esc_html__('410 Gone', 'siteseo').'</option>
				<option value="451" '.(!empty($metabox_data['redirections_type']) && $metabox_data['redirections_type'] == '451' ? 'selected' : '').'>'. esc_html__('451 Unavailable For Legal Reasons', 'siteseo').'</option>';
			}
		echo '</select>
	</div>
</div>
<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label for="siteseo_redirections_value_meta">'.esc_html__('Redirection URL', 'siteseo').'</label>
	</div>
	<div class="siteseo-metabox-input-wrap">
		<input id="siteseo_redirections_value_meta" type="text" name="siteseo_redirections_value" value="'.(!empty($metabox_data['redirections_value']) ? esc_attr($metabox_data['redirections_value']): '').'">
	</div>
	<input type="hidden" id="analysis_tabs" name="analysis_tabs" value="'.esc_html(wp_json_encode(array_keys($siteseo_metabox_tabs))).'">
</div>';

if($typenow === 'siteseo_404'){
echo '<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label for="siteseo_redirections_param">'.esc_html__('Query parameters', 'siteseo').'</label>
	</div>
	<div class="siteseo-metabox-input-wrap">
		<select name="siteseo_redirections_param" id="siteseo_redirections_param">
			<option value="exact_match" '.(!empty($metabox_data['redirections_param']) && $metabox_data['redirections_param'] == 'exact_match' ? 'selected' : '').'>'.esc_html__('Exactly parameters with exact match', 'siteseo').'</option>
			<option value="without_param" '.(!empty($metabox_data['redirections_param']) && $metabox_data['redirections_param'] == 'without_param' ? 'selected' : '').'>'.esc_html__('Exclude all parameters', 'siteseo').'</option>
			<option value="with_ignored_param" '.(!empty($metabox_data['redirections_param']) && $metabox_data['redirections_param'] == 'with_ignored_param' ? 'selected' : '').'>'.esc_html__('Exclude all parameters and pass them to the redirection', 'siteseo').'</option>
		</select>
	</div>
</div>';
}

echo '</div>
</div>';

}

function siteseo_resolve_text($text){
	global $pagenow;

	// The text does not have any variable.
	if(strpos($text, '%%') === FALSE){
		return $text;
	}

	$post_id = get_the_id();
	if('term.php' == $pagenow || 'edit-tags.php' == $pagenow){
		global $tag;
		$post_id = $tag->term_id;
		$term_id = $tag->term_id;
	}

	$home_id = (int) get_option('page_on_front');	
	$contextPage = siteseo_get_service('ContextPage')->buildContextWithCurrentId($post_id);

	if(isset($post_id)){
		$contextPage->setPostById($post_id);
		$contextPage->setIsSingle(true);

		$terms = get_the_terms($post_id, 'post_tag');

		if(!empty($terms)){
			$contextPage->setHasTag(true);
		}

		$categories = get_the_terms($post_id, 'category');
		if(!empty($categories)){
			$contextPage->setHasCategory(true);
		}
	}

	if($post_id === $home_id && null !== $home_id){
		$contextPage->setIsHome(true);
	}

	if(isset($term_id) && $term_id !== null && $post_id === $term_id){
		$contextPage->setIsCategory(true);
		$contextPage->setTermId($term_id);
	}

	$resolved_text = siteseo_get_service('TagsToString')->replace($text, $contextPage->getContext());

	return $resolved_text;
}