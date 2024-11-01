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

namespace SiteSEO\Services;

if (! defined('ABSPATH')) {
	exit;
}

class EnqueueModuleMetabox
{
	public function canEnqueue()
	{
		$response = true;

		global $pagenow;

		if ('widgets.php' == $pagenow) {
			$response = false;
		}

		if (isset($_GET['siteseo_preview']) || isset($_GET['preview'])) {
			$response = false;
		}

		if (isset($_GET['oxygen_iframe'])) {
			$response = false;
		}

		if (isset($_GET['brickspreview'])) {
			$response = false;
		}

		if (isset($_GET['et_bfb'])) {
			$response = false;
		}

		if(!is_admin() && !is_singular()){
			$response = false;
		}

		if(get_the_ID() === (int) get_option('page_on_front')){
			$response = true;
		}

		if(get_the_ID() ===  (int) get_option('page_for_posts')){
			$response = true;
		}


		if (function_exists('get_current_screen')) {
			$currentScreen = \get_current_screen();

			if($currentScreen && method_exists($currentScreen, 'is_block_editor') &&  $currentScreen->is_block_editor() === false){
				$response = false;
			}

			if($currentScreen && !siteseo_get_service('AdvancedOption')->getAccessUniversalMetaboxGutenberg() && method_exists($currentScreen, 'is_block_editor') &&  $currentScreen->is_block_editor() !== false){
				$response = false;
			}
		}

		if(siteseo_get_service('AdvancedOption')->getDisableUniversalMetaboxGutenberg()){
			$response = false;
		}

		if(!current_user_can('edit_posts')){
			$response = false;
		}

		$settingsAdvanced = siteseo_get_service('AdvancedOption');
		$rolesTabs = [
			"GLOBAL" => $settingsAdvanced->getSecurityMetaboxRole(),
			"CONTENT_ANALYSIS" => $settingsAdvanced->getSecurityMetaboxRoleContentAnalysis(),
		];


		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;
		$counterCanEdit = 0;

		foreach ($rolesTabs as $key => $roleTab) {
			if($roleTab === null){
				continue;
			}

			$diff = array_diff($roles, array_keys($roleTab));
			if(count($diff) !== count($roles)){
				$counterCanEdit++;
			}
		}

		if($counterCanEdit >= 2){
			$response = false;
		}

		if(isset($_POST['can_enqueue_siteseo_metabox']) && $_POST['can_enqueue_siteseo_metabox'] !== '1'){
			$response = false;
		}
		if(isset($_POST['can_enqueue_siteseo_metabox']) && $_POST['can_enqueue_siteseo_metabox'] === '1'){
			$response = true;
		}

		return apply_filters('siteseo_can_enqueue_universal_metabox', $response);
	}
}
