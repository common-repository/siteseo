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

function siteseo_social_knowledge_graph_tab(){

	if(!empty($_POST['submit'])){
		siteseo_save_social_settings();
	}

	$options = get_option('siteseo_social_option_name');

	//siteseo_social_option_name
	$social_knowledge_type = isset($options['social_knowledge_type']) ? $options['social_knowledge_type'] : null;
	$social_knowledge_name = isset($options['social_knowledge_name']) ? esc_html($options['social_knowledge_name']) : null;
	$social_knowledge_img = isset($options['social_knowledge_img']) ? esc_attr($options['social_knowledge_img']) : null;
	$social_knowledge_phone = isset($options['social_knowledge_phone']) ? esc_html($options['social_knowledge_phone']) : null;
	$social_knowledge_contact_type = isset($options['social_knowledge_contact_type']) ? $options['social_knowledge_contact_type'] : null;
	$social_knowledge_contact_option = isset($options['social_knowledge_contact_option']) ? $options['social_knowledge_contact_option'] : null;
	
	echo '<div class="siteseo-section-header">
		<h2>'.esc_html__('Knowledge Graph', 'siteseo').'</h2>
	</div>

	<p>'.esc_html__('Configure Google Knowledge Graph.', 'siteseo').'</p>

	<p class="siteseo-help">
		<span class="dashicons dashicons-external"></span>
		<a href="https://developers.google.com/search/docs/guides/enhance-site" target="_blank">
			'.esc_html__('Learn more on Google official website.', 'siteseo').'
		</a>
	</p>

	<table class="form-table">
		<tr valign="top">
			<th scope="row">'.esc_html__('Person or organization', 'siteseo').'</th>
			<td>
				<select id="siteseo_social_knowledge_type" name="siteseo_social_option_name[social_knowledge_type]">
					<option value="none" '.selected($social_knowledge_type, 'none', false).'>
						'.esc_html__('None (will disable this feature)', 'siteseo').'
					</option>
					<option value="Person" '.selected($social_knowledge_type, 'Person', false).'>
						'.esc_html__('Person', 'siteseo').'
					</option>
					<option value="Organization" '.selected($social_knowledge_type, 'Organization', false).'>
						'.esc_html__('Organization', 'siteseo').'
					</option>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">'.esc_html__('Your name/organization', 'siteseo').'</th>
			<td>
				<input type="text" name="siteseo_social_option_name[social_knowledge_name]" placeholder="'.esc_html__('eg: Miremont', 'siteseo').'" aria-label="'.esc_html__('Your name/organization', 'siteseo').'" value="'.esc_attr($social_knowledge_name).'"/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">'.esc_html__('Your photo/organization logo', 'siteseo').'</th>
			<td>
				<input id="siteseo_social_knowledge_img_meta" type="text" value="'.esc_attr($social_knowledge_img).'" name="siteseo_social_option_name[social_knowledge_img]" aria-label="'.esc_html__('Your photo/organization logo', 'siteseo').'" placeholder="'.esc_html__('Select your logo', 'siteseo').'" />
				<input id="siteseo_social_knowledge_img_upload" class="btn btnSecondary" type="button" value="'.esc_html__('Upload an Image', 'siteseo').'" />
				<p class="description">'.esc_html__('JPG, PNG, WebP and GIF allowed.', 'siteseo').'</p>
				<img style="width:300px;max-height:400px;" src="'.esc_attr(siteseo_get_service('SocialOption')->getSocialKnowledgeImage()).'" />
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">'.esc_html__('Organization\'s phone number (only for Organizations)', 'siteseo').'</th>
			<td>
				<input type="text" name="siteseo_social_option_name[social_knowledge_phone]" placeholder="'.esc_html__('eg: +33123456789 (internationalized version required)', 'siteseo').'" aria-label="'.esc_html__('Organization\'s phone number (only for Organizations)', 'siteseo').'" value="'.esc_attr($social_knowledge_phone).'"/>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">'.esc_html__('Contact type (only for Organizations)', 'siteseo').'</th>
			<td>
				<select id="siteseo_social_knowledge_contact_type" name="siteseo_social_option_name[social_knowledge_contact_type]">
					<option value="customer support" '.selected($social_knowledge_contact_type, 'customer support', false).'>
						'.esc_html__('Customer support', 'siteseo').'
					</option>
					<option value="technical support" '.selected($social_knowledge_contact_type, 'technical support', false).'>
						'.esc_html__('Technical support', 'siteseo').'
					</option>
					<option value="billing support" '.selected($social_knowledge_contact_type, 'billing support', false).'>
						'.esc_html__('Billing support', 'siteseo').'
					</option>
					<option value="bill payment" '.selected($social_knowledge_contact_type, 'bill payment', false).'>
						'.esc_html__('Bill payment', 'siteseo').'
					</option>
					<option value="sales" '.selected($social_knowledge_contact_type, 'sales', false).'>
						'.esc_html__('Sales', 'siteseo').'
					</option>
					<option value="credit card support" '.selected($social_knowledge_contact_type, 'credit card support', false).'>
						'.esc_html__('Credit card support', 'siteseo').'
					</option>
					<option value="emergency" '.selected($social_knowledge_contact_type, 'emergency', false).'>
						'.esc_html__('Emergency', 'siteseo').'
					</option>
					<option value="baggage tracking" '.selected($social_knowledge_contact_type, 'baggage tracking', false).'>
						'.esc_html__('Baggage tracking', 'siteseo').'
					</option>
					<option value="roadside assistance" '.selected($social_knowledge_contact_type, 'roadside assistance', false).'>
						'.esc_html__('Roadside assistance', 'siteseo').'
					</option>
					<option value="package tracking" '.selected($social_knowledge_contact_type, 'package tracking', false).'>
						'.esc_html__('Package tracking', 'siteseo').'
					</option>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">'.esc_html__('Contact option (only for Organizations)', 'siteseo').'</th>
			<td>
				<select id="siteseo_social_knowledge_contact_option" name="siteseo_social_option_name[social_knowledge_contact_option]">
					<option value="None" '.selected($social_knowledge_contact_option, 'None', false).'>
						'.esc_html__('None', 'siteseo').'
					</option>
					<option value="TollFree" '.selected($social_knowledge_contact_option, 'TollFree', false).'>
						'.esc_html__('Toll Free', 'siteseo').'
					</option>
					<option value="HearingImpairedSupported" '.selected($social_knowledge_contact_option, 'HearingImpairedSupported', false).'>
						'.esc_html__('Hearing impaired supported', 'siteseo').'
					</option>
				</select>
			</td>
		</tr>
	</table>';
} 
function siteseo_social_accounts_tab(){
    if(!empty($_POST['submit'])){
        siteseo_save_social_settings();
    }

    $options = get_option('siteseo_social_option_name');
    
    echo '<div class="siteseo-section-header">
        <h2>'.esc_html__('Your social accounts', 'siteseo').'</h2>
    </div>

    <div class="siteseo-notice">
        <span class="dashicons dashicons-info"></span>
        <div>
            <p>'.esc_html__('Link your site with your social accounts.', 'siteseo').'</p>
            <p>'.esc_html__('Use markup on your website to add your social profile information to a Google Knowledge panel.', 'siteseo').'</p>
            <p>'.esc_html__('Knowledge panels prominently display your social profile information in some Google Search results.', 'siteseo').'</p>
            <p>'.esc_html__('Filling in these fields does not guarantee the display of this data in search results.', 'siteseo').'</p>
        </div>
    </div>

    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label for="social_accounts_facebook">'.esc_html__('Facebook', 'siteseo').'</label></th>
                <td><input type="text" name="siteseo_social_option_name[social_accounts_facebook]" id="social_accounts_facebook" placeholder="'.esc_attr__('eg: https://facebook.com/my-page-url', 'siteseo').'" value="'.esc_attr(isset($options['social_accounts_facebook']) ? $options['social_accounts_facebook'] : '').'" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="social_accounts_twitter">'.esc_html__('X Username', 'siteseo').'</label></th>
                <td><input type="text" name="siteseo_social_option_name[social_accounts_twitter]" id="social_accounts_twitter" placeholder="'.esc_attr__('eg: @my_twitter_account', 'siteseo').'" value="'.esc_attr(isset($options['social_accounts_twitter']) ? $options['social_accounts_twitter'] : '').'" /></td>
            </tr>
            <tr>
               <th scope="row"><label for="social_accounts_pinterest">'.esc_html__('Pinterest URL', 'siteseo').'</label></th>
                <td><input type="text" name="siteseo_social_option_name[social_accounts_pinterest]" id="social_accounts_pinterest" placeholder="'.esc_attr__('eg: https://pinterest.com/my-page-url/', 'siteseo').'" value="'.esc_attr(isset($options['social_accounts_pinterest']) ? $options['social_accounts_pinterest'] : '').'" /></td>
            </tr>
            <tr>
               <th scope="row"><label for="social_accounts_instagram">'.esc_html__('Instagram URL', 'siteseo').'</label></th>
                <td><input type="text" name="siteseo_social_option_name[social_accounts_instagram]" id="social_accounts_instagram" placeholder="'.esc_attr__('eg: https://www.instagram.com/my-page-url/', 'siteseo').'" value="'.esc_attr(isset($options['social_accounts_instagram']) ? $options['social_accounts_instagram'] : '').'" /></td>
            </tr>
            <tr>
               <th scope="row"><label for="social_accounts_youtube">'.esc_html__('YouTube URL', 'siteseo').'</label></th>
                <td><input type="text" name="siteseo_social_option_name[social_accounts_youtube]" id="social_accounts_youtube" placeholder="'.esc_attr__('eg: https://www.youtube.com/my-channel-url', 'siteseo').'" value="'.esc_attr(isset($options['social_accounts_youtube']) ? $options['social_accounts_youtube'] : '').'" /></td>
            </tr>
            <tr>
               <th scope="row"><label for="social_accounts_linkedin">'.esc_html__('LinkedIn URL', 'siteseo').'</label></th>
                <td><input type="text" name="siteseo_social_option_name[social_accounts_linkedin]" id="social_accounts_linkedin" placeholder="'.esc_attr__('eg: http://linkedin.com/company/my-company-url/', 'siteseo').'" value="'.esc_attr(isset($options['social_accounts_linkedin']) ? $options['social_accounts_linkedin'] : '').'" /></td>
            </tr>
        </tbody>
    </table>';
}

