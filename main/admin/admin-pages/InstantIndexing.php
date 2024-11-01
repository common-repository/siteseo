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

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');


function siteseo_instant_indexing_general_tab() {

	if(!empty($_POST['submit'])){
		siteseo_save_instantindexing_settings();
	}

	$docs = function_exists('siteseo_get_docs_links') ? siteseo_get_docs_links() : '';
	$options = get_option('siteseo_instant_indexing_option_name');
	$log = get_option('siteseo_instant_indexing_log_option_name');

	$search_engines = [
		'google' => 'Google',
		'bing' => 'Bing'
	];

	$actions = [
		'URL_UPDATED' => esc_html__('Update URLs', 'siteseo'),
		'URL_DELETED' => esc_attr__('Remove URLs (URL must return a 404 or 410 status code or the page contains <meta name="robots" content="noindex" /> meta tag)', 'siteseo'),
	];

	$indexing_plugins = [
		'indexnow/indexnow-url-submission.php' => 'IndexNow',
		'bing-webmaster-tools/bing-url-submission.php' => 'Bing Webmaster Url Submission',
		'fast-indexing-api/instant-indexing.php' => 'Instant Indexing',
	];

	$urls = isset($log['log']['urls']) ? $log['log']['urls'] : null;
	$date = isset($log['log']['date']) ? $log['log']['date'] : null;
	$error = isset($log['error']) ? $log['error'] : null;
	$bing_response = isset($log['bing']['response']) ? $log['bing']['response'] : null;
	$google_response = isset($log['google']['response']) ? $log['google']['response'] : null;
	$check = isset($options['instant_indexing_manual_batch']) ? esc_attr($options['instant_indexing_manual_batch']) : null;

	echo '<div class="siteseo-section-header">
		<h2>' . esc_html__('Instant Indexing', 'siteseo') . '</h2>
	</div>

	<p>' . esc_html__('You can use the Indexing API to tell Google & Bing to update or remove pages from the Google / Bing index. The process can take a few minutes. You can submit your URLs in batches of 100 (max 200 requests per day for Google).', 'siteseo') . '</p>

	<p class="siteseo-help">
		<span class="dashicons dashicons-external"></span>
		<a href="' . esc_attr($docs['indexing_api']['google']) . '" target="_blank">' . esc_html__('401 / 403 error?', 'siteseo') . '</a>
	</p>

	<div class="siteseo-notice">
		<span class="dashicons dashicons-info"></span>
		<div>
			<h3>' . esc_html__('How does this work?', 'siteseo') . '</h3>
			<ol>
				<li>' . wp_kses_post(__('Setup your Google / Bing API keys from the <strong>Settings</strong> tab', 'siteseo')) . '</li>
				<li>' . wp_kses_post(__('<strong>Enter your URLs</strong> to index in the field below', 'siteseo')) . '</li>
				<li>' . wp_kses_post(__('<strong>Save changes</strong>', 'siteseo')) . '</li>
				<li>' . wp_kses_post(__('Click <strong>Submit URLs to Google & Bing</strong>', 'siteseo')) . '</li>
			</ol>
		</div>
	</div>';

	foreach ($indexing_plugins as $key => $value) {
		if (is_plugin_active($key)) {
			echo '<div class="siteseo-notice is-warning">
				<span class="dashicons dashicons-warning"></span>
				<div>
					<h3>' . sprintf(wp_kses_post(__('We noticed that you use <strong>%s</strong> plugin.', 'siteseo')), esc_html($value)) . '</h3>
					<p>' . sprintf(esc_html__('To prevent any conflicts with our Indexing feature, please disable it.', 'siteseo')) . '</p>
					<a class="btn btnPrimary" href="' . esc_url(admin_url('plugins.php')) . '">' . esc_html__('Fix this!', 'siteseo') . '</a>
				</div>
			</div>';
		}
	}

	echo '<form method="post">
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row">' . esc_html__('Select search engines', 'siteseo') . '</th>
					<td>';

	foreach ($search_engines as $key => $value) {
		$checked = isset($options['engines'][$key]) ? 'checked' : '';
		echo '<div class="siteseo_wrap_single_cpt">
				<label for="siteseo_instant_indexing_engines_' . esc_attr($key) . '">
					<input id="siteseo_instant_indexing_engines_' . esc_attr($key) . '" name="siteseo_instant_indexing_option_name[engines][' . esc_attr($key) . ']" type="checkbox" value="1" ' . esc_attr($checked) . '>
					' . esc_html($value) . '
				</label>
			</div>';
	}

	echo '</td>
				</tr>
				<tr>
					<th scope="row">' . esc_html__('Which action to run for Google?', 'siteseo') . '</th>
					<td>';

	foreach ($actions as $key => $value) {
		$checked = (isset($options['instant_indexing_google_action']) && $options['instant_indexing_google_action'] === $key) ? 'checked' : '';
		echo '<div class="siteseo_wrap_single_cpt">
				<label for="siteseo_instant_indexing_google_action_include_' . esc_attr($key) . '">
					<input id="siteseo_instant_indexing_google_action_include_' . esc_attr($key) . '" name="siteseo_instant_indexing_option_name[instant_indexing_google_action]" type="radio" value="' . esc_attr($key) . '" ' . esc_attr($checked) . '>
					' . esc_html($value) . '
				</label>
			</div>';
	}

	echo '</td>
				</tr>
				<tr>
					<th scope="row">' . esc_html__('Submit URLs for indexing', 'siteseo') . '</th>
					<td>
						<textarea id="siteseo_instant_indexing_manual_batch" name="siteseo_instant_indexing_option_name[instant_indexing_manual_batch]" rows="20" placeholder="' . esc_html__('Enter one URL per line to submit them to search engines (max 100 URLs)', 'siteseo') . '" aria-label="' . esc_html__('Enter one URL per line to submit them to search engines (max 100 URLs)', 'siteseo') . '">' . esc_html($check) . '</textarea>
						<div class="wrap-siteseo-progress">
							<div class="siteseo-progress" style="margin:0">
								<div id="siteseo_instant_indexing_url_progress" class="siteseo-progress-bar" role="progressbar" style="width: 1%;" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">1%</div>
							</div>
							<div class="wrap-siteseo-counters">
								<div id="siteseo_instant_indexing_url_count"></div>
								<strong>' . esc_html__(' / 100 URLs', 'siteseo') . '</strong>
							</div>
						</div>
						<br>
						<button type="submit" class="siteseo-instant-indexing-batch btn btnPrimary">
							' . esc_html__('Submit URLs to Google & Bing', 'siteseo') . '
						</button>
						<span class="spinner"></span>
						</p>
						<h3>' . esc_html__('Latest indexing request', 'siteseo') . '</h3>
						<p><em>' . esc_html($date) . '</em></p>';

	if (!empty($error)) {
		echo '<span class="indexing-log indexing-failed"></span>' . wp_kses_post($error) . '
			<h4>' . esc_html__('Latest URLs submitted', 'siteseo') . '</h4>';

		if (!empty($urls[0])) {
			foreach ($urls as $url) {
				echo '<tr><td>' . esc_url($url) . '</td></tr>';
			}
		} else {
			esc_html_e('None', 'siteseo');
		}
	}

	echo '</td>
				</tr>
			</tbody>
		</table>
	</form>';

	if (!empty($bing_response['response'])) {
		switch ($bing_response['response']['code']) {
			case 200:
				$msg = esc_html__('URLs submitted successfully', 'siteseo');
				break;
			case 202:
				$msg = esc_html__('URL received. IndexNow key validation pending.', 'siteseo');
				break;
			case 400:
				$msg = esc_html__('Bad request: Invalid format', 'siteseo');
				break;
			case 403:
				$msg = esc_html__('Forbidden: In case of key not valid (e.g. key not found, file found but key not in the file)', 'siteseo');
				break;
			case 422:
				$msg = esc_html__('Unprocessable Entity: In case of URLs don\'t belong to the host or the key is not matching the schema in the protocol', 'siteseo');
				break;
			case 429:
				$msg = esc_html__('Too Many Requests: Too Many Requests (potential Spam)', 'siteseo');
				break;
			default:
				$msg = esc_html__('Something went wrong', 'siteseo');
		}

		echo '<div class="wrap-bing-response">
			
			<table class="form-table">
				<tr><th scope="row"></th>
					<td><h4>' . esc_html__('Bing Response', 'siteseo') . '</h4>';

		if ($bing_response['response']['code'] == 200 || $bing_response['response']['code'] == 202) {
			echo '<span class="indexing-log indexing-done"></span>';
		} else {
			echo '<span class="indexing-log indexing-failed"></span>';
		}

		echo '</td>
					<td><code>' . esc_html($msg) . '</code></td>
				</tr>
			</table>
		</div>';
	}

	if (is_array($google_response) && !empty($google_response)) {
		echo '<div class="wrap-google-response">
			<h4>' . esc_html__('Google Response', 'siteseo') . '</h4>';

		$google_exception = $google_response[siteseo_array_key_first($google_response)];
		if (is_a($google_exception, 'Google\Service\Exception')) {
			$error = json_decode($google_exception->getMessage(), true);
			echo '<table class="form-table"><th scope="row">
					<tr>
						<td><span class="indexing-log indexing-failed"></span></td>
						<td><code>' . esc_html($error['error']['code']) . ' - ' . esc_html($error['error']['message']) . '</code></td>
					</tr></th>
				</table>';
		} elseif (!empty($google_response['error'])) {
			echo '<table class="form-table"><th scope="row">
					<tr>
						<td><span class="indexing-log indexing-failed"></span></td>
						<td><code>' . esc_html($google_response['error']['code']) . ' - ' . esc_html($google_response['error']['message']) . '</code></td>
					</tr></th>
				</table>';
		} else {
			echo '<table class="form-table"><th scope="row">
					<tr>
						<td<p>
					<span class="indexing-log indexing-done"></span>
					<code>' . esc_html__('URLs submitted successfully', 'siteseo') . '</code>
				</p></td>
				</tr>
				</th>
				<table>';

			foreach ($google_response as $result) {
				if ($result) {
					echo '<tr>';
					if (!empty($result->urlNotificationMetadata->latestUpdate["url"])) {
						echo '<td>' . esc_url($result->urlNotificationMetadata->latestUpdate["url"]) . '</td>';
					}
					if (!empty($result->urlNotificationMetadata->latestUpdate["type"])) {
						echo '<td><code>' . esc_html($result->urlNotificationMetadata->latestUpdate["type"]) . '</code></td>';
					}
					echo '</tr>';
					echo '<tr>';
					if (!empty($result->urlNotificationMetadata->latestRemove["url"])) {
						echo '<td>' . esc_url($result->urlNotificationMetadata->latestRemove["url"]) . '</td>';
					}
					if (!empty($result->urlNotificationMetadata->latestRemove["type"])) {
						echo '<td><code>' . esc_html($result->urlNotificationMetadata->latestRemove["type"]) . '</code></td>';
					}
					echo '</tr>';
				}
			}

			echo '</table>';
		}

		echo '</div>';
	}
}
function siteseo_instant_indexing_settings_tab() {
	if(!empty($_POST['submit'])){
		siteseo_save_instantindexing_settings();
	}

	echo '<div class="siteseo-section-header">
		<h2>' . esc_html__('Settings', 'siteseo') . '</h2>
	</div>
	<table class="form-table">
		<tr>
			<th scope="row">' . esc_html__('Instant Indexing Google API Key', 'siteseo') . '</th>
			<td>';

	$docs = function_exists('siteseo_get_docs_links') ? siteseo_get_docs_links() : '';
	$options = get_option('siteseo_instant_indexing_option_name');
	$check = isset($options['instant_indexing_google_api_key']) ? esc_attr($options['instant_indexing_google_api_key']) : null;

	echo '<textarea id="instant_indexing_google_api_key" name="siteseo_instant_indexing_option_name[instant_indexing_google_api_key]" rows="12" placeholder="' . esc_html__('Paste your Google JSON key file here', 'siteseo') . '" aria-label="' . esc_html__('Paste your Google JSON key file here', 'siteseo') . '">' . esc_html($check) . '</textarea>
	<p class="siteseo-help description">' . sprintf(wp_kses_post(__('To use the <span class="dashicons dashicons-external"></span><a href="%1$s" target="_blank">Google Indexing API</a> and generate your JSON key file, please <span class="dashicons dashicons-external"></span><a href="%2$s" target="_blank">follow our guide.')), esc_url($docs['indexing_api']['api']), esc_url($docs['indexing_api']['google'])) . '</p>
			</td>
		</tr>
		<tr>
			<th scope="row">' . esc_html__('Instant Indexing Bing API Key', 'siteseo') . '</th>
			<td>';

	$options = get_option('siteseo_instant_indexing_option_name');
	$check = isset($options['instant_indexing_bing_api_key']) ? esc_attr($options['instant_indexing_bing_api_key']) : null;

	echo '<input type="text" id="siteseo_instant_indexing_bing_api_key" name="siteseo_instant_indexing_option_name[instant_indexing_bing_api_key]" placeholder="' . esc_html__('Enter your Bing Instant Indexing API', 'siteseo') . '" aria-label="' . esc_html__('Enter your Bing Instant Indexing API', 'siteseo') . '" value="' . esc_attr($check) . '" />
	<button type="button" class="siteseo-instant-indexing-refresh-api-key btn btnSecondary">' . esc_html__('Generate key', 'siteseo') . '</button>
	<p class="description">' . esc_html__('The Bing Indexing API key is automatically generated. Click Generate key if you want to recreate it, or if it\'s missing.', 'siteseo') . '</p>
	<p class="description">' . esc_html__('A key should look like this:', 'siteseo') . ' ZjA2NWI3ZWM3MmNhNDRkODliYmY0YjljMzg5YTk2NGE=</p>
			</td>
		</tr>
		<tr>
			<th scope="row">' . esc_html__('Automate URL Submission', 'siteseo') . '</th>
			<td>';

	$options = get_option('siteseo_instant_indexing_option_name');
	$check = isset($options['instant_indexing_automate_submission']);

	echo '<label for="siteseo_instant_indexing_automate_submission">
		<input id="siteseo_instant_indexing_automate_submission" name="siteseo_instant_indexing_option_name[instant_indexing_automate_submission]" type="checkbox" ' . checked($check, true, false) . ' value="1" />
		' . esc_html__('Enable automatic URL submission for IndexNow API', 'siteseo') . '
	</label>
	<p class="description">' . esc_html__('Notify search engines using IndexNow protocol (currently Bing and Yandex) whenever a post is created, updated or deleted.', 'siteseo') . '</p>
			</td>
		</tr>
	</table>';
}

