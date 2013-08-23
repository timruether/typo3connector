<?php
class Holosystems_Typo3languagemapping_Block_Adminhtml_Typo3languagemapping extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_typo3languagemapping';
    $this->_blockGroup = 'typo3languagemapping';
    $this->_headerText = Mage::helper('typo3languagemapping')->__('Language Mapping Manager');
    $this->_addButtonLabel = Mage::helper('typo3languagemapping')->__('Add Mapping');
    parent::__construct();
  }
}