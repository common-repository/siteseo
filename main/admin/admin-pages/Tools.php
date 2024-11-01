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
	die('HACKING ATTEMPT');
}

function siteseo_tools_page(){
	
	$docs = siteseo_get_docs_links();

	if(function_exists('siteseo_admin_header')){
		siteseo_admin_header();
	}

	$current_tab = '';
	$plugin_settings_tabs = [
		'tab_siteseo_tool_settings' => __('Settings', 'siteseo'),
		'tab_siteseo_tool_plugins' => __('Plugins', 'siteseo'),
		'tab_siteseo_tool_reset' => __('Reset', 'siteseo'),
	];

	$plugin_settings_tabs = apply_filters('siteseo_tools_tabs', $plugin_settings_tabs);
	$feature_title_kses = [
		'h1' => true,
		'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 
		'label' => ['for' => true], 
		'span' => ['id' => true, 'class' => true], 
		'div' => ['id' => true, 'class' => true]
	];
	
	$plugins = [
		'yoast' => 'Yoast SEO',
		'aio' => 'All In One SEO',
		'seo-framework' => 'The SEO Framework',
		'rk' => 'Rank Math',
		'squirrly' => 'Squirrly SEO',
		'seo-ultimate' => 'SEO Ultimate',
		'wp-meta-seo' => 'WP Meta SEO',
		'premium-seo-pack' => 'Premium SEO Pack',
		'wpseo' => 'wpSEO',
		'platinum-seo' => 'Platinum SEO Pack',
		'smart-crawl' => 'SmartCrawl',
		'seopressor' => 'SeoPressor',
		'slim-seo' => 'Slim SEO'
	];

	echo '<div class="siteseo-option">';

	echo '<div id="siteseo-tabs" class="wrap">'.wp_kses(siteseo_feature_title(null), $feature_title_kses). 
	'<div class="nav-tab-wrapper">';

	foreach($plugin_settings_tabs as $tab_key => $tab_caption){
		echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-import-export#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
	}
	echo '</div>';

	do_action('siteseo_tools_before', $current_tab, $docs);

	echo '<div class="siteseo-tab' . (!empty($current_tab) && $current_tab === 'tab_siteseo_tool_settings' ? ' active' : '') . '" id="tab_siteseo_tool_settings">
		<div class="postbox section-tool">
			<div class="siteseo-section-header">
				<h2>' . esc_html__('Settings', 'siteseo') . '</h2>
			</div>
			<div class="inside">
				<h3><span>' . esc_html__('Export plugin settings', 'siteseo') . '</span></h3>
				<p>' . esc_html__('Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'siteseo') . '</p>
				<form method="post">
					<input type="hidden" name="siteseo_action" value="export_settings" />';
					wp_nonce_field('siteseo_export_nonce', 'siteseo_export_nonce', true);
					echo '<button id="siteseo-export" type="submit" class="btn btnSecondary">' . esc_html__('Export', 'siteseo') . '</button>
				</form>
			</div>
		</div>
		<div class="postbox section-tool">
			<div class="inside">
				<h3><span>' . esc_html__('Import plugin settings', 'siteseo') . '</span></h3>
				<p>' . esc_html__('Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'siteseo') . '</p>
				<form method="post" enctype="multipart/form-data">
					<p><input type="file" name="import_file" /></p>
					<input type="hidden" name="siteseo_action" value="import_settings" />';
					wp_nonce_field('siteseo_import_nonce', 'siteseo_import_nonce', true);
					echo '<button id="siteseo-import-settings" type="submit" class="btn btnSecondary">' . esc_html__('Import', 'siteseo') . '</button>';

	if (!empty($_GET['success']) && 'true' == siteseo_opt_get('success')) {
		echo '<div class="log" style="display:block"><div class="siteseo-notice is-success"><p>' . esc_html__('Import completed!', 'siteseo') . '</p></div></div>';
	}

	echo '</form>
			</div>
		</div>
	</div>
	<div class="siteseo-tab ' . ('tab_siteseo_tool_plugins' == $current_tab ? 'active' : '') . '" id="tab_siteseo_tool_plugins">
		<div class="siteseo-section-header">
			<h2>' . esc_html__('Plugins', 'siteseo') . '</h2>
		</div>
		<h3><span>' . esc_html__('Import posts and terms metadata from', 'siteseo') . '</span></h3>
		<p><select id="select-wizard-import" name="select-wizard-import">
			<option value="none">' . esc_html__('Select an option', 'siteseo') . '</option>';
			foreach($plugins as $plugin => $name){
				echo '<option value="' . esc_attr($plugin) . '-migration-tool">' . esc_html($name) . '</option>';
			}

	echo '</select></p>
		<p class="description">' . esc_html__('You don\'t have to enable the selected SEO plugin to run the import.', 'siteseo') . '</p>';

	foreach($plugins as $plugin => $name){
		echo wp_kses_post(siteseo_migration_tool($plugin, $name));
	}

	do_action('siteseo_tools_migration', $current_tab);

	echo '</div>
	<div class="siteseo-tab ' . ('tab_siteseo_tool_reset' == $current_tab ? 'active' : '') . '" id="tab_siteseo_tool_reset">
		<div class="postbox section-tool">
			<div class="siteseo-section-header">
				<h2>' . esc_html__('Reset', 'siteseo') . '</h2>
			</div>
			<div class="inside">
				<h3><span>' . esc_html__('Reset All Notices From Notifications Center', 'siteseo') . '</span></h3>
				<p>' . esc_html__('By clicking Reset Notices, all notices in the notifications center will be set to their initial status.', 'siteseo') . '</p>
				<form method="post" enctype="multipart/form-data">
					<input type="hidden" name="siteseo_action" value="reset_notices_settings" />';
					wp_nonce_field('siteseo_reset_notices_nonce', 'siteseo_reset_notices_nonce', true);
					echo '<button type="submit" class="btn btnSecondary">' . esc_html__('Reset notices', 'siteseo') . '</button>
				</form>
			</div>
		</div>
		<div class="postbox section-tool">
			<div class="inside">
				<h3>' . esc_html__('Reset All Settings', 'siteseo') . '</h3>
				<div class="siteseo-notice is-warning">
					<span class="dashicons dashicons-warning"></span>
					<div>
						<p>' . wp_kses_post(__('<strong>WARNING:</strong> Delete all options related to this plugin in your database.', 'siteseo')) . '</p>
					</div>
				</div>
				<form method="post" enctype="multipart/form-data">
					<input type="hidden" name="siteseo_action" value="reset_settings" />';
					wp_nonce_field('siteseo_reset_nonce', 'siteseo_reset_nonce', true);
					echo '<button type="submit" class="btn btnSecondary is-deletable">' . esc_html__('Reset settings', 'siteseo') . '</button>
				</form>
			</div>
		</div>
	</div>
	</div>
	</div>';
}

siteseo_tools_page();