function siteseo_instant_indexing_page_html(){

	$current_tab = '';
	$plugin_settings_tabs = [
		'tab_siteseo_instant_indexing_general' => esc_html__('General', 'siteseo'),
		'tab_siteseo_instant_indexing_settings'	=> esc_html__('Settings', 'siteseo')
	];
	$feature_title_kses = ['h1' => true, 'input' => ['type' => true, 'name' => true, 'id' => true, 'class' => true, 'data-*' => true], 'label' => ['for' => true], 'span' => ['id' => true, 'class' => true], 'div' => ['id' => true, 'class' => true]];

	if(function_exists('siteseo_admin_header')){
		siteseo_admin_header();
	}

	echo '<form method="post" class="siteseo-option">';
			wp_nonce_field('siteseo_instant_indexing_nonce');

		echo '<div id="siteseo-tabs" class="wrap">'.
			wp_kses(siteseo_feature_title('instant-indexing'), $feature_title_kses).
			'<div class="nav-tab-wrapper">';

			foreach($plugin_settings_tabs as $tab_key => $tab_caption){
				echo '<a id="' . esc_attr($tab_key) . '-tab" class="nav-tab" href="?page=siteseo-instant-indexing-page#tab=' . esc_attr($tab_key) . '">' . esc_html($tab_caption) . '</a>';
			}
			echo '</div>';

		echo '<!-- General -->
		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_instant_indexing_general' ? ' active' : '').'" id="tab_siteseo_instant_indexing_general">';
		siteseo_instant_indexing_general_tab();
		echo '</div>

		<div class="siteseo-tab'.(!empty($current_tab) && $current_tab == 'tab_siteseo_instant_indexing_settings' ? ' active' : '').'" id="tab_siteseo_instant_indexing_settings">';
		siteseo_instant_indexing_settings_tab();
		echo'</div>'.
		wp_kses_post(siteseo_feature_save()).
		wp_kses(siteseo_submit_button(esc_html__('Save changes', 'siteseo'), false), [
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
		]).
		'</div>
	</form>';
}

