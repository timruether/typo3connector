<?php

class Holosystems_Typo3connector_Model_Mysql4_Typo3connector_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('typo3connector/typo3connector');
    }
}