<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_Mysql4_OrderedItem_Collection extends Amasty_Giftregistry_Model_Mysql4_AbstractCollection
{
    public function _construct()
    {
        $this->_init('amgiftreg/orderedItem');
    }

    public function joinEvent()
    {
        $this->getSelect()
            ->join(
                array('amgiftreg_item' => $this->getTable('amgiftreg/item')),
                'amgiftreg_item.item_id = main_table.item_id',
                array('product_id' => 'amgiftreg_item.product_id')
            )
            ->join(
                array('amgiftreg' => $this->getTable('amgiftreg/event')),
                'amgiftreg.event_id = amgiftreg_item.event_id',
                array('title'=>'amgiftreg.event_title')
            );

        return $this;
    }

    /**
     * @param string $orderFields
     * @param string $orderItemFields
     *
     * @return $this
     */
    public function joinOrder($orderFields = null, $orderItemFields = null)
    {
        if(is_null($orderFields)) {
            $orderFields =  array('customer_id' => 'order.customer_id', 'customer_name'=>'order.billing_name');
        } elseif(is_array($orderFields)) {
            foreach($orderFields as $alias => $field) {
                $orderFields[$alias] = "order.".$field;
            }
        }

        if(is_null($orderItemFields)) {
            $orderItemFields = array('*');
        }
        // На всякий случай считаем canceled продукты,
        // Вдруг есть плагин, позволяющий кэнселить по одному товару из ордера?
        $orderItemFields['real_qty'] = new Zend_Db_Expr(
            "
                IF(order_item2.qty_canceled>0,
                    order_item.qty_ordered - order_item2.qty_canceled,
                    order_item.qty_ordered - order_item.qty_refunded
                )");

        $this->getSelect()
            ->join(
                array('order_item' => $this->getTable('sales/order_item')),
                'order_item.item_id = main_table.order_item_id',
                $orderItemFields
            )
            ->joinLeft(
                array('order_item2' => $this->getTable('sales/order_item')),
                'order_item2.item_id = order_item.parent_item_id',
                false
            )
            ->join(
                array('order' => $this->getTable('sales/order_grid')),
                'order.entity_id = order_item.order_id AND order.status = "'.Mage_Sales_Model_Order::STATE_COMPLETE.'"',
                $orderFields
            );

        return $this;
    }
}
