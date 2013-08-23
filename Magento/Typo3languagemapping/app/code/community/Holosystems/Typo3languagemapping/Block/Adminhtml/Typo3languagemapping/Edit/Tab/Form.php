<?php

class Holosystems_Typo3languagemapping_Block_Adminhtml_Typo3languagemapping_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('typo3languagemapping_form', array('legend' => Mage::helper('typo3languagemapping')->__('Mapping information')));

           
        $field = $fieldset->addField('store_view_id', 'select', array(
            'name' => 'store_view_id',
            'label' => Mage::helper('catalog')->__('Store'),
            'title' => Mage::helper('catalog')->__('Store'),
            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(true, false),
            'required' => true,
        ));
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);

        $fieldset->addField('language_id', 'text', array(
            'label' => Mage::helper('typo3languagemapping')->__('TYPO3 Language ID'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'language_id',
        ));
      

        if (Mage::getSingleton('adminhtml/session')->getTypo3languagemappingData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getTypo3languagemappingData());
            Mage::getSingleton('adminhtml/session')->setTypo3languagemappingData(null);
        } elseif (Mage::registry('typo3languagemapping_data')) {
            $form->setValues(Mage::registry('typo3languagemapping_data')->getData());
        }
        return parent::_prepareForm();
    }

}