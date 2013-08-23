<?php

class Holosystems_Typo3connector_Block_Adminhtml_Typo3connector_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('typo3connector_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('typo3connector')->__('Content Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('typo3connector')->__('Content Information'),
          'title'     => Mage::helper('typo3connector')->__('Content Information'),
          'content'   => $this->getLayout()->createBlock('typo3connector/adminhtml_typo3connector_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}