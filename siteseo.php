<?php
/*
Plugin Name: SiteSEO
Plugin URI: http://wordpress.org/plugins/siteseo/
Description: This plugin handles On Page SEO, Content Analysis, Social Previews, Google Preview, Hyperlink Analysis, Image Analysis, Home Page Monitor, Schemas for various type of posts.
Author: Softaculous
Version: 1.1.1
Author URI: https://siteseo.io/
License: GPLv2
Text Domain: siteseo
Domain Path: /languages
*/

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

// We need the ABSPATH
if (!defined('ABSPATH')) exit;

if(!function_exists('add_action')){
	echo 'You are not allowed to access this page directly.';
	exit;
}

// If SITESEO_VERSION exists then the plugin is loaded already !
if(defined('SITESEO_VERSION')) {
	return;
}

define('SITESEO_FILE', __FILE__);

include_once(dirname(__FILE__).'/init.php');
