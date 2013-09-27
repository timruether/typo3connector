<?php

class Holosystems_Typo3connector_Helper_Data extends Mage_Core_Helper_Abstract {

    var $additionalParams = array();

    /**
     *
     * @param string $identifier 
     * @param string $extension Extensionname
     * @param array $additionalParams addtional URL Params
     * @return string $content
     */
    public function getContent($identifier, $extension = '', $additionalParams = array()) {

        $content = '';
        if ($identifier != null && $identifier != '') {
            $typo3connector = Mage::getModel('typo3connector/typo3connector')->getCollection()->addFilter('identifier', array('eq' => $identifier))->getFirstItem();
            if ($typo3connector->getStatus() == 1) {
                if ($typo3connector->getTypo3PagesId()) {
                    $content .= $this->_getPageContent($typo3connector->getTypo3PagesId(), $additionalParams, $extension);
                }
                if ($typo3connector->getTypo3TtContentIds()) {
                    $content .= $this->_getContentElements($typo3connector->getTypo3TtContentIds());
                }
                if ($extension == 'news') {
                    $content = $this->_parseNewsLinks($content);
                }
            }
        }

		$helper = Mage::helper('cms');
		$processor = $helper->getPageTemplateProcessor();

		return $processor->filter($content);
    }

    /**
     *
     * @return string
     */
    private function _getLanguageParams() {
        if (Mage::getConfig()->getModuleConfig('Holosystems_Typo3languagemapping')->is('active', 'true')) {
            $storeId = Mage::app()->getStore()->getStoreId();
            if ($storeId) {
                $collection = Mage::getModel('typo3languagemapping/typo3languagemapping')
                        ->getCollection()
                        ->addFieldToFilter('store_view_id', $storeId);
                if ($collection) {
                    $mapping = $collection->getFirstItem();
                    $typo3LanguageUid = $mapping->getLanguageId();
                    return '&L=' . intval($typo3LanguageUid);
                }
            }
        }
        return '';
    }

    /**
     *
     * @param integer $id
     * @param array additionalParams
     * @return string $content
     */
    private function _getPageContent($id, $additionalParams = array()) {

        $content = '';
        $baseUrl = Mage::getStoreConfig('system/holosystems/typo3connector_baseurl');
        if ($baseUrl) {
            $feed_url = $baseUrl . "index.php?id=" . intval($id) . $this->_getLanguageParams() . $this->_getAdditionalParams($additionalParams);
            $content = $this->_getCurlContent($feed_url);
        }
        return $content;
    }

    /**
     *
     * @param string $ids
     * @return string $content
     */
    private function _getContentElements($ids) {

        $content = '';
        $connectorUrl = Mage::getStoreConfig('system/holosystems/typo3connector_connectorurl');
        if ($connectorUrl) {
            $feed_url = $connectorUrl . "&tx_magentosync_content[contentIds]=" . $ids;
            $content = $this->_getCurlContentWithoutTrim($feed_url);
            return $content;
        }
    }

    /**
     * @param string $url
     * @return string 
     */
    private function _getCurlContent($url) {
        $ch = curl_init();
        $curl = new Varien_Http_Adapter_Curl();
        $curl->setConfig(curl_setopt($ch, CURLOPT_HEADER, false));
        if (Mage::getStoreConfig('system/holosystems/typo3connector_httpuser') != '' && Mage::getStoreConfig('system/holosystems/typo3connector_httppassword') != '') {
            $curl->setConfig(array('timeout' => 60, 'header' => false, 'userpwd' => Mage::getStoreConfig('system/holosystems/typo3connector_httpuser') . ':' . Mage::getStoreConfig('system/holosystems/typo3connector_httppassword')));
        } else {
            $curl->setConfig(array('timeout' => 60, 'header' => false)); //Timeout in no of seconds 
        }
        $curl->write(Zend_Http_Client::GET, $url, '1.1', array());

        $data = $curl->read();

        if ($data === false) {
            $content = '<!-- empty Content -->';
        }

        $curl->close();
        try {
            $content = $data; //output the data 
        } catch (Exception $e) {
            $content = '<!-- Content delivery failed -->';
        }
        return $content;
    }

    /**
     * @param string $url
     * @return string 
     */
    private function _getCurlContentWithoutTrim($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
        $data = curl_exec($ch);
        curl_close($ch);
        try {
            //$content = '<!-- '.$data.' -->'; //output the data 
            $content = $data; //output the data 
        } catch (Exception $e) {
            $content = '<!-- Content delivery failed -->';
        }
        return $content;
    }

    /**
     * generates URL Param string
     * @param array additionalParams
     * @return string
     */
    private function _getAdditionalParams($additionalParams) {
        return '&' . implode('&', $additionalParams);
    }

    /**
     * 
     * @param string $content
     * @return string
     */
    private function _parseNewsLinks($content) {
        //$new_content = preg_replace('!(<a\s*[^>]*)href="([^"]+)"!','\1 href="'.Mage::getUrl('typo3connector/index').'?url=\2"', $content);
        $content = preg_replace_callback('!(<a\s*[^>]*)href="([^"]+)"!', function($url) {
                    return '<a href="' . Mage::getUrl("typo3connector/index/news") . 'url/' . base64_encode($url[2]).'"';
                }, $content);

        return $content;
    }
    
    /**
     * 
     * @param string $url
     * @return string
     */
    public function getNewsContentUrl($url) {
        return $this->_parseNewsLinks($this->_getCurlContent($url));
    }
    
    /**
     * 
     * @param string $url
     * @return string
     */
    public function getContentUrl($url) {
        return $this->_getCurlContent($url);
    }

	/**
	 * @param $id
	 * @param array $additionalParams
	 * @return string
	 */
	public function getPageContent($id, $additionalParams = array()) {
		return $this->_getPageContent($id, $additionalParams);
	}

}

?>
