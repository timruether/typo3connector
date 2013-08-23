<?php

class Holosystems_Typo3languagemapping_Block_Adminhtml_Typo3languagemapping_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'typo3languagemapping';
        $this->_controller = 'adminhtml_typo3languagemapping';
        
        $this->_updateButton('save', 'label', Mage::helper('typo3languagemapping')->__('Save Mapping'));
        //$this->_updateButton('delete', 'label', Mage::helper('typo3languagemapping')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('typo3languagemapping_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'typo3languagemapping_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'typo3languagemapping_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('typo3languagemapping_data') && Mage::registry('typo3languagemapping_data')->getId() ) {
            return Mage::helper('typo3languagemapping')->__("Edit Mapping '%s'", $this->htmlEscape(Mage::registry('typo3languagemapping_data')->getId()));
        } else {
            return Mage::helper('typo3languagemapping')->__('Add Mapping');
        }
    }
}