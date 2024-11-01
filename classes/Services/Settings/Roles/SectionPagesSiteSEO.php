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

namespace SiteSEO\Services\Settings\Roles;

if ( ! defined('ABSPATH')) {
	exit;
}

class SectionPagesSiteSEO {
	/**
	 * @since 4.6.0
	 *
	 * @param string $keySettings
	 *
	 * @return void
	 */
	public function render($keySettings) {
		$options = siteseo_get_service('AdvancedOption')->getOption();

		global $wp_roles;

		if ( ! isset($wp_roles)) {
			$wp_roles = new WP_Roles();
		}

		foreach ($wp_roles->get_names() as $key => $value) {
			if ('administrator' === $key) {
				continue;
			}
			$uniqueKey   = sprintf('%s_%s', $keySettings, $key);
			$nameKey	 = \sprintf('%s_%s', 'siteseo_advanced_security_metaboxe', $keySettings);
			$dataOptions = isset($options[$nameKey]) ? $options[$nameKey] : [];

			if ('titles-metas_editor' === $uniqueKey) { ?>
	<p class="description">
		<?php esc_html_e('Check a user role to authorized it to edit a specific SEO page.', 'siteseo'); ?>
	</p>
	<?php } ?>

	<p>
		<label
			for="siteseo_advanced_security_metaboxe_role_pages_<?php echo esc_attr($uniqueKey); ?>">
			<input type="checkbox"
				id="siteseo_advanced_security_metaboxe_role_pages_<?php echo esc_attr($uniqueKey); ?>"
				value="1"
				name="siteseo_advanced_option_name[<?php echo esc_attr($nameKey); ?>][<?php echo esc_attr($key); ?>]"
				<?php if (isset($dataOptions[$key])) {
				checked($dataOptions[$key], '1');
			} ?>
			/>
			<strong><?php echo esc_html($value); ?></strong> (<em><?php echo esc_html(translate_user_role($value,  'default')); ?></em>)
		</label>
	</p>
	<?php
		}
	}

	/**
	 * @since 4.6.0
	 *
	 * @param string $name
	 * @param array  $params
	 *
	 * @return void
	 */
	public function __call($name, $params) {
		$functionWithKey = explode('_', $name);
		if ( ! isset($functionWithKey[1])) {
			return;
		}

		$this->render($functionWithKey[1]);
	}

	/**
	 * @since 4.6.0
	 *
	 * @return void
	 */
	public function printSectionPages() {
		global $submenu;
		if ( ! isset($submenu['siteseo'])) {
			return;
		}
		$menus = $submenu['siteseo'];

		foreach ($menus as $key => $item) {
			$keyClean = $item[2];
			if (in_array($item[2], [
				'siteseo', // dashboard
				'siteseo-license',
				'edit.php?post_type=siteseo_schemas',
				'edit.php?post_type=siteseo_404',
				'edit.php?post_type=siteseo_bot', ])) {
				continue;
			}

			add_settings_field(
				'siteseo_advanced_security_metaboxe_' . $keyClean,
				$item[0],
				[$this, sprintf('render_%s', $keyClean)],
				'siteseo-settings-admin-advanced-security',
				'siteseo_setting_section_advanced_security_roles'
			);
		}

		do_action('siteseo_add_settings_field_advanced_security');
	}
}
