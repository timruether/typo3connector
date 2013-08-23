<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('typo3languagemapping')};
CREATE TABLE {$this->getTable('typo3languagemapping')} (
  `typo3languagemapping_id` int(11) unsigned NOT NULL auto_increment,
  `store_view_id` int(11) unsigned NOT NULL,
  `language_id` int(11) unsigned NOT NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`typo3languagemapping_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 