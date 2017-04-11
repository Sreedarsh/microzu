<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Items extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @var Amasty_Giftregistry_Model_Mysql4_Event_Collection
     */
    protected $_allEvents = null;

    /**
     * for "move item to"  function
     * @param int $exclude Exclude event id
     *
     * @return Amasty_Giftregistry_Model_Mysql4_Event_Collection
     */
    public function getAllEvents($exclude=0)
    {
        if (is_null($this->_allEvents)){
            $this->_allEvents = Mage::getResourceModel('amgiftreg/event_collection')
                ->addCustomerFilter(Mage::getSingleton('customer/session')->getCustomerId())
                ->addFieldToFilter('main_table.event_id', array('neq' => $exclude))
                ->load(); 
        }
        return $this->_allEvents;
    }

    /**
     * @return Amasty_Giftregistry_Model_Event
     */
    public function getEvent()
    {
        return Mage::registry('current_event');
    }

}