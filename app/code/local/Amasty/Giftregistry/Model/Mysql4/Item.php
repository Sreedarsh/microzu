<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_Mysql4_Item extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('amgiftreg/item', 'item_id');
    }
    
    public function findDuplicate($item)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'item_id')
            ->where('event_id = ?',  $item->getEventId())
            ->where('product_id = ?',  $item->getProductId())
            ->where('buy_request = ?',  $item->getBuyRequest())
            ->limit(1);
        $id = $this->_getReadAdapter()->fetchOne($select);          
        return $id;
    }
}