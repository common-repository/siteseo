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

function siteseo_title_home_tab(){
	$docs = siteseo_get_docs_links();

	$options = get_option('siteseo_titles_option_name');

	$titles_sep = isset($options['titles_sep']) ? $options['titles_sep'] : null;
	$home_site_title = isset($options['titles_home_site_title']) ? $options['titles_home_site_title'] : null;
	$home_site_title_alt = isset($options['titles_home_site_title_alt']) ? $options['titles_home_site_title_alt'] : null;
	$home_site_desc = isset($options['titles_home_site_desc']) ? $options['titles_home_site_desc'] : null;
	
	// List of html entities to allow when escaping siteseo_render_dyn_variables
	$tag_dropdown_kses = [
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
	
	echo '<div class="siteseo-section-header">
		<h2>'.esc_html__('Home', 'siteseo').'</h2>
	</div>

	<div class="siteseo-notice">
		<span class="dashicons dashicons-info"></span>
		<p>'.esc_html__('Title and meta description are used by search engines to generate the snippet of your site in search results page.', 'siteseo').'</p>
	</div>

	<div>
		<p>'.esc_html__('Customize your title & meta description for homepage.', 'siteseo').'</p>

		<span class="dashicons dashicons-external"></span>
		<a href="'.esc_attr($docs['titles']['wrong_meta']).'" target="_blank">'.esc_html__('Wrong meta title / description in SERP?', 'siteseo').'</a>
	</div>

	<script>
		function siteseo_get_field_length(e) {
			if (e.val().length > 0) {
				meta = e.val() + " ";
			} else {
				meta = e.val();
			}
			return meta;
		}
	</script>
	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_sep">'.esc_html__('Separator', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<input type="text" id="siteseo_titles_sep" name="siteseo_titles_option_name[titles_sep]" placeholder="'.esc_html__('Enter your separator, eg: "-"', 'siteseo').'" aria-label="'.esc_html__('Separator', 'siteseo').'" value="'.esc_html($titles_sep).'" />

			<p class="description">'.esc_html__('Use this separator with %%sep%% in your title and meta description.', 'siteseo').'</p>
		</div>
	</div>
	
	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_sep">'.esc_html__('Site title', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<input type="text" id="siteseo_titles_home_site_title"
				name="siteseo_titles_option_name[titles_home_site_title]"
				placeholder="'.esc_html__('My awesome website', 'siteseo').'"
				aria-label="'.esc_html__('Site title', 'siteseo').'"
				value="'.esc_html($home_site_title).'" />

			<div class="wrap-tags">
				<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title" data-tag="%%sitetitle%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Site Title', 'siteseo').'
				</button>

				<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-sep" data-tag="%%sep%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Separator', 'siteseo').'
				</button>

				<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-desc" data-tag="%%tagline%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Tagline', 'siteseo').'
				</button>

				'.wp_kses(siteseo_render_dyn_variables('tag-title'), $tag_dropdown_kses).'
			</div>
		</div>
	</div>	
	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_sep">'.esc_html__('Alternative site title', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<input type="text" id="siteseo_titles_home_site_title_alt"
				name="siteseo_titles_option_name[titles_home_site_title_alt]"
				placeholder="'.esc_html__('My alternative site title', 'siteseo').'"
				aria-label="'.esc_html__('Alternative site title', 'siteseo').'"
				value="'.esc_html($home_site_title_alt).'" />
		
			<p class="description">'.sprintf(wp_kses_post(__('The alternate name of the website (for example, if there\'s a commonly recognized acronym or shorter name for your site), if applicable. Make sure the name meets the <a href="%s" target="_blank">content guidelines</a>.<span class="dashicons dashicons-external"></span>','siteseo')), esc_url($docs['titles']['alt_title'])).'</p>
		</div>
	</div>
		
	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_sep">'.esc_html__('Meta description', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<textarea id="siteseo_titles_home_site_desc" name="siteseo_titles_option_name[titles_home_site_desc]"
				placeholder="'.esc_html__('This is a cool website about Wookiees', 'siteseo').'"
				aria-label="'.esc_html__('Meta description', 'siteseo').'">'.esc_html($home_site_desc).'</textarea>

			<div class="wrap-tags">
				<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-meta-desc" data-tag="%%tagline%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Tagline', 'siteseo').'
				</button>

				'.wp_kses(siteseo_render_dyn_variables('tag-description'), $tag_dropdown_kses).'
			</div>';

			if(get_option('page_for_posts')){
				echo '<p><a href="'.esc_url(admin_url('post.php?post=' . get_option('page_for_posts') . '&action=edit')).'">'.esc_html__('Looking to edit your blog page?', 'siteseo').'</a></p>';
			}
		echo '</div>
	</div>';
}

