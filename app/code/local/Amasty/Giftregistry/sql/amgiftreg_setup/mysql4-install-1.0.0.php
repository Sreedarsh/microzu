<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
$installer = $this;
$installer->startSetup();


$installer->run("
DROP TABLE IF EXISTS {$installer->getTable('amgiftreg/event')};
CREATE TABLE {$installer->getTable('amgiftreg/event')} (
  `event_id` int(10) unsigned NOT NULL auto_increment,
  `event_title` varchar(255) NULL DEFAULT NULL,
  `password` varchar(255) NULL DEFAULT NULL,
  `event_hosts` text NULL DEFAULT NULL,
  `event_date` date NULL DEFAULT NULL,
  `event_time` time NULL DEFAULT NULL,
  `event_location` text NULL DEFAULT NULL,
  `additional_information` text NULL DEFAULT NULL,
  `event_image_path` VARCHAR(255) NULL DEFAULT NULL,
  `customer_id` int(10) unsigned NOT NULL default '0',
  `shipping_address_id` int(10) unsigned NULL default '0',
  `searchable` tinyint(1) default '1',
  `created_at` date NOT NULL,
  PRIMARY KEY  (`event_id`),
  KEY `IDX_CUSTOMER` (`customer_id`),
  CONSTRAINT `FK_AMGIFTREG_EVENT_CUSTOMER` FOREIGN KEY (`customer_id`) REFERENCES `{$installer->getTable('customer_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_AMGIFTREG_EVENT_CUSTOMER_SHIPPING_ADDRESS` FOREIGN KEY (`shipping_address_id`) REFERENCES `{$installer->getTable('customer/address_entity')}` (`entity_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS {$installer->getTable('amgiftreg/item')};
CREATE TABLE {$installer->getTable('amgiftreg/item')} (
  `item_id` int(10) unsigned NOT NULL auto_increment,
  `event_id` int(10) unsigned NOT NULL default '0',
  `product_id` int(10) unsigned NOT NULL default '0',
  `qty` smallint(5) default '0',
  `descr` varchar(255) NOT NULL,
  `buy_request` TEXT NOT NULL,
  `comments` TEXT NULL DEFAULT NULL,
  `priority` smallint(5) default '10',
  PRIMARY KEY  (`item_id`),
  KEY `IDX_EVENT` (`event_id`),
  KEY `IDX_PRODUCT` (`product_id`),
  `created_at` date NOT NULL,
  CONSTRAINT `FK_AMGIFTREG_ITEM_EVENT` FOREIGN KEY (`event_id`) REFERENCES `{$installer->getTable('amgiftreg/event')}` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_AMGIFTREG_ITEM_PRODUCT` FOREIGN KEY (`product_id`) REFERENCES `{$installer->getTable('catalog_product_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS {$installer->getTable('amgiftreg/ordered_item')};
CREATE TABLE {$installer->getTable('amgiftreg/ordered_item')} (
  `ordered_item_id` int(10) unsigned NOT NULL auto_increment,
  `item_id` int(10) unsigned NOT NULL default '0',
  `qty` smallint(5) default '0',
  `order_item_id` INT(10) UNSIGNED default '0' COMMENT 'Order ID',
  `created_at` date NOT NULL,
  PRIMARY KEY  (`ordered_item_id`),
  KEY `IDX_ITEM` (`item_id`),
  CONSTRAINT `FK_AMGIFTREG_ORDERED_ITEM_EVENT` FOREIGN KEY (`item_id`) REFERENCES `{$installer->getTable('amgiftreg/item')}` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_AMGIFTREG_ORDERED_ITEM_ORDER_ITEM` FOREIGN KEY (`order_item_id`) REFERENCES `{$installer->getTable('sales_flat_order_item')}` (`item_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS {$installer->getTable('amgiftreg/file')};
CREATE TABLE {$installer->getTable('amgiftreg/file')} (
  `file_id` int(10) unsigned NOT NULL auto_increment,
  `event_id` int(10) unsigned NOT NULL default '0',
  `file_path` VARCHAR(255) NULL DEFAULT NULL,
  `file_name` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY  (`file_id`),
  KEY `IDX_EVENT` (`event_id`),
  CONSTRAINT `FK_AMGIFTREG_FILE_EVENT` FOREIGN KEY (`event_id`) REFERENCES `{$installer->getTable('amgiftreg/event')}` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


");

$installer->endSetup();