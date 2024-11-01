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

function siteseo_sitemap_post_types_tab() {

	if(!empty($_POST['submit'])){
		siteseo_save_sitemaps_settings();
	}

	echo '<div class="siteseo-section-header">
		<h2>' . esc_html__('Post Types', 'siteseo') . '</h2>
	</div>
	<p>' . esc_html__('Include/Exclude Post Types.', 'siteseo') . '</p>

	<table class="form-table">
		<tbody>
			<tr>
				<th colspan="2">
					<h3>' . esc_html__('Check to INCLUDE Post Types', 'siteseo') . '</h3>
				</th>
			</tr>';

	$options = get_option('siteseo_xml_sitemap_option_name');
	$postTypes = siteseo_get_service('WordPressData')->getPostTypes();
	$postTypes[] = get_post_type_object('attachment');
	$postTypes = apply_filters('siteseo_sitemaps_cpt', $postTypes);

	foreach ($postTypes as $siteseo_cpt_key => $siteseo_cpt_value) {
		$check = isset($options['xml_sitemap_post_types_list'][$siteseo_cpt_key]['include']);

		echo '<tr>
			<th class="row">
			<td>
				<h3><label for="siteseo_xml_sitemap_taxonomies_list_include[' . esc_html($siteseo_cpt_value->labels->name) . ']">
					' . esc_html($siteseo_cpt_value->labels->name) . '
					<em><small>[' . esc_html($siteseo_cpt_value->name) . ']</small></em>
				</label></h3>
			
			
				<div class="siteseo_wrap_single_cpt">
					<label for="siteseo_xml_sitemap_post_types_list_include[' . esc_attr($siteseo_cpt_key) . ']">
						<input 
							id="siteseo_xml_sitemap_post_types_list_include[' . esc_attr($siteseo_cpt_key) . ']" 
							name="siteseo_xml_sitemap_option_name[xml_sitemap_post_types_list][' . esc_attr($siteseo_cpt_key) . '][include]" 
							type="checkbox" ' . checked($check, '1', false) . ' 
							value="1" />
						' . esc_html__('Include', 'siteseo') . '
					</label>
				</div>';

		if ('attachment' == $siteseo_cpt_value->name) {
			echo '<div class="siteseo-notice is-warning is-inline">
					<p>' . wp_kses_post(__('You should never include <strong>attachment</strong> post type in your sitemap. Be careful if you checked this.', 'siteseo')) . '</p>
				</div>';
		}

		echo '</td>
			</th>
		</tr>';
	}

	echo '</tbody>
	</table>';
}
function siteseo_sitemap_taxonomies_tab(){

	if(!empty($_POST['submit'])){
		siteseo_save_sitemaps_settings();
	}

	echo '<div class="siteseo-section-header">
		<h2>' . esc_html__('Taxonomies', 'siteseo') . '</h2>
	</div>
	<p>' . esc_html__('Include/Exclude Taxonomies.', 'siteseo') . '</p>

	<table class="form-table">
		<tbody>';

	$options = get_option('siteseo_xml_sitemap_option_name');
	$taxonomies = siteseo_get_service('WordPressData')->getTaxonomies();
	$taxonomies = apply_filters('siteseo_sitemaps_tax', $taxonomies);

	echo '<th scope="row">' . esc_html__('Check to INCLUDE Taxonomies', 'siteseo') . '</th>';

	foreach ($taxonomies as $siteseo_tax_key => $siteseo_tax_value) { 
		$check = isset($options['xml_sitemap_taxonomies_list'][$siteseo_tax_key]['include']);

		echo '<tr>
			<th scope="row">
				<td>
					<h3>
						<label for="siteseo_xml_sitemap_taxonomies_list_include[' . esc_attr($siteseo_tax_key) . ']">
							' . esc_html($siteseo_tax_value->labels->name) . '
							<em><small>[' . esc_html($siteseo_tax_value->name) . ']</small></em>
						</label>
					</h3>
					<label for="siteseo_xml_sitemap_post_types_list_include">
						<input
							id="siteseo_xml_sitemap_taxonomies_list_include[' . esc_attr($siteseo_tax_key) . ']"
							name="siteseo_xml_sitemap_option_name[xml_sitemap_taxonomies_list][' . esc_attr($siteseo_tax_key) . '][include]"
							type="checkbox" ' . checked($check, '1', false) . '
							value="1"/>
						' . esc_html__('Include', 'siteseo') . '
					</label>
				</td>
			</th>
		</tr>';
	}

	echo '</tbody>
	</table>';
}

