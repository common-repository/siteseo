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

namespace SiteSEO\Core\Container;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

class ContainerSiteseo implements ManageContainer {
	/**
	 * List actions WordPress.
	 *
	 * @var array
	 */
	protected $actions = [];

	/**
	 * List class services.
	 *
	 * @var array
	 */
	protected $services = [];

	/**
	 * Set actions.
	 *
	 * @param array $actions
	 */
	public function setActions($actions) {
		$this->actions = $actions;

		return $this;
	}

	public function setAction($action) {
		$this->actions[$action] = $action;

		return $this;
	}

	/**
	 * Get services.
	 *
	 * @return array
	 */
	public function getActions() {
		return $this->actions;
	}

	/**
	 * @param string $name
	 *
	 * @return object
	 */
	public function getAction($name) {
		try {
			if ( ! array_key_exists($name, $this->actions)) {
				return null;
				// @TODO : Throw exception
			}

			if (is_string($this->actions[$name])) {
				$this->actions[$name] = new $this->actions[$name]();
			}

			return $this->actions[$name];
		} catch (\Exception $th) {
			return null;
		}
	}

	/**
	 * Set services.
	 *
	 * @param array $services
	 */
	public function setServices($services) {
		foreach ($services as $service) {
			$this->setService($service);
		}

		return $this;
	}

	/**
	 * Set a service.
	 *
	 * @param string $service
	 */
	public function setService($service) {
		$name = explode('\\', $service);
		end($name);
		$key = key($name);

		if (defined($service . '::NAME_SERVICE')) {
			$name = $service::NAME_SERVICE;
		} else {
			$name = $name[$key];
		}

		$this->services[$name] = $service;

		return $this;
	}

	/**
	 * Get services.
	 *
	 * @return array
	 */
	public function getServices() {
		return $this->services;
	}

	public function getServiceByName($name) {
		if ( ! isset($this->services[$name])) {
			return null;
		}

		try {
			if (is_string($this->services[$name]) && class_exists($this->services[$name])) {
				$this->services[$name] = new $this->services[$name]();
			}
		} catch (\Exception $e) {
			$this->services[$name] = null;
		}

		return $this->services[$name];
	}
}
