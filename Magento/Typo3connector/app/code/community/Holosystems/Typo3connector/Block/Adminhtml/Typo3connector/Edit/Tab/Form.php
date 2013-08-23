<?php

class Holosystems_Typo3connector_Block_Adminhtml_Typo3connector_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('typo3connector_form', array('legend'=>Mage::helper('typo3connector')->__('Content information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('typo3connector')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('identifier', 'text', array(
          'label'     => Mage::helper('typo3connector')->__('Code'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'identifier',
      ));
      
      $fieldset->addField('typo3_tt_content_ids', 'text', array(
          'label'     => Mage::helper('typo3connector')->__('TYPO3 Content Element ID\'s'),
          'comment'   => 'Add Id\'s commaseparated',
          'required'  => false,
          'name'      => 'typo3_tt_content_ids',
	  ));
      
      $fieldset->addField('typo3_pages_id', 'text', array(
          'label'     => Mage::helper('typo3connector')->__('TYPO3 Page ID'),
          'comment'   => 'Just add one page ID',
          'required'  => false,
          'name'      => 'typo3_pages_id',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('typo3connector')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('typo3connector')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('typo3connector')->__('Disabled'),
              ),
          ),
      ));

     
      if ( Mage::getSingleton('adminhtml/session')->getTypo3connectorData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getTypo3connectorData());
          Mage::getSingleton('adminhtml/session')->setTypo3connectorData(null);
      } elseif ( Mage::registry('typo3connector_data') ) {
          $form->setValues(Mage::registry('typo3connector_data')->getData());
      }
      return parent::_prepareForm();
  }
}