function siteseo_title_post_tab(){
	
	$options = get_option('siteseo_titles_option_name');

	$anchor_html = '';
	$html = '';
	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
	$active_tab = true;
	$docs = siteseo_get_docs_links();
	
	// List of html entities to allow when escaping siteseo_render_dyn_variables
	$tag_dropdown_kses = [
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

	foreach($postTypes as $anchor_val){
		if(empty(!$anchor_val->labels->name)){
			$active_class = $active_tab ? 'class="siteseo-active-sub-tabs"' : '';
			$anchor_html .='<a '.$active_class.' href="#siteseo-post-type-'.esc_attr(str_replace(" ","-",strtolower(trim($anchor_val->labels->name)))).'">'.esc_html(ucfirst(str_replace("_"," ",$anchor_val->labels->name))).'</a>'; 
			$active_tab = false;
		}
	}

	if(!empty($anchor_html)){
		$html .= '<div class="siteseo-sub-tabs">'. $anchor_html .'</div>';
	}
	
	echo wp_kses_post($html);
	echo '<div class="siteseo-section-body">
		<div class="siteseo-section-header">
			<h2>'.esc_html__('Post Types', 'siteseo').'</h2>
		</div>
		<p>'.esc_html__('Customize your titles & metas for Single Custom Post Types.', 'siteseo').'</p>';

	echo wp_kses_post(siteseo_get_empty_templates('cpt', 'title'));
	echo wp_kses_post(siteseo_get_empty_templates('cpt', 'description'));

	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();

	foreach($postTypes as $siteseo_cpt_key => $siteseo_cpt_value){
		$single_titles = isset($options['titles_single_titles'][$siteseo_cpt_key]['enable']) ? $options['titles_single_titles'][$siteseo_cpt_key]['enable'] : null;
		$single_titles_title = isset($options['titles_single_titles'][$siteseo_cpt_key]['title']) ? $options['titles_single_titles'][$siteseo_cpt_key]['title'] : null;
		$single_titles_description = isset($options['titles_single_titles'][$siteseo_cpt_key]['description']) ? $options['titles_single_titles'][$siteseo_cpt_key]['description'] : null;
		$single_titles_noindex = isset($options['titles_single_titles'][$siteseo_cpt_key]['noindex']);
		$single_titles_nofollow = isset($options['titles_single_titles'][$siteseo_cpt_key]['nofollow']);
		$single_titles_date = isset($options['titles_single_titles'][$siteseo_cpt_key]['date']);
		$thumb_gcs = isset($options['titles_single_titles'][$siteseo_cpt_key]['thumb_gcs']);
		
		
		echo '<h3 id="siteseo-post-type-'.esc_attr(str_replace(" ","-",strtolower(trim($siteseo_cpt_value->labels->name)))).'">'.
			esc_html($siteseo_cpt_value->labels->name).'
			<em><small>['.esc_html($siteseo_cpt_value->name).']</small></em>
			<!--Single on/off CPT-->
			<div class="siteseo_wrap_single_cpt">
				<input id="siteseo_titles_single_cpt_enable['.esc_attr($siteseo_cpt_key).']" data-id='.esc_attr($siteseo_cpt_key).'
				name="siteseo_titles_option_name[titles_single_titles]['.esc_attr($siteseo_cpt_key).'][enable]" class="toggle" type="checkbox" '.(('1' == $single_titles) ? 'checked="yes" data-toggle="0"' : 'data-toggle="1"').' value="1"/>

				<label for="siteseo_titles_single_cpt_enable['.esc_attr($siteseo_cpt_key).']">'.esc_html__('Click to hide any SEO metaboxes / columns for this post type', 'siteseo').'</label>';

				if('1' == $single_titles){
					echo '<span id="titles-state-default" class="feature-state">
						<span class="dashicons dashicons-arrow-left-alt"></span>
						'.esc_html__('Click to display any SEO metaboxes / columns for this post type', 'siteseo').'
					</span>
					<span id="titles-state" class="feature-state feature-state-off">
						<span class="dashicons dashicons-arrow-left-alt"></span>
						'.esc_html__('Click to hide any SEO metaboxes / columns for this post type', 'siteseo').'
					</span>';
				}else{
					echo '<span id="titles-state-default" class="feature-state">
					<span class="dashicons dashicons-arrow-left-alt"></span>
					'.esc_html__('Click to hide any SEO metaboxes / columns for this post type', 'siteseo').'
					</span>
					<span id="titles-state" class="feature-state feature-state-off">
						<span class="dashicons dashicons-arrow-left-alt"></span>
						'.esc_html__('Click to display any SEO metaboxes / columns for this post type', 'siteseo').'
					</span>';
				}

				$toggle_txt_on  = '<span class="dashicons dashicons-arrow-left-alt"></span>' . __('Click to display any SEO metaboxes / columns for this post type', 'siteseo');
				$toggle_txt_off = '<span class="dashicons dashicons-arrow-left-alt"></span>' . __('Click to hide any SEO metaboxes / columns for this post type', 'siteseo');
				
				echo '<script>
					jQuery(document).ready(function($) {
						$("input[data-id='.esc_attr($siteseo_cpt_key).']")
							.on("click", function() {
								$(this).attr("data-toggle", $(this).attr("data-toggle") == "1" ? "0" : "1");
								if($(this).attr("data-toggle") == "1"){
									$(this).next().next(".feature-state").html(
										`'.wp_kses_post($toggle_txt_off).'`
									);
								}else{
									$(this).next().next(".feature-state").html(
										`'.wp_kses_post($toggle_txt_on).'`
									);
								}
							});
					});
				</script>

			</div>
		</h3>

		<!--Single Title CPT-->
		<div class="siteseo_wrap_single_cpt">
			<p>'.esc_html__('Title template', 'siteseo').'</p>

			<script>
				jQuery(document).ready(function($) {
					$("#siteseo-tag-single-title-'.esc_attr($siteseo_cpt_key).'")
						.click(function() {
							$("#siteseo_titles_single_titles_'.esc_attr($siteseo_cpt_key).'")
								.val(siteseo_get_field_length($(
									"#siteseo_titles_single_titles_'.esc_attr($siteseo_cpt_key).'"
								)) + $(
									"#siteseo-tag-single-title-'.esc_attr($siteseo_cpt_key).'"
								).attr("data-tag"));
						});
					$("#siteseo-tag-sep-'.esc_attr($siteseo_cpt_key).'")
						.click(function() {
							$("#siteseo_titles_single_titles_'.esc_attr($siteseo_cpt_key).'")
								.val(siteseo_get_field_length($(
									"#siteseo_titles_single_titles_'.esc_attr($siteseo_cpt_key).'"
								)) + $(
									"#siteseo-tag-sep-'.esc_attr($siteseo_cpt_key).'"
								).attr("data-tag"));
						});
					$("#siteseo-tag-single-sitetitle-'.esc_attr($siteseo_cpt_key).'")
						.click(function() {
							$("#siteseo_titles_single_titles_'.esc_attr($siteseo_cpt_key).'")
								.val(siteseo_get_field_length($(
									"#siteseo_titles_single_titles_'.esc_attr($siteseo_cpt_key).'"
								)) + $(
									"#siteseo-tag-single-sitetitle-'.esc_attr($siteseo_cpt_key).'"
								).attr("data-tag"));
						});
				});
			</script>

			'.sprintf(
			'<input type="text" id="siteseo_titles_single_titles_' . esc_attr($siteseo_cpt_key) . '" name="siteseo_titles_option_name[titles_single_titles][' . esc_attr($siteseo_cpt_key) . '][title]" value="%s"/>',
			esc_html($single_titles_title)
			).'

			<div class="wrap-tags">
				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-single-title-'.esc_attr($siteseo_cpt_key).'"
					data-tag="%%post_title%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Post Title', 'siteseo').'
				</button>

				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-sep-'.esc_attr($siteseo_cpt_key).'"
					data-tag="%%sep%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Separator', 'siteseo').'
				</button>

				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-single-sitetitle-'.esc_attr($siteseo_cpt_key).'"
					data-tag="%%sitetitle%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Site Title', 'siteseo').'
				</button>'.
				wp_kses(siteseo_render_dyn_variables('tag-title'), $tag_dropdown_kses).'
			</div>
		</div>

		<!--Single Meta Description CPT-->
		<div class="siteseo_wrap_single_cpt">
			<p>'.esc_html__('Meta description template', 'siteseo').'</p>

			<script>
				jQuery(document).ready(function($) {
					$("#siteseo-tag-single-desc-'.esc_attr($siteseo_cpt_key).'")
						.click(function() {
							$("#siteseo_titles_single_desc_'.esc_attr($siteseo_cpt_key).'")
								.val(siteseo_get_field_length($(
									"#siteseo_titles_single_desc_'.esc_attr($siteseo_cpt_key).'"
								)) + $("#siteseo-tag-single-desc-'.esc_attr($siteseo_cpt_key).'").attr("data-tag"));
						});
				});
			</script>

			'.sprintf('<textarea id="siteseo_titles_single_desc_' . esc_attr($siteseo_cpt_key) . '" name="siteseo_titles_option_name[titles_single_titles][' . esc_attr($siteseo_cpt_key) . '][description]">%s</textarea>', esc_html($single_titles_description)).'
			<div class="wrap-tags">
				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-single-desc-'.esc_attr($siteseo_cpt_key).'"
					data-tag="%%post_excerpt%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Post excerpt', 'siteseo').'
				</button>
				'.wp_kses(siteseo_render_dyn_variables('tag-description'), $tag_dropdown_kses).'
			</div>
		</div>

		<!--Single No-Index CPT-->
		<div class="siteseo_wrap_single_cpt">
			<label
				for="siteseo_titles_single_cpt_noindex['.esc_attr($siteseo_cpt_key).']">
				<input
					id="siteseo_titles_single_cpt_noindex['.esc_attr($siteseo_cpt_key).']"
					name="siteseo_titles_option_name[titles_single_titles]['.esc_attr($siteseo_cpt_key).'][noindex]"
					type="checkbox" '.(('1' == $single_titles_noindex) ? 'checked="yes"' : 'value="1"').'/>

				'.wp_kses_post(__('Do not display this single post type in search engine results <strong>(noindex)</strong>', 'siteseo')).'
			</label>';

			$cpt_in_sitemap = siteseo_get_service('SitemapOption')->getPostTypesList();

			if ('1' == $single_titles_noindex && isset($cpt_in_sitemap[$siteseo_cpt_key]) && '1' === $cpt_in_sitemap[$siteseo_cpt_key]['include']) {
				
				echo '<div class="siteseo-notice is-error is-inline">
					<p>'.sprintf(wp_kses_post(__('This custom post type is <strong>NOT</strong> excluded from your XML sitemaps despite the fact that it is set to <strong>NOINDEX</strong>. We recommend that you <a href="%s">check this out here</a>.', 'siteseo')), esc_url(admin_url('admin.php?page=siteseo-xml-sitemap'))).'
					</p>
				</div>';
			}

		echo '</div>

		<!--Single No-Follow CPT-->
		<div class="siteseo_wrap_single_cpt">

			<label
				for="siteseo_titles_single_cpt_nofollow['.esc_attr($siteseo_cpt_key).']">
				<input
					id="siteseo_titles_single_cpt_nofollow['.esc_attr($siteseo_cpt_key).']"
					name="siteseo_titles_option_name[titles_single_titles]['.esc_attr($siteseo_cpt_key).'][nofollow]"
					type="checkbox" '.(('1' == $single_titles_nofollow) ? 'checked="yes"' : 'value="1"').'/>

				'.wp_kses_post(__('Do not follow links for this single post type <strong>(nofollow)</strong>', 'siteseo')).'
			</label>
		</div>

		<!--Single Published / modified date CPT-->
		<div class="siteseo_wrap_single_cpt">
			<label
				for="siteseo_titles_single_cpt_date['.esc_attr($siteseo_cpt_key).']">
				<input
					id="siteseo_titles_single_cpt_date['.esc_attr($siteseo_cpt_key).']"
					name="siteseo_titles_option_name[titles_single_titles]['.esc_attr($siteseo_cpt_key).'][date]"
					type="checkbox" '.(('1' == $single_titles_date) ? 'checked="yes"' : 'value="1"').'/>

				'.wp_kses_post(__('Display date in Google search results by adding <code>article:published_time</code> and <code>article:modified_time</code> meta?', 'siteseo')).'
			</label>

			<p class="description">'.esc_html__('Unchecking this doesn\'t prevent Google to display post date in search results.', 'siteseo').'</p>
		</div>

		<!--Single meta thumbnail CPT-->
		<div class="siteseo_wrap_single_cpt">
			<label
				for="siteseo_titles_single_cpt_thumb_gcs['.esc_attr($siteseo_cpt_key).']">
				<input
					id="siteseo_titles_single_cpt_thumb_gcs['.esc_attr($siteseo_cpt_key).']"
					name="siteseo_titles_option_name[titles_single_titles]['.esc_attr($siteseo_cpt_key).'][thumb_gcs]"
					type="checkbox" '.(('1' == $thumb_gcs) ? 'checked="yes"' : 'value="1"').'/>

				'.esc_html__('Display post thumbnail in Google Custom Search results?', 'siteseo').'
			</label>


			<p class="description">'.sprintf(wp_kses_post(__('This option does not apply to traditional search results. <a href="%s" target="_blank">Learn more</a>', 'siteseo')), esc_url($docs['titles']['thumbnail'])).'<span class="dashicons dashicons-external"></span></p>
		</div>';

		if(empty($options['titles_single_titles'][$siteseo_cpt_key]['title'])){
			$t[] = $siteseo_cpt_key;
		}
	}
	
	if(is_plugin_active('buddypress/bp-loader.php') || is_plugin_active('buddyboss-platform/bp-loader.php')){
		$groups_title = isset($options['titles_bp_groups_title']) ? $options['titles_bp_groups_title'] : null;
		$groups_desc = isset($options['titles_bp_groups_desc']) ? $options['titles_bp_groups_desc'] : null;
		$no_index = isset($options['titles_bp_groups_noindex']);
		
		echo '<h3>'.esc_html__('BuddyPress groups', 'siteseo').'</h3>
		<p>'.esc_html__('Title template', 'siteseo').'</p>

		<input id="siteseo_titles_bp_groups_title" type="text"
			name="siteseo_titles_option_name[titles_bp_groups_title]"
			value="'.esc_html($groups_title).'" />

		<div class="wrap-tags">
			<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-post-title-bd-groups" data-tag="%%post_title%%">
				<span class="dashicons dashicons-insert"></span>
				'.esc_html__('Post Title', 'siteseo').'
			</button>
			<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-sep-bd-groups" data-tag="%%sep%%">
				<span class="dashicons dashicons-insert"></span>
				'.esc_html__('Separator', 'siteseo').'
			</button>

			<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title-bd-groups" data-tag="%%sitetitle%%">
				<span class="dashicons dashicons-insert"></span>
				'.esc_html__('Site Title', 'siteseo').'
			</button>

			'.wp_kses(siteseo_render_dyn_variables('tag-title'), $tag_dropdown_kses).'
		</div>
		
		<p>'.esc_html__('Meta description template', 'siteseo').'</p>

		<textarea name="siteseo_titles_option_name[titles_bp_groups_desc]">'.esc_html($groups_desc).'</textarea>

		<label for="siteseo_titles_bp_groups_noindex">
			<input id="siteseo_titles_bp_groups_noindex"
				name="siteseo_titles_option_name[titles_bp_groups_noindex]" type="checkbox" '.checked($no_index, '1', false).'
			value="1"/>
			'.wp_kses_post(__('Do not display BuddyPress groups in search engine results <strong>(noindex)</strong>', 'siteseo')).'
		</label>';
	}
	
	echo '</div>';
	
}

