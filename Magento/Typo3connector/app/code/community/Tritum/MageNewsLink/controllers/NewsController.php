<?php
/**
 * Tritum_MageNewsLink extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category	Tritum
 * @package		Tritum_MageNewsLink
 * @copyright	Copyright © 2013 TRITUM GmbH ( http://www.tritum.de )
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Link Block
 *
 * @category	Tritum
 * @package		Tritum_MageNewsLink
 * @subpackage	Block
 * @author		Daniel Lorenz <daniel.lorenz@mail.tritum.de>
 * @since		0.1.0
 */

class Tritum_MageNewsLink_NewsController extends Mage_Core_Controller_Front_Action {

	const ACTION_LOGOUT = 'logout';
	const ACTION_LOGIN = 'login';

	/**
	 * @return Holosystems_Typo3connector_Helper_Data
	 */
	protected function getTypo3ConnectorHelper() {
		return Mage::helper('typo3connector');
	}

	/**
	 * tx_news connector
	 */
	public function showAction() {
		$pid = intval($this->getRequest()->getParam('pid'));
		$id = intval($this->getRequest()->getParam('id'));

		if ( empty($pid) || empty($id) ) {
			Mage::exception('Tritum_MageNewsLink', 'Can\'t find this news.');
		}

		$this->loadLayout();
		$block = $this->getLayout()->getBlock('typo3connector');

		$block->setT3Controller('News');
		$block->setT3Action('detail');
		$block->setT3Id($id);
		$block->setT3PageId($pid);

		if ($cacheKey = $block->getCacheKey()) {
			$cacheData = Mage::app()->loadCache($cacheKey);
			if (!$cacheData) {
				$this->setMetaFromContent($block);
			}
		}

		$this->setMetaFromCache($block->getControllerInfo());

		$this->renderLayout();
	}

	protected function setMetaFromContent($block) {
		$params = array(
			'tx_news_pi1[controller]=News',
			'tx_news_pi1[action]=detail',
			'tx_news_pi1[news]=' . $block->getT3Id()
		);

		$content = $this->getTypo3ConnectorHelper()->getPageContent($block->getT3PageId(), $params);

		$controllerInfo = $block->getControllerInfo();
		$this->getTypo3ConnectorHelper()->getMetaFromContent('mage-title', $controllerInfo, $content);
		$this->getTypo3ConnectorHelper()->getMetaFromContent('mage-keywords', $controllerInfo, $content);
		$this->getTypo3ConnectorHelper()->getMetaFromContent('mage-description', $controllerInfo, $content);
	}

	protected function setMetaFromCache($controllerInfo) {
		$head = $this->getLayout()->getBlock('head');

		$cache = Mage::app()->getCache();
		$title = $cache->load($controllerInfo . '#' . 'mage-title');
		if ($title) {
			$head->setTitle($title);
		}
		$keywords = $cache->load($controllerInfo . '#' . 'mage-keywords');
		if ($keywords) {
			$head->setKeywords($keywords);
		}
		$description = $cache->load($controllerInfo . '#' . 'mage-description');
		if ($description) {
			$head->setDescription($description);
		}
	}

}