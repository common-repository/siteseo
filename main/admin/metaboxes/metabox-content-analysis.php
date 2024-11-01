<?php

if(!defined('ABSPATH')){
	die('HACKING ATTEMPT!');
}

function siteseo_content_analysis_tab(&$metabox_data){
	global $post;
	
	echo '<div class="siteseo-metabox-option-wrap">
	<div class="siteseo-metabox-label-wrap">
		<label for="siteseo_titles_title_meta">'.esc_html__('Focus Keywords', 'title').'</label>
	</div>
	<div class="siteseo-metabox-input-wrap">
		<input id="siteseo_analysis_target_kw_meta" type="text" name="siteseo_analysis_target_kw" placeholder="'.esc_html__('Enter your target keywords', 'siteseo').'" value="'.esc_attr($metabox_data['analysis_target_kw']).'" />

		<button id="siteseo_launch_analysis" type="button" style="margin-top:10px;" class="'.esc_attr(siteseo_btn_secondary_classes()).'" data_id="'. esc_attr(get_the_ID()).'" data_post_type="'.esc_attr(get_current_screen()->post_type).'">'.esc_html__('Refresh analysis', 'siteseo').'</button>
		<p class="description">'.esc_html__('Refresh analysis after saving the post to improve the accuracy of the analysis', 'siteseo').'
	</div>
</div>
<div id="siteseo-metabox-content-analysis">
<div class="siteseo-metabox-subtabs">
	<div class="siteseo-metabox-tab-label siteseo-metabox-tab-label-active" data-tab="siteseo-metabox-seo-analysis-tab">'.esc_html__('SEO Analysis', 'siteseo').'</div>
	<div class="siteseo-metabox-tab-label" data-tab="siteseo-metabox-readibility-analysis-tab">'.esc_html__('Content Readibility', 'siteseo').'</div>
</div>
<div class="siteseo-metabox-seo-analysis-tab siteseo-metabox-tab" style="display:block;">';
if(function_exists('siteseo_get_service')){
	$analyzes = siteseo_get_service('GetContentAnalysis')->getAnalyzes($post);
	siteseo_get_service('RenderContentAnalysis')->render($analyzes, $metabox_data['analysis_data']);
}

echo '</div>
<div class="siteseo-metabox-readibility-analysis-tab siteseo-metabox-tab">
<p class="description">
'.esc_html__('This section works as a guide to help you write, better content for your user, this do not have a direct affect on SEO, but it will help you write better content for your users which will help user stay on your site longer, or will improve the Click Through rate.
Which will signal search engines about the userfulness and likeleyness of your content by your user which indirectly improve SEO of the page.', 'siteseo').'
</p>';
if(function_exists('siteseo_get_service')){
	$analyzes = [];
	$analyzes = siteseo_get_service('GetContentAnalysis')->getReadibilityAnalyzes($post);
	siteseo_get_service('RenderContentAnalysis')->renderReadibility($analyzes, $metabox_data['readibility_data']);
}

echo '</div>
';


echo '</div>';

}