<?php

/**
 * Hookhandler class for Extbase 1.3  
 * @todo implement signalslots for Extbase 1.4+
 */
class tx_magentosync_hooksHandler {

    /**
     *
     * @var array 
     */
    private $settings;

    /**
     * TCEMain Hook
     * @param type $status
     * @param type $table
     * @param type $id
     * @param type $fieldArray
     * @param type $obj 
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, $obj) {
        $this->settings = $this->loadDefaultConfiguration();
        if ($table == 'fe_users') {
            
            //datamapAction
            $configuration = array(
                'extensionName' => 'MagentoSync',
                'pluginName' => 'User',
                'controller' => 'User',
                'action' => 'datamap',
                'switchableControllerActions' => array('User' => array('actions' => 'datamap')),
                'settings' => $this->settings
            );
            $_POST['status'] = $status;
            $_POST['table'] = $recordArray;
            $_POST['id'] = $id;
            $_POST['fieldArray'] = $fieldArray;
            $_POST['obj'] = $obj;
            $bootstrap = new Tx_Extbase_Core_Bootstrap();
            $result = $bootstrap->run('', $configuration);
            return $result;
        }
    }

    /**
     * Data of frontenduser is edited in frontend via extension sr_feuser_register
     * Hook in sr_feuser_register
     * @param type $recordArray
     * @param type $invokingObj 
     */
    function registrationProcess_afterSaveEdit($recordArray, $invokingObj) {
        $this->settings = $this->loadDefaultConfiguration();
        //saveeditAction
        $configuration = array(
            'extensionName' => 'MagentoSync',
            'pluginName' => 'User',
            'controller' => 'User',
            'action' => 'saveedit',
            'switchableControllerActions' => array('User' => array('actions' => 'saveedit')),
            'settings' => $this->settings
        );
        $_POST['recordArray'] = $recordArray;
        $_POST['invokingObj'] = $invokingObj;
        $bootstrap = new Tx_Extbase_Core_Bootstrap();
        $result = $bootstrap->run('', $configuration);
        return $result;
    }

    /**
     * User has registered itself via extension sr_feuser_register
     * Hook in sr_feuser_register
     * @param type $recordArray
     * @param type $invokingObj 
     */
    /*  HOOK Params
      $theTable,
      $dataArray,
      $origArray,
      $token,
      &$newRow,
      $cmd,
      $cmdKey,
      $pid,
      $fieldList,
      $pObj // object of type tx_srfeuserregister_data
     */
    function registrationProcess_afterSaveCreate($theTable, $recordArray, $origArray, $token, &$newRow, $cmd, $cmdKey, $pid, $fieldList, $invokingObj) {
        $this->settings = $this->loadDefaultConfiguration();
        //savecreateAction
        $configuration = array(
            'extensionName' => 'MagentoSync',
            'pluginName' => 'User',
            'controller' => 'User',
            'action' => 'savecreate',
            'switchableControllerActions' => array('User' => array('actions' => 'savecreate')),
            'settings' => $this->settings
        );

        $bootstrap = new Tx_Extbase_Core_Bootstrap();
        //$bootstrap->cObj = t3lib_div::makeInstance('tslib_cObj');
        $_POST['recordArray'] = $recordArray;
        $_POST['invokingObj'] = $invokingObj;
        $result = $bootstrap->run('', $configuration);
        return $result;
    }

    function login() {
        $password = $_POST['pass'];
        $backend = tx_rsaauth_backendfactory::getBackend();
        $this->settings = $this->loadDefaultConfiguration();
        $storage = tx_rsaauth_storagefactory::getStorage();
        $key = $storage->get();
        if ($key != null && substr($password, 0, 4) == 'rsa:') {
            $decryptedPassword = $backend->decrypt($key, substr($password, 4));
            $_POST['password'] = $decryptedPassword;
        }

        //loginaction
        $configuration = array(
            'extensionName' => 'MagentoSync',
            'pluginName' => 'User',
            'controller' => 'User',
            'action' => 'login',
            'switchableControllerActions' => array('User' => array('actions' => 'login')),
            'settings' => $this->settings
        );

        $bootstrap = new Tx_Extbase_Core_Bootstrap();

        $result = $bootstrap->run('', $configuration);
        return $result;
    }

    function logout() {
        $this->settings = $this->loadDefaultConfiguration();
        //logoutaction
        $configuration = array(
            'extensionName' => 'MagentoSync',
            'pluginName' => 'User',
            'controller' => 'User',
            'action' => 'logout',
            'switchableControllerActions' => array('User' => array('actions' => 'logout')),
            'settings' => $this->settings
        );

        $bootstrap = new Tx_Extbase_Core_Bootstrap();
        $result = $bootstrap->run('', $configuration);
        return $result;
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