function siteseo_sitemap_html_tab() {
	
	if(!empty($_POST['submit'])){
		siteseo_save_sitemaps_settings();
	}

    $docs = siteseo_get_docs_links();
    $options = get_option('siteseo_xml_sitemap_option_name');

    echo '<div class="siteseo-section-header">
        <h2>' . esc_html__('HTML Sitemap', 'siteseo') . '</h2>
    </div>
    <p>' . esc_html__('Create an HTML Sitemap for your visitors and boost your SEO.', 'siteseo') . '</p>
    <p>' . esc_html__('Limited to 1,000 posts per post type. You can change the order and sorting criteria below.', 'siteseo') . '
        <a class="siteseo-doc" href="' . esc_url($docs['sitemaps']['html']) . '" target="_blank">
            <span class="dashicons dashicons-editor-help"></span>
            <span class="screen-reader-text">' . esc_html__('Guide to enable a HTML Sitemap - new window', 'siteseo') . '</span>
        </a>
    </p>

    <div class="siteseo-notice">
        <span class="dashicons dashicons-info"></span>
        <div>
            <h3>' . esc_html__('How to use the HTML Sitemap?', 'siteseo') . '</h3>

            <h4>' . esc_html__('Block Editor', 'siteseo') . '</h4>
            <p>' . wp_kses_post(__('Add the HTML sitemap block using the <strong>Block Editor</strong>.', 'siteseo')) . '</p>

            <hr>
            <h4>' . esc_html__('Shortcode', 'siteseo') . '</h4>
            <p>' . esc_html__('You can also use this shortcode in your content (post, page, post type...):', 'siteseo') . '</p>
            <pre>[siteseo_html_sitemap]</pre>
            <p>' . esc_html__('To include specific custom post types, use the CPT attribute:', 'siteseo') . '</p>
            <pre>[siteseo_html_sitemap cpt="post,product"]</pre>

            <h4>' . esc_html__('Other', 'siteseo') . '</h4>
            <p>' . esc_html__('Dynamically display the sitemap by entering an ID to the first field below.', 'siteseo') . '</p>
        </div>
    </div>

    <table class="form-table">
        <tr>
            <th scope="row">' . esc_html__('Post, Page, or Custom Post Type IDs to display:', 'siteseo') . '</th>
            <td>
                <input type="text" name="siteseo_xml_sitemap_option_name[xml_sitemap_html_mapping]" 
                       placeholder="' . esc_html__('eg: 2, 28, 68', 'siteseo') . '" 
                       aria-label="' . esc_html__('Enter a post, page or custom post type ID(s) to display the sitemap', 'siteseo') . '" 
                       value="' . esc_html(isset($options['xml_sitemap_html_mapping']) ? $options['xml_sitemap_html_mapping'] : '') . '"/>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Exclude Posts, Pages, Custom Post Types or Terms IDs:', 'siteseo') . '</th>
            <td>
                <input type="text" name="siteseo_xml_sitemap_option_name[xml_sitemap_html_exclude]" 
                       placeholder="' . esc_html__('eg: 13, 8, 38', 'siteseo') . '" 
                       aria-label="' . esc_html__('Exclude some Posts, Pages, Custom Post Types or Terms IDs', 'siteseo') . '" 
                       value="' . esc_html(isset($options['xml_sitemap_html_exclude']) ? $options['xml_sitemap_html_exclude'] : '') . '"/>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Order:', 'siteseo') . '</th>
            <td>
                <select id="siteseo_xml_sitemap_html_order" name="siteseo_xml_sitemap_option_name[xml_sitemap_html_order]">
                    <option value="DESC" ' . selected(isset($options['xml_sitemap_html_order']) && $options['xml_sitemap_html_order'] === 'DESC', true, false) . '>' . esc_html__('DESC (descending order from highest to lowest values (3, 2, 1; c, b, a))', 'siteseo') . '</option>
                    <option value="ASC" ' . selected(isset($options['xml_sitemap_html_order']) && $options['xml_sitemap_html_order'] === 'ASC', true, false) . '>' . esc_html__('ASC (ascending order from lowest to highest values (1, 2, 3; a, b, c))', 'siteseo') . '</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Order By:', 'siteseo') . '</th>
            <td>
                <select id="siteseo_xml_sitemap_html_orderby" name="siteseo_xml_sitemap_option_name[xml_sitemap_html_orderby]">
                    <option value="date" ' . selected(isset($options['xml_sitemap_html_orderby']) && $options['xml_sitemap_html_orderby'] === 'date', true, false) . '>' . esc_html__('Default (date)', 'siteseo') . '</option>
                    <option value="title" ' . selected(isset($options['xml_sitemap_html_orderby']) && $options['xml_sitemap_html_orderby'] === 'title', true, false) . '>' . esc_html__('Post Title', 'siteseo') . '</option>
                    <option value="modified" ' . selected(isset($options['xml_sitemap_html_orderby']) && $options['xml_sitemap_html_orderby'] === 'modified', true, false) . '>' . esc_html__('Modified date', 'siteseo') . '</option>
                    <option value="ID" ' . selected(isset($options['xml_sitemap_html_orderby']) && $options['xml_sitemap_html_orderby'] === 'ID', true, false) . '>' . esc_html__('Post ID', 'siteseo') . '</option>
                    <option value="menu_order" ' . selected(isset($options['xml_sitemap_html_orderby']) && $options['xml_sitemap_html_orderby'] === 'menu_order', true, false) . '>' . esc_html__('Menu order', 'siteseo') . '</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Disable Date:', 'siteseo') . '</th>
            <td>
                <label for="siteseo_xml_sitemap_html_date">
                    <input id="siteseo_xml_sitemap_html_date" name="siteseo_xml_sitemap_option_name[xml_sitemap_html_date]" type="checkbox" ' . checked(isset($options['xml_sitemap_html_date']), '1', false) . ' value="1"/>
                    ' . esc_html__('Disable date after each post, page, post type?', 'siteseo') . '
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove Archive Links:', 'siteseo') . '</th>
            <td>
                <label for="siteseo_xml_sitemap_html_archive_links">
                    <input id="siteseo_xml_sitemap_html_archive_links" name="siteseo_xml_sitemap_option_name[xml_sitemap_html_archive_links]" type="checkbox" ' . checked(isset($options['xml_sitemap_html_archive_links']), '1', false) . ' value="1"/>
                    ' . esc_html__('Remove links from archive pages (eg: Products)', 'siteseo') . '
                </label>
            </td>
        </tr>
    </table>';
}
function siteseo_sitemap_general_tab() {

	if(!empty($_POST['submit'])){
		siteseo_save_sitemaps_settings();
	}

    $docs = siteseo_get_docs_links();
    $options = get_option('siteseo_xml_sitemap_option_name');
    $server_software = isset($_SERVER['SERVER_SOFTWARE']) ? explode('/', sanitize_text_field(wp_unslash($_SERVER['SERVER_SOFTWARE']))) : [];
    $is_nginx = 'nginx' == current($server_software);

    echo '<div class="siteseo-section-header">
        <h2>' . esc_html__('General', 'siteseo') . '</h2>
    </div>';

    if ('' == get_option('permalink_structure')) {
        echo '<div class="siteseo-notice is-error">
            <p>' . wp_kses_post(__('Your permalinks are not <strong>SEO Friendly</strong>! Enable <strong>pretty permalinks</strong> to fix this.', 'siteseo')) . '</p>
            <p><a href="' . esc_url(admin_url('options-permalink.php')) . '" class="btn btnSecondary">' . esc_html__('Change this settings', 'siteseo') . '</a></p>
        </div>';
    }

    echo '<p>' . wp_kses_post(__('A sitemap is a file where you provide information about the <strong>pages, images, videos... and the relationships between them</strong>. Search engines like Google read this file to <strong>crawl your site more efficiently</strong>.', 'siteseo')) . '</p>
    <p>' . wp_kses_post(__('The XML sitemap is an <strong>exploration aid</strong>. Not having a sitemap will absolutely <strong>NOT prevent engines from indexing your content</strong>. For this, opt for meta robots.', 'siteseo')) . '</p>
    <p>' . esc_html__('This is the URL of your index sitemaps to submit to search engines:', 'siteseo') . '</p>
    <p><pre><span class="dashicons dashicons-external"></span><a href="' . esc_url(get_option('home')) . '/sitemaps.xml" target="_blank">' . esc_url(get_option('home')) . '/sitemaps.xml</a></pre></p>
    <p><button type="button" id="siteseo-flush-permalinks" class="btn btnSecondary">' . esc_html__('Flush permalinks', 'siteseo') . '</button><span class="spinner"></span></p>';

    echo '<div class="siteseo-notice">
        <span class="dashicons dashicons-info"></span>
        <div>
            <p>' . wp_kses_post(__('To view your sitemap, <strong>enable permalinks</strong> (not default one), and save settings to flush them.', 'siteseo')) . '</p>
            <p>' . wp_kses_post(__('<strong>Noindex content</strong> will not be displayed in Sitemaps. Same for custom canonical URLs.', 'siteseo')) . '</p>
            <p>' . wp_kses_post(__('If you disable globally this feature (using the blue toggle from above), the <strong>native WordPress XML sitemaps</strong> will be re-activated.', 'siteseo')) . '</p>
            <p class="siteseo-help"><span class="dashicons dashicons-external"></span><a href="' . esc_url($docs['sitemaps']['error']['blank']) . '" target="_blank">' . esc_html__('Blank sitemap?', 'siteseo') . '</a>
                <span class="dashicons dashicons-external"></span><a href="' . esc_url($docs['sitemaps']['error']['404']) . '" target="_blank">' . esc_html__('404 error?', 'siteseo') . '</a>
                <span class="dashicons dashicons-external"></span><a href="' . esc_url($docs['sitemaps']['error']['html']) . '" target="_blank">' . esc_html__('HTML error? Exclude XML and XSL from caching plugins!', 'siteseo') . '</a>
                <span class="dashicons dashicons-external"></span><a href="' . esc_url(array_shift($docs['get_started']['sitemaps'])) . '" target="_blank">' . esc_html__('Add your XML sitemaps to Google Search Console (video)', 'siteseo') . '</a></p>
        </div>
    </div>';

    if ($is_nginx) {
        echo '<div class="siteseo-notice">
            <span class="dashicons dashicons-info"></span>
            <div>
                <p>' . esc_html__('Your server uses NGINX. If XML Sitemaps doesn\'t work properly, you need to add this rule to your configuration:', 'siteseo') . '</p>
                <pre>location ~ (([^/]*)sitemap(.*)|news|author|video(.*))\.x(m|s)l$ {
    ## SiteSEO
    rewrite ^.*/sitemaps\.xml$ /index.php?siteseo_sitemap=1 last;
    rewrite ^.*/news.xml$ /index.php?siteseo_news=$1 last;
    rewrite ^.*/video.xml$ /index.php?siteseo_video=$1 last;
    rewrite ^.*/author.xml$ /index.php?siteseo_author=$1 last;
    rewrite ^.*/sitemaps_xsl\.xsl$ /index.php?siteseo_sitemap_xsl=1 last;
    rewrite ^.*/sitemaps_video_xsl\.xsl$ /index.php?siteseo_sitemap_video_xsl=1 last;
    rewrite ^.*/([^/]+?)-sitemap([0-9]+)?.xml$ /index.php?siteseo_cpt=$1&siteseo_paged=$2 last;
}</pre>
            </div>
        </div>';
    }

    echo '<table class="form-table">
        <tr valign="top">
            <th scope="row">' . esc_html__('Enable XML Sitemap', 'siteseo') . '</th>
            <td>
                <label for="siteseo_xml_sitemap_general_enable">
                    <input id="siteseo_xml_sitemap_general_enable"
                           name="siteseo_xml_sitemap_option_name[xml_sitemap_general_enable]" type="checkbox" ' . checked(isset($options['xml_sitemap_general_enable']), '1', false) . '
                           value="1"/>
                    ' . esc_html__('Enable XML Sitemap', 'siteseo') . '
                    ' . wp_kses_post(siteseo_tooltip_link($docs['sitemaps']['xml'], __('Guide to enable XML Sitemaps - new window', 'siteseo'))) . '
                </label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">' . esc_html__('Enable Image Sitemap', 'siteseo') . '</th>
            <td>
                <label for="siteseo_xml_sitemap_img_enable">
                    <input id="siteseo_xml_sitemap_img_enable" name="siteseo_xml_sitemap_option_name[xml_sitemap_img_enable]"
                           type="checkbox" ' . checked(isset($options['xml_sitemap_img_enable']), '1', false) . '
                           value="1"/>
                    ' . esc_html__('Enable Image Sitemap (standard images, image galleries, featured image, WooCommerce product images)', 'siteseo') . '
                    ' . wp_kses_post(siteseo_tooltip_link($docs['sitemaps']['image'], __('Guide to enable XML image sitemap - new window', 'siteseo'))) . '
                </label>
                <p class="description">' . esc_html__('Images in XML sitemaps are visible only from the source code.', 'siteseo') . '</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">' . esc_html__('Enable Author Sitemap', 'siteseo') . '</th>
            <td>
                <label for="siteseo_xml_sitemap_author_enable">
                    <input id="siteseo_xml_sitemap_author_enable"
                           name="siteseo_xml_sitemap_option_name[xml_sitemap_author_enable]" type="checkbox" ' . checked(isset($options['xml_sitemap_author_enable']), '1', false) . '
                           value="1"/>
                    ' . esc_html__('Enable Author Sitemap', 'siteseo') . '
                </label>
                <p class="description">' . esc_html__('Make sure to enable author archive from SEO, titles and metas, archives tab.', 'siteseo') . '</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">' . esc_html__('Enable HTML Sitemap', 'siteseo') . '</th>
            <td>
                <label for="siteseo_xml_sitemap_html_enable">
                    <input id="siteseo_xml_sitemap_html_enable"
                           name="siteseo_xml_sitemap_option_name[xml_sitemap_html_enable]" type="checkbox" ' . checked(isset($options['xml_sitemap_html_enable']), '1', false) . '
                           value="1"/>
                    ' . esc_html__('Enable HTML Sitemap', 'siteseo') . '
                    ' . wp_kses_post(siteseo_tooltip_link($docs['sitemaps']['html'], __('Guide to enable a HTML Sitemap - new window', 'siteseo'))) . '
                </label>
            </td>
        </tr>
    </table>';
}

