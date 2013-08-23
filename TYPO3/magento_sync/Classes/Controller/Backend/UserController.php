<?php
ini_set("default_socket_timeout", 160);
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Tim Ruether <tim@holosystems.de>, Holosystems
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 *
 *
 * @package magento_sync
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_MagentoSync_Controller_Backend_UserController extends Tx_Extbase_MVC_Controller_ActionController {

    /**
     *
     * @var string 
     */
    private $_magentoPasswordHash;

    /**
     *
     * @var int 
     */
    private $_magentoGroupId;

    /**
     *
     * @var int 
     */
    private $_magentoStoreId;

    /**
     *
     * @var int 
     */
    private $_magentoWebsiteId;

    /**
     *
     * @var int 
     */
    private $_autoActivate;

    /**
     *
     * @var int 
     */
    private $_magentoRemoteFile;

    /**
     *
     * @var string
     */
    private $_magentoBaseUrl;

    /**
     *
     * @var string 
     */
    private $_magentoApiUrl;

    /**
     *
     * @var string 
     */
    private $_magentoApiUser;

    /**
     *
     * @var string 
     */
    private $_magentoApiPassword;

    /**
     *
     * @var string 
     */
    private $_cookieDomain;

    /**
     *
     * @var string 
     */
    private $_cookiePath;

    /**
     *
     * @var int 
     */
    private $_cookieExpire;

    /**
     *
     * @var string 
     */
    private $_magentoCookieName;

    /**
     *
     * @var int 
     */
    private $_magentoDefaultBillingAddress;

    /**
     *
     * @var int 
     */
    private $_magentoDefaultShippingAddress;

    /**
     *
     * @var int 
     */
    private $_magentoCreateUserFromTypo3;

    /**
     *
     * @var int 
     */
    private $_magentoUpdateUserFromTypo3;

    /**
     *
     * @var int 
     */
    private $_magentoCreateUserFromFeUserRegister;

    /**
     *
     * @var int 
     */
    private $_magentoUpdateUserFromFeUserRegister;

    public function __construct() {
        $this->settings = $this->loadDefaultConfiguration();
        $this->_magentoPasswordHash = $this->settings['magentoPasswordHash'];
        $this->_magentoGroupId = $this->settings['magentoGroupId'];
        $this->_magentoStoreId = $this->settings['magentoStoreId'];
        $this->_magentoWebsiteId = $this->settings['magentoWebsiteId'];
        $this->_autoActivate = $this->settings['autoActivate'];
        $this->_magentoRemoteFile = $this->settings['magentoRemoteFile'];
        $this->_magentoBaseUrl = $this->settings['magentoBaseUrl'];
        $this->_magentoApiUrl = $this->settings['magentoApiUrl'];
        $this->_magentoApiUser = $this->settings['magentoApiUser'];
        $this->_magentoApiPassword = $this->settings['magentoApiPassword'];
        $this->_cookieDomain = $this->settings['cookieDomain'];
        $this->_cookiePath = $this->settings['cookiePath'];
        $this->_cookieExpire = $this->settings['cookieExpire'];
        $this->_magentoCookieName = $this->settings['magentoCookieName'];

        $this->_magentoDefaultBillingAddress = $this->settings['magentoDefaultBillingAddress'];
        $this->_magentoDefaultShippingAddress = $this->settings['magentoDefaultShippingAddress'];
        $this->_magentoCreateUserFromTypo3 = $this->settings['magentoCreateUserFromTypo3'];
        $this->_magentoUpdateUserFromTypo3 = $this->settings['magentoUpdateUserFromTypo3'];
        $this->_magentoCreateUserFromFeUserRegister = $this->settings['magentoCreateUserFromFeUserRegister'];
        $this->_magentoUpdateUserFromFeUserRegister = $this->settings['magentoUpdateUserFromFeUserRegister'];
    }

    /**
     * action new
     *
     * @param Tx_MagentoSync_Domain_Model_User $newUser
     * @dontvalidate $newUser
     * @return void
     */
    public function newAction(Tx_MagentoSync_Domain_Model_User $newUser = NULL) {
        $this->view->assign('newUser', $newUser);
    }

    /**
     * action create
     *
     * @param Tx_MagentoSync_Domain_Model_User $newUser
     * @return void
     */
    public function createAction(Tx_MagentoSync_Domain_Model_User $newUser) {

        $this->userRepository->add($newUser);
        $this->flashMessageContainer->add('Your new User was created.');
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param Tx_MagentoSync_Domain_Model_User $user
     * @return void
     */
    public function editAction(Tx_MagentoSync_Domain_Model_User $user) {
        $this->view->assign('user', $user);
    }

    /**
     * action update
     *
     * @param Tx_MagentoSync_Domain_Model_User $user
     * @return void
     */
    public function updateAction(Tx_MagentoSync_Domain_Model_User $user) {
        $user->setFirstName('test');
        var_dump($user);
        return $user;
    }

    /**
     * helper function to get the current domain, needed for cookie
     * @return string
     */
    private function _getCookieDomain() {
        if (is_null($this->_cookieDomain) || !isset($this->_cookieDomain) || '' == $this->_cookieDomain) {
            $this->_cookieDomain = '.' . $_SERVER['HTTP_HOST'];
        }
        return $this->_cookieDomain;
    }

    /**
     * action login
     * 
     */
    public function loginAction() {
        $url = $this->_magentoBaseUrl . 'typo3connector/auth/index/data/' . $this->_encryptData($GLOBALS['TSFE']->fe_user->user['username'], $_POST['password'], $_SERVER['REMOTE_ADDR'], 'login');
        $session = file_get_contents($url);
        setcookie($this->_magentoCookieName, $session, $this->_cookieExpire, $this->_cookiePath, $this->_getCookieDomain(), false);
    }

    /**
     * action logout
     * 
     */
    public function logoutAction() {
        $url = $this->_magentoBaseUrl . 'typo3connector/auth/index/data/' . $this->_encryptData($GLOBALS['TSFE']->fe_user->user['username'], $_POST['password'], $_SERVER['REMOTE_ADDR'], 'logout');
        $session = file_get_contents($url);
        setcookie($this->_magentoCookieName, '', time() - 3600, $this->_cookiePath, $this->_getCookieDomain(), false);
        return $session;
    }

    /**
     * action datamap
     * 
     * Data of frontenduser is edited in Backend
     * 
     */
    public function datamapAction() {
        var_dump($_POST);
        $status = $_POST['$status'];
        $table = $_POST['table'];
        $id = $_POST['id'];
        $fieldArray = $_POST['fieldArray'];
        $obj = $_POST['obj'];

        if ($table == 'fe_users' && ($status == 'update' || $status == 'create')) {
            if (($this->_magentoUpdateUserFromTypo3 == '1' && $status = 'update') || ($this->_magentoCreateUserFromTypo3 == '1' && $status = 'create')) {
                $this->_sendToMagento($obj->datamap[$table][$id]);
            }
        }
    }

    /**
     * action saveedit
     * 
     * Data of frontenduser is edited in frontend via extension sr_feuser_register
     * Hook in sr_feuser_register
     */
    function saveeditAction() {
        if ($this->_magentoUpdateUserFromFeUserRegister == '1') {
            $this->sendToMagento($recordArray);
        }
    }

    /**
     * action savecreate
     * 
     * User has registered itself via extension sr_feuser_register
     * Hook in sr_feuser_register
     */
    function savecreateAction() {

        $recordArray = $_POST['recordArray']; //$this->request->getArgument('recordArray');
        $invokingObj = $_POST['invokingObj']; //$this->request->getArgument('invokingObj');
        if ($this->_magentoCreateUserFromFeUserRegister == '1') {
            $this->_sendToMagento($recordArray);
        }
    }

    /**
     * encrypt the data to be send, right now we only base64 encode it
     * @todo implement encryption algorythm
     */
    private function _encryptData($pUsername, $pPassword, $pIP, $pAction) {
        $return = base64_encode(serialize(array('username' => $pUsername, 'password' => $pPassword, 'ip' => $pIP, 'action' => $pAction)));
        return $return;
    }

    /**
     * send feuser data to magento via api call after create or edit
     */
    private function _sendToMagento($pCustomerData) {
        try {
            $client = new SoapClient($this->_magentoApiUrl);
        } catch (Exception $e) {
            die($e);
        }
        $session = $client->login($this->_magentoApiUser, $this->_magentoApiPassword);

        if ($pCustomerData['city']
                && $pCustomerData['first_name']
                && $pCustomerData['last_name']
                && $pCustomerData['zip']
                && $pCustomerData['address']
                && $pCustomerData['telephone']
        ) {
            $addressdata = $this->_mapCustomerToMagentoAddressApi($pCustomerData);
        } else {
            $addressData = false;
        }
        try {
            //Laden der Magento engine
//            $mageFilename = '../venta_shop/app/Mage.php';
//            //
//            require_once $mageFilename;
//            Mage::app();
//
//            $customers = Mage::getResourceModel('customer/customer_collection')
//                    ->addAttributeToSelect('*')
//                    ->addAttributeToFilter('email', $pCustomerData['email']);
            // SOAP V1
            //$customers = $client->call($session, 'customer.list', array('filter' => array('email' => $pCustomerData['email'])));
            // SOAP V2
            $customers = $client->customerCustomerList($session, array('filters' => array('email' => $pCustomerData['email'])));
        } catch (Exception $e) {
            throw new Exception("customer.list: " . $e->getMessage());
        }
        if (count($customers) == 0) {
            $customerdata = $this->_mapCustomerToMagentoCustomerApi($pCustomerData, 'create');
            try {
                // SOAP V1
                //$newcustomerid = $client->call($session, 'customer.create', array($customerdata));
                // SOAP V2
                $newcustomerid = $client->customerCustomerCreate($session, $customerdata);
            } catch (Exception $e) {
                throw new Exception("customer.create: " . $e->getMessage());
            }
            if ($addressdata) {
                try {
                    // SOAP V1
                    //$newaddressid = $client->call($session, 'customer_address.create', array($newcustomerid, $addressdata));
                    // SOAP V2
                    $newaddressid = $client->customerAddressCreate($session, $newcustomerid, $addressdata);
                } catch (Exception $e) {
                    throw new Exception("customer_address.create: " . $e->getMessage());
                }
            }
        } else {
            $customerdata = $this->_mapCustomerToMagentoCustomerApi($pCustomerData, 'update');
            //var_dump($customers);
            $customerid = $customers[0]->customer_id;
            try {
                // SOAP V1
                //$addresses = $client->call($session, 'customer_address.list', $customerid);
                // SOAP V2
                $addresses = $client->customerAddressList($session, $customerid);

                $addressid = $addresses[0]->customer_address_id;
            } catch (Exception $e) {
                throw new Exception("customer_address.list: " . $e->getMessage());
            }
            try {
                // SOAP V1
                //$client->call($session, 'customer.update', array($customerid, $customerdata));
                // SOAP V2
                //var_dump($session, $customerid, $customerdata);
                $result = $client->customerCustomerUpdate($session, intval($customerid), $customerdata);
                //var_dump($result);
            } catch (Exception $e) {
                throw new Exception("customer.update: " . $e->getMessage());
            }
            if ($addressdata) {
                try {
                    // SOAP V1
                    //$client->call($session, 'customer_address.update', array($addressid, $addressdata));
                    // SOAP V2
                    $client->customerAddressUpdate($session, $addressid, $addressdata);
                } catch (Exception $e) {
                    throw new Exception("customer_address.update: " . $e->getMessage());
                }
            }
        }
        $client->endSession($session);
    }

    /**
     * map the typo3 userdata to the magento userdata
     */
    private function _mapCustomerToMagentoCustomerApi($pCustomerData) {
        $activated = ($this->_autoActivate == '1' ? 1 : 0);
        $customerData = array(
            'store_id' => $this->_magentoStoreId,
            'website_id' => $this->_magentoWebsiteId,
            'email' => $pCustomerData['email'],
            'firstname' => $pCustomerData['first_name'],
            'lastname' => $pCustomerData['last_name'],
            'group_id' => $this->_magentoGroupId,
            'dob' => $pCustomerData['date_of_birth'],
            /** @todo  Salted Password  if needed */
            //'password_hash' => md5($this->_magentoPasswordHash . $pCustomerData['password']) . ':' . $this->_magentoPasswordHash,
            'password' => $pCustomerData['password'],
            'is_activated' => $activated,
                /** @todo this is only set if a special plugin for magento is present! */
                //'customer_activated' => $activated 
        );
        return $customerData;
    }

    /**
     * map the typo3 userdata to the magento addressdata
     */
    private function _mapCustomerToMagentoAddressApi($t3AddressData) {

        $addressData = array(
            'city' => $t3AddressData['city'],
            'country_id' => 'US',
            'region' => 'New York',
            'region_id' => '43',
            'city' => 'New York',
            /** @todo set Region ove mapping */
            //'region_id'  => '43',  $t3AddressData[zone]  /mapping auf Zone nötig
            //'region'     => 'New York',
            'firstname' => $t3AddressData['first_name'],
            'lastname' => $t3AddressData['last_name'],
            'postcode' => $t3AddressData['zip'],
            'street' => array($t3AddressData['address']),
            'telephone' => $t3AddressData['telephone'],
            'fax' => $t3AddressData['fax'],
            'is_default_billing' => $this->_magentoDefaultBillingAddress,
            'is_default_shipping' => $this->_magentoDefaultShippingAddress
        );

        return $addressData;
    }

    /**
     *  get Settings from localconf 
     */
    function loadDefaultConfiguration() {
        $extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['magento_sync']);
        return $extConfig;
    }

}

?>