function siteseo_social_facebook_graph_tab() {
	
	if(!empty($_POST['submit'])){
		siteseo_save_social_settings();
	}

	 $docs = siteseo_get_docs_links();
    $options = get_option('siteseo_social_option_name');

    $options_set_attachment_id = isset($options['social_facebook_img_attachment_id']) ? $options['social_facebook_img_attachment_id'] : '';
    $options_set_width = isset($options['social_facebook_img_width']) ? $options['social_facebook_img_width'] : '';
    $options_set_height = isset($options['social_facebook_img_height']) ? $options['social_facebook_img_height'] : '';
    $social_facebook_og_checked = isset($options['social_facebook_og']) ? $options['social_facebook_og'] : false;
    $social_facebook_img = isset($options['social_facebook_img']) ? esc_attr($options['social_facebook_img']) : null;
    $social_facebook_img_default_checked = isset($options['social_facebook_img_default']) ? $options['social_facebook_img_default'] : false;
    $social_facebook_link_ownership_id = isset($options['social_facebook_link_ownership_id']) ? $options['social_facebook_link_ownership_id'] : null;
    $social_facebook_admin_id = isset($options['social_facebook_admin_id']) ? $options['social_facebook_admin_id'] : null;
	
	echo '<div class="siteseo-section-header">
			<h2>'.esc_html__('Facebook (Open Graph)', 'siteseo').'</h2>
		</div>

	<p>
		'.esc_html__('Manage Open Graph data. These metatags will be used by Facebook, Pinterest, LinkedIn, WhatsApp... when a user shares a link on its own social network. Increase your click-through rate by providing relevant information such as an attractive image.', 'siteseo').'
		'.wp_kses_post(siteseo_tooltip_link($docs['social']['og'], esc_html__('Manage Facebook Open Graph and Twitter Cards metas - new window', 'siteseo'))).'
	</p>

	<div class="siteseo-notice">
		<span class="dashicons dashicons-info"></span>
		<div>
			<p>
				'.wp_kses_post(__('We generate the <strong>og:image</strong> meta in this order:', 'siteseo')).'
			</p>

			<ol>
				<li>
					'.esc_html__('Custom OG Image from SEO metabox', 'siteseo').'
				</li>
				<li>
					'.esc_html__('Post thumbnail / Product category thumbnail (Featured image)', 'siteseo').'
				</li>
				<li>
					'.esc_html__('First image of your post content', 'siteseo').'
				</li>
				<li>
					'.esc_html__('Global OG Image set in SEO > Social > Open Graph', 'siteseo').'
				</li>
				<li>
					'.esc_html__('Site icon from the Customizer', 'siteseo').'
				</li>
			</ol>
		</div>
	</div>

	<table class="form-table">
		<tr>
			<th scope="row">'.esc_html__('Enable OG data', 'siteseo').'</th>
			<td><label for="siteseo_social_facebook_og">
				<input id="siteseo_social_facebook_og" name="siteseo_social_option_name[social_facebook_og]" type="checkbox" '.checked($social_facebook_og_checked, '1', false).' value="1"/>
				'.esc_html__('Enable OG data', 'siteseo').'
			</label></td>
		</tr>
		<tr>
			<th scope="row">'.esc_html__('Default Image', 'siteseo').'</th>
			<td>
				<input id="siteseo_social_fb_img_meta" type="text" value="'.esc_attr($social_facebook_img).'" name="siteseo_social_option_name[social_facebook_img]" aria-label="'.esc_html__('Select a default image', 'siteseo').'" placeholder="'.esc_html__('Select your default thumbnail', 'siteseo').'" />
				<input type="hidden" name="siteseo_social_facebook_img_attachment_id" id="siteseo_social_fb_img_attachment_id" value="'.esc_attr($options_set_attachment_id).'">
				<input type="hidden" name="siteseo_social_facebook_img_width" id="siteseo_social_fb_img_width" value="'.esc_attr($options_set_width).'">
				<input type="hidden" name="siteseo_social_facebook_img_height" id="siteseo_social_fb_img_height" value="'.esc_attr($options_set_height).'">
				<input id="siteseo_social_fb_img_upload" class="btn btnSecondary" type="button" value="'.esc_html__('Upload an Image', 'siteseo').'" />
				<p class="description">'.esc_html__('Minimum size: 200x200px, ideal ratio 1.91:1, 8Mb max. (eg: 1640x856px or 3280x1712px for retina screens)', 'siteseo').'</p>
				<p class="description">'.esc_html__('If no default image is set, we‘ll use your site icon defined from the Customizer.', 'siteseo').'</p>
			</td>
		</tr>
		<tr>
			<th scope="row">'.esc_html__('Override Default Image', 'siteseo').'</th>
			<td><label for="siteseo_social_facebook_img_default">
				<input id="siteseo_social_facebook_img_default" name="siteseo_social_option_name[social_facebook_img_default]" type="checkbox" '.checked($social_facebook_img_default_checked, '1', false).' value="1"/>
				'.wp_kses_post(__('Override every <strong>og:image</strong> tag with this default image (except if a custom og:image has already been set from the SEO metabox).', 'siteseo')).'
			</label>';
	if ('' == $social_facebook_img) {
		echo '<div class="siteseo-notice is-warning is-inline">
					<p>'.wp_kses_post(__('Please define a <strong>default OG Image</strong> from the field above', 'siteseo')).'</p>
				</div>';
	}
	echo '</td>
		</tr>
		<tr>
			<th scope="row">'.esc_html__('Link Ownership ID', 'siteseo').'</th>
			<td>'.sprintf('<input type="text" placeholder="' . esc_html__('1234567890','siteseo') . '" name="siteseo_social_option_name[social_facebook_link_ownership_id]" value="%s"/>', esc_attr($social_facebook_link_ownership_id)).'
				<p class="description">'.esc_html__('One or more Facebook Page IDs that are associated with a URL in order to enable link editing and instant article publishing.', 'siteseo').'</p>
				<pre>&lt;meta property="fb:pages" content="page ID"/&gt;</pre>
				<p><span class="siteseo-help dashicons dashicons-external"></span><a class="siteseo-help" href="https://www.facebook.com/help/1503421039731588" target="_blank">'.esc_html__('How do I find my Facebook Page ID?', 'siteseo').'</a></p>
			</td>
		</tr>
		<tr>
			<th scope="row">'.esc_html__('Admin ID', 'siteseo').'</th>
			<td>'.sprintf('<input type="text" placeholder="' . esc_html__('1234567890','siteseo') . '" name="siteseo_social_option_name[social_facebook_admin_id]" value="%s"/>', esc_attr($social_facebook_admin_id)).'
				<p class="description">'.esc_html__('The ID (or comma-separated list for properties that can accept multiple IDs) of an app, person using the app, or Page Graph API object.', 'siteseo').'</p>
				<pre>&lt;meta property="fb:admins" content="admins ID"/&gt;</pre>
			</td>
		</tr>
	</table>';
}


