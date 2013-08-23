<?php

class Holosystems_Typo3connector_Block_Adminhtml_Typo3connector_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'typo3connector';
        $this->_controller = 'adminhtml_typo3connector';
        
        $this->_updateButton('save', 'label', Mage::helper('typo3connector')->__('Save Content'));
        $this->_updateButton('delete', 'label', Mage::helper('typo3connector')->__('Delete Content'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('typo3connector_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'typo3connector_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'typo3connector_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('typo3connector_data') && Mage::registry('typo3connector_data')->getId() ) {
            return Mage::helper('typo3connector')->__("Edit Content '%s'", $this->htmlEscape(Mage::registry('typo3connector_data')->getTitle()));
        } else {
            return Mage::helper('typo3connector')->__('Add Content');
        }
    }
}