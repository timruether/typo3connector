<?php
/**
 * Holosystems_Typo3connector extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category	Holosystems
 * @package		Holosystems_Typo3connector
 * @copyright	Copyright © 2013 holosystems ( http://www.holosystems.de/ )
 * @license		http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Webservice connector model
 *
 * @category    Holosystems
 * @package     Holosystems_Typo3connector
 * @subpackage  Model
 * @author      Sebastian Wagner <sebastian.wagner@tritum.de>
 * @since       0.1.0
 */
class Holosystems_Typo3connector_Model_Typo3connector_Api
	extends Mage_Api_Model_Resource_Abstract
{

	/**
	 * @return string
	 */
	public function cleancache() {
		Mage::log('cleancache: Clearing Cache', NULL, 'success.log', TRUE);
		Mage::app()->cleanCache(Holosystems_Typo3connector_Block_Typo3connector::CACHE_TAG);
		return 'cleancache';
	}

	/**
	 * @param int $pid
	 * @return Holosystems_Typo3connector_Model_Mysql4_Typo3connector_Collection
	 */
	protected function getConnectorsByPid($pid = 0) {
		$collection = Mage::getModel('typo3connector/typo3connector')->getCollection();
		$collection->addFieldToFilter('typo3_pages_id', $pid);
		$collection->addFieldToSelect('identifier');
		return $collection;
	}

	/**
	 * @param int $identifier
	 * @return string
	 */
	public function cleanpagecache($identifier = 0) {
		//identifier is a missleading name
		$pid = $identifier;
		if($pid > 0) {
			Mage::log('cleanpagecache: Clearing Page Cache for PID ' . $pid, NULL, 'success.log', TRUE);
			$connectors = $this->getConnectorsByPid($pid);
			foreach($connectors as $connector) {
				Mage::log('cleanpagecache: Clearing identifier ' . $connector->getIdentifier() . ' from Page Cache for PID ' . $pid, NULL, 'success.log', TRUE);
				Mage::app()->cleanCache(Holosystems_Typo3connector_Block_Typo3connector::CACHE_TAG . '_' . $connector->getIdentifier());
			}
		} else {
			Mage::log('cleanpagecache: Missing PID', Zend_Log::NOTICE, 'success.log', TRUE);
		}
		return 'pid: ' . $pid;
	}

}
