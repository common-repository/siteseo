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

// Are we being accessed directly ?
if(!defined('ABSPATH')) {
	die('Hacking Attempt !');
}
//============ advanced tab
function siteseo_advanced_advanced_tab() {
	
	if(!empty($_POST['submit'])){
		siteseo_save_advanced_settings();
	}
	
    $options = get_option('siteseo_advanced_option_name');

    echo '<div class="siteseo-section-header">
			<h2>' . esc_html__('Advanced', 'siteseo') . '</h2>
	</div>
	<p>' . esc_html__('Advanced SEO options for advanced users.', 'siteseo') . '</p>
	<table class="form-table">
        <tr>
            <th scope="row">' . esc_html__('Add WP Editor to taxonomy description textarea', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_tax_desc_editor">
                    <input id="siteseo_advanced_advanced_tax_desc_editor" name="siteseo_advanced_option_name[advanced_tax_desc_editor]" type="checkbox" ' . checked(isset($options['advanced_tax_desc_editor']), '1', false) . ' value="1"/>
                Add TINYMCE editor to term description</label>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove /category/ in URL', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_category_url">
                    <input id="siteseo_advanced_advanced_category_url" name="siteseo_advanced_option_name[advanced_category_url]" type="checkbox" ' . checked(isset($options['advanced_category_url']), '1', false) . ' value="1"/>
                </label>';
                
    $category_base = '/' . (get_option('category_base') ?: 'category') . '/';
    printf(wp_kses_post(__('Remove <strong>%s</strong> in your permalinks', 'siteseo')), esc_html($category_base));
    
    echo '<p class="description">' . esc_html__('e.g. "https://example.com/category/my-post-category/" => "https://example.com/my-post-category/"', 'siteseo') . '</p>
                <div class="siteseo-notice">
                    <span class="dashicons dashicons-info"></span>
                    <p>' . esc_html__('You have to flush your permalinks each time you change this setting.', 'siteseo') . '</p>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove product category base from permalinks', 'siteseo') . '</th>
            <td>';
    if (is_plugin_active('woocommerce/woocommerce.php')) {
        echo '<label for="siteseo_advanced_advanced_product_cat_url">
                    <input id="siteseo_advanced_advanced_product_cat_url" name="siteseo_advanced_option_name[advanced_product_cat_url]" type="checkbox" ' . checked(isset($options['advanced_product_cat_url']), '1', false) . ' value="1"/>
                </label>';
                
        $category_base = '/' . (get_option('woocommerce_permalinks')['category_base'] ?: 'product-category') . '/';
        printf(wp_kses_post(__('Remove <strong>%s</strong> in your permalinks', 'siteseo')), esc_html($category_base));
        
        echo '<p class="description">' . esc_html__('e.g. "https://example.com/product-category/my-product-category/" => "https://example.com/my-product-category/"', 'siteseo') . '</p>
                <div class="siteseo-notice">
                    <span class="dashicons dashicons-info"></span>
                    <p>' . esc_html__('You have to flush your permalinks each time you change this setting.', 'siteseo') . '</p>
                    <p>' . esc_html__('Make sure you don\'t have identical URLs after activating this option to prevent conflicts.', 'siteseo') . '</p>
                </div>';
    } else {
        echo '<div class="siteseo-notice is-warning">
                    <span class="dashicons dashicons-warning"></span>
                    <p>' . wp_kses_post(__('You need to enable <strong>WooCommerce</strong> to apply these settings.', 'siteseo')) . '</p>
                </div>';
    }
    echo '</td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove ?replytocom link to avoid duplicate content', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_replytocom">
                    <input id="siteseo_advanced_advanced_replytocom" name="siteseo_advanced_option_name[advanced_replytocom]" type="checkbox" ' . checked(isset($options['advanced_replytocom']), '1', false) . ' value="1"/>
                Remove ?replytocom link in source code and replace it with a simple anchor</label>
                <p class="description">' . esc_html__('e.g. "https://www.example.com/my-blog-post/?replytocom=10#respond" => "#comment-10"', 'siteseo') . '</p>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove noreferrer link attribute in post content', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_noreferrer">
                    <input id="siteseo_advanced_advanced_noreferrer" name="siteseo_advanced_option_name[advanced_noreferrer]" type="checkbox" ' . checked(isset($options['advanced_noreferrer']), '1', false) . ' value="1"/>
                Remove noreferrer link attribute in source code</label>
                <p class="description">' . esc_html__('Useful for affiliate links (eg: Amazon).', 'siteseo') . '</p>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove WordPress meta generator tag', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_wp_generator">
                    <input id="siteseo_advanced_advanced_wp_generator" name="siteseo_advanced_option_name[advanced_wp_generator]" type="checkbox" ' . checked(isset($options['advanced_wp_generator']), '1', false) . ' value="1"/>
                Remove WordPress meta generator in source code </label>
                <pre>' . esc_attr__('<meta name="generator" content="WordPress 6.0.3" />', 'siteseo') . '</pre>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove hentry post class', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_hentry">
                    <input id="siteseo_advanced_advanced_hentry" name="siteseo_advanced_option_name[advanced_hentry]" type="checkbox" ' . checked(isset($options['advanced_hentry']), '1', false) . ' value="1"/>
