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

namespace SiteSEO\Thirds\RankMath;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Helpers\TagCompose;

class Tags {
	protected $variables = [
		'%sep%'											 => 'sep',
		'%search_query%'									=> 'search_keywords',
		'%count(varname)%'								  => '',
		'%filename%'										=> '',
		'%sitename%'										=> 'sitetitle',
		'%sitedesc%'										=> 'tagline',
		'%currentdate%'									 => 'currentdate',
		'%currentday%'									  => 'currentday',
		'%currentmonth%'									=> 'currentmonth',
		'%currentyear%'									 => 'currentyear',
		'%currenttime%'									 => 'currenttime',
		'%currenttime(F jS, Y)%'							=> 'currenttime',
		'%org_name%'										=> '',
		'%org_logo%'										=> '',
		'%title%'										   => 'post_title',
		'%parent_title%'									=> '',
		'%excerpt%'										 => 'post_excerpt',
		'%excerpt_only%'									=> 'post_excerpt',
		'%seo_title%'									   => '',
		'%seo_description%'								 => '',
		'%url%'											 => 'post_url',
		'%post_thumbnail%'								  => 'post_thumbnail_url',
		'%date%'											=> 'post_date',
		'%modified%'										=> 'post_modified_date',
		'%date(F jS, Y)%'								   => 'post_date',
		'%modified(F jS, Y)%'							   => 'post_modified_date',
		'%category%'										=> 'post_category',
		'%categories%'									  => '',
		'%categories(limit=3&separator= | &exclude=12,23)%' => '',
		'%tag%'											 => 'post_tag',
		'%tags%'											=> '',
		'%tags(limit=3&separator= | &exclude=12,23)%'	   => '',
		'%term%'											=> 'term_title',
		'%term_description%'								=> 'term_description',
		'%customterm(taxonomy-name)%'					   => '',
		'%customterm_desc(taxonomy-name)%'				  => '',
		'%userid%'										  => '',
		'%name%'											=> 'post_author',
		'%user_description%'								=> '',
		'%id%'											  => '',
		'%focuskw%'										 => 'target_keyword',
		'%customfield(field-name)%'						 => '',
		'%page%'											=> 'page',
		'%pagenumber%'									  => 'current_pagination',
		'%pagetotal%'									   => '',
		'%pt_single%'									   => '',
		'%pt_plural%'									   => 'cpt_plural',
	];

	/**
	 * @since 4.3.0
	 *
	 * @param string $input
	 *
	 * @return string
	 */
	public function replaceTags($input) {
		foreach ($this->variables as $key => $value) {
			if ( ! empty($value)) {
				$value = TagCompose::getValueWithTag($value);
			}

			$input = str_replace($key, $value, $input);
		}

		return $input;
	}
}
