<?php

/***************************************************************
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

require_once (PATH_t3lib.'class.t3lib_page.php');
require_once (PATH_t3lib.'class.t3lib_tstemplate.php');
require_once (PATH_t3lib.'class.t3lib_tsparser_ext.php');

class ClearCacheHook implements \TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface {

	/**
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Add an entry to the CacheMenuItems array
	 *
	 * @param array $cacheActions Array of CacheMenuItems
	 * @param array $optionValues Array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
	 * @return void
	 */
	public function manipulateCacheActions(&$cacheActions, &$optionValues) {
		if ($GLOBALS['BE_USER']->isAdmin()) {
			$this->settings = $this->loadDefaultConfiguration();

			// only show Magento-Sync-Clear-Cache if configuration for Soap is available
			if (($this->settings['magentoApiUrl'] || $this->settings['magentoApiLocalFile']) && $this->settings['magentoApiUser'] && $this->settings['magentoApiPassword']) {
				$cacheActions[] = array(
					'id'    => 'magento_sync',
					'title' => "Magento-Sync-Clear-Cache",
					'href'  => $this->backPath . 'tce_db.php?vC=' . $GLOBALS['BE_USER']->veriCode() . '&cacheCmd=magento_sync&ajaxCall=1' . \TYPO3\CMS\Backend\Utility\BackendUtility::getUrlToken('tceAction'),
					'icon'  => \TYPO3\CMS\Backend\Utility\IconUtility::getSpriteIcon('actions-system-cache-clear-impact-medium')
				);
			}
		}
	}

	/**
	 * This method is called by the CacheMenuItem in the Backend
	 * @param \array $_params
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
	 */
	public function clearCachePostProc($_params, $dataHandler) {
		if ($_params['cacheCmd'] == 'magento_sync') {
			$this->clearCacheCmdAllPages();
		} elseif (intval($_params['cacheCmd'])) {
			$this->clearCacheCmdPage(intval($_params['cacheCmd']));
		}
	}

	private function clearCacheCmdAllPages() {
		if ($GLOBALS['BE_USER']->isAdmin()) {
			$this->settings = $this->loadDefaultConfiguration();

			if ($this->settings['magentoApiLocalFile']) {
				if(is_file(PATH_site . $this->settings['magentoApiLocalFile'])){
					$proxy = new Tx_MagentoSync_Service_MagentoService(array(), PATH_site . $this->settings['magentoApiLocalFile']);
				}
			} elseif ($this->settings['magentoApiUrl']) {
				$proxy = new Tx_MagentoSync_Service_MagentoService(array(), $this->settings['magentoApiUrl']);
			}

			if ($proxy) {
				$sessionId = $proxy->login($this->settings['magentoApiUser'], $this->settings['magentoApiPassword']);
			}

			if ($sessionId) {
				$info = $proxy->typo3connectorCleancache($sessionId);
			}
		}
	}

	private function clearCacheCmdPage($pid) {
		if (($GLOBALS['BE_USER']->isAdmin()) || ($this->settings['beUserCanClearMagentoPageCache'])) {
			$this->settings = $this->loadDefaultConfiguration();

			$this->settings = array_merge($this->settings, $this->getSetting( $this->loadTypoScript($pid) ));

			if ($this->settings['magentoApiLocalFile']) {
				if(is_file(PATH_site . $this->settings['magentoApiLocalFile'])){
					$proxy = new Tx_MagentoSync_Service_MagentoService(array(), PATH_site . $this->settings['magentoApiLocalFile']);
				}
			} elseif ($this->settings['magentoApiUrl']) {
				$proxy = new Tx_MagentoSync_Service_MagentoService(array(), $this->settings['magentoApiUrl']);
			}

			if ($proxy) {
				$sessionId = $proxy->login($this->settings['magentoApiUser'], $this->settings['magentoApiPassword']);
			}

			if ($sessionId) {
				$info = $proxy->typo3connectorCleanpagecache($sessionId, $pid);
			}
		}
	}

	/**
	 *  get Settings from localconf
	 */
	function loadDefaultConfiguration() {
		$extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['magento_sync']);

		return $extConfig;
	}

	function loadTypoScript($pageUid) {
		$sysPageObj = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sysPageObj->getRootLine($pageUid);
		$TSObj = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();

		return $TSObj->setup['plugin.']['tx_magentosync.'];
	}

	function getSetting($tsarray) {
		return $tsarray['settings.'];
	}

}
?>