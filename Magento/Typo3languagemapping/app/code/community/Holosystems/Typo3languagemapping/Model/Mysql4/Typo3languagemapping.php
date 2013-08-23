<?php

class Holosystems_Typo3languagemapping_Model_Mysql4_Typo3languagemapping extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        // Note that the typo3languagemapping_id refers to the key field in your database table.
        $this->_init('typo3languagemapping/typo3languagemapping', 'typo3languagemapping_id');
    }
}