Remove hentry post class to prevent Google from seeing this as structured data (schema)</label>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove author URL', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_comments_author_url">
                    <input id="siteseo_advanced_advanced_comments_author_url" name="siteseo_advanced_option_name[advanced_comments_author_url]" type="checkbox" ' . checked(isset($options['advanced_comments_author_url']), '1', false) . ' value="1"/> Remove comment author URL in comments if the website is filled from profile page
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove website field from comment form', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_comments_website">
                    <input id="siteseo_advanced_advanced_comments_website" name="siteseo_advanced_option_name[advanced_comments_website]" type="checkbox" ' . checked(isset($options['advanced_comments_website']), '1', false) . ' value="1"/>Remove website field from comment form to reduce spam
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Add "nofollow noopener noreferrer" rel attributes to the comments form link', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_comments_form_link">
                    <input id="siteseo_advanced_advanced_comments_form_link" name="siteseo_advanced_option_name[advanced_comments_form_link]" type="checkbox" ' . checked(isset($options['advanced_comments_form_link']), '1', false) . ' value="1"/>Prevent search engines to follow / index the link to the comments form
                </label>
                <pre>https://www.example.com/my-blog-post/#respond</pre>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove WordPress shortlink meta tag', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_wp_shortlink">
                    <input id="siteseo_advanced_advanced_wp_shortlink" name="siteseo_advanced_option_name[advanced_wp_shortlink]" type="checkbox" ' . checked(isset($options['advanced_wp_shortlink']), '1', false) . ' value="1"/>Remove WordPress shortlink meta tag in source
                </label>
                <pre>' . esc_attr__('<link rel="shortlink" href="https://www.example.com/"/>', 'siteseo') . '</pre>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove Windows Live Writer meta tag', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_wp_wlw">
                    <input id="siteseo_advanced_advanced_wp_wlw" name="siteseo_advanced_option_name[advanced_wp_wlw]" type="checkbox" ' . checked(isset($options['advanced_wp_wlw']), '1', false) . ' value="1"/>
                 Remove Windows Live Writer meta tag in source code</label>
                <pre>' . esc_attr__('<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="https://www.example.com/wp-includes/wlwmanifest.xml" />', 'siteseo') . '</pre>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Remove RSD meta tag', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_wp_rsd">
                    <input id="siteseo_advanced_advanced_wp_rsd" name="siteseo_advanced_option_name[advanced_wp_rsd]" type="checkbox" ' . checked(isset($options['advanced_wp_rsd']), '1', false) . ' value="1"/>
                Remove Really Simple Discovery meta tag in source code</label>
                <p class="description">' . esc_html__('WordPress Site Health feature will return a HTTPS warning if you enable this option. This is a false positive of course.', 'siteseo') . '</p>
                <pre>' . esc_attr__('<link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://www.example.com/xmlrpc.php?rsd" />', 'siteseo') . '</pre>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Google site verification', 'siteseo') . '</th>
            <td>
                <input type="text" name="siteseo_advanced_option_name[advanced_google]" placeholder="' . esc_html__('Enter Google meta value site verification', 'siteseo') . '" aria-label="' . esc_html__('Google site verification', 'siteseo') . '" value="' . esc_html(isset($options['advanced_google']) ? $options['advanced_google'] : '') . '"/>
                <p class="description">' . wp_kses_post(__('If your site is already verified in <strong>Google Search Console</strong>, you can leave this field empty.', 'siteseo')) . '</p>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Bing site verification', 'siteseo') . '</th>
            <td>
                <input type="text" name="siteseo_advanced_option_name[advanced_bing]" placeholder="' . esc_html__('Enter Bing meta value site verification', 'siteseo') . '" aria-label="' . esc_html__('Bing site verification', 'siteseo') . '" value="' . esc_html(isset($options['advanced_bing']) ? $options['advanced_bing'] : '') . '"/>
                <p class="description">' . wp_kses_post(__('If your site is already verified in <strong>Bing Webmaster tools</strong>, you can leave this field empty.', 'siteseo')) . '</p>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Pinterest site verification', 'siteseo') . '</th>
            <td>
                <input type="text" name="siteseo_advanced_option_name[advanced_pinterest]" placeholder="' . esc_html__('Enter Pinterest meta value site verification', 'siteseo') . '" aria-label="' . esc_html__('Pinterest site verification', 'siteseo') . '" value="' . esc_html(isset($options['advanced_pinterest']) ? $options['advanced_pinterest'] : '') . '"/>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Yandex site verification', 'siteseo') . '</th>
            <td>
                <input type="text" name="siteseo_advanced_option_name[advanced_yandex]" placeholder="' . esc_html__('Enter Yandex meta value site verification', 'siteseo') . '" aria-label="' . esc_html__('Yandex site verification', 'siteseo') . '" value="' . esc_html(isset($options['advanced_yandex']) ? $options['advanced_yandex'] : '') . '"/>
            </td>
        </tr>
    </table>';
}
//============imageseo tab
function siteseo_advanced_imageseo_tab() {
	
	if(!empty($_POST['submit'])){
		siteseo_save_advanced_settings();
	}
	
    $options = get_option('siteseo_advanced_option_name');

    echo '<div class="siteseo-section-header">
        <h2>' . esc_html__('Image SEO', 'siteseo') . '</h2>
    </div>
    <p>' . esc_html__('Images can generate a lot of traffic to your site. Make sure to always add alternative texts, optimize their file size, filename etc.', 'siteseo') . '</p>

    <table class="form-table">
        <tr valign="top">
            <th scope="row">' . esc_html__('Redirect attachment pages to post parent', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_attachments">
                    <input id="siteseo_advanced_advanced_attachments" name="siteseo_advanced_option_name[advanced_attachments]" type="checkbox" ' . checked(isset($options['advanced_attachments']), '1', false) . ' value="1"/>
					Redirect attachment pages to post parent (or homepage if none)
                </label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">' . esc_html__('Redirect attachment pages to their file URL', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_attachments_file">
                    <input id="siteseo_advanced_advanced_attachments_file" name="siteseo_advanced_option_name[advanced_attachments_file]" type="checkbox" ' . checked(isset($options['advanced_attachments_file']), '1', false) . ' value="1"/>Redirect attachment pages to their file URL (https://www.example.com/my-image-file.jpg)
                </label>
                <p class="description">' . esc_html__('If this option is checked, it will take precedence over the redirection of attachments to the post\'s parent.', 'siteseo') . '</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">' . esc_html__('Cleaning media filename', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_clean_filename">
                    <input id="siteseo_advanced_advanced_clean_filename" name="siteseo_advanced_option_name[advanced_clean_filename]" type="checkbox" ' . checked(isset($options['advanced_clean_filename']), '1', false) . ' value="1"/>
					When upload a media, remove accents, spaces, capital letters... and force UTF-8 encoding
                </label>
                <p class="description">' . esc_html__('e.g. "ExãMple 1 cópy!.jpg" => "example-1-copy.jpg"', 'siteseo') . '</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">' . esc_html__('Automatically set the image Title', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_image_auto_title_editor">
                    <input id="siteseo_advanced_advanced_image_auto_title_editor" name="siteseo_advanced_option_name[advanced_image_auto_title_editor]" type="checkbox" ' . checked(isset($options['advanced_image_auto_title_editor']), '1', false) . ' value="1"/>
					When uploading an image file, automatically set the title based on the filename
                </label>
                <p class="description">' . esc_html__('We use the product title for WooCommerce products.', 'siteseo') . '</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">' . esc_html__('Automatically set the image Alt text', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_image_auto_alt_editor">
                    <input id="siteseo_advanced_advanced_image_auto_alt_editor" name="siteseo_advanced_option_name[advanced_image_auto_alt_editor]" type="checkbox" ' . checked(isset($options['advanced_image_auto_alt_editor']), '1', false) . ' value="1"/>
               When uploading an image file, automatically set the alternative text based on the filename</label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">' . esc_html__('Automatically set the image Alt text from target keywords', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_image_auto_alt_target_kw">
                    <input id="siteseo_advanced_advanced_image_auto_alt_target_kw" name="siteseo_advanced_option_name[advanced_image_auto_alt_target_kw]" type="checkbox" ' . checked(isset($options['advanced_image_auto_alt_target_kw']), '1', false) . ' value="1"/>
               Use the target keywords if not alternative text set for the image </label>
                <p class="description">' . esc_html__('This setting will be applied to images without any alt text only on frontend. This setting is retroactive. If you turn it off, alt texts that were previously empty will be empty again.', 'siteseo') . '</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">' . esc_html__('Automatically set the image Caption', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_image_auto_caption_editor">
                    <input id="siteseo_advanced_advanced_image_auto_caption_editor" name="siteseo_advanced_option_name[advanced_image_auto_caption_editor]" type="checkbox" ' . checked(isset($options['advanced_image_auto_caption_editor']), '1', false) . ' value="1"/>
                When uploading an image file, automatically set the caption based on the filename</label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">' . esc_html__('Automatically set the image Description', 'siteseo') . '</th>
            <td>
                <label for="siteseo_advanced_advanced_image_auto_desc_editor">
                    <input id="siteseo_advanced_advanced_image_auto_desc_editor" name="siteseo_advanced_option_name[advanced_image_auto_desc_editor]" type="checkbox" ' . checked(isset($options['advanced_image_auto_desc_editor']), '1', false) . ' value="1"/>
                When uploading an image file, automatically set the description based on the filename</label>
            </td>
        </tr>
    </table>';
}
//============ breadcrumbs tab
function siteseo_advanced_breadcrumbs_tab() {
	
	if(!empty($_POST['submit'])){
		siteseo_save_advanced_settings();
	}
	
    $options = get_option('siteseo_advanced_option_name', []);
    $enabled = !empty($options) && isset($options['breadcrumbs_enable']);
    $separators = ['-', '|', '/', '←', '→', '↠', '⇒', '►', '—', '•', '»', '›', '–'];
    $separator = !empty($options['breadcrumbs_seperator']) ? $options['breadcrumbs_seperator'] : '';
    $custom_separator = !empty($options['breadcrumbs_custom_seperator']) ? $options['breadcrumbs_custom_seperator'] : '';
    $hide_home = isset($options['breadcrumbs_home']) ? $options['breadcrumbs_home'] : false;
    $home_label = !empty($options['breadcrumb_home_label']) ? $options['breadcrumb_home_label'] : __('Home', 'siteseo');
    $prefix = !empty($options['breadcrumb_prefix']) ? $options['breadcrumb_prefix'] : '';

    echo '<div class="siteseo-section-header">';
    echo '<h2>' . esc_html__('Breadcrumbs', 'siteseo') . '</h2>';
    echo '</div>';
    echo '<p>' . esc_html__('Breadcrumbs work as a navigation tool for users, helping them know their current location and providing quick links to their previous browsing path, which improves the user experience.', 'siteseo') . '</p>';

    echo '<table class="form-table">
        <tr>
            <th scope="row">' . esc_html__('Enable Breadcrumbs', 'siteseo') . '</th>
            <td>
                <label>
                    <input type="checkbox" value="1" id="siteseo_breadcrumbs_enable" name="siteseo_advanced_option_name[breadcrumbs_enable]" ' . checked($enabled, true, false) . '/>
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Breadcrumbs Display Methods', 'siteseo') . '</th>
            <td>
                <div class="siteseo-inner-tabs-wrap">
                    <input type="radio" id="siteseo-breadcrumbs-gutenberg" name="siteseo-inner-tabs" checked>
                    <input type="radio" id="siteseo-breadcrumbs-shortcode" name="siteseo-inner-tabs">
                    <input type="radio" id="siteseo-breadcrumbs-php" name="siteseo-inner-tabs">
                    
                    <ul class="siteseo-inner-tabs">
                        <li class="siteseo-inner-tab"><label for="siteseo-breadcrumbs-gutenberg"><span class="dashicons dashicons-block-default"></span>' . esc_html__('Gutenberg Blocks', 'siteseo') . '</label></li>
                        <li class="siteseo-inner-tab"><label for="siteseo-breadcrumbs-shortcode"><span class="dashicons dashicons-shortcode"></span>' . esc_html__('Shortcode', 'siteseo') . '</label></li>
                        <li class="siteseo-inner-tab"><label for="siteseo-breadcrumbs-php"><span class="dashicons dashicons-editor-code"></span>' . esc_html__('PHP Code', 'siteseo') . '</label></li>
                    </ul>
                    
                    <div class="siteseo-inner-tab-content">
                        <h4>' . esc_html__('Gutenberg Block', 'siteseo') . '</h4>
                        <p>' . esc_html__('Generate Block can be accessed by going to edit post using the Gutenberg Editor, the default editor of WordPress. There search for Breadcrumbs block.', 'siteseo') . '</p>
                    </div>
                    
                    <div class="siteseo-inner-tab-content">
                        <h4>' . esc_html__('Shortcode', 'siteseo') . '</h4>
                        <p>' . esc_html__('WordPress shortcodes are shortcuts ([shortcode]) that insert features without coding. You can use these shortcodes with Classic Editor, Gutenberg, or any other editor. Copy the shortcode below and use it in the editor.', 'siteseo') . '</p>
                        <pre>[siteseo_breadcrumbs]</pre>
                    </div>
                    
                    <div class="siteseo-inner-tab-content">
                        <h4>' . esc_html__('PHP Code', 'siteseo') . '</h4>
                        <p>' . esc_html__('You can add the breadcrumbs by directly adding PHP code. Make sure you are aware of what you are doing. Use the code below anywhere in your theme.', 'siteseo') . '</p>
                        <pre>' . esc_html("<?php if(function_exists('siteseo_render_breadcrumbs')){ echo siteseo_render_breadcrumbs(); } ?>") . '</pre>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Separator', 'siteseo') . '</th>
            <td>
                <div class="siteseo_breadcrumbs_seperator_callback">
                   <div class="siteseo-seperator-btns">';
    foreach ($separators as $sep) {
        $checked = ($separator == $sep) ? 'checked' : '';
        echo '<label>
            <input type="radio" name="siteseo_advanced_option_name[breadcrumbs_seperator]" value="' . esc_attr($sep) . '" ' . esc_attr($checked) . '/>
            ' . esc_html($sep) . '</label>';
    }
    echo '</div>
                    <input type="text" style="width:200px" name="siteseo_advanced_option_name[breadcrumbs_custom_seperator]" placeholder="' . esc_html__('Custom Separator', 'siteseo') . '" value="' . esc_attr($custom_separator) . '"/>
                </div>
				</div>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Home Settings', 'siteseo') . '</th>
            <td>
                <div>
                    <label style="margin:10px 0;">
                        <input type="checkbox" name="siteseo_advanced_option_name[breadcrumbs_home]" ' . checked($hide_home, true, false) . '/>
                        ' . esc_html__('Hide Home', 'siteseo') . '
                    </label>
                    <label>
                        <input type="text" name="siteseo_advanced_option_name[breadcrumb_home_label]" placeholder="' . esc_attr__('Homepage label', 'siteseo') . '" value="' . esc_attr($home_label) . '"/>
                        <p class="description">' . esc_html__('Home label', 'siteseo') . '</p>
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Prefix', 'siteseo') . '</th>
            <td>
                <div>
                    <label>
                        <input type="text" id="siteseo_breadcrumbs_prefix" name="siteseo_advanced_option_name[breadcrumb_prefix]" placeholder="' . esc_attr__('Breadcrumb Prefix', 'siteseo') . '" value="' . esc_attr($prefix) . '"/>
                    </label>
                </div>
            </td>
        </tr>
    </table>';
}
//============appearance tab
function siteseo_advanced_appearance_tab() {
	
	if(!empty($_POST['submit'])){
		siteseo_save_advanced_settings();
	}	

	$options = get_option('siteseo_advanced_option_name');

	if (!is_array($options)) {
		$options = array();
	}

	echo '
	<div class="siteseo-sub-tabs">
		<a class="siteseo-active-sub-tabs" href="#siteseo-advanced-metaboxes">' . esc_html__('Metaboxes', 'siteseo') . '</a>
		<a href="#siteseo-advanced-adminbar">' . esc_html__('Admin bar', 'siteseo') . '</a>
		<a href="#siteseo-advanced-seo-dashboard">' . esc_html__('SEO Dashboard', 'siteseo') . '</a>
		<a href="#siteseo-advanced-columns">' . esc_html__('Columns', 'siteseo') . '</a>
		<a href="#siteseo-advanced-misc">' . esc_html__('Misc', 'siteseo') . '</a>
	</div>
	<div class="siteseo-section-body">
		<div class="siteseo-section-header">
			<h2>' . esc_html__('Appearance', 'siteseo') . '</h2>
		</div>
		<p>' . esc_html__('Customize the plugin to fit your needs.', 'siteseo') . '</p>
		
		<table class="form-table">
			<tr>
				<td colspan="2"><hr></td>
			</tr>
			<tr>
				<td colspan="2"><h3 id="siteseo-advanced-metaboxes">' . esc_html__('Metaboxes', 'siteseo') . '</h3></td>
			</tr>
			<tr>
				<td colspan="2"><p>' . esc_html__('Edit your SEO metadata directly from your favorite page builder.', 'siteseo') . '</p></td>
			</tr>
			<tr>
				<td colspan="2">';
					if ((function_exists('siteseo_get_toggle_white_label_option') && '1' !== siteseo_get_toggle_white_label_option())) {
						echo wp_oembed_get('https://www.youtube.com/@SiteSEOPlugin'); //phpcs:ignore
					}
				echo '</td>
			</tr>
			<tr>
				<th scope="row">Universal Metabox (Gutenberg)</th>
				<td>
					<label for="siteseo_advanced_appearance_universal_metabox">
						<input id="siteseo_advanced_appearance_universal_metabox" name="siteseo_advanced_option_name[appearance_universal_metabox]" type="checkbox" ' . checked(isset($options['appearance_universal_metabox']) && $options['appearance_universal_metabox'] === '1', true, false) . ' value="1"/>
						' . esc_html__('Enable the universal SEO metabox for the Block Editor (Gutenberg)', 'siteseo') . '
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row">Disable Universal Metabox</th>
				<td>
					<label for="siteseo_advanced_appearance_universal_metabox_disable">
						<input id="siteseo_advanced_appearance_universal_metabox_disable" name="siteseo_advanced_option_name[appearance_universal_metabox_disable]" type="checkbox" ' . checked(isset($options['appearance_universal_metabox_disable']) && $options['appearance_universal_metabox_disable'] === '1', true, false) . ' value="1"/>
						' . esc_html__('Disable the universal SEO metabox', 'siteseo') . '
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row">Move SEO metabox\'s position</th>
				<td colspan="2">
					<select id="siteseo_advanced_appearance_metaboxe_position" name="siteseo_advanced_option_name[appearance_metaboxe_position]">
						<option ' . selected(isset($options['appearance_metaboxe_position']) ? $options['appearance_metaboxe_position'] : '', 'high', false) . ' value="high">' . esc_html__('High priority (top)', 'siteseo') . '</option>
						<option ' . selected(isset($options['appearance_metaboxe_position']) ? $options['appearance_metaboxe_position'] : '', 'default', false) . ' value="default">' . esc_html__('Normal priority (default)', 'siteseo') . '</option>
						<option ' . selected(isset($options['appearance_metaboxe_position']) ? $options['appearance_metaboxe_position'] : '', 'low', false) . ' value="low">' . esc_html__('Low priority', 'siteseo') . '</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">Remove Content Analysis Metabox</th>
				<td>
					<label for="siteseo_advanced_appearance_ca_metaboxe">
						<input id="siteseo_advanced_appearance_ca_metaboxe" name="siteseo_advanced_option_name[appearance_ca_metaboxe]" type="checkbox" ' . checked(isset($options['appearance_ca_metaboxe']), true, false) . ' value="1"/>
						' . esc_html__('Remove Content Analysis Metabox', 'siteseo') . '
					</label>
					<p class="description">' . esc_html__('By checking this option, we will no longer track the significant keywords.', 'siteseo') . '</p>
				</td>
			</tr>
			<tr>
				<th scope="row">Hide Genesis SEO Metabox</th>
				<td>
					<label for="siteseo_advanced_appearance_genesis_seo_metaboxe">
						<input id="siteseo_advanced_appearance_genesis_seo_metaboxe" name="siteseo_advanced_option_name[appearance_genesis_seo_metaboxe]" type="checkbox" ' . checked(isset($options['appearance_genesis_seo_metaboxe']), true, false) . ' value="1"/>
						' . esc_html__('Remove Genesis SEO Metabox', 'siteseo') . '
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row">Hide advice in Structured Data Types metabox</th>
				<td>
					<label for="siteseo_advanced_appearance_advice_schema">
						<input id="siteseo_advanced_appearance_advice_schema" name="siteseo_advanced_option_name[appearance_advice_schema]" type="checkbox" ' . checked(isset($options['appearance_advice_schema']), true, false) . ' value="1"/>
						' . esc_html__('Remove the advice if None schema selected', 'siteseo') . '
					</label>
				</td>
			</tr>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
			<tr>
				<td colspan="2"><h3 id="siteseo-advanced-adminbar">' . esc_html__('Admin bar', 'siteseo') . '</h3></td>
			</tr>
			<tr>
				<td colspan="2"><p>' . esc_html__('The admin bar appears on the top of your pages when logged in to your WP admin.', 'siteseo') . '</p></td>
			</tr>
			<tr>
				<th>' . esc_html__('SEO in admin bar', 'siteseo') . '</th>
				<td>
					<label for="siteseo_advanced_appearance_adminbar">
						<input id="siteseo_advanced_appearance_adminbar" name="siteseo_advanced_option_name[appearance_adminbar]" type="checkbox" ' . checked(isset($options['appearance_adminbar']), true, false) . ' value="1"/>
						' . esc_html__('Remove SEO from Admin Bar in backend and frontend', 'siteseo') . '
					</label>
				</td>
			</tr>
			<tr>
				<th>' . esc_html__('Noindex in admin bar', 'siteseo') . '</th>
				<td>
					<label for="siteseo_advanced_appearance_adminbar_noindex">
						<input id="siteseo_advanced_appearance_adminbar_noindex" name="siteseo_advanced_option_name[appearance_adminbar_noindex]" type="checkbox" ' . checked(isset($options['appearance_adminbar_noindex']), true, false) . ' value="1"/>
						' . esc_html__('Remove noindex item from Admin Bar in backend and frontend', 'siteseo') . '
					</label>
				</td>
			</tr>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
			<tr>
				<td colspan="2"><h3 id="siteseo-advanced-seo-dashboard">' . esc_html__('SEO Dashboard', 'siteseo') . '</h3></td>
			</tr>
			<tr>
				<td colspan="2"><p>' . esc_html__('Customize the SEO dashboard UI.', 'siteseo') . '</p></td>
			</tr>
			<tr>
				<th scope="row">' . esc_html__('Hide Notifications Center', 'siteseo') . '</th>
				<td>
					<label for="siteseo_advanced_appearance_notifications">
						<input id="siteseo_advanced_appearance_notifications" name="siteseo_advanced_option_name[appearance_notifications]" type="checkbox" ' . checked(isset($options['appearance_notifications']), true, false) . ' value="1"/>
						' . esc_html__('Hide Notifications Center in SEO Dashboard page', 'siteseo') . '
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row">' . esc_html__('Hide SEO News', 'siteseo') . '</th>
				<td>
					<label for="siteseo_advanced_appearance_news">
						<input id="siteseo_advanced_appearance_news" name="siteseo_advanced_option_name[appearance_news]" type="checkbox" ' . checked(isset($options['appearance_news']), true, false) . ' value="1"/>
						' . esc_html__('Hide SEO News in SEO Dashboard page', 'siteseo') . '
					</label>
				</td>
			</tr>
			<tr>
			
			<tr>
				<th scope="row">' . esc_html__('Hide Site Overview', 'siteseo') . '</th>
				<td>
					<label for="siteseo_advanced_appearance_seo_tools">
						<input id="siteseo_advanced_appearance_seo_tools" name="siteseo_advanced_option_name[appearance_seo_tools]" type="checkbox" ' . checked(isset($options['appearance_seo_tools']), true, false) . ' value="1"/>
						' . esc_html__('Hide Site Overview in SEO Dashboard page', 'siteseo') . '
					</label>
				</td>
			</tr>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
			<tr>
				<td colspan="2"><h3 id="siteseo-advanced-columns">' . esc_html__('Columns', 'siteseo') . '</h3></td>
			</tr>
			<tr>
				<td colspan="2"><p>' . esc_html__('Customize the SEO columns displayed in the posts/pages list.', 'siteseo') . '</p></td>
			</tr>
			<tr>
				<th scope="row">' . esc_html__('Show Title tag column in post types', 'siteseo') . '</th>
				<td>
					<label for="siteseo_advanced_appearance_column_seo_score">
						<input id="siteseo_advanced_appearance_column_seo_score" name="siteseo_advanced_option_name[appearance_title_col]" type="checkbox" ' . checked(isset($options['appearance_title_col']), true, false) . ' value="1"/>
						' . esc_html__('Add title column', 'siteseo') . '
					</label>
				</td>
			</tr>
			<tr>
				<th scope="row">' . esc_html__('Show Meta description column in post types', 'siteseo') . '</th>
				<td>
					<label for="siteseo_advanced_appearance_column_seo_score">
						<input id="siteseo_advanced_appearance_column_seo_score" name="siteseo_advanced_option_name[appearance_meta_desc_col]" type="checkbox" ' . checked(isset($options['appearance_meta_desc_col']), true, false) . ' value="1"/>
						' . esc_html__('Add meta description column', 'siteseo') . '
					</label>
				</td>
			</tr>
				
			<tr>
				<th scope="row">Show Redirection Enable column in post types</th>
				<td>
					<label for="siteseo_advanced_appearance_column_seo_score">
						<input id="siteseo_advanced_appearance_column_seo_score" name="siteseo_advanced_option_name[appearance_redirect_enable_col]" type="checkbox" '. checked(isset($options['appearance_redirect_enable_col']), true, false) .'value="1"/>
						'. esc_html__('Add redirection enable column', 'siteseo').'
					</label>
				</td>
			</tr>

			<tr>
				<th scope="row">Show Redirect URL column in post types</th>
				<td>
					<label for="siteseo_advanced_appearance_column_seo_score">
						<input id="siteseo_advanced_appearance_column_seo_score" name="siteseo_advanced_option_name[appearance_redirect_url_col]" type="checkbox" '. checked(isset($options['appearance_redirect_url_col']), true, false).' value="1"/>
						'. esc_html__('Add redirection URL column', 'siteseo').'
					</label>
				</td>
			</tr>

			<tr><th scope="row">Show canonical URL column in post types</th>
				<td>
					<label for="siteseo_advanced_appearance_column_seo_score">
						<input id="siteseo_advanced_appearance_column_seo_score" name="siteseo_advanced_option_name[appearance_canonical]" type="checkbox" '. checked(isset($options['appearance_canonical']), true, false).' value="1"/>
						'. esc_html__('Add canonical URL column', 'siteseo').'
					</label>
				</td>
			</tr>
				
			<tr>
				<th scope="row">Show Target Keyword column in post types</th>
				<td>
					<label for="siteseo_advanced_appearance_column_seo_score">
						<input id="siteseo_advanced_appearance_column_seo_score" name="siteseo_advanced_option_name[appearance_target_kw_col]" type="checkbox" '. checked(isset($options['appearance_target_kw_col']), true, false).' value="1"/>
						'. esc_html__('Add target keyword column', 'siteseo') .'
					</label>
				</td>
			</tr>
			
			
			<tr>
				<th scope="row">Show noindex column in post types</th>
				<td>
					<label for="siteseo_advanced_appearance_column_seo_score">
						<input id="siteseo_advanced_appearance_column_seo_score" name="siteseo_advanced_option_name[appearance_noindex_col]" type="checkbox" '. checked(isset($options['appearance_noindex_col']), true, false).' value="1"/>
						'. esc_html__('Display noindex status', 'siteseo').'
					</label>
				</td>
			</tr>
			
			<tr>
				<th scope="row">Show nofollow column in post types</th>
				<td>
					<label for="siteseo_advanced_appearance_column_seo_score">
						<input id="siteseo_advanced_appearance_column_seo_score" name="siteseo_advanced_option_name[appearance_nofollow_col]" type="checkbox" '. checked(isset($options['appearance_nofollow_col']), true, false).' value="1"/>
						'. esc_html__('Display nofollow status', 'siteseo').'
					</label>
				</td>
			</tr>
			
			<tr>
				<th scope="row">Show total number of words column in post types</th>
				<td>
					<label for="siteseo_advanced_appearance_column_seo_score">
						<input id="siteseo_advanced_appearance_column_seo_score" name="siteseo_advanced_option_name[appearance_words_col]" type="checkbox" '. checked(isset($options['appearance_words_col']), true, false).' value="1"/>
						'. esc_html__('Display total number of words in content', 'siteseo').'
					</label>
				</td>
			</tr>
			
			
			<tr>
			<th scope="row">Show content analysis score column in post types</th>
				<td>
					<label for="siteseo_advanced_appearance_column_seo_score">
						<input id="siteseo_advanced_appearance_column_seo_score" name="siteseo_advanced_option_name[appearance_score_col]" type="checkbox" '. checked(isset($options['appearance_score_col']), true, false).' value="1"/>
						'. esc_html__('Display Content Analysis results column ("Good" or "Should be improved")', 'siteseo').'
					</label>
				</td>
			</th>
			</tr>
			
			<tr>
				<td colspan="2"><hr></td>
			</tr>
			<tr>
				<td colspan="2"><h3 id="siteseo-advanced-misc">' . esc_html__('Misc', 'siteseo') . '</h3></td>
			</tr>
			<tr>
				<td colspan="2"><p>' . esc_html__('Miscellaneous settings for the SEO plugin.', 'siteseo') . '</p></td>
			</tr>
			<tr>
				<th scope="row">' . esc_html__('Hide Genesis SEO Settings link', 'siteseo') . '</th>
				<td>
					<label for="siteseo_advanced_appearance_genesis_seo_menu">
						<input id="siteseo_advanced_appearance_genesis_seo_menu" name="siteseo_advanced_option_name[appearance_genesis_seo_menu]" type="checkbox" ' . checked(isset($options['appearance_genesis_seo_menu']), true, false) . ' value="1"/>
						' . esc_html__('Remove Genesis SEO link in WP Admin Menu', 'siteseo') . '
					</label>
				</td>
			</tr>
		</table>
	</div>';
}
//============  security tab
function siteseo_advanced_security_tab() {
	
	if(!empty($_POST['submit'])){
		siteseo_save_advanced_settings();
	}
	
    $options = get_option('siteseo_advanced_option_name');
    $docs    = siteseo_get_docs_links();
    global $wp_roles;

    if(!isset($wp_roles)) {
        $wp_roles = new WP_Roles();
    }

    echo '<div class="siteseo-sub-tabs">
        <a class="siteseo-active-sub-tabs" href="#siteseo-security-metaboxes">' . esc_html__('SiteSEO metaboxes', 'siteseo') . '</a>
        <a href="#siteseo-security-settings">' . esc_html__('SiteSEO settings pages', 'siteseo') . '</a>
    </div>

    <div class="siteseo-section-body">
        <div class="siteseo-section-header">
            <h2>' . esc_html__('Security', 'siteseo') . '</h2>
            <p>' . esc_html__('Control access to SEO settings and metaboxes by user roles.', 'siteseo') . '</p>
        </div>

        <div id="siteseo-security-metaboxes" class="siteseo-content siteseo-active-tab-content">
            <hr>
            <h3>' . esc_html__('SiteSEO metaboxes', 'siteseo') . '</h3>
            <p>' . esc_html__('Check a user role to prevent it from editing a specific metabox.', 'siteseo') . '</p>

            <table class="form-table">
			<th scope="row">Block SEO metabox to user roles</th>';

    foreach($wp_roles->get_names() as $key => $value){
		$check = isset($options['security_metaboxe_role'][$key]);
        echo '<tr><th scope="row"></th>
            <td>
                <label for="siteseo_advanced_security_metaboxe_role_' . esc_attr($key) . '">
                    <input id="siteseo_advanced_security_metaboxe_role_' . esc_attr($key) . '" name="siteseo_advanced_option_name[security_metaboxe_role][' . esc_attr($key) . ']" type="checkbox" ' . (isset($options['security_metaboxe_role'][$key]) ? checked(isset($options['security_metaboxe_role'][$key]), '1', false) : '') . ' value="1"/>
                    <strong>' . esc_html($value) . '</strong> (<em>' . esc_html(translate_user_role($value, 'default')) . '</em>)
                </label>
	    </td>
	    </tr>';
    }

	echo wp_kses_post(siteseo_tooltip_link($docs['security']['metaboxe_seo'], esc_html__('Hook to filter structured data types metabox call by post type - new window', 'siteseo')));
    echo '</td></th></tr>';
	echo '<th scope="row">Block Content analysis metabox to user roles</th>';
	
	
	foreach($wp_roles->get_names() as $key => $value){
       $check = isset($options['security_metaboxe_ca_role'][$key]);
	   echo '<tr><th scope="row"></th>
		<td>
			<label for="siteseo_advanced_security_metaboxe_ca_role_' . esc_attr($key) . '">
				<input id="siteseo_advanced_security_metaboxe_ca_role_' . esc_attr($key) . '" name="siteseo_advanced_option_name[security_metaboxe_ca_role][' . esc_attr($key) . ']" type="checkbox" ' . checked(isset($options['security_metaboxe_ca_role'][$key]), '1', false) . ' value="1"/>
				<strong>' . esc_html($value) . '</strong> (<em>' . esc_html(translate_user_role($value, 'default')) . '</em>)
			</label>
			</td>
		</tr>';
    }

    echo '</table>
	</div>
	<hr><h3 id="siteseo-security-settings">' . esc_html__('SiteSEO settings pages', 'siteseo') . '</h3>
	<p>' . esc_html__('Check a user role to allow it to edit a specific settings page.', 'siteseo') . '</p>
	<table class="form-table">';

	$options = siteseo_get_service('AdvancedOption')->getOption();

	global $submenu;
	
	if(isset($submenu['siteseo'])){
		$menus = $submenu['siteseo'];

		foreach($menus as $key => $item){

			if(in_array($item[2], [
				'siteseo', // dashboard
				'siteseo-license',
				'edit.php?post_type=siteseo_schemas',
				'edit.php?post_type=siteseo_404',
				'edit.php?post_type=siteseo_bot', ])) {
				continue;
			}
			
			$keySettings = $item[2];
			
			echo '<tr><th scope="row">'.esc_html($item[0]).'</th><td>';

			if(!isset($wp_roles)){
				$wp_roles = new WP_Roles();
			}

			foreach($wp_roles->get_names() as $key => $value){
				if ('administrator' === $key) {
					continue;
				}

				$uniqueKey = sprintf('%s_%s', $keySettings, $key);
				$nameKey = \sprintf('%s_%s', 'siteseo_advanced_security_metaboxe', $keySettings);
				$dataOptions = isset($options[$nameKey]) ? $options[$nameKey] : [];
				
				echo '<tr><th scope="row">';
				if ('titles-metas_editor' === $uniqueKey){
					echo '<p class="description">'.esc_html__('Check a user role to authorized it to edit a specific SEO page.', 'siteseo').'</p>';
				}
				echo '</th>';

				echo'<td><p>
					<label
						for="siteseo_advanced_security_metaboxe_role_pages_'.esc_attr($uniqueKey).'">
						<input type="checkbox"
							id="siteseo_advanced_security_metaboxe_role_pages_'.esc_attr($uniqueKey).'"
							value="1"
							name="siteseo_advanced_option_name['.esc_attr($nameKey).']['.esc_attr($key).']"
							'.(isset($dataOptions[$key]) ?
							checked($dataOptions[$key], '1', false) : '').'
						/>
						<strong>'.esc_html($value).'</strong> (<em>'.esc_html(translate_user_role($value,  'default')).'</em>)
					</label>
				</p></td></tr>';
			}
		}
	}

	echo wp_kses_post(siteseo_tooltip_link($docs['security']['metaboxe_ca'], esc_html__('Hook to filter structured data types metabox call by post type - new window', 'siteseo')));
    echo '</td>
        </tr></table>';

    echo '</div>';
}
//============ table of content tab
function siteseo_advanced_toc_tab() {
	
	if(!empty($_POST['submit'])){
		siteseo_save_advanced_settings();
	}
	
    $options = get_option('siteseo_advanced_option_name', []);
    $enabled = !empty($options) && isset($options['toc_enable']) ? true : false;
    $label = !empty($options) && isset($options['toc_label']) ? $options['toc_label'] : 'Table of Content';
    $headings = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
    $list_types = [
        'ol' => __('Ordered List', 'siteseo'),
        'ul' => __('Unordered List', 'siteseo')
    ];

    echo '<div class="siteseo-section-header">
        <h2>' . esc_html__('Table of Contents', 'siteseo') . '</h2>
    </div>
    <p>' . esc_html__('A table of content works as an index section for your post or page. It helps search engines understand your page structure and users find specific sections quickly, which might help SEO, as it helps search engines better understand the structure of your content and also improves user experience.', 'siteseo') . '</p>
    <p>' . esc_html__('To use Table of Content on your pages, you can use this shortcode', 'siteseo') . ' <code>[siteseo_toc]</code></p>

    <table class="form-table">
        <tr>
            <th scope="row">' . esc_html__('Enable TOC', 'siteseo') . '</th>
            <td>
                <label>
                    <input type="checkbox" value="1" id="siteseo_toc_enable" name="siteseo_advanced_option_name[toc_enable]" ' . checked($enabled, true, false) . '/>
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('TOC Label', 'siteseo') . '</th>
            <td>
                <label>
                    <input type="text" value="' . esc_attr($label) . '" name="siteseo_advanced_option_name[toc_label]" placeholder="' . esc_attr__('Table of content', 'siteseo') . '"/>
                </label>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('Exclude Headings', 'siteseo') . '</th>
            <td>
                <div style="display:flex; gap: 20px;">';
                foreach ($headings as $heading) {
                    $checked = !empty($options) && !empty($options['toc_excluded_headings']) && is_array($options['toc_excluded_headings']) && in_array($heading, $options['toc_excluded_headings']);
                    echo '<label>
                        <input type="checkbox" value="' . esc_attr($heading) . '" name="siteseo_advanced_option_name[toc_excluded_headings][]" ' . checked($checked, true, false) . '/>
                        ' . esc_html(strtoupper($heading)) . '
                    </label>';
                }
                echo '</div>
            </td>
        </tr>
        <tr>
            <th scope="row">' . esc_html__('List Type', 'siteseo') . '</th>
            <td>
                <div style="display:flex; gap: 20px;">
                    <label>
                        <select name="siteseo_advanced_option_name[toc_heading_type]">';
                            foreach ($list_types as $list_type => $list_title) {
                                $selected = !empty($options['toc_heading_type']) && $options['toc_heading_type'] == $list_type ? 'selected' : '';
                                echo '<option value="' . esc_attr($list_type) . '" ' . esc_attr($selected) . '>' . esc_html($list_title) . '</option>';
                            }
                        echo '</select>
                    </label>
                </div>
            </td>
        </tr>
    </table>';
}
//============robots.txt tab
function siteseo_advanced_robots_txt_tab() {

    echo '<p>' . esc_html__('Manage your robots.txt file here. Adjust settings according to your SEO requirements.', 'siteseo') . '</p>';
    echo '<table class="form-table">';

    // Preview or create button
    if (!file_exists(ABSPATH . 'robots.txt')) {
        echo '<tr><td colspan="2"><button class="btn btnSecondary" id="siteseo-create-robots">'.esc_html__('Create robots.txt', 'siteseo').'</button><span class="spinner"></span></td></tr>';
    } else {
        echo '<tr><th class="row">'.esc_html__('Preview', 'siteseo').'</th><td colspan="2"><a href="'.esc_url(get_home_url()).'/robots.txt" class="btn btnSecondary" target="_blank">'.esc_html__('View Robots.txt', 'siteseo').'</a></td></tr>';
	}

    // If robots.txt does not exist
    if (!file_exists(ABSPATH . 'robots.txt')) {
        echo '</table>';
        return;
    }

    $robots_txt = file_exists(ABSPATH . 'robots.txt') ? file_get_contents(ABSPATH . 'robots.txt') : '';

    echo '<tr><th class="row">'.esc_html__('robots.txt File', 'siteseo').'</th><td colspan="2"><textarea id="siteseo_robots_file_content" placeholder="'.esc_attr__('Enter your robots.txt rules here', 'siteseo').'" rows="15" cols="50">' . esc_textarea($robots_txt) . '</textarea></td></tr>';
	echo '<tr><th></th><td colspan="2"><button  class="btn btnSecondary" id="siteseo-update-robots">'.esc_html__('Update robots.txt', 'siteseo').'</button><span class="spinner"></span></td></tr>';	
    echo '</table>';
}


