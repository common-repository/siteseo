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

namespace SiteSEO\ManualHooks\Thirds\WooCommerce;

use SiteSEO\Thirds\WooCommerce\WooCommerceAnalyticsService;

if ( ! defined('ABSPATH')) {
	exit;
}

class WooCommerceAnalytics {

	/**
	 * @var WooCommerceAnalyticsService
	 */
	protected $wooCommerceAnalytics;

	public function __construct() {
		/**
		 * @var WooCommerceAnalyticsService
		 */
		$this->wooCommerceAnalytics = siteseo_get_service('WooCommerceAnalyticsService');
	}

	/**
	 * @since 4.4.0
	 *
	 * @return void
	 */
	public function hooks() {
		if ( ! siteseo_get_service('WooCommerceActivate')->isActive()) {
			return;
		}

		$addToCartOption = siteseo_get_service('GoogleAnalyticsOption')->getAddToCart();

		if ($addToCartOption) {
			// Listing page
			add_action('woocommerce_after_shop_loop_item', [$this, 'addToCart']);

			//Single
			add_action('woocommerce_after_add_to_cart_button', [$this, 'singleAddToCart']);
		}

		$removeFromCartOption = siteseo_get_service('GoogleAnalyticsOption')->getRemoveFromCart();

		if ($removeFromCartOption) {
			// Cart page
			add_filter('woocommerce_cart_item_remove_link', [$this, 'removeFromCart'], 10, 2);
		}

		if ($addToCartOption && $removeFromCartOption) {
			// Before update
			add_action('woocommerce_cart_actions', [$this, 'updateCartOrCheckout']);
		}
	}

	/**
	 * @since 4.4.0
	 *
	 * @return void
	 */
	public function addToCart() {
		if (apply_filters('siteseo_fallback_woocommerce_analytics', false)) {
			return;
		}
		$this->wooCommerceAnalytics->addToCart();
	}

	/**
	 * @since 4.4.0
	 *
	 * @return void
	 */
	public function singleAddToCart() {
		if (apply_filters('siteseo_fallback_woocommerce_analytics', false)) {
			return;
		}
		$this->wooCommerceAnalytics->singleAddToCart();
	}

	/**
	 * @since 4.4.0
	 *
	 * @param string $sprintf
	 * @param string $cartKey
	 *
	 * @return void
	 */
	public function removeFromCart($sprintf, $cartKey) {
		if (apply_filters('siteseo_fallback_woocommerce_analytics', false)) {
			return;
		}

		return $this->wooCommerceAnalytics->removeFromCart($sprintf, $cartKey);
	}

	/**
	 * @since 4.4.0
	 *
	 * @param string $sprintf
	 * @param string $cartKey
	 *
	 * @return void
	 */
	public function updateCartOrCheckout() {
		if (apply_filters('siteseo_fallback_woocommerce_analytics', false)) {
			return;
		}
		$this->wooCommerceAnalytics->updateCartOrCheckout();
	}
}