function siteseo_title_archive_tab(){
	
	$options = get_option('siteseo_titles_option_name');
	
	$anchor_html = '';
	$html = '';
	$custom_field = array(
		'author-archives' => 'Author archives',
		'date-archives' => 'Date archives',
		'search-archives' => 'Search archives',
		'404-archives' => '404 archives'
	);
	
	// List of html entities to allow when escaping siteseo_render_dyn_variables
	$tag_dropdown_kses = [
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
	
	$author_title = isset($options['titles_archives_author_title']) ? $options['titles_archives_author_title'] : null;
	$author_desc = isset($options['titles_archives_author_desc']) ? $options['titles_archives_author_desc'] : null;
	$author_noindex = isset($options['titles_archives_author_noindex']);
	$author_disabled = isset($options['titles_archives_author_disable']);
	$date_title = isset($options['titles_archives_date_title']) ? $options['titles_archives_date_title'] : null;
	$date_desc = isset($options['titles_archives_date_desc']) ? $options['titles_archives_date_desc'] : null;
	$date_noindex = isset($options['titles_archives_date_noindex']);
	$date_disabled = isset($options['titles_archives_date_disable']);
	$search_title = isset($options['titles_archives_search_title']) ? $options['titles_archives_search_title'] : null;
	$search_desc = isset($options['titles_archives_search_desc']) ? $options['titles_archives_search_desc'] : null;
	$search_title_noindex = isset($options['titles_archives_search_title_noindex']);
	$title_404 = isset($options['titles_archives_404_title']) ? $options['titles_archives_404_title'] : null;
	$desc_404 = isset($options['titles_archives_404_desc']) ? $options['titles_archives_404_desc'] : null;

	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
	$active_tab = true;
	//For Archive post type from wordpress
	foreach($postTypes as $anchor_key => $anchor_val){
		if(!in_array($anchor_key, ['post', 'page'])){
			if(empty(!$anchor_val->labels->name)){
				$active_class = $active_tab ? 'class="siteseo-active-sub-tabs"' : '';
				$anchor_html .='<a '.$active_class.' href="#siteseo-archive-'.esc_attr(str_replace(" ","-",strtolower(trim($anchor_val->labels->name)))).'">'.esc_html(ucfirst(str_replace("_"," ",$anchor_val->labels->name))).'</a>';
				$active_tab = false;
			}
		}
	}

	//For Custom Archive post type
	foreach($custom_field as $anchor_key => $anchor_val){
		if(empty(!$anchor_val)){
			$anchor_html .='<a href="#siteseo-archive-'.esc_attr($anchor_key).'">'.esc_html(ucfirst(str_replace("_"," ",$anchor_val))).'</a>'; 
		}
	}

	if(!empty($anchor_html)){
		$html .= '<div class="siteseo-sub-tabs">'. $anchor_html.'</div>';
	}
	
	echo wp_kses_post($html);
	
	echo '<div class="siteseo-section-body">	
		<div class="siteseo-section-header">
			<h2>'.esc_html__('Archives', 'siteseo').'</h2>
		</div>
	<p>'.esc_html__('Customize your metas for all archives.', 'siteseo').'</p>';
	
	
	foreach($postTypes as $siteseo_cpt_key => $siteseo_cpt_value){
		// Options
		$title = isset($options['titles_archive_titles'][$siteseo_cpt_key]['title']) ? $options['titles_archive_titles'][$siteseo_cpt_key]['title'] : null;
		$description = isset($options['titles_archive_titles'][$siteseo_cpt_key]['description']) ? $options['titles_archive_titles'][$siteseo_cpt_key]['description'] : null;
		$noindex = isset($options['titles_archive_titles'][$siteseo_cpt_key]['noindex']);
		$nofollow = isset($options['titles_archive_titles'][$siteseo_cpt_key]['nofollow']);

		if(!in_array($siteseo_cpt_key, ['post', 'page'])){
			
			echo '<h3 id="siteseo-archive-'.esc_attr(str_replace(" ","-",strtolower(trim($siteseo_cpt_value->labels->name)))).'">'.esc_html($siteseo_cpt_value->labels->name).'
				<em><small>['.esc_html($siteseo_cpt_value->name).']</small></em>';

				if(get_post_type_archive_link($siteseo_cpt_value->name)){
					echo '<span class="link-archive">
						<span class="dashicons dashicons-external"></span>
						<a href="'.esc_url(get_post_type_archive_link($siteseo_cpt_value->name)).'"
							target="_blank">'.esc_html__('See archive', 'siteseo').'</a>
					</span>';
				}
			echo'</h3>

			<!--Archive Title CPT-->
			<div class="siteseo_wrap_archive_cpt">
				<p>'.esc_html__('Title template', 'siteseo').'</p>

				<script>
					jQuery(document).ready(function($) {
						$("#siteseo-tag-archive-title-'.esc_attr($siteseo_cpt_key).'")
							.click(
								function() {
									$("#siteseo_titles_archive_titles_'.esc_attr($siteseo_cpt_key).'")
										.val(siteseo_get_field_length($(
											"#siteseo_titles_archive_titles_'.esc_attr($siteseo_cpt_key).'"
										)) + $(
											"#siteseo-tag-archive-title-'.esc_attr($siteseo_cpt_key).'"
										).attr("data-tag"));
								});
						$("#siteseo-tag-archive-sep-'.esc_attr($siteseo_cpt_key).'")
							.click(
								function() {
									$("#siteseo_titles_archive_titles_'.esc_attr($siteseo_cpt_key).'")
										.val(siteseo_get_field_length($(
											"#siteseo_titles_archive_titles_'.esc_attr($siteseo_cpt_key).'"
										)) + $(
											"#siteseo-tag-archive-sep-'.esc_attr($siteseo_cpt_key).'"
										).attr("data-tag"));
								});
						$("#siteseo-tag-archive-sitetitle-'.esc_attr($siteseo_cpt_key).'")
							.click(function() {
								$("#siteseo_titles_archive_titles_'.esc_attr($siteseo_cpt_key).'")
									.val(siteseo_get_field_length($(
										"#siteseo_titles_archive_titles_'.esc_attr($siteseo_cpt_key).'"
									)) + $(
										"#siteseo-tag-archive-sitetitle-'.esc_attr($siteseo_cpt_key).'"
									).attr("data-tag"));
							});
					});
				</script>

				'.sprintf('<input type="text" id="siteseo_titles_archive_titles_' . esc_attr($siteseo_cpt_key) . '"
				name="siteseo_titles_option_name[titles_archive_titles][' . esc_attr($siteseo_cpt_key) . '][title]"
				value="%s" />',esc_html($title)).'

				<div class="wrap-tags"><button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-archive-title-'.esc_attr($siteseo_cpt_key).'"
					data-tag="%%cpt_plural%%"><span
						class="dashicons dashicons-insert"></span>'.esc_html__('Post Type Archive Name', 'siteseo').'</button>

					<button type="button" class="btn btnSecondary tag-title"
						id="siteseo-tag-archive-sep-'.esc_attr($siteseo_cpt_key).'"
						data-tag="%%sep%%"><span class="dashicons dashicons-insert"></span>'.esc_html__('Separator', 'siteseo').'</button>

					<button type="button" class="btn btnSecondary tag-title"
						id="siteseo-tag-archive-sitetitle-'.esc_attr($siteseo_cpt_key).'"
						data-tag="%%sitetitle%%"><span class="dashicons dashicons-insert"></span>'.esc_html__('Site Title', 'siteseo').'</button>

					'.wp_kses(siteseo_render_dyn_variables('tag-title'), $tag_dropdown_kses).'
				</div>
			</div>

			<!--Archive Meta Description CPT-->
			<div class="siteseo_wrap_archive_cpt">

				<p>'.esc_html__('Meta description template', 'siteseo').'</p>

				<script>
					jQuery(document).ready(function($) {
						$("#siteseo-tag-archive-desc-'.esc_attr($siteseo_cpt_key).'")
							.click(
								function() {
									$("#siteseo_titles_archive_desc_'.esc_attr($siteseo_cpt_key).'")
										.val(siteseo_get_field_length($(
											"#siteseo_titles_archive_desc_'.esc_attr($siteseo_cpt_key).'"
										)) + $(
											"#siteseo-tag-archive-desc-'.esc_attr($siteseo_cpt_key).'"
										).attr("data-tag"));
								});
						$("#siteseo-tag-archive-desc-sep-'.esc_attr($siteseo_cpt_key).'")
							.click(
								function() {
									$("#siteseo_titles_archive_desc_'.esc_attr($siteseo_cpt_key).'")
										.val(siteseo_get_field_length($(
											"#siteseo_titles_archive_desc_'.esc_attr($siteseo_cpt_key).'"
										)) + $(
											"#siteseo-tag-archive-desc-sep-'.esc_attr($siteseo_cpt_key).'"
										).attr("data-tag"));
								});
						$("#siteseo-tag-archive-desc-sitetitle-'.esc_attr($siteseo_cpt_key).'")
							.click(function() {
								$("#siteseo_titles_archive_desc_'.esc_attr($siteseo_cpt_key).'")
									.val(siteseo_get_field_length($(
										"#siteseo_titles_archive_desc_'.esc_attr($siteseo_cpt_key).'"
									)) + $(
										"#siteseo-tag-archive-desc-sitetitle-'.esc_attr($siteseo_cpt_key).'"
									).attr("data-tag"));
							});
					});
				</script>

				'.sprintf('<textarea name="siteseo_titles_option_name[titles_archive_titles][' . esc_attr($siteseo_cpt_key) . '][description]">%s</textarea>', esc_html($description)).'
					<div class="wrap-tags">
						'.wp_kses(siteseo_render_dyn_variables('tag-description'), $tag_dropdown_kses).'
					</div>
			</div>

			<!--Archive No-Index CPT-->
			<div class="siteseo_wrap_archive_cpt">
				<label
					for="siteseo_titles_archive_cpt_noindex['.esc_attr($siteseo_cpt_key).']">
					<input
						id="siteseo_titles_archive_cpt_noindex['.esc_attr($siteseo_cpt_key).']"
						name="siteseo_titles_option_name[titles_archive_titles]['.esc_attr($siteseo_cpt_key).'][noindex]"
						type="checkbox" '.checked($noindex, '1', false).' value="1"/>
					'.wp_kses_post(__('Do not display this post type archive in search engine results <strong>(noindex)</strong>', 'siteseo')).'
				</label>
			</div>

			<!--Archive No-Follow CPT-->
			<div class="siteseo_wrap_archive_cpt">
				<label for="siteseo_titles_archive_cpt_nofollow['.esc_attr($siteseo_cpt_key).']">
					<input
						id="siteseo_titles_archive_cpt_nofollow['.esc_attr($siteseo_cpt_key).']"
						name="siteseo_titles_option_name[titles_archive_titles]['.esc_attr($siteseo_cpt_key).'][nofollow]"
						type="checkbox" '.checked($nofollow, '1', false).' value="1"/>
					'.wp_kses_post(__('Do not follow links for this post type archive <strong>(nofollow)</strong>', 'siteseo')).'
				</label>
			</div>';
		}
	}
	
	echo '<h3 id="siteseo-archive-author-archives">'.esc_html__('Author archives', 'siteseo').'</h3>

	<p>'.esc_html__('Title template', 'siteseo').'</p>

	<input id="siteseo_titles_archive_post_author" type="text"
		name="siteseo_titles_option_name[titles_archives_author_title]"
		value="'.esc_html($author_title).'" />

	<div class="wrap-tags">
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-post-author" data-tag="%%post_author%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Post author', 'siteseo').'
		</button>
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-sep-author" data-tag="%%sep%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Separator', 'siteseo').'
		</button>

		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title-author" data-tag="%%sitetitle%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Site Title', 'siteseo').'
		</button>
		'.wp_kses(siteseo_render_dyn_variables('tag-title'), $tag_dropdown_kses).'
	</div>

	<p>'.esc_html__('Meta description template', 'siteseo').'</p>
	<textarea name="siteseo_titles_option_name[titles_archives_author_desc]">'.esc_html($author_desc).'</textarea><br>

	<label for="siteseo_titles_archives_author_noindex">
		<input id="siteseo_titles_archives_author_noindex" name="siteseo_titles_option_name[titles_archives_author_noindex]" type="checkbox" '.checked($author_noindex, '1', false).' value="1"/>
		'.wp_kses_post(__('Do not display author archives in search engine results <strong>(noindex)</strong>', 'siteseo')).'
	</label>

	<label for="siteseo_titles_archives_author_disable">
		<input id="siteseo_titles_archives_author_disable" name="siteseo_titles_option_name[titles_archives_author_disable]" type="checkbox"'.checked($author_disabled, '1', false).' value="1"/>
		'.esc_html__('Disable author archives', 'siteseo').'
	</label>
	
	<h3 id="siteseo-archive-date-archives">'.esc_html__('Date archives', 'siteseo').'</h3>
	<p>'.esc_html__('Title template', 'siteseo').'</p>

	<input id="siteseo_titles_archives_date_title" type="text" name="siteseo_titles_option_name[titles_archives_date_title]" value="'.esc_html($date_title).'" />

	<div class="wrap-tags">
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-archive-date" data-tag="%%archive_date%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Date archives', 'siteseo').'
		</button>
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-sep-date" data-tag="%%sep%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Separator', 'siteseo').'
		</button>
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title-date" data-tag="%%sitetitle%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Site Title', 'siteseo').'
		</button>'.
		wp_kses(siteseo_render_dyn_variables('tag-title'), $tag_dropdown_kses).'
	</div>
	<p>'.esc_html__('Meta description template', 'siteseo').'</p>

	<textarea name="siteseo_titles_option_name[titles_archives_date_desc]">'.esc_html($date_desc).'</textarea>
	<br>
	<label for="siteseo_titles_archives_date_noindex">
		<input id="siteseo_titles_archives_date_noindex" name="siteseo_titles_option_name[titles_archives_date_noindex]" type="checkbox" 
		'.checked($date_noindex, '1', false).' value="1"/>
		'.wp_kses_post(__('Do not display date archives in search engine results <strong>(noindex)</strong>', 'siteseo')).'
	</label>

	<label for="siteseo_titles_archives_date_disable">
		<input id="siteseo_titles_archives_date_disable"
			name="siteseo_titles_option_name[titles_archives_date_disable]"
			type="checkbox" '.checked($date_disabled, '1', false).'
		value="1"/>
		'.esc_html__('Disable date archives', 'siteseo').'
	</label>
	
	<h3 id="siteseo-archive-search-archives">'.esc_html__('Search archives', 'siteseo').'</h3>
	<p>'.esc_html__('Title template', 'siteseo').'</p>

	<input id="siteseo_titles_archives_search_title" type="text"
		name="siteseo_titles_option_name[titles_archives_search_title]"
		value="'.esc_html($search_title).'" />

	<div class="wrap-tags">
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-search-keywords" data-tag="%%search_keywords%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Search Keywords', 'siteseo').'
		</button>

		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-sep-search" data-tag="%%sep%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Separator', 'siteseo').'
		</button>

		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title-search" data-tag="%%sitetitle%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Site Title', 'siteseo').'
		</button>
		'.wp_kses(siteseo_render_dyn_variables('tag-title'), $tag_dropdown_kses).'
	</div>
	
	<p>'.esc_html__('Meta description template', 'siteseo').'</p>

	<textarea name="siteseo_titles_option_name[titles_archives_search_desc]">'.esc_html($search_desc).'</textarea>
	<br>
	<label for="siteseo_titles_archives_search_title_noindex">
		<input
			id="siteseo_titles_archives_search_title_noindex"
			name="siteseo_titles_option_name[titles_archives_search_title_noindex]"
			type="checkbox" '.checked($search_title_noindex, '1', false).' value="1"/>
		'.wp_kses_post(__('Do not display search archives in search engine results <strong>(noindex)</strong>', 'siteseo')).'
	</label>

	<h3 id="siteseo-archive-404-archives">'.esc_html__('404 archives', 'siteseo').'</h3>
	<p>'.esc_html__('Title template', 'siteseo').'</p>

	<input id="siteseo_titles_archives_404_title" type="text"
		name="siteseo_titles_option_name[titles_archives_404_title]"
		value="'.esc_html($title_404).'" />

	<div class="wrap-tags">
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-site-title-404" data-tag="%%sitetitle%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Site Title', 'siteseo').'
		</button>
		<button type="button" class="btn btnSecondary tag-title" id="siteseo-tag-sep-404" data-tag="%%sep%%">
			<span class="dashicons dashicons-insert"></span>
			'.esc_html__('Separator', 'siteseo').'
		</button>

		'.wp_kses(siteseo_render_dyn_variables('tag-title'), $tag_dropdown_kses).'
	</div>

	<p><label for="siteseo_titles_archives_404_desc">'.esc_html__('Meta description template', 'siteseo').'</label></p>

	<textarea id="siteseo_titles_archives_404_desc" name="siteseo_titles_option_name[titles_archives_404_desc]">'.esc_html($desc_404).'</textarea>
	
	</div>';
}

function siteseo_title_taxonomies_tab(){

	$options = get_option('siteseo_titles_option_name');
	
	$anchor_html = '';
	$html = '';
	$postTypes = siteseo_get_service('WordPressData')->getTaxonomies();
	$active_tab = true;
	// List of html entities to allow when escaping siteseo_render_dyn_variables
	$tag_dropdown_kses = [
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
	
	foreach ($postTypes as $anchor_val) {
		if(empty(!$anchor_val->labels->name)){
			$active_class = $active_tab ? 'class="siteseo-active-sub-tabs"' : '';
			$anchor_html .='<a '.$active_class.' href="#siteseo-taxonomies-'.esc_attr(str_replace(" ","-",strtolower(trim($anchor_val->labels->name)))).'">'.esc_html(ucfirst(str_replace("_"," ",$anchor_val->labels->name))).'</a>';
			$active_tab = false;
		}
	}

	if(!empty($anchor_html)){
		$html .= '<div class="siteseo-sub-tabs">'. $anchor_html .'</div>';
	}
	
	echo wp_kses_post($html).
	
	'<div class="siteseo-section-body">	
	<div class="siteseo-section-header">
		<h2>'.esc_html__('Taxonomies', 'siteseo').'</h2>
	</div>
	<p>'.esc_html__('Customize your metas for all taxonomies archives.', 'siteseo').'</p>';
	
	echo wp_kses_post(siteseo_get_empty_templates('tax', 'title'));
	echo wp_kses_post(siteseo_get_empty_templates('tax', 'description'));

	$taxonomies = siteseo_get_service('WordPressData')->getTaxonomies();
	foreach($taxonomies as $siteseo_tax_key => $siteseo_tax_value){
		$enabled = isset($options['titles_tax_titles'][$siteseo_tax_key]['enable']) ? $options['titles_tax_titles'][$siteseo_tax_key]['enable'] : null;
		$title = isset($options['titles_tax_titles'][$siteseo_tax_key]['title']) ? $options['titles_tax_titles'][$siteseo_tax_key]['title'] : null;
		$description = isset($options['titles_tax_titles'][$siteseo_tax_key]['description']) ? $options['titles_tax_titles'][$siteseo_tax_key]['description'] : null;
		$noindex = isset($options['titles_tax_titles'][$siteseo_tax_key]['noindex']);
		$nofollow = isset($options['titles_tax_titles'][$siteseo_tax_key]['nofollow']);
		
		echo '<h3 id="siteseo-taxonomies-'.esc_attr(str_replace(" ","-",strtolower(trim($siteseo_tax_value->labels->name)))).'">
			'.esc_html($siteseo_tax_value->labels->name).'
			<em><small>['.esc_html($siteseo_tax_value->name).']</small></em>
		</h3>

		<!--Single on/off Tax-->
		<div class="siteseo_wrap_tax">
			<input
				id="siteseo_titles_tax_titles_enable['.esc_attr($siteseo_tax_key).']"
				data-id='.esc_attr($siteseo_tax_key).'
			name="siteseo_titles_option_name[titles_tax_titles]['.esc_attr($siteseo_tax_key).'][enable]"
			class="toggle" type="checkbox" '.(('1' == $enabled) ? 'checked="yes" data-toggle="0"': 'data-toggle="1"').' value="1"/>

			<label for="siteseo_titles_tax_titles_enable['.esc_attr($siteseo_tax_key).']">
				'.esc_html__('Click to hide any SEO metaboxes for this taxonomy', 'siteseo').'
			</label>';

			if('1' == $enabled){
				echo '<span id="titles-state-default" class="feature-state">
					<span class="dashicons dashicons-arrow-left-alt"></span>
					'.esc_html__('Click to display any SEO metaboxes for this taxonomy', 'siteseo').'
				</span>
				<span id="titles-state" class="feature-state feature-state-off">
					<span class="dashicons dashicons-arrow-left-alt"></span>
					'.esc_html__('Click to hide any SEO metaboxes for this taxonomy', 'siteseo').'
				</span>';
			}else{
				echo '<span id="titles-state-default" class="feature-state">
					<span class="dashicons dashicons-arrow-left-alt"></span>
					'.esc_html__('Click to hide any SEO metaboxes for this taxonomy', 'siteseo').'
				</span>
				<span id="titles-state" class="feature-state feature-state-off">
					<span class="dashicons dashicons-arrow-left-alt"></span>
					'.esc_html__('Click to display any SEO metaboxes for this taxonomy', 'siteseo').'
				</span>';
			}

			$toggle_txt_on  = '<span class="dashicons dashicons-arrow-left-alt"></span>' . __('Click to display any SEO metaboxes for this taxonomy', 'siteseo');
			$toggle_txt_off = '<span class="dashicons dashicons-arrow-left-alt"></span>' . __('Click to hide any SEO metaboxes for this taxonomy', 'siteseo');

			echo '<script>
				jQuery(document).ready(function($) {
					$(" input[data-id='.esc_attr($siteseo_tax_key).']")
						.on("click",
							function() {
								$(this).attr("data-toggle", $(this).attr("data-toggle") == "1" ? "0" :
									"1");
								if ($(this).attr("data-toggle") == "1") {
									$(this).next().next(".feature-state").html(
										`'.wp_kses_post($toggle_txt_off).'`
									);
								} else {
									$(this).next().next(".feature-state").html(
										`'.wp_kses_post($toggle_txt_on).'`
									);
								}
							});
				});
			</script>
		</div>

		<!--Tax Title-->
		<div class="siteseo_wrap_tax">
			<p>'.esc_html__('Title template', 'siteseo').'</p>

			<script>
				jQuery(document).ready(function($) {
					$(" #siteseo-tag-tax-title-'.esc_attr($siteseo_tax_key).'")
						.click(function() {
							$("#siteseo_titles_tax_titles_'.esc_attr($siteseo_tax_key).'")
								.val(siteseo_get_field_length($(
									"#siteseo_titles_tax_titles_'.esc_attr($siteseo_tax_key).'"
								)) + $(
									"#siteseo-tag-tax-title-'.esc_attr($siteseo_tax_key).'"
								).attr("data-tag"));
						});
					$("#siteseo-tag-sep-'.esc_attr($siteseo_tax_key).'")
						.click(function() {
							$("#siteseo_titles_tax_titles_'.esc_attr($siteseo_tax_key).'")
								.val(siteseo_get_field_length($(
									"#siteseo_titles_tax_titles_'.esc_attr($siteseo_tax_key).'"
								)) + $(
									"#siteseo-tag-sep-'.esc_attr($siteseo_tax_key).'"
								).attr("data-tag"));
						});
					$("#siteseo-tag-tax-sitetitle-'.esc_attr($siteseo_tax_key).'")
						.click(function() {
							$("#siteseo_titles_tax_titles_'.esc_attr($siteseo_tax_key).'")
								.val(siteseo_get_field_length($(
									"#siteseo_titles_tax_titles_'.esc_attr($siteseo_tax_key).'"
								)) + $(
									"#siteseo-tag-tax-sitetitle-'.esc_attr($siteseo_tax_key).'"
								).attr("data-tag"));
						});
				});
			</script>

			'.sprintf('<input type="text" id="siteseo_titles_tax_titles_' . esc_attr($siteseo_tax_key) . '" name="siteseo_titles_option_name[titles_tax_titles][' . esc_attr($siteseo_tax_key) . '][title]" value="%s"/>', esc_html($title));

		if('category' == $siteseo_tax_key){
			echo '<div class=" wrap-tags">
			<span
				id="siteseo-tag-tax-title-'.esc_attr($siteseo_tax_key).'"
				data-tag="%%_category_title%%" class="btn btnSecondary tag-title">
				<span class="dashicons dashicons-insert"></span>
				'.esc_html__('Category Title', 'siteseo').'
			</span>';
			} elseif ('post_tag' == $siteseo_tax_key) {
				echo '<div class="wrap-tags">
				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-tax-title-'.esc_attr($siteseo_tax_key).'"
					data-tag="%%tag_title%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Tag Title', 'siteseo').'
				</button>';
			} else {
				echo '<div class="wrap-tags">
					<button type="button" class="btn btnSecondary tag-title"
						id="siteseo-tag-tax-title-'.esc_attr($siteseo_tax_key).'"
						data-tag="%%term_title%%">
						<span class="dashicons dashicons-insert"></span>
						'.esc_html__('Term Title', 'siteseo').'
					</button>';
			}

				echo'<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-sep-'.esc_attr($siteseo_tax_key).'"
					data-tag="%%sep%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Separator', 'siteseo').'
				</button>

				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-tax-sitetitle-'.esc_attr($siteseo_tax_key).'"
					data-tag="%%sitetitle%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Site Title', 'siteseo').'
				</button>

				'.wp_kses(siteseo_render_dyn_variables('tag-title'), $tag_dropdown_kses).'
				</div>
			</div>

			<!--Tax Meta Description-->
			<div class="siteseo_wrap_tax">
				<p>'.esc_html__('Meta description template', 'siteseo').'</p>

				<script>
					jQuery(document).ready(function($) {
						$("#siteseo-tag-tax-desc-'.esc_attr($siteseo_tax_key).'")
							.click(function() {
								$("#siteseo_titles_tax_desc_'.esc_attr($siteseo_tax_key).'")
									.val(
										siteseo_get_field_length($(
											"#siteseo_titles_tax_desc_'.esc_attr($siteseo_tax_key).'"
										)) + $(
											"#siteseo-tag-tax-desc-'.esc_attr($siteseo_tax_key).'"
										).attr("data-tag"));
							});
					});
				</script>
			'.sprintf('<textarea id="siteseo_titles_tax_desc_' . esc_attr($siteseo_tax_key) . '" name="siteseo_titles_option_name[titles_tax_titles][' . esc_attr($siteseo_tax_key) . '][description]">%s</textarea>', esc_html($description));
		
		if('category' == $siteseo_tax_key){
			echo'<div class="wrap-tags">
				<button type="button" class="btn btnSecondary tag-title"
					id="siteseo-tag-tax-desc-'.esc_attr($siteseo_tax_key).'"
					data-tag="%%_category_description%%">
					<span class="dashicons dashicons-insert"></span>
					'.esc_html__('Category Description', 'siteseo').'
				</button>';
		} elseif('post_tag' == $siteseo_tax_key){
					echo'<div class="wrap-tags">
					<button type="button" class="btn btnSecondary tag-title"
						id="siteseo-tag-tax-desc-'.esc_attr($siteseo_tax_key).'"
						data-tag="%%tag_description%%">
						<span class="dashicons dashicons-insert"></span>
						'.esc_html__('Tag Description', 'siteseo').'
					</button>';
		} else {
			echo '<div class="wrap-tags">
			<button type="button" class="btn btnSecondary tag-title"
				id="siteseo-tag-tax-desc-'.esc_attr($siteseo_tax_key).'"
				data-tag="%%term_description%%">
				<span class="dashicons dashicons-insert"></span>
				'.esc_html__('Term Description', 'siteseo').'
			</button>';
		}
		
		echo wp_kses(siteseo_render_dyn_variables('tag-description'), $tag_dropdown_kses).'
		</div>
		</div>

		<!--Tax No-Index-->
		<div class="siteseo_wrap_tax">
			<label for="siteseo_titles_tax_noindex['.esc_attr($siteseo_tax_key).']">
				<input
					id="siteseo_titles_tax_noindex['.esc_attr($siteseo_tax_key).']"
					name="siteseo_titles_option_name[titles_tax_titles]['.esc_attr($siteseo_tax_key).'][noindex]"
					type="checkbox" '.checked($noindex, '1', false).'
				value="1"/>
				'.wp_kses_post(__('Do not display this taxonomy archive in search engine results <strong>(noindex)</strong>', 'siteseo'));
				if($siteseo_tax_key ==='post_tag'){
					echo '<div class="siteseo-notice is-warning is-inline">
						<p>'.wp_kses_post(__('We do not recommend indexing <strong>tags</strong> which are, in the vast majority of cases, a source of duplicate content.', 'siteseo')).'</p>
					</div>';
				}
			echo '</label>';

		$tax_in_sitemap = siteseo_get_service('SitemapOption')->getTaxonomiesList();

		if('1' == $noindex && isset($tax_in_sitemap[$siteseo_tax_key]) && '1' === $tax_in_sitemap[$siteseo_tax_key]['include']){
			echo '<div class="siteseo-notice is-error">
				<p>'.wp_kses_post(__('This custom taxonomy is <strong>NOT</strong> excluded from your XML sitemaps despite the fact that it is set to <strong>NOINDEX</strong>. We recommend that you check this out.', 'siteseo')).'
				</p>
			</div>';
		}

		echo '</div>

		<!--Tax No-Follow-->
		<div class="siteseo_wrap_tax">
			<label
				for="siteseo_titles_tax_nofollow['.esc_attr($siteseo_tax_key).'>]">
				<input
					id="siteseo_titles_tax_nofollow['.esc_attr($siteseo_tax_key).']"
					name="siteseo_titles_option_name[titles_tax_titles]['.esc_attr($siteseo_tax_key).'][nofollow]"
					type="checkbox" '.checked($nofollow, '1', false) .' value="1"/>
				'.wp_kses_post(__('Do not follow links for this taxonomy archive <strong>(nofollow)</strong>', 'siteseo')).'
			</label>
		</div>';
	}
	
	echo '</div>';
}

function siteseo_advanced_tab(){
	$options = get_option('siteseo_titles_option_name');
	
	$noindex = isset($options['titles_noindex']);
	$nofollow = isset($options['titles_nofollow']);
	$noimageindex = isset($options['titles_noimageindex']);
	$noarchive = isset($options['titles_noarchive']);
	$nosnippet = isset($options['titles_nosnippet']);
	$nositelinkssearchbox = isset($options['titles_nositelinkssearchbox']);
	$paged_rel = isset($options['titles_paged_rel']);
	$paged_noindex = isset($options['titles_paged_noindex']);
	$attachments_noindex = isset($options['titles_attachments_noindex']);
	
	echo '<div class="siteseo-section-header">
		<h2>'.esc_html__('Advanced', 'siteseo').'</h2>
	</div>
	<p>'.esc_html__('Customize your metas for all pages.', 'siteseo').'</p>

	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_noindex">'.esc_html__('noindex', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<label for="siteseo_titles_noindex">
				<input id="siteseo_titles_noindex"
					name="siteseo_titles_option_name[titles_noindex]"
					type="checkbox" '.checked($noindex, '1', false).' value="1"/>
				'.esc_html__('noindex', 'siteseo').'
			</label>
			<p class="description">'.esc_html__('Do not display all pages of the site in Google search results and do not display "Cached" links in search results.', 'siteseo').'</p>

			<p class="description">'.sprintf(wp_kses_post(__('Check also the <strong>"Search engine visibility"</strong> setting from the <a href="%s">WordPress Reading page</a>.', 'siteseo')), esc_url(admin_url('options-reading.php'))).'</p>
		</div>
	</div>

	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_nofollow">'.esc_html__('nofollow', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<label for="siteseo_titles_nofollow">
				<input id="siteseo_titles_nofollow"
					name="siteseo_titles_option_name[titles_nofollow]"
					type="checkbox" '.checked($nofollow, '1', false).' value="1"/>
				'.esc_html__('nofollow', 'siteseo').'
			</label>
			<p class="description">'.esc_html__('Do not follow links for all pages.', 'siteseo').'</p>
		</div>
	</div>

	
	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_noimageindex">'.esc_html__('noimageindex', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<label for="siteseo_titles_noimageindex">
				<input id="siteseo_titles_noimageindex"
					name="siteseo_titles_option_name[titles_noimageindex]"
					type="checkbox" '.checked($noimageindex, '1', false).'
				value="1"/>
				'.esc_html__('noimageindex', 'siteseo').'
			</label>

			<p class="description">'.esc_html__('Do not index images from the entire site.', 'siteseo').'</p>
		</div>
	</div>

	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_noarchive">'.esc_html__('noarchive', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<label for="siteseo_titles_noarchive">
				<input id="siteseo_titles_noarchive" name="siteseo_titles_option_name[titles_noarchive]" type="checkbox" '.checked($noarchive, '1', false).' value="1"/>'.esc_html__('noarchive', 'siteseo').'
			</label>

			<p class="description">'.esc_html__('Do not display a "Cached" link in the Google search results.', 'siteseo').'</p>
		</div>
	</div>
	
	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_nosnippet">'.esc_html__('nosnippet', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<label for="siteseo_titles_nosnippet">
				<input id="siteseo_titles_nosnippet"
					name="siteseo_titles_option_name[titles_nosnippet]"
					type="checkbox" '.checked($nosnippet, '1', false).'
				value="1"/>
				'.esc_html__('nosnippet', 'siteseo').'
			</label>
			<p class="description">'.esc_html__('Do not display a description in the Google search results for all pages.', 'siteseo').'</p>
		</div>
	</div>


	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_nositelinkssearchbox">'.esc_html__('nositelinkssearchbox', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<label for="siteseo_titles_nositelinkssearchbox">
				<input id="siteseo_titles_nositelinkssearchbox"
					name="siteseo_titles_option_name[titles_nositelinkssearchbox]"
					type="checkbox" '.checked($nositelinkssearchbox, '1', false).'
				value="1"/>
				'.esc_html__('nositelinkssearchbox', 'siteseo').'
			</label>

			<p class="description">
				'.esc_html__('Prevents Google to display a sitelinks searchbox in search results. Enable this option will remove the "Website" schema from your source code.', 'siteseo').'
			</p>
		</div>
	</div>

	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_paged_rel">'.esc_html__('Indicate paginated content to Google', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<label for="siteseo_titles_paged_rel">
				<input id="siteseo_titles_paged_rel" name="siteseo_titles_option_name[titles_paged_rel]" type="checkbox" '.checked($paged_rel, '1', false).' value="1"/>
				'.esc_html__('Add rel next/prev link in head of paginated archive pages', 'siteseo').'
			</label>
		</div>
	</div>
	
	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_paged_noindex">'.esc_html__('noindex on paged archives', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<label for="siteseo_titles_paged_noindex">
				<input id="siteseo_titles_paged_noindex"
					name="siteseo_titles_option_name[titles_paged_noindex]"
					type="checkbox" '.checked($paged_noindex, '1', false).'
				value="1"/>
				'.esc_html__('Add a "noindex" meta robots for all paginated archive pages', 'siteseo').'
			</label>

			<p class="description">'.esc_html__('eg: https://example.com/category/my-category/page/2/', 'siteseo').'</p>
		</div>
	</div>

	<div class="siteseo-option-wrapper">
		<div class="siteseo-option-label">
			<label for="siteseo_titles_attachments_noindex">'.esc_html__('noindex on attachment pages', 'siteseo').'</label>
		</div>
		<div class="siteseo-option-input">
			<label for="siteseo_titles_attachments_noindex">
				<input id="siteseo_titles_attachments_noindex" name="siteseo_titles_option_name[titles_attachments_noindex]" type="checkbox" '.checked($attachments_noindex, '1', false).' value="1"/>
				'.esc_html__('Add a "noindex" meta robots for all attachment pages', 'siteseo').'
			</label>

			<p class="description">'.esc_html__('eg: https://example.com/my-media-attachment-page', 'siteseo').'</p>
		</div>
	</div>';
}

function siteseo_title_page(){
	
	if(!empty($_POST['submit'])){
		siteseo_save_title_settings();
	}
	
	$docs = siteseo_get_docs_links();
	
	$current_tab = '';

	if(function_exists('siteseo_admin_header')){
		siteseo_admin_header();
	}

	echo '<form method="post" class="siteseo-option">';
		wp_nonce_field('siteseo_titles_nonce');

		echo '<div id="siteseo-tabs" class="wrap">'
			.wp_kses(siteseo_feature_title('titles'), ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]]);

		$plugin_settings_tabs = [
			'tab_siteseo_titles_home' => __('Home', 'siteseo'),
			'tab_siteseo_titles_single' => __('Post Types', 'siteseo'),
			'tab_siteseo_titles_archives' => __('Archives', 'siteseo'),
			'tab_siteseo_titles_tax' => __('Taxonomies', 'siteseo'),
			'tab_siteseo_titles_advanced' => __('Advanced', 'siteseo'),
		];
		echo '<div class="nav-tab-wrapper">';
		foreach ($plugin_settings_tabs as $tab_key => $tab_caption) {
			echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-titles#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
		}
		echo '</div>
		<div class="siteseo-tab '.('tab_siteseo_titles_home' == $current_tab ? 'active' : '').'" id="tab_siteseo_titles_home">';
		siteseo_title_home_tab();
		echo '</div>
		<div class="siteseo-tab '.('tab_siteseo_titles_single' == $current_tab ? 'active' : '').'" id="tab_siteseo_titles_single">';
		siteseo_title_post_tab();
		echo '</div>
		<div class="siteseo-tab '.('tab_siteseo_titles_archives' == $current_tab ? 'active' : '').'" id="tab_siteseo_titles_archives">';
		siteseo_title_archive_tab();
		echo'</div>
		<div class="siteseo-tab '.('tab_siteseo_titles_tax' == $current_tab ? 'active' : '').'" id="tab_siteseo_titles_tax">';
		siteseo_title_taxonomies_tab();
		echo '</div>
		<div class="siteseo-tab '.('tab_siteseo_titles_advanced' == $current_tab ? 'active' : '').'" id="tab_siteseo_titles_advanced">';
		siteseo_advanced_tab();
		echo '</div>
		</div>';
		
		echo wp_kses(siteseo_submit_button(esc_html__('Save changes', 'siteseo'), false), [
			'input' => [
				'type' => true,
				'name' => true,
				'value' => true,
				'id' => true,
				'class' => true
			],
			'p' => [
				'class' => true,
			]
		]);
		
	echo '</form>';
}