//============htaccess tab
function siteseo_advanced_htaccess_tab() {
	
	echo '<h2>' .esc_html__('.htaccess' ,'siteseo').'</h2>';
    echo '<p>' . esc_html__('Edit your .htaccess file to configure advanced settings for your site', 'siteseo') . '</p>';

	$home_path = get_home_path();
	$htaccess_file = $home_path . '.htaccess';

	if (!file_exists($htaccess_file) || !is_writable($htaccess_file)) {
		echo '<table class="siteseo-notice-table"><tr><td class="siteseo-notice is-error"><p>'.esc_html__('The .htaccess file does not exist or You do not have permission to edit the .htaccess file', 'siteseo').'</p></td></tr></table>';
		return;
	}

echo '<table class="siteseo-notice-table" style="width: 82%;padding-left:42%">
    <tr>
	<th class="row"></th>
        <td colspan="2" class="siteseo-notice is-error">
            '.esc_html__('Be careful editing this file. If any incorrect edits are made, your site could go down. You can restore the htaccess file by replacing it with the backup copy created by SiteSEO with name .htaccess_backup.siteseo', 'siteseo').'
            <label style="margin-top:10px; display: block;">
                <input type="checkbox" value="1" id="siteseo_htaccess_enable"/><strong>I understand the risk and I want to edit this file.</strong>
            </label>
        </td>
		
    </tr>
</table>';

$htaccess_code = file_get_contents($htaccess_file);

echo '<table class="form-table" style="width: 100%;">
    <tr>
	<th class="row">Edit your htaccess file</th>
        <td>
		
            <textarea id="siteseo_htaccess_file" name="siteseo_advanced_option_names[htaccess_code]" rows="22" style="width: 100%;">' . esc_textarea($htaccess_code) . '</textarea>
        </td>
    </tr>
    <tr>
	<th class="row">
        <td style="padding-top: 10px;">
            <div style="display: flex; align-items: center;">
                <button id="siteseo_htaccess_btn" class="btn btnSecondary">'.esc_html__('Update htaccess.txt', 'siteseo').'</button>
                <span class="spinner" style="margin-left: 10px;"></span>
            </div>
        </td>
		</th>
    </tr>
</table>';
}