function siteseo_save_instantindexing_settings(){

	check_admin_referer('siteseo_instant_indexing_nonce');

	if(!current_user_can('manage_options') || !is_admin()){
		return;
	}

	$instant_indexing_options = [];

	if(empty($_POST['siteseo_instant_indexing_option_name'])){
		return;
	}

	// general-tab
	if(isset($_POST['siteseo_instant_indexing_option_name']['engines']['google'])){
		$instant_indexing_options['engines']['google'] = !empty($_POST['siteseo_instant_indexing_option_name']['engines']['google']) ? sanitize_text_field(wp_unslash($_POST['siteseo_instant_indexing_option_name']['engines']['google'])): '';
	}	
	
	if(isset($_POST['siteseo_instant_indexing_option_name']['engines']['bing'])){
		$instant_indexing_options['engines']['bing'] = !empty($_POST['siteseo_instant_indexing_option_name']['engines']['bing']) ? sanitize_text_field(wp_unslash($_POST['siteseo_instant_indexing_option_name']['engines']['bing'])) : '';
	}
		//check
	if(!empty($_POST['siteseo_instant_indexing_option_name']['instant_indexing_google_action'])){
		$instant_indexing_options['instant_indexing_google_action'] = sanitize_text_field(wp_unslash($_POST['siteseo_instant_indexing_option_name']['instant_indexing_google_action']));
	}
	
	if(isset($_POST['siteseo_instant_indexing_option_name']['instant_indexing_manual_batch'])){
		$instant_indexing_options['instant_indexing_manual_batch'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_instant_indexing_option_name']['instant_indexing_manual_batch']));
	}
	//settings tab
	if(isset($_POST['siteseo_instant_indexing_option_name']['instant_indexing_google_api_key'])){
		$instant_indexing_options['instant_indexing_google_api_key'] = sanitize_textarea_field(wp_unslash($_POST['siteseo_instant_indexing_option_name']['instant_indexing_google_api_key']));
	}
	
	if(isset($_POST['siteseo_instant_indexing_option_name']['instant_indexing_bing_api_key'])){
		$instant_indexing_options['instant_indexing_bing_api_key'] = sanitize_text_field(wp_unslash($_POST['siteseo_instant_indexing_option_name']['instant_indexing_bing_api_key']));
	}
	
	if(isset($_POST['siteseo_instant_indexing_option_name']['instant_indexing_automate_submission'])){
		$instant_indexing_options['instant_indexing_automate_submission'] = !empty($_POST['siteseo_instant_indexing_option_name']['instant_indexing_automate_submission']) ? sanitize_text_field(wp_unslash($_POST['siteseo_instant_indexing_option_name']['instant_indexing_automate_submission'])) : '';
	}
	
	update_option('siteseo_instant_indexing_option_name', $instant_indexing_options);
	
}

siteseo_instant_indexing_page_html();