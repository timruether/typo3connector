<?php

class Holosystems_Typo3connector_Model_Mysql4_Typo3connector extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the typo3connector_id refers to the key field in your database table.
        $this->_init('typo3connector/typo3connector', 'typo3connector_id');
    }
}