function siteseo_social_twitter_card_tab(){

	if(!empty($_POST['submit'])){
		siteseo_save_social_settings();
	}
	
    $docs = siteseo_get_docs_links();
    $options = get_option('siteseo_social_option_name');

    $social_twitter_card = isset($options['social_twitter_card']);
    $social_twitter_card_og = isset($options['social_twitter_card_og']);
    $social_twitter_card_img = isset($options['social_twitter_card_img']) ? esc_attr($options['social_twitter_card_img']) : null;
    $social_twitter_card_img_size = isset($options['social_twitter_card_img_size']) ? $options['social_twitter_card_img_size'] : 'default';
	
	echo '<div class="siteseo-section-header">
		<h2>'.esc_html__('Twitter (Twitter card)', 'siteseo').'</h2>
	</div>
	<p>
		'.esc_html__('Manage your Twitter card.', 'siteseo').'
		'.wp_kses_post(siteseo_tooltip_link($docs['social']['og'], esc_html__('Manage Facebook Open Graph and Twitter Cards metas - new window', 'siteseo'))).'
	</p>

	<div class="siteseo-notice">
		<span class="dashicons dashicons-info"></span>
		<div>
			<p>
				'.wp_kses_post(__('We generate the <strong>twitter:image</strong> meta in this order:', 'siteseo')).'
			</p>

			<ol>
				<li>
					'.esc_html__('Custom Twitter image from SEO metabox', 'siteseo').'
				</li>
				<li>
					'.esc_html__('Post thumbnail / Product category thumbnail (Featured image)', 'siteseo').'
				</li>
				<li>
					'.esc_html__('First image of your post content', 'siteseo').'
				</li>
				<li>
					'.esc_html__('Global Twitter:image set in SEO > Social > Twitter Card', 'siteseo').'
				</li>
			</ol>
		</div>
	</div>


    <table class="form-table">
        <tr valign="top">
            <th scope="row">'.esc_html__('Twitter (Twitter card)', 'siteseo').'</th>
            <td>
                <p>'.esc_html__('Manage your Twitter card.', 'siteseo').'
                '.wp_kses_post(siteseo_tooltip_link($docs['social']['og'], esc_html__('Manage Facebook Open Graph and Twitter Cards metas - new window', 'siteseo'))).'</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">'.esc_html__('Enable Twitter card', 'siteseo').'</th>
            <td>
                <label for="siteseo_social_twitter_card">
                    <input id="siteseo_social_twitter_card" name="siteseo_social_option_name[social_twitter_card]" type="checkbox" '.checked($social_twitter_card, true, false).' value="1"/>
                    '.esc_html__('Enable Twitter card', 'siteseo').'
                </label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">'.esc_html__('Use OG if no Twitter Cards', 'siteseo').'</th>
            <td>
                <label for="siteseo_social_twitter_card_og">
                    <input id="siteseo_social_twitter_card_og" name="siteseo_social_option_name[social_twitter_card_og]" type="checkbox" '.checked($social_twitter_card_og, true, false).' value="1"/>
                    '.esc_html__('Use OG if no Twitter Cards', 'siteseo').'
                </label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">'.esc_html__('Default Twitter Image', 'siteseo').'</th>
            <td>
                <input id="siteseo_social_twitter_img_meta" type="text" value="'.esc_attr($social_twitter_card_img).'" name="siteseo_social_option_name[social_twitter_card_img]" aria-label="'.esc_html__('Default Twitter Image', 'siteseo').'" placeholder="'.esc_html__('Select your default thumbnail', 'siteseo').'" />
                <input id="siteseo_social_twitter_img_upload" class="btn btnSecondary" type="button" value="'.esc_html__('Upload an Image', 'siteseo').'" />
                <p class="description">'.esc_html__('Minimum size: 144x144px (300x157px with large card enabled), ideal ratio 1:1 (2:1 with large card), 5Mb max.', 'siteseo').'</p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">'.esc_html__('Twitter Card Image Size', 'siteseo').'</th>
            <td>
                <select id="siteseo_social_twitter_card_img_size" name="siteseo_social_option_name[social_twitter_card_img_size]">
                    <option value="default" '.selected($social_twitter_card_img_size, 'default', false).'>'.esc_html__('Default', 'siteseo').'</option>
                    <option value="large" '.selected($social_twitter_card_img_size, 'large', false).'>'.esc_html__('Large', 'siteseo').'</option>
                </select>
                <p class="description">'.wp_kses_post(__('The Summary Card with <strong>Large Image</strong> features a large, full-width prominent image alongside a tweet. It is designed to give the reader a rich photo experience, and clicking on the image brings the user to your website.', 'siteseo')).'</p>
            </td>
        </tr>
    </table>';

}

