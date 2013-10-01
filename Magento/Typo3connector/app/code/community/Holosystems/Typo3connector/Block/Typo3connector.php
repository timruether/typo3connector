<?php

class Holosystems_Typo3connector_Block_Typo3connector extends Mage_Core_Block_Template {
    
    /**
     * String
     */
    const CACHE_TAG = 'holo_t3conblock';

    /**
     * string Identifier 
     */
    var $identifier;
    
    /**
     * string Extension 
     */
    var $extension;
    
    /**
     * string Extension 
     */
    var $content;

	/**
	 * string T3Action
	 */
	const T3ACTION_DEFAULT = "show";

	/**
	 * @return mixed
	 */
	public function getT3action() {
		if ($this->getData('t3action')) {
			return $this->getData('t3action');
		}
		return self::T3ACTION_DEFAULT;
	}

	/**
	 * @return Holosystems_Typo3connector_Helper_Data
	 */
	protected function getTypo3ConnectorHelper() {
		return Mage::helper('typo3connector');
	}
    
    /**
     * Additional Conditions to match for a CacheHit to happen
     *
     * @return array
     */
    protected function _getCacheKeyInfo() {
        return array(
            //prevent multiple uses of block to be cached the same way
            $this->getNameInLayout(),
            //build cache in per-Identifier-base
            $this->getIdentifier(),
			// build cache hash for detail views of typo3 extensions
			$this->getControllerInfo(),
        );
    }

    /**
     * @return array
     */
    public function getCacheKeyInfo() {
        //reuse parent key-info eg. storeCode or template-file
        $info = parent::getCacheKeyInfo();
        $info['typo3connector'] = implode('#', $this->_getCacheKeyInfo());
        return $info;
    }

    /**
     * Additional Events that invalidate the cached Block
     * @return array
     */
    protected function _getCacheTags() {
        return array(
            self::CACHE_TAG,
            //invalidate cache when Identifier changes
            self::CACHE_TAG . "_" . $this->getIdentifier(),
        );
    }

    /**
     * @return array
     */
    public function getCacheTags() {
        //reuse parent tags
        $tags = parent::getCacheTags();
        $tags = array_merge($tags, $this->_getCacheTags());
        return $tags;
    }

    /**
     * @return int
     */
    public function getCacheLifetime() {
        //parent::getCacheLifetime();
        //use Magento default cache Lifetime
        return FALSE;
    }

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

	/**
	 * @return string
	 */
	protected function _getContent() {
		if ( $this->getT3Controller() ) {
			$params = array(
				'tx_news_pi1[controller]=' . $this->getT3Controller(),
				'tx_news_pi1[action]=' . $this->getT3Action(),
				'tx_news_pi1[' . strtolower($this->getT3Controller()) . ']=' . $this->getT3Id()
			);

			$content = $this->getTypo3ConnectorHelper()->getPageContent($this->getT3PageId(), $params);

			$controllerInfo = $this->getControllerInfo();
			$content = $this->getTypo3ConnectorHelper()->getMetaFromContent('mage-title', $controllerInfo, $content);
			$content = $this->getTypo3ConnectorHelper()->getMetaFromContent('mage-keywords', $controllerInfo, $content);
			$content = $this->getTypo3ConnectorHelper()->getMetaFromContent('mage-description', $controllerInfo, $content);

			return $content;
		}

		return '';
	}

    public function _toHtml() {
		if ( $this->getT3Controller() ) {
			$content = $this->_getContent();

			$this->assign('content', $content);
		}

        $html = parent::_toHtml();
        if ($identifier = $this->getIdentifier()) {
            $html .= $this->helper('typo3connector')->getContent($identifier,$this->getExtension());
        }
        return $html;
    }

    public function getTypo3connector() {
        if (!$this->hasData('typo3connector')) {
            $this->setData('typo3connector', Mage::registry('typo3connector'));
        }
        return $this->getData('typo3connector');
    }

	/**
	 * @return string
	 */
	public function getControllerInfo() {
		if ( $this->getT3Controller() ) {
			return $this->getT3Controller() . '#' .
				$this->getT3Action() . '#' .
				$this->getT3Id() . '#' .
				$this->getT3PageId();
		}

		return '';
	}
}