$current_tab = '';
$plugin_settings_tabs = [
	'tab_siteseo_advanced_image' => esc_html__('Image SEO', 'siteseo'),
	'tab_siteseo_advanced_advanced' => esc_html__('Advanced', 'siteseo'),
	'tab_siteseo_advanced_appearance' => esc_html__('Appearance', 'siteseo'),
	'tab_siteseo_advanced_security' => esc_html__('Security', 'siteseo'),
	'tab_siteseo_advanced_breadcrumbs' => esc_html__('Breadcrumbs', 'siteseo'),
	'tab_siteseo_advanced_toc' => esc_html__('Table of Content', 'siteseo'),
	'tab_siteseo_advanced_robots_txt' => esc_html__('robots.txt','siteseo'),
	'tab_siteseo_advanced_htaccess' => esc_html__('.htaccess','siteseo')
];

if(function_exists('siteseo_admin_header')){
	siteseo_admin_header();
}

echo '<form method="post" class="siteseo-option">';
	wp_nonce_field('siteseo_advanced_nonce');
	
	echo '<div id="siteseo-tabs" class="wrap">';
		echo wp_kses(siteseo_feature_title('advanced'), ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]]);

		echo '<div class="nav-tab-wrapper">';
			foreach ($plugin_settings_tabs as $tab_key => $tab_caption) {
				echo '<a id="'.esc_attr($tab_key).'-tab" class="nav-tab"
				href="?page=siteseo-advanced#tab='.esc_attr($tab_key).'">'.esc_html($tab_caption).'</a>';
			}

		echo '</div>
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_advanced_image' ? ' active' : '').'" id="tab_siteseo_advanced_image">';
		siteseo_advanced_imageseo_tab();
		echo '</div>
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_advanced_advanced' ? ' active' : '').'" id="tab_siteseo_advanced_advanced">';
		siteseo_advanced_advanced_tab();
		echo '</div>
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_advanced_appearance' ? ' active' : '').'" id="tab_siteseo_advanced_appearance">';
		siteseo_advanced_appearance_tab();
		echo '</div>
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_advanced_security' ? ' active' : '').'" id="tab_siteseo_advanced_security">';
		siteseo_advanced_security_tab();
		echo '</div>
	
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_advanced_breadcrumbs' ? ' active' : '').'" id="tab_siteseo_advanced_breadcrumbs">';
		siteseo_advanced_breadcrumbs_tab();
		echo '</div>
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_advanced_toc' ? ' active' : '').'" id="tab_siteseo_advanced_toc">';
		siteseo_advanced_toc_tab();
		echo '</div>
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_advanced_robots_txt' ? ' active' : '').'" id="tab_siteseo_advanced_robots_txt">';
		siteseo_advanced_robots_txt_tab();
		echo '</div>
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_advanced_htaccess' ? ' active' : '').'" id="tab_siteseo_advanced_htaccess">';
		siteseo_advanced_htaccess_tab();
		echo '</div>';


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

		echo '</div>