function siteseo_save_title_settings(){
	
	check_admin_referer('siteseo_titles_nonce');

	if(!current_user_can('manage_options') || !is_admin()){
		return;
	}
	
	$title_options = [];

	if(empty($_POST['siteseo_titles_option_name'])){
		return;
	}

	// Home tab
	if(isset($_POST['siteseo_titles_option_name']['titles_sep'])){
		$title_options['titles_sep'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_sep']));
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_home_site_title'])){
		$title_options['titles_home_site_title'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_home_site_title']));
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_home_site_title_alt'])){
		$title_options['titles_home_site_title_alt'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_home_site_title_alt']));
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_home_site_desc'])){
		$title_options['titles_home_site_desc'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_home_site_desc']));
	}

	// Archives tab
	if(isset($_POST['siteseo_titles_option_name']['titles_archives_author_title'])){
		$title_options['titles_archives_author_title'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_author_title']));
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_archive_titles'])){
		$title_options['titles_archive_titles'] = map_deep($_POST['siteseo_titles_option_name']['titles_archive_titles'], 'sanitize_text_field');
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_archives_author_desc'])){
		$title_options['titles_archives_author_desc'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_author_desc']));
	}

	if(!empty($_POST['siteseo_titles_option_name']['titles_archives_author_noindex'])){
		$title_options['titles_archives_author_noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_archives_author_noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_author_noindex'])) : '';
	}
	
	if(!empty($_POST['siteseo_titles_option_name']['titles_archives_author_disable'])){
		$title_options['titles_archives_author_disable'] = !empty($_POST['siteseo_titles_option_name']['titles_archives_author_disable']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_author_disable'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_archives_date_title'])){
		$title_options['titles_archives_date_title'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_date_title']));
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_archives_date_desc'])){
		$title_options['titles_archives_date_desc'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_date_desc']));
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_archives_date_noindex'])){
		$title_options['titles_archives_date_noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_archives_date_noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_date_noindex'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_archives_date_disable'])){
		$title_options['titles_archives_date_disable'] = !empty($_POST['siteseo_titles_option_name']['titles_archives_date_disable']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_date_disable'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_archives_search_title'])){
		$title_options['titles_archives_search_title'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_search_title']));
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_archives_search_desc'])){
		$title_options['titles_archives_search_desc'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_search_desc']));
	}
	
	$title_options['titles_archives_search_title_noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_archives_search_title_noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_search_title_noindex'])) : '';
	
	if(isset($_POST['siteseo_titles_option_name']['titles_archives_404_title'])){
		$title_options['titles_archives_404_title'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_404_title']));
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_archives_404_desc'])){
		$title_options['titles_archives_404_desc'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_archives_404_desc']));
	}
	
	// Advanced page
	if(isset($_POST['siteseo_titles_option_name']['titles_noindex'])){
		$title_options['titles_noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_noindex'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_nofollow'])){
		$title_options['titles_nofollow'] = !empty($_POST['siteseo_titles_option_name']['titles_nofollow']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_nofollow'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_noimageindex'])){
		$title_options['titles_noimageindex'] = !empty($_POST['siteseo_titles_option_name']['titles_noimageindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_noimageindex'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_noarchive'])){
		$title_options['titles_noarchive'] = !empty($_POST['siteseo_titles_option_name']['titles_noarchive']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_noarchive'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_nosnippet'])){
		$title_options['titles_nosnippet'] = !empty($_POST['siteseo_titles_option_name']['titles_nosnippet']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_nosnippet'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_nositelinkssearchbox'])){
		$title_options['titles_nositelinkssearchbox'] = !empty($_POST['siteseo_titles_option_name']['titles_nositelinkssearchbox']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_nositelinkssearchbox'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_paged_rel'])){
		$title_options['titles_paged_rel'] = !empty($_POST['siteseo_titles_option_name']['titles_paged_rel']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_paged_rel'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_paged_noindex'])){
		$title_options['titles_paged_noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_paged_noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_paged_noindex'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_attachments_noindex'])){
		$title_options['titles_attachments_noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_attachments_noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_attachments_noindex'])) : '';
	}
	
	// Taxonomies tab
	if(isset($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['category'])){
		$title_options['titles_tax_titles']['category']['enable'] = !empty($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['category']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['category'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['title'])){
		$title_options['titles_tax_titles']['category']['title'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['title']));
	}	
	
	if(isset($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['description'])){
		$title_options['titles_tax_titles']['category']['description'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['description']));
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['noindex'])){
		$title_options['titles_tax_titles']['category']['noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['noindex'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['nofollow'])){
		$title_options['titles_tax_titles']['category']['nofollow'] = !empty($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['nofollow']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['nofollow'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['post_tag'])){
		$title_options['titles_tax_titles']['post_tag']['enable'] = !empty($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['post_tag']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles']['category']['post_tag'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_tax_titles']['post_tag']['title'])){
		$title_options['titles_tax_titles']['post_tag']['title'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles']['post_tag']['title']));
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_tax_titles']['post_tag']['description'])){
		$title_options['titles_tax_titles']['post_tag']['description'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles']['post_tag']['description']));
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_tax_titles']['post_tag']['noindex'])){
		$title_options['titles_tax_titles']['post_tag']['noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_tax_titles']['post_tag']['noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles']['post_tag']['noindex'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_tax_titles']['post_tag']['nofollow'])){
		$title_options['titles_tax_titles']['post_tag']['nofollow'] = !empty($_POST['siteseo_titles_option_name']['titles_tax_titles']['post_tag']['nofollow']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_tax_titles']['post_tag']['nofollow'])) : '';
	}

	// Post type tab
	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['enable'])){
		$title_options['titles_single_titles']['post']['enable'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['enable']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['enable'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['enable'])){
		$title_options['titles_single_titles']['page']['enable'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['enable']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['enable'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['title'])){
		$title_options['titles_single_titles']['post']['title'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['title']));
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['description'])){
		$title_options['titles_single_titles']['post']['description'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['description']));
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['title'])){
		$title_options['titles_single_titles']['page']['title'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['title']));
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['description'])){
		$title_options['titles_single_titles']['page']['description'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['description']));
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['noindex'])){
		$title_options['titles_single_titles']['post']['noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['noindex'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['nofollow'])){
		$title_options['titles_single_titles']['post']['nofollow'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['nofollow']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['nofollow'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['date'])){
		$title_options['titles_single_titles']['post']['date'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['date']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['date'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['thumb_gcs'])){
		$title_options['titles_single_titles']['post']['thumb_gcs'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['thumb_gcs']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['post']['thumb_gcs'])) : '';
	}

	// Page type
	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['noindex'])){
		$title_options['titles_single_titles']['page']['noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['noindex'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['nofollow'])){
		$title_options['titles_single_titles']['page']['nofollow'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['nofollow']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['nofollow'])) : '';
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['date'])) {
		$title_options['titles_single_titles']['page']['date'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['date']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['date'])) : '';
	}	
	
	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['thumb_gcs'])) {
		$title_options['titles_single_titles']['page']['thumb_gcs'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['thumb_gcs']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['page']['thumb_gcs'])) : '';
	}

	// Products settings
	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['title'])){
		$title_options['titles_single_titles']['product']['title'] = sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['title']));
	}

	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['description'])){
		$title_options['titles_single_titles']['product']['description'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['description']));
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['noindex'])){
		$title_options['titles_single_titles']['product']['noindex'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['noindex']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['noindex'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['nofollow'])){
		$title_options['titles_single_titles']['product']['nofollow'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['nofollow']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['nofollow'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['date'])){
		$title_options['titles_single_titles']['product']['date'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['date']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['date'])) : '';
	}
	
	if(isset($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['thumb_gcs'])){
		$title_options['titles_single_titles']['product']['thumb_gcs'] = !empty($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['thumb_gcs']) ? sanitize_text_field(wp_unslash($_POST['siteseo_titles_option_name']['titles_single_titles']['product']['thumb_gcs'])) : '';
	}

	update_option('siteseo_titles_option_name', $title_options);
}

siteseo_title_page();


