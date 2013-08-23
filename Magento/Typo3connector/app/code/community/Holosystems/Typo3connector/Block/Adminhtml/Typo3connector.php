<?php

class Holosystems_Typo3connector_Block_Adminhtml_Typo3connector extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_typo3connector';
        $this->_blockGroup = 'typo3connector';
        $this->_headerText = Mage::helper('typo3connector')->__('Content Manager');
        $this->_addButtonLabel = Mage::helper('typo3connector')->__('Add Content');
        parent::__construct();
    }

}