function siteseo_sitemaps_page_html(){
	
	$current_tab = '';
	$plugin_settings_tabs	= [
		'tab_siteseo_xml_sitemap_general' => __('General', 'siteseo'),
		'tab_siteseo_xml_sitemap_post_types' => __('Post Types', 'siteseo'),
		'tab_siteseo_xml_sitemap_taxonomies' => __('Taxonomies', 'siteseo'),
		'tab_siteseo_html_sitemap' => __('HTML Sitemap', 'siteseo'),
	];
	
	$feature_title_kses = ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]];
	
	if(function_exists('siteseo_admin_header')){
		siteseo_admin_header();
	}
	echo '<form method="post" class="siteseo-option" name="siteseo-flush">';
	wp_nonce_field('siteseo_sitemap_nonce');

	echo '<div id="siteseo-tabs" class="wrap">'.
	wp_kses(siteseo_feature_title('xml-sitemap'), $feature_title_kses).
	'<div class="nav-tab-wrapper">';
	foreach($plugin_settings_tabs as $tab_key => $tab_caption){
		echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-xml-sitemap#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
	}
	echo '</div>
	<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_xml_sitemap_general' ? ' active' : '').'" id="tab_siteseo_xml_sitemap_general">';
	siteseo_sitemap_general_tab();
	echo '</div>
	<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_xml_sitemap_post_types' ? ' active' : '').'" id="tab_siteseo_xml_sitemap_post_types">';
	siteseo_sitemap_post_types_tab();
	echo '</div>
	<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_xml_sitemap_taxonomies' ? ' active' : '').'" id="tab_siteseo_xml_sitemap_taxonomies">';
	siteseo_sitemap_taxonomies_tab();
	echo '</div>
	<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_html_sitemap' ? ' active' : '').'" id="tab_siteseo_html_sitemap">';
	siteseo_sitemap_html_tab();
	echo '</div>
	</div>';

	siteseo_submit_button(__('Save changes', 'siteseo'));
	echo '</form>';
}


