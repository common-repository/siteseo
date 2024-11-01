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

namespace SiteSEO\Core;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Core\Container\ContainerSiteseo;
use SiteSEO\Core\Hooks\ActivationHook;
use SiteSEO\Core\Hooks\DeactivationHook;
use SiteSEO\Core\Hooks\ExecuteHooks;
use SiteSEO\Core\Hooks\ExecuteHooksBackend;
use SiteSEO\Core\Hooks\ExecuteHooksFrontend;

abstract class Kernel {
	protected static $container = null;

	protected static $data = ['slug' => null, 'main_file' => null, 'file' => null, 'root' => null];

	public static function setContainer(ManageContainer $container) {
		self::$container = self::getDefaultContainer();
	}

	protected static function getDefaultContainer() {
		return new ContainerSiteseo();
	}

	public static function getContainer() {
		if (null === self::$container) {
			self::$container = self::getDefaultContainer();
		}

		return self::$container;
	}

	public static function handleHooksPlugin() {
		switch (current_filter()) {
			case 'plugins_loaded':
				foreach (self::getContainer()->getActions() as $key => $class) {
					try {
						if ( ! class_exists($class)) {
							continue;
						}

						$class = new $class();
						switch (true) {
							case $class instanceof ExecuteHooksBackend:
								if (is_admin()) {
									$class->hooks();
								}
								break;

							case $class instanceof ExecuteHooksFrontend:
								if ( ! is_admin()) {
									$class->hooks();
								}
								break;

							case $class instanceof ExecuteHooks:
								$class->hooks();
								break;
						}
					} catch (\Exception $e) {
					}
				}
				break;
			case 'activate_' . self::$data['slug'] . '/' . self::$data['main_file'] . '.php':
				foreach (self::getContainer()->getActions() as $key => $class) {
					try {
						if ( ! class_exists($class)) {
							continue;
						}
						$class = new $class();

						if ($class instanceof ActivationHook) {
							$class->activate();
						}
					} catch (\Exception $e) {
					}
				}
				break;
			case 'deactivate_' . self::$data['slug'] . '/' . self::$data['main_file'] . '.php':
				foreach (self::getContainer()->getActions() as $key => $class) {
					try {
						if ( ! class_exists($class)) {
							continue;
						}
						$class = new $class();
						if ($class instanceof DeactivationHook) {
							$class->deactivate();
						}
					} catch (\Exception $e) {
					}
				}
				break;
		}
	}

	/**
	 * @static
	 *
	 * @return void
	 */
	public static function buildContainer() {
		self::buildClasses(SITESEO_CLASSES.'/Services', 'services', 'Services\\');
		self::buildClasses(SITESEO_CLASSES.'/Thirds', 'services', 'Thirds\\');
		self::buildClasses(SITESEO_CLASSES.'/Actions', 'actions', 'Actions\\');
	}

	/**
	 * @static
	 *
	 * @param string $path
	 * @param string $type
	 * @param string $namespace
	 *
	 * @return void
	 */
	public static function buildClasses($path, $type, $namespace = '') {
		try {
			$files = array_diff(scandir($path), ['..', '.']);
			foreach ($files as $filename) {
				$pathCheck = $path . '/' . $filename;

				if (is_dir($pathCheck)) {
					self::buildClasses($pathCheck, $type, $namespace . $filename . '\\');
					continue;
				}

				$pathinfo = pathinfo($filename);
				if (isset($pathinfo['extension']) && 'php' !== $pathinfo['extension']) {
					continue;
				}

				$data = '\\SiteSEO\\' . $namespace . str_replace('.php', '', $filename);

				switch ($type) {
					case 'services':
						self::getContainer()->setService($data);
						break;
					case 'actions':
						self::getContainer()->setAction($data);
						break;
				}
			}
		} catch (\Exception $e) {
		}
	}

	public static function execute($data) {
		self::$data = array_merge(self::$data, $data);

		self::buildContainer();

		add_action('plugins_loaded', [__CLASS__, 'handleHooksPlugin']);
		register_activation_hook($data['file'], [__CLASS__, 'handleHooksPlugin']);
		register_deactivation_hook($data['file'], [__CLASS__, 'handleHooksPlugin']);
	}
}
