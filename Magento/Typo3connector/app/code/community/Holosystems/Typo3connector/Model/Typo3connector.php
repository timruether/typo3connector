<?php

class Holosystems_Typo3connector_Model_Typo3connector extends Mage_Core_Model_Abstract {

    public function _construct() {
	parent::_construct();
	$this->_init('typo3connector/typo3connector');
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