function siteseo_social_page_html(){
	
	$current_tab = '';
	$plugin_settings_tabs	= [
		'tab_siteseo_social_knowledge' => esc_html__('Knowledge Graph', 'siteseo'),
		'tab_siteseo_social_accounts'  => esc_html__('Your social accounts', 'siteseo'),
		'tab_siteseo_social_facebook'  => esc_html__('Facebook (Open Graph)', 'siteseo'),
		'tab_siteseo_social_twitter'   => esc_html__('Twitter (Twitter card)', 'siteseo'),
	];
	
	$feature_title_kses = ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]];
	
	$save_btn_kses = [
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
	];

	if(function_exists('siteseo_admin_header')){
		siteseo_admin_header();
	}

	echo '<form method="post" class="siteseo-option">';
		wp_nonce_field('siteseo_social_nonce');

		echo '<div id="siteseo-tabs" class="wrap">'.wp_kses(siteseo_feature_title('social'), $feature_title_kses).
	
		'<div class="nav-tab-wrapper">';
		foreach($plugin_settings_tabs as $tab_key => $tab_caption){
			echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-social#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
		}
		echo '</div>

		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_social_knowledge' ? ' active' : '').'" id="tab_siteseo_social_knowledge">';
		siteseo_social_knowledge_graph_tab();
		echo'</div>
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_social_accounts' ? ' active' : '').'" id="tab_siteseo_social_accounts">';
		siteseo_social_accounts_tab();
		echo '</div>
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_social_facebook' ? ' active' : '').'" id="tab_siteseo_social_facebook">';
		siteseo_social_facebook_graph_tab();
		echo'</div>
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_social_twitter' ? ' active' : '').'" id="tab_siteseo_social_twitter">';
		siteseo_social_twitter_card_tab();
		echo'</div>
		</div>'.
		wp_kses(siteseo_submit_button(esc_html__('Save changes', 'siteseo'), false), $save_btn_kses).'
	</form>';
}

