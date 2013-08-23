<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Content',
	'Contentdelivery'
);

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'User',
	'Usermanagement'
);


t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Magento Connector');

$tmp_magento_sync_columns = array(

);

t3lib_extMgm::addTCAcolumns('fe_users',$tmp_magento_sync_columns);

$TCA['fe_users']['columns'][$TCA['fe_users']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:magento_sync/Resources/Private/Language/locallang_db.xml:fe_users.tx_extbase_type.Tx_MagentoSync_User','Tx_MagentoSync_User');

$TCA['fe_users']['types']['Tx_MagentoSync_User']['showitem'] = $TCA['fe_users']['types']['1']['showitem'];
$TCA['fe_users']['types']['Tx_MagentoSync_User']['showitem'] .= ',--div--;LLL:EXT:magento_sync/Resources/Private/Language/locallang_db.xml:tx_magentosync_domain_model_user,';
$TCA['fe_users']['types']['Tx_MagentoSync_User']['showitem'] .= '';

$tmp_magento_sync_columns = array(

);

t3lib_extMgm::addTCAcolumns('tt_content',$tmp_magento_sync_columns);

$TCA['tt_content']['columns'][$TCA['tt_content']['ctrl']['type']]['config']['items'][] = array('LLL:EXT:magento_sync/Resources/Private/Language/locallang_db.xml:tt_content.tx_extbase_type.Tx_MagentoSync_Content','Tx_MagentoSync_Content');

$TCA['tt_content']['types']['Tx_MagentoSync_Content']['showitem'] = $TCA['tt_content']['types']['1']['showitem'];
$TCA['tt_content']['types']['Tx_MagentoSync_Content']['showitem'] .= ',--div--;LLL:EXT:magento_sync/Resources/Private/Language/locallang_db.xml:tx_magentosync_domain_model_content,';
$TCA['tt_content']['types']['Tx_MagentoSync_Content']['showitem'] .= '';

?>