<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_Mysql4_OrderedItem extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('amgiftreg/ordered_item', 'ordered_item_id');
    }

    public function massSave(array $data)
    {
        $adapter = $this->_getWriteAdapter();
        $table = $this->getTable('amgiftreg/ordered_item');

        $adapter->insertMultiple($table, $data);
    }
}