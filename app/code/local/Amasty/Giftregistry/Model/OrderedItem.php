<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_OrderedItem extends Mage_Core_Model_Abstract
{
    /**
     * @var Mage_Sales_Model_Order_Item
     */
    protected $_orderItem = null;

    
    protected function _construct()
    {
        $this->_init('amgiftreg/orderedItem');
    }


    public function eventOrderItem($observer)
    {
        /* @var $order Mage_Sales_Model_Order*/
        $order = $observer->getOrder();
        $listGiftRegItemsOrdered = array();

        $isData = false;
        foreach($order->getAllItems() as $item) {
            // Configurable продукты мы получим по parent_id из simples products
            if($item->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
                continue;
            }
            $options  = $item->getProductOptions();

            if($options && !empty($options['info_buyRequest']['amgiftreg_item_id'])) {
                $listGiftRegItemsOrdered[] = array(
                    'item_id' => $options['info_buyRequest']['amgiftreg_item_id'],
                    'qty' => $item->getQtyOrdered(),
                    'order_item_id' => $item->getId(),
                    'created_at' => date('Y-m-d')
                );
                $isData = true;
            }
        }

        if($isData) {
            $this->_getResource()->massSave($listGiftRegItemsOrdered);
        }
    }


    /**
     * @return Mage_Sales_Model_Order_Item|Mage_Core_Model_Abstract
     */
    public function getOrderItem()
    {
        if(is_null($this->_orderItem)) {
            $this->_orderItem = Mage::getModel('sales/order_item')->load($this->getOrderItemId());
        }

        return $this->_orderItem;
    }
}