function siteseo_save_social_settings(){
	
	check_admin_referer('siteseo_social_nonce');

	if(!current_user_can('manage_options') || !is_admin()){
		return;
	}

	$social_options = [];

	if(empty($_POST['siteseo_social_option_name'])){
		return;
	}

	if(isset($_POST['siteseo_social_option_name']['social_knowledge_type'])){
		$social_options['social_knowledge_type'] = sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_knowledge_type']));
	}

	if(isset($_POST['siteseo_social_option_name']['social_knowledge_name'])){
		$social_options['social_knowledge_name'] = sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_knowledge_name']));
	}

	if(isset($_POST['siteseo_social_option_name']['social_knowledge_img'])){
		$social_options['social_knowledge_img'] = sanitize_url(wp_unslash($_POST['siteseo_social_option_name']['social_knowledge_img']));
	}

	if(isset($_POST['siteseo_social_option_name']['social_knowledge_phone'])){
		$social_options['social_knowledge_phone'] = sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_knowledge_phone']));	
	}

	if(isset($_POST['siteseo_social_option_name']['social_knowledge_contact_type'])){
		$social_options['social_knowledge_contact_type'] = sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_knowledge_contact_type']));
	}
	
	if(isset($_POST['siteseo_social_option_name']['social_knowledge_contact_option'])){
		$social_options['social_knowledge_contact_option'] = sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_knowledge_contact_option']));
	}

	if(isset($_POST['siteseo_social_option_name']['social_accounts_facebook'])){
		$social_options['social_accounts_facebook'] = sanitize_url(wp_unslash($_POST['siteseo_social_option_name']['social_accounts_facebook']));
	}
	
	if(isset($_POST['siteseo_social_option_name']['social_accounts_twitter'])){
		$social_options['social_accounts_twitter'] = sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_accounts_twitter']));
	}
	
	if(isset($_POST['siteseo_social_option_name']['social_accounts_pinterest'])){
		$social_options['social_accounts_pinterest'] = sanitize_url(wp_unslash($_POST['siteseo_social_option_name']['social_accounts_pinterest']));
	}
	
	if(isset($_POST['siteseo_social_option_name']['social_accounts_instagram'])){
		$social_options['social_accounts_instagram'] = sanitize_url(wp_unslash($_POST['siteseo_social_option_name']['social_accounts_instagram']));
	}

	if(isset($_POST['siteseo_social_option_name']['social_accounts_youtube'])){
		$social_options['social_accounts_youtube'] = sanitize_url(wp_unslash($_POST['siteseo_social_option_name']['social_accounts_youtube']));
	}

	if(isset($_POST['siteseo_social_option_name']['social_accounts_linkedin'])){
		$social_options['social_accounts_linkedin'] = sanitize_url(wp_unslash($_POST['siteseo_social_option_name']['social_accounts_linkedin']));
	}

	if(isset($_POST['siteseo_social_option_name']['social_facebook_og'])){
		$social_options['social_facebook_og'] = !empty($_POST['siteseo_social_option_name']['social_facebook_og']) ? sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_facebook_og'])) : '';
	}
	
	if(isset($_POST['siteseo_social_option_name']['social_facebook_img'])){
		$social_options['social_facebook_img'] = sanitize_url(wp_unslash($_POST['siteseo_social_option_name']['social_facebook_img']));
	}


	if(!empty($_POST['siteseo_social_option_name']['social_facebook_img_default'])){
		$social_options['social_facebook_img_default'] = !empty($_POST['siteseo_social_option_name']['social_facebook_img_default']) ? sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_facebook_img_default'])): '';
	}
	
	if(isset($_POST['siteseo_social_option_name']['social_facebook_link_ownership_id'])){
		$social_options['social_facebook_link_ownership_id'] = sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_facebook_link_ownership_id']));
	}

	if(isset($_POST['siteseo_social_option_name']['social_facebook_admin_id'])){
		$social_options['social_facebook_admin_id'] = sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_facebook_admin_id']));
	}
	
	if(isset($_POST['siteseo_social_option_name']['social_twitter_card'])){
		$social_options['social_twitter_card'] = !empty($_POST['siteseo_social_option_name']['social_twitter_card']) ? sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_twitter_card'])) : '';
	}
	
	if(isset($_POST['siteseo_social_option_name']['social_twitter_card_og'])){
		$social_options['social_twitter_card_og'] = !empty($_POST['siteseo_social_option_name']['social_twitter_card_og']) ? sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_twitter_card_og'])) : '';
	}

	if(isset($_POST['siteseo_social_option_name']['social_twitter_card_img'])){
		$social_options['social_twitter_card_img'] = sanitize_url(wp_unslash($_POST['siteseo_social_option_name']['social_twitter_card_img']));
	}

	if(isset($_POST['siteseo_social_option_name']['social_twitter_card_img_size'])){
		$social_options['social_twitter_card_img_size'] = sanitize_text_field(wp_unslash($_POST['siteseo_social_option_name']['social_twitter_card_img_size']));
	}

	update_option('siteseo_social_option_name', $social_options);
}

siteseo_social_page_html();