<?php

if(!defined('ABSPATH')){
	die('Hacking Attempt!');
}

function siteseo_register_sidebar(){
	$assets = include SITESEO_MAIN . '/public/editor/sidebar/build/index.asset.php';
	$css_file = SITESEO_MAIN . '/public/editor/sidebar/build/index.css';
	
	if(file_exists($css_file)){
		wp_register_style('siteseo-sidebar-css', SITESEO_DIR_URL . '/main/public/editor/sidebar/build/index.css', [], $assets['version'].time());
	}
	
	$other_dependencies = ['siteseo-cpt-tabs-js', 'siteseo-tagify-js'];
	$js_dependencies = array_merge($assets['dependencies'], $other_dependencies);

	wp_register_script('siteseo-sidebar-js', SITESEO_DIR_URL . '/main/public/editor/sidebar/build/index.js', $js_dependencies, $assets['version']);
}

add_action('init', 'siteseo_register_sidebar');

function siteseo_sidebar_script_enqueue() {
    wp_enqueue_script('siteseo-sidebar-js');
	wp_enqueue_style('siteseo-sidebar-css');

	wp_localize_script('siteseo-sidebar-js', 'siteseo_sidebar', [
		'nonce' => wp_create_nonce('siteseo_sidebar_nonce'),
		'ajax_url' => admin_url('admin-ajax.php')
	]);
}

add_action('enqueue_block_editor_assets', 'siteseo_sidebar_script_enqueue');