</form>';


function siteseo_save_advanced_settings(){
	
	check_admin_referer('siteseo_advanced_nonce');
	
	if(!current_user_can('manage_options') || !is_admin()){
		return;
	}

	$advanced_options = [];
	
	//image-seo-tab
	if(empty($_POST['siteseo_advanced_option_name'])){
		return;
	}
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_attachments'])){
		$advanced_options['advanced_attachments'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_attachments'] ? true : 0));
	}
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_attachments_file'])){
		$advanced_options['advanced_attachments_file'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_attachments_file'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_clean_filename'])){
		$advanced_options['advanced_clean_filename'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_clean_filename'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_image_auto_title_editor'])){
		$advanced_options['advanced_image_auto_title_editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_image_auto_title_editor'] ? true : 0));
	}
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_image_auto_alt_editor'])){
		$advanced_options['advanced_image_auto_alt_editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_image_auto_alt_editor'] ? true : 0));
	}	

	if(isset($_POST['siteseo_advanced_option_name']['advanced_image_auto_alt_target_kw'])){
		$advanced_options['advanced_image_auto_alt_target_kw'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_image_auto_alt_target_kw'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_image_auto_caption_editor'])){
		$advanced_options['advanced_image_auto_caption_editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_image_auto_caption_editor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_image_auto_desc_editor'])){
		$advanced_options['advanced_image_auto_desc_editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_image_auto_desc_editor'] ? true : 0));
	}	
	
	//advanced
	if(isset($_POST['siteseo_advanced_option_name']['advanced_tax_desc_editor'])){
		$advanced_options['advanced_tax_desc_editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_tax_desc_editor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_category_url'])){
		$advanced_options['advanced_category_url'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_category_url'] ? true : 0));
	}
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_replytocom'])){
		$advanced_options['advanced_replytocom'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_replytocom'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_noreferrer'])){
		$advanced_options['advanced_noreferrer'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_noreferrer'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_wp_generator'])){
		$advanced_options['advanced_wp_generator'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_wp_generator'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_hentry'])){
		$advanced_options['advanced_hentry'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_hentry'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_comments_author_url'])){
		$advanced_options['advanced_comments_author_url'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_comments_author_url'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_comments_website'])){
		$advanced_options['advanced_comments_website'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_comments_website'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_comments_form_link'])){
		$advanced_options['advanced_comments_form_link'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_comments_form_link'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_wp_shortlink'])){
		$advanced_options['advanced_wp_shortlink'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_wp_shortlink'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_wp_rsd'])){
		$advanced_options['advanced_wp_rsd'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_wp_rsd'] ? true : 0));
	}
	if(isset($_POST['siteseo_advanced_option_name']['advanced_wp_wlw'])){
		$advanced_options['advanced_wp_wlw'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_wp_wlw'] ? true : 0));
	}
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_google'])){
		$advanced_options['advanced_google'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_google']));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_bing'])){
		$advanced_options['advanced_bing'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_bing']));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_pinterest'])){
		$advanced_options['advanced_pinterest'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_pinterest']));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['advanced_yandex'])){
		$advanced_options['advanced_yandex'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['advanced_yandex']));
	}
	
	//breadcrumb tab
	if(isset($_POST['siteseo_advanced_option_name']['breadcrumbs_enable'])){
		$advanced_options['breadcrumbs_enable'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['breadcrumbs_enable'] ? true : 0));
	}
	
	if(isset($_POST['siteseo_advanced_option_name']['breadcrumbs_seperator'])){
		$advanced_options['breadcrumbs_seperator'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['breadcrumbs_seperator']));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['breadcrumbs_custom_seperator'])){
		$advanced_options['breadcrumbs_custom_seperator'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['breadcrumbs_custom_seperator']));
	}

	if (isset($_POST['siteseo_advanced_option_name']['breadcrumbs_home'])) {
		$advanced_options['breadcrumbs_home'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['breadcrumbs_home'] ? true : 0 ));
	}

	if(isset($_POST['siteseo_advanced_option_name']['breadcrumb_home_label'])){
		$advanced_options['breadcrumb_home_label'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['breadcrumb_home_label']));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['breadcrumb_prefix'])){
		$advanced_options['breadcrumb_prefix'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['breadcrumb_prefix']));
	}
	
	// table of contents tab 

	if(isset($_POST['siteseo_advanced_option_name']['toc_enable'])){
		$advanced_options['toc_enable'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['toc_enable'] ? true : 0));
	}
	
	if(isset($_POST['siteseo_advanced_option_name']['toc_label'])){
		$advanced_options['toc_label'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['toc_label']));
	}
	
	//toc_excluded_headings
	if(isset($_POST['siteseo_advanced_option_name']['toc_excluded_headings'])){
		$advanced_options['toc_excluded_headings'] = map_deep(map_deep($_POST['siteseo_advanced_option_name']['toc_excluded_headings'], 'wp_unslash'), 'sanitize_text_field');
	}

	if(isset($_POST['siteseo_advanced_option_name']['toc_heading_type'])){
		$advanced_options['toc_heading_type'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['toc_heading_type']));
	}
	
	// security
	if(isset($_POST['siteseo_advanced_option_name']['security_metaboxe_role']['administrator'])){
		$advanced_options['security_metaboxe_role']['administrator'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['security_metaboxe_role']['administrator'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['security_metaboxe_role']['editor'])){
		$advanced_options['security_metaboxe_role']['editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['security_metaboxe_role']['editor'] ? true : 0));
	}
		
	if(isset($_POST['siteseo_advanced_option_name']['security_metaboxe_role']['author'])){
		$advanced_options['security_metaboxe_role']['author'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['security_metaboxe_role']['author'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['security_metaboxe_role']['contributor'])){
		$advanced_options['security_metaboxe_role']['contributor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['security_metaboxe_role']['contributor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['security_metaboxe_role']['subscriber'])){
		$advanced_options['security_metaboxe_role']['subscriber'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['security_metaboxe_role']['subscriber'] ? true : 0));
	}
	//mextabox_ca
	if(isset($_POST['siteseo_advanced_option_name']['security_metaboxe_ca_role']['administrator'])){
		$advanced_options['security_metaboxe_ca_role']['administrator'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['security_metaboxe_ca_role']['administrator'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['security_metaboxe_ca_role']['editor'])){
		$advanced_options['security_metaboxe_ca_role']['editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['security_metaboxe_ca_role']['editor'] ? true : 0));
	}
		
	if(isset($_POST['siteseo_advanced_option_name']['security_metaboxe_ca_role']['author'])){
		$advanced_options['security_metaboxe_ca_role']['author'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['security_metaboxe_ca_role']['author'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['security_metaboxe_ca_role']['contributor'])){
		$advanced_options['security_metaboxe_ca_role']['contributor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['security_metaboxe_ca_role']['contributor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['security_metaboxe_ca_role']['subscriber'])){
		$advanced_options['security_metaboxe_ca_role']['subscriber'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['security_metaboxe_ca_role']['subscriber'] ? true : 0));
	}

	//siteseo_advanced_security_metaboxe_siteseo
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-titles']['editor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-titles']['editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-titles']['editor'] ? true : 0));
	}
		
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-titles']['author'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-titles']['author'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-titles']['author'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-titles']['contributor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-titles']['contributor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-titles']['contributor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-titles']['subscriber'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-titles']['subscriber'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-titles']['subscriber'] ? true : 0));
	}

	//xml-sitemap
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['editor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['editor'] ? true : 0));
	}
		
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['author'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['author'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['author'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['contributor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['contributor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['contributor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['subscriber'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['subscriber'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-xml-sitemap']['subscriber'] ? true : 0));
	}
	//social	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-social']['editor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-social']['editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-social']['editor'] ? true : 0));
	}
		
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-social']['author'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-social']['author'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-social']['author'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-social']['contributor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-social']['contributor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-social']['contributor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-social']['subscriber'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-social']['subscriber'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-social']['subscriber'] ? true : 0));
	}
	//siteseo_advanced_security_metaboxe_siteseo-google-analytics
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['editor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['editor'] ? true : 0));
	}
		
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['author'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['author'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['author'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['contributor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['contributor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['contributor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['subscriber'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['subscriber'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-google-analytics']['subscriber'] ? true : 0));
	}
	//instant-indexing
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['editor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['editor'] ? true : 0));
	}
		
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['author'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['author'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['author'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['contributor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['contributor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['contributor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['subscriber'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['subscriber'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-instant-indexing']['subscriber'] ? true : 0));
	}
	
	//-advanced
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-advanced']['editor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-advanced']['editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-advanced']['editor'] ? true : 0));
	}
		
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-advanced']['author'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-advanced']['author'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-advanced']['author'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-advanced']['contributor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-advanced']['contributor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-advanced']['contributor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-advanced']['subscriber'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-advanced']['subscriber'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-advanced']['subscriber'] ? true : 0));
	}

	// tools -import-export
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-import-export']['editor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-import-export']['editor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-import-export']['editor'] ? true : 0));
	}
		
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-import-export']['author'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-import-export']['author'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-import-export']['author'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-import-export']['contributor'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-import-export']['contributor'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-import-export']['contributor'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-import-export']['subscriber'])){
		$advanced_options['siteseo_advanced_security_metaboxe_siteseo-import-export']['subscriber'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['siteseo_advanced_security_metaboxe_siteseo-import-export']['subscriber'] ? true : 0));
	}
	// Appearance tab
	if(isset($_POST['siteseo_advanced_option_name']['appearance_universal_metabox'])){
		$advanced_options['appearance_universal_metabox'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_universal_metabox']));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_universal_metabox_disable'])){
		$advanced_options['appearance_universal_metabox_disable'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_universal_metabox_disable']));
	}

	//appearance_metaboxe_position
	
	if(isset($_POST['siteseo_advanced_option_name']['appearance_metaboxe_position'])){
		$advanced_options['appearance_metaboxe_position'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_metaboxe_position']));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['appearance_ca_metaboxe'])){
		$advanced_options['appearance_ca_metaboxe'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_ca_metaboxe'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['appearance_genesis_seo_metaboxe'])){
		$advanced_options['appearance_genesis_seo_metaboxe'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_genesis_seo_metaboxe'] ? true : 0));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_advice_schema'])){
		$advanced_options['appearance_advice_schema'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_advice_schema'] ? true : 0));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_adminbar'])){
		$advanced_options['appearance_adminbar'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_adminbar'] ? true : 0));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_adminbar_noindex'])){
		$advanced_options['appearance_adminbar_noindex'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_adminbar_noindex'] ? true : 0));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_notifications'])){
		$advanced_options['appearance_notifications'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_notifications'] ? true : 0));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_news'])){
		$advanced_options['appearance_news'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_news'] ? true : 0));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_seo_tools'])){
		$advanced_options['appearance_seo_tools'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_seo_tools'] ? true : 0));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_title_col'])){
		$advanced_options['appearance_title_col'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_title_col'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['appearance_meta_desc_col'])){
		$advanced_options['appearance_meta_desc_col'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_meta_desc_col'] ? true : 0));
	}
	
	if(isset($_POST['siteseo_advanced_option_name']['appearance_redirect_enable_col'])){
		$advanced_options['appearance_redirect_enable_col'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_redirect_enable_col'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['appearance_redirect_url_col'])){
		$advanced_options['appearance_redirect_url_col'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_redirect_url_col'] ? true : 0));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_canonical'])){
		$advanced_options['appearance_canonical'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_canonical'] ? true : 0));
	}	

	if(isset($_POST['siteseo_advanced_option_name']['appearance_target_kw_col'])){
		$advanced_options['appearance_target_kw_col'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_target_kw_col'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['appearance_noindex_col'])){
		$advanced_options['appearance_noindex_col'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_noindex_col'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['appearance_nofollow_col'])){
		$advanced_options['appearance_nofollow_col'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_nofollow_col'] ? true : 0));
	}	
	
	if(isset($_POST['siteseo_advanced_option_name']['appearance_words_col'])){
		$advanced_options['appearance_words_col'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_words_col'] ? true : 0));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_score_col'])){
		$advanced_options['appearance_score_col'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_score_col'] ? true : 0));
	}

	if(isset($_POST['siteseo_advanced_option_name']['appearance_genesis_seo_menu'])){
		$advanced_options['appearance_genesis_seo_menu'] = sanitize_text_field(wp_unslash($_POST['siteseo_advanced_option_name']['appearance_genesis_seo_menu'] ? true : 0));
	}
	
	update_option('siteseo_advanced_option_name',$advanced_options);	
}
