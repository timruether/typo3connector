<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Daniel Lorenz <info@extco.de>, Tritum GmbH
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
 *
 * @package wt_cart_event
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_MagentoSync_ViewHelpers_LinkViewHelper
	extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'a';

	/**
	 * @var Tx_News_Service_SettingsService
	 */
	protected $pluginSettingsService;

	/**
	 * @var array
	 */
	protected $detailPidDeterminationCallbacks = array(
		'flexform' => 'getDetailPidFromFlexform',
		'categories' => 'getDetailPidFromCategories',
		'default' => 'getDetailPidFromDefaultDetailPid',
	);

	/**
	 * @var Tx_News_Service_SettingsService $pluginSettingsService
	 * @return void
	 */
	public function injectSettingsService(Tx_News_Service_SettingsService $pluginSettingsService) {
		$this->pluginSettingsService = $pluginSettingsService;
	}

	/**
	 * Initialize arguments of this view helper
	 *
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerUniversalTagAttributes();
	}

	/**
	 * Render mage link to news item or internal/external pages
	 *
	 * @param Tx_News_Domain_Model_News $newsItem current news object
	 * @param array $settings
	 * @param boolean $uriOnly return only the url without the a-tag
	 * @param array $configuration optional typolink configuration
	 * @return string link
	 */
	public function render(Tx_News_Domain_Model_News $newsItem, array $settings = array(), $uriOnly = FALSE, $configuration = array()) {
		$tsSettings = $this->pluginSettingsService->getSettings();

		/** @var $cObj tslib_cObj */
		$cObj = t3lib_div::makeInstance('tslib_cObj');

		$newsType = (int)$newsItem->getType();
		switch ($newsType) {
			// internal news
			case 1:
				$detailPid = $newsItem->getInternalurl();
				break;
			// external news
			case 2:
				$detailPid = $newsItem->getExternalurl();
				break;
			// normal news record
			default:
				$detailPid = 0;
				$detailPidDeterminationMethods = t3lib_div::trimExplode(',', $settings['detailPidDetermination'], TRUE);

				// if TS is not set, prefer flexform setting
				if (!isset($settings['detailPidDetermination'])) {
					$detailPidDeterminationMethods[] = 'flexform';
				}

				foreach ($detailPidDeterminationMethods as $determinationMethod) {
					if ($callback = $this->detailPidDeterminationCallbacks[$determinationMethod]) {
						if ($detailPid = call_user_func(array($this, $callback), $settings, $newsItem)) {
							break;
						}
					}
				}

				if (!$detailPid) {
					$detailPid = $GLOBALS['TSFE']->id;
				}

				$configuration['useCacheHash'] = 1;

		}

		$configuration['returnLast'] = 'url';
		if (isset($tsSettings['link']['typesOpeningInNewWindow'])) {
			if (t3lib_div::inList($tsSettings['link']['typesOpeningInNewWindow'], $newsType)) {
				$this->tag->addAttribute('target', '_blank');
			}
		}

		$configuration['parameter'] = 'http://www.mage.local/';
		$typolink = $cObj->typolink('', $configuration);
		$mage_link = $this->magenewslink($detailPid, $newsItem->getUid());
		$typolink = str_replace('http://www.mage.local/', $mage_link, $typolink);

		if ($uriOnly) {
			return $typolink;
		}

		$this->tag->addAttribute('href', $typolink);
		$this->tag->setContent($this->renderChildren());
		return $this->tag->render();
	}

	/**
	 * Process the typolink
	 *
	 * @param $detailPid
	 * @param $news_id
	 *
	 * @return string the rendered link
	 */
	function magenewslink($detailPid, $news_id) {
		return '{{widget type=\'tritum_magenewslink/link\' template=\'tritum_magenewslink/link.phtml\' detail_pid=\'' . $detailPid . '\' news_id=\'' . $news_id . '\'}}';
	}

	/**
	 * Gets detailPid from categories of the given news item. First will be return.
	 *
	 * @param  array $settings
	 * @param  Tx_News_Domain_Model_News $newsItem
	 * @return int
	 */
	protected function getDetailPidFromCategories($settings, $newsItem) {
		$detailPid = 0;
		foreach ($newsItem->getCategories() as $category) {
			if ($detailPid = (int)$category->getSinglePid()) {
				break;
			}
		}
		return $detailPid;
	}

	/**
	 * Gets detailPid from defaultDetailPid setting
	 *
	 * @param  array $settings
	 * @param  Tx_News_Domain_Model_News $newsItem
	 * @return int
	 */
	protected function getDetailPidFromDefaultDetailPid($settings, $newsItem) {
		return (int)$settings['defaultDetailPid'];
	}

	/**
	 * Gets detailPid from flexform of current plugin.
	 *
	 * @param  array $settings
	 * @param  Tx_News_Domain_Model_News $newsItem
	 * @return int
	 */
	protected function getDetailPidFromFlexform($settings, $newsItem) {
		return (int)$settings['detailPid'];
	}
}

?>