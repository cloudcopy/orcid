<?php
/**
 *
 * Orcid - based on user_orcid from Lars Naesbye Christensen
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Lars Naesbye Christensen, DeIC
 * @author Maxence Lange <maxence@pontapreta.net>
 * @copyright 2017
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Orcid\Service;

use OCP\IConfig;
use OCP\Util;

class ConfigService {

	const ORCID_CLIENT_APPID = 'orcidAppID';
	const ORCID_CLIENT_SECRET = 'orcidSecret';

	const ORCID_USER_ORCID = 'user_orcid';
	const ORCID_USER_TOKEN = 'user_token';

	private $defaults = [
		'configured'              => '0',
		self::ORCID_CLIENT_APPID  => '',
		self::ORCID_CLIENT_SECRET => ''
	];

	/** @var string */
	private $appName;

	/** @var IConfig */
	private $config;

	/** @var string */
	private $userId;

	/** @var MiscService */
	private $miscService;

	/**
	 * ConfigService constructor.
	 *
	 * @param string $appName
	 * @param IConfig $config
	 * @param string $userId
	 * @param MiscService $miscService
	 */
	public function __construct($appName, IConfig $config, $userId, MiscService $miscService) {
		$this->appName = $appName;
		$this->config = $config;
		$this->userId = $userId;
		$this->miscService = $miscService;
	}

	/**
	 * Get a value by key
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function getAppValue($key) {
		$defaultValue = null;
		if (array_key_exists($key, $this->defaults)) {
			$defaultValue = $this->defaults[$key];
		}

		return $this->config->getAppValue($this->appName, $key, $defaultValue);
	}

	/**
	 * Set a value by key
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function setAppValue($key, $value) {
		$this->config->setAppValue($this->appName, $key, $value);
	}

	/**
	 * remove a key
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function deleteAppValue($key) {
		return $this->config->deleteAppValue($this->appName, $key);
	}

	/**
	 * Get a user value by key
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function getUserValue($key) {
		return $this->config->getUserValue($this->userId, $this->appName, $key);
	}

	/**
	 * Set a user value by key
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return string
	 */
	public function setUserValue($key, $value) {
		return $this->config->setUserValue($this->userId, $this->appName, $key, $value);
	}


	/**
	 * Get a user value by key and user
	 *
	 * @param string $userId
	 * @param string $key
	 *
	 * @return string
	 */
	public function getValueForUser($userId, $key) {
		return $this->config->getUserValue($userId, $this->appName, $key);
	}


	/**
	 * return the cloud version.
	 * if $complete is true, return a string x.y.z
	 *
	 * @param boolean $complete
	 *
	 * @return string|integer
	 */
	public function getCloudVersion($complete = false) {
		$ver = Util::getVersion();

		if ($complete) {
			return implode('.', $ver);
		}

		return $ver[0];
	}
}
