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

/**
 * Sitemap block display callback
 *
 * @param   array	 $attributes  Block attributes
 * @param   string	$content	 Inner block content
 * @param   WP_Block  $block	   Actual block
 * @return  string	$html
 */
function siteseo_sitemap_block( $attributes, $content, $block ){
	$attr = get_block_wrapper_attributes();
	$html = '';
	if ( '1' == siteseo_get_toggle_option( 'xml-sitemap' ) && '1' == siteseo_get_service('SitemapOption')->getHtmlEnable() ) {
		$atts = ! empty( $attributes['postTypes'] ) ? ['cpt' => join( ',', $attributes['postTypes'] ) ] : [];
		$html = sprintf( '<div %s>%s</div>', $attr, siteseo_xml_sitemap_html_hook( $atts ) );
	}
	return $html;
}