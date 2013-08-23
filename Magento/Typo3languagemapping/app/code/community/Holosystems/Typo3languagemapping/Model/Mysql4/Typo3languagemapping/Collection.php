<?php

class Holosystems_Typo3languagemapping_Model_Mysql4_Typo3languagemapping_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('typo3languagemapping/typo3languagemapping');
    }
}