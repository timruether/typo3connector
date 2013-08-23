<?php

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('typo3connector')};
CREATE TABLE {$this->getTable('typo3connector')} (
  `typo3connector_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `typo3_tt_content_ids` varchar(255) NOT NULL DEFAULT '',
  `typo3_pages_id` varchar(255) NOT NULL DEFAULT '',
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`typo3connector_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


$installer->endSetup();
