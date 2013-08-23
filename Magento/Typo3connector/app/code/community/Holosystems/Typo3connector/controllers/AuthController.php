<?php

class Holosystems_Typo3connector_AuthController extends Mage_Core_Controller_Front_Action {

    /**
     *
     * @var array 
     */
    private $_data;

    /**
     * Auth Action  
     */
    public function indexAction() {

	$this->_data = $this->_decryptData($this->getRequest()->getParam('data'));

	switch ($this->_data['action']) {
	    case 'login':
		$this->_login();
		break;
	    case 'logout':
		$this->_logout();
		break;
	    default:
		die();
	}
    }

    /**
     * Create Session 
     */
    private function _login() {
	try {
	    $user = $this->_data['username'];
	    $pass = $this->_data['password'];
	    $ip = $this->_data['ip'];
	    $session = Mage::getSingleton('customer/session');
	    $session->login($user, $pass);
	    $session->setData('_session_validator_data', array('remote_addr' => $ip, 'http_via' => null, 'http_x_forwarded_for' => null, 'http_user_agent' => null));
	    echo session_id();
	} catch (Exception $e) {
	    return false;
	}
	exit;
    }

    /**
     * Destroy Session 
     */
    private function _logout() {
	try {
	    $session = Mage::getSingleton('customer/session');
	    $session->logout();
	} catch (Exception $e) {
	    return false;
	}
    }

    /**
     *
     * @param string $pData
     * @return Array 
     */
    private function _decryptData($pData) {
	$data = unserialize(base64_decode($pData));
	return $data;
    }

}