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
class Tritum_MageNewsLink_Block_Link
	extends Mage_Core_Block_Template
	implements Mage_Widget_Block_Interface
	//extends Mage_Cms_Block_Widget_Page_Link //only when manually triggering setTemplate
{
	/**
	 * A model to serialize attributes
	 * @var Varien_Object
	 */
	protected $_serializer = null;

	/**
	 * Initialization
	 */
	protected function _construct()
	{
		$this->_serializer = new Varien_Object();
		parent::_construct();
	}

	/**
	 * @return string
	 */
	protected function getNewsUrl() {
		$href = '';

		$detail_pid = $this->getData('detail_pid');
		$news_id = $this->getData('news_id');
		if( empty( $detail_pid ) || empty( $news_id ) ){
			return $href;
		}

		if($this->hasStoreId()) {
			$store = Mage::app()->getStore($this->getStoreId());
		} else {
			$store = Mage::app()->getStore();
		}


		$href = Mage::getUrl('tritum_magenewslink/news/show', array('pid' => $detail_pid, 'id' => $news_id));

		/* @var $urlRewriteResource Mage_Core_Model_Mysql4_Url_Rewrite */
		$urlRewriteResource = Mage::getResourceSingleton('core/url_rewrite');
		/*
		$href = $urlRewriteResource->getRequestPathByIdPath('product/' . $productId, $store);
		if (!$href) {
			return false;
		}
		$href = $store->getUrl('', array('_direct' => $href));
		*/

		return $href;
	}
}