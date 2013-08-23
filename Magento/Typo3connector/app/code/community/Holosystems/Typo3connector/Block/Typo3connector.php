<?php

class Holosystems_Typo3connector_Block_Typo3connector extends Mage_Core_Block_Template {

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getTypo3connector() {
        if (!$this->hasData('typo3connector')) {
            $this->setData('typo3connector', Mage::registry('typo3connector'));
        }
        return $this->getData('typo3connector');
    }

}