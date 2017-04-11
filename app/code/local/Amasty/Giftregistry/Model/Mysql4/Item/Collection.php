<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_Mysql4_Item_Collection extends Amasty_Giftregistry_Model_Mysql4_AbstractCollection
{
    protected  $_loadProducts = false;


    public function _construct()
    {
        $this->_init('amgiftreg/item');
    }

    public function joinOrderedItems()
    {
        $orderItemFields = array();

        $orderItemFields['count_received'] = new Zend_Db_Expr(
            "
                SUM(IF(order.status='complete', IF(order_item2.qty_canceled>0,
                    order_item.qty_ordered - order_item2.qty_canceled,
                    order_item.qty_ordered - order_item.qty_refunded
                ), 0))");

        $this->getSelect()
            ->joinLeft(
                array('amgiftreg_ordered_items' => $this->getTable(
                    'amgiftreg/ordered_item'
                )),
                'amgiftreg_ordered_items.item_id = main_table.item_id',
                //array('count_received' => 'SUM(amgiftreg_ordered_items.qty)')
                false
            )
            ->joinLeft(
                array('order_item' => $this->getTable('sales/order_item')),
                'order_item.item_id = amgiftreg_ordered_items.order_item_id',
                $orderItemFields
            )
            ->joinLeft(
                array('order_item2' => $this->getTable('sales/order_item')),
                'order_item2.item_id = order_item.parent_item_id',
                false
            )
            ->joinLeft(
                array('order' => $this->getTable('sales/order_grid')),
                'order.entity_id = order_item.order_id AND order.status = "'.Mage_Sales_Model_Order::STATE_COMPLETE.'"',
                false
            )
            ->group('main_table.item_id');

        return $this;
    }

    public function joinProductName()
    {
        $entityTypeId = Mage::getResourceModel('catalog/config')
            ->getEntityTypeId();
        $attribute = Mage::getModel('catalog/entity_attribute')
            ->loadByCode($entityTypeId, 'name');

        $this->getSelect()
            ->join(
                array('product_name_table' => $attribute->getBackendTable()),
                'product_name_table.entity_id=main_table.product_id' .
                ' AND product_name_table.store_id=0' .
                ' AND product_name_table.attribute_id=' . $attribute->getId() .
                ' AND product_name_table.entity_type_id=' . $entityTypeId,
                array(
                    'product_name' => 'product_name_table.value',
                )
            );

        $this->_map['fields']['product_name'] = 'product_name_table.value';

        return $this;
    }

    public function joinProductEntity($fields = '*')
    {
        if ($fields == '*') {
            $fields = array(
                '*'
            );
        } elseif (is_array($fields)) {
            $tmpFields = array();
            foreach ($fields as $alias => $field) {
                $tmpFields[$alias] = 'catalog_product_entity.' . $field;
            }
        }
        $entityTypeId = Mage::getResourceModel('catalog/config')
            ->getEntityTypeId();
        $this->getSelect()
            ->join(
                array('catalog_product_entity' => $this->getTable(
                    'catalog/product'
                )),
                'catalog_product_entity.entity_id=main_table.product_id' .
                ' AND catalog_product_entity.entity_type_id=' . $entityTypeId,
                $fields
            );


        $this->_map['fields']['product_sku'] = 'catalog_product_entity.sku';

        return $this;
    }

    public function joinEvent($fieldsEvent = null)
    {
        if(is_null($fieldsEvent)){
            $fieldsEvent = array('title' => 'amgiftreg.event_title');
        }
        $this->getSelect()
            ->join(
                array('amgiftreg' => $this->getTable('amgiftreg/event')),
                'amgiftreg.event_id = main_table.event_id',
                $fieldsEvent
            );

        return $this;
    }

    public function getPopularGifts()
    {
        $this->addProducts();

        //$this->getSelect()->columns('product_id')
        return $this;
    }

    public function addProducts()
    {
        $this->_loadProducts = true;
    }



    protected function _afterLoad()
    {
        parent::_afterLoad();

        if($this->_loadProducts) {
            $this->_loadProducts();
        }

        return $this;
    }

    protected function _loadProducts()
    {
        $ids = array();
        foreach($this as $item)
        {
            $ids[$item->getProductId()] = $item->getProductId();
        }

        $collection = Mage::getModel('catalog/product')->getResourceCollection()
            ->addIdFilter($ids)
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes());
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        $products = array();

        foreach($collection as $product) {
            $products[$product->getId()] = $product;
        }

        foreach($this as $item) {
            if(isset($products[$item->getProductId()])) {
                $item->setProduct($products[$item->getProductId()]);
            }
        }
    }
}
