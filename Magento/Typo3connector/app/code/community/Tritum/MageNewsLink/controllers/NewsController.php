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
	 * tx_news connector
	 */
	public function showAction() {
		$pid = intval($this->getRequest()->getParam('pid'));
		$id = intval($this->getRequest()->getParam('id'));

		$content = '';
		if ( empty($pid) || empty($id) ) {
			$content = 'Can\'t find this news.';
		} else {
			$news_url = 'http://new17typo3.americandj.eu/index.php?id=' . $pid . '&tx_news_pi1[news]=' . $id . '&tx_news_pi1[controller]=News&tx_news_pi1[action]=detail';
			$content = Mage::helper('typo3connector')->getNewsContentUrl($news_url);
		}

		$this->loadLayout();
		$this->getLayout()->getBlock('typo3connector')->assign('content', $content);
		$this->renderLayout();
	}

}