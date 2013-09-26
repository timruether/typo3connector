<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'Content', array(
    'Content' => 'show',
        )
);

Tx_Extbase_Utility_Extension::configurePlugin(
        $_EXTKEY, 'User', array(
    'User' => 'new,  create, edit, update, savecreate, saveedit, datamap, login, logout',
        ),
        // non-cacheable actions
        array(
    'User' => 'create, update, savecreate, saveedit, datamap, login, logout',
        )
);

/**
 * HOOKS
 */
//User register hook
//$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sr_feuser_register']['tx_srfeuserregister_pi1']['registrationProcess']['magento_sync']  = 'EXT:magento_sync/Classes/Hooks/class.tx_magentosync_hooksHandler.php:tx_magentosync_hooksHandler';
//save hook
#$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:magento_sync/Classes/Hooks/class.tx_magentosync_hooksHandler.php:tx_magentosync_hooksHandler';
//felogin Hooks
//$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['login_confirmed']['magento_sync'] = 'EXT:magento_sync/Classes/Hooks/class.tx_magentosync_hooksHandler.php:tx_magentosync_hooksHandler->login';
//$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['felogin']['logout_confirmed']['magento_sync'] = 'EXT:magento_sync/Classes/Hooks/class.tx_magentosync_hooksHandler.php:tx_magentosync_hooksHandler->logout';


// The Backend-MenuItem in ClearCache-Pulldown
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = 'EXT:magento_sync/Classes/Hooks/ClearCacheHook.php:ClearCacheHook';

// The AjaxCall to clear the cache
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc'][] = 'EXT:magento_sync/Classes/Hooks/ClearCacheHook.php:ClearCacheHook->clearCachePostProc';

// The Backend-MenuItem in ClearCache-Pulldown
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['Tx_MagentoSync_Hooks_BrowseLinksHook'][] = 'EXT:magento_sync/Classes/Hooks/BrowseLinksHook.php';

//register hook for mage-links in the backend
#$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/rtehtmlarea/mod3/class.tx_rtehtmlarea_browse_links.php']['browseLinksHook']['mage'] = 'EXT:magento_sync/Classes/Hooks/ClearCacheHook.php:Tx_MagentoSync_Hooks_BrowseLinksHook';
#$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.browse_links.php']['browseLinksHook']['mage'] = 'EXT:magento_sync/Classes/Hooks/ClearCacheHook.php:Tx_MagentoSync_Hooks_BrowseLinksHook';

//register hook for parsing mage_links in the frontend
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['mage'] = 'EXT:magento_sync/Classes/Hooks/TypolinkLinkHandler.php:Tx_MagentoSync_Hooks_TypolinkLinkHandler';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['product'] = 'EXT:magento_sync/Classes/Hooks/TypolinkLinkHandler.php:Tx_MagentoSync_Hooks_TypolinkLinkHandler';

?>