<?php

class Holosystems_Typo3languagemapping_Block_Adminhtml_Typo3languagemapping_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('typo3languagemapping_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('typo3languagemapping')->__('Mapping Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('typo3languagemapping')->__('Mapping'),
          'title'     => Mage::helper('typo3languagemapping')->__('Mapping Information'),
          'content'   => $this->getLayout()->createBlock('typo3languagemapping/adminhtml_typo3languagemapping_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}