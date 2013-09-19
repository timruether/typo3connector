<?php

class Holosystems_Typo3connector_IndexController extends Mage_Core_Controller_Front_Action {

    const ACTION_LOGOUT = 'logout';
    const ACTION_LOGIN = 'login';

    /**
     * Standard Action 
     */
    public function indexAction() {

        $this->loadLayout();
        $this->renderLayout();
    }

    public function topprodukteAction() {
        /*
        $catagory_model = Mage::getModel('catalog/category')->load(488);
        $productCollection = Mage::getResourceModel('catalog/product_collection')
                        ->addAttributeToSelect('*')
                        ->addCategoryFilter($catagory_model)->load();
        $products = array('products' => array());
        $i = 0;
        foreach ($productCollection as $product) {
            $products['products'][$i]['name'] = $product->getName();
            $products['products'][$i]['manufacturer'] = $product->getAttributeText('manufacturer');
            $products['products'][$i]['url'] = $product->getProductUrl();
            $products['products'][$i]['price'] = $product->getPrice();
            $products['products'][$i]['priceIncludingTax'] = Mage::helper('tax')->getPrice($product, $product->getFinalPrice(), true);
            $products['products'][$i]['image'] = $product->getImageUrl();
            $i++;
        }
        echo json_encode($products);
         
         */
        exit();
    }

    public function customerinfoAction() {
        
        $session = Mage::getSingleton('customer/session');

        if ($session->getId()) {
            $customer = Mage::getModel('customer/customer')->load($session->getId());
            $group = Mage::getModel('customer/group')->load($session->getCustomerGroupId());

            echo json_encode(array('customer' => $customer->getData(), 'customergroup' => $group->getData()));
        }
        exit();
    }
    /**
     * tx_news connector
     */
    public function newsAction() {
        //echo "<pre>";
        $content = Mage::helper('typo3connector')->getNewsContentUrl(base64_decode($this->getRequest()->getParam('url')));
        $this->loadLayout();
        //var_dump($this->getLayout());
        $this->getLayout()->getBlock('typo3connector')->assign('content', $content);
        $this->renderLayout();
    }

}