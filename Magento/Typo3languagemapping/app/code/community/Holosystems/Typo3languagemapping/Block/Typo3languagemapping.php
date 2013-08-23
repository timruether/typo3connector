<?php
class Holosystems_Typo3languagemapping_Block_Typo3languagemapping extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getTypo3languagemapping()     
     { 
        if (!$this->hasData('typo3languagemapping')) {
            $this->setData('typo3languagemapping', Mage::registry('typo3languagemapping'));
        }
        return $this->getData('typo3languagemapping');
        
    }
}