function siteseo_save_sitemaps_settings(){
	
	check_admin_referer('siteseo_sitemap_nonce');

	if(!current_user_can('manage_options') || !is_admin()){
		return;
	}

	$sitemap_options = [];

	if(empty($_POST['siteseo_xml_sitemap_option_name'])){
		return;
	}

	// General tab
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_general_enable'])){
		$sitemap_options['xml_sitemap_general_enable'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_general_enable']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_general_enable'])) : '';
	}

	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_img_enable'])){
		$sitemap_options['xml_sitemap_img_enable'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_img_enable']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_img_enable'])) : '';
	}

	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_author_enable'])){
		$sitemap_options['xml_sitemap_author_enable'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_author_enable']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_author_enable'])) : '';
	}	
	
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_enable'])){
		$sitemap_options['xml_sitemap_html_enable'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_enable']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_enable'])) : '';
	}
	
	// Post tab
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_post_types_list']['post']['include'])){
		$sitemap_options['xml_sitemap_post_types_list']['post']['include'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_post_types_list']['post']['include']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_post_types_list']['post']['include'])) : '';
	}
	
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_post_types_list']['page']['include'])){
		$sitemap_options['xml_sitemap_post_types_list']['page']['include'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_post_types_list']['page']['include']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_post_types_list']['page']['include'])) : '';
	}	
	
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_post_types_list']['product']['include'])){
		$sitemap_options['xml_sitemap_post_types_list']['product']['include'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_post_types_list']['product']['include']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_post_types_list']['product']['include'])) : '';
	} 
	
	// Taxonomies
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_taxonomies_list']['category']['include'])){
		$sitemap_options['xml_sitemap_taxonomies_list']['category']['include'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_taxonomies_list']['category']['include']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_taxonomies_list']['category']['include'])) : '';
	}	

	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_taxonomies_list']['post_tag']['include'])){
		$sitemap_options['xml_sitemap_taxonomies_list']['post_tag']['include'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_taxonomies_list']['post_tag']['include']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_taxonomies_list']['post_tag']['include'])) : '';
	}

	// Html Sitemap Tab
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_mapping'])){
		$sitemap_options['xml_sitemap_html_mapping'] = sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_mapping']));
	}	
	
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_exclude'])){
		$sitemap_options['xml_sitemap_html_exclude'] = sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_exclude']));
	}
	
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_order'])){
		$sitemap_options['xml_sitemap_html_order'] = sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_order']));
	}	
	
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_orderby'])){
		$sitemap_options['xml_sitemap_html_orderby'] = sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_orderby']));
	}
	
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_taxonomies_list']['post_tag']['include'])){
		$sitemap_options['xml_sitemap_taxonomies_list']['post_tag']['include'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_taxonomies_list']['post_tag']['include']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_taxonomies_list']['post_tag']['include'])) : '';
	}
	
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_date'])){
		$sitemap_options['xml_sitemap_html_date'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_date']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_date'])) : '';
	}	
	
	if(isset($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_archive_links'])){
		$sitemap_options['xml_sitemap_html_archive_links'] = !empty($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_archive_links']) ? sanitize_text_field(wp_unslash($_POST['siteseo_xml_sitemap_option_name']['xml_sitemap_html_archive_links'])) : '';
	}
	
	update_option('siteseo_xml_sitemap_option_name', $sitemap_options);
}

siteseo_sitemaps_page_html();