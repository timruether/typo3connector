<?php

class Holosystems_Typo3connector_Model_Typo3connector extends Mage_Core_Model_Abstract {

    public function _construct() {
	parent::_construct();
	$this->_init('typo3connector/typo3connector');
    }

	/**
	 * Clear cache for current identifier
	 *
	 * @return Holosystems_Typo3connector_Model_Typo3connector
	 */
	protected function cleanCache() {
		Mage::app()->cleanCache(Holosystems_Typo3connector_Block_Typo3connector::CACHE_TAG . '_' . $this->getIdentifier());
		return $this;
	}

	/**
	 * Processing object before save data
	 *
	 * @return Holosystems_Typo3connector_Model_Typo3connector
	 */
	protected function _beforeSave() {
		parent::_beforeSave();
		$this->cleanCache();
		return $this;
	}

    /**
     * encrypt the data to be send, right now we only base64 encode it
     * @todo implement encryption algorythm
     */
    private function _encryptData($data) {
	$return = base64_encode(serialize($data));
	return $return;
    }

}