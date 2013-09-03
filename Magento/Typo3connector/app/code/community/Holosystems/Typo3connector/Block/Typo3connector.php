<?php

class Holosystems_Typo3connector_Block_Typo3connector extends Mage_Core_Block_Template {

	/**
	 * String
	 */
	const CACHE_TAG = 'holo_t3conblock';

	/**
	 * Additional Conditions to match for a CacheHit to happen
	 *
	 * @return array
	 */
	protected function _getCacheKeyInfo()
	{
		return array(
			//prevent multiple uses of block to be cached the same way
			$this->getNameInLayout(),
			//build cache in per-Identifier-base
			$this->getIdentifier(),
		);
	}

	/**
	 * @return array
	 */
	public function getCacheKeyInfo()
	{
		//reuse parent key-info eg. storeCode or template-file
		$info = parent::getCacheKeyInfo();
		$info['typo3connector'] = implode('#', $this->_getCacheKeyInfo());
		return $info;
	}

	/**
	 * Additional Events that invalidate the cached Block
	 * @return array
	 */
	protected function _getCacheTags()
	{
		return array(
			self::CACHE_TAG,
			//invalidate cache when Identifier changes
			self::CACHE_TAG . "_" . $this->getIdentifier(),
		);
	}

	/**
	 * @return array
	 */
	public function getCacheTags()
	{
		//reuse parent tags
		$tags = parent::getCacheTags();
		$tags = array_merge($tags, $this->_getCacheTags());
		return $tags;
	}

	/**
	 * @return int
	 */
	public function getCacheLifetime()
	{
		//parent::getCacheLifetime();
		//use Magento default cache Lifetime
		return FALSE;
	}

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

	public function _toHtml() {
		$html = parent::_toHtml();
		if ($identifier = $this->getData('identifier')) {
			$html .= $this->helper('typo3connector')->getContent($identifier);
		}
		return $html;
    }

    public function getTypo3connector() {
        if (!$this->hasData('typo3connector')) {
            $this->setData('typo3connector', Mage::registry('typo3connector'));
        }
        return $this->getData('typo3connector');
    }

}