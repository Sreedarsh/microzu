<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Edit extends Mage_Core_Block_Template
{
    /**
     * @var Amasty_Giftregistry_Model_Event
     */
    protected $_event;

    /**
     * @var Mage_Customer_Model_Customer
     */
    protected $_customer;

    /**
     * Preparing layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        $this->_event = Mage::registry('current_event');

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($this->getTitle());
        }
        if ($postedData = Mage::getSingleton('amgiftreg/session')->getEventFormData(true)) {
            $this->_event->setData($postedData);
        }
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        if ($title = $this->getData('event_title')) {
            return $title;
        }
        if ($this->getEvent()->getId()) {
            $title = Mage::helper('amgiftreg')->__('Edit Gift Registry');
        }
        else {
            $title = Mage::helper('amgiftreg')->__('Add New Gift Registry');
        }
        return $title;
    }

    /**
     * @return Amasty_Giftregistry_Model_Event
     */
    public function getEvent()
    {
        return $this->_event;
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {
        if (empty($this->_customer)) {
            $this->_customer = Mage::getSingleton('customer/session')->getCustomer();
        }
        return $this->_customer;
    }

    /**
     * @return array
     */
    public function getShippingAddresses()
    {
        $options = array(
            '' => $this->__("Address specified by gift buyer"),
        );
        foreach ($this->getCustomer()->getAddresses() as $address) {
            $options[$address->getId()] = $address->format('oneline');
        }
        return $options;
    }

    /**
     * @return int
     */
    public function getDefaultShippingAddressId()
    {
        $addressId = 0;
        /*$address = $this->getCustomer()->getPrimaryShippingAddress();
        if ($address) {
            $addressId = $address->getId();
        }*/

        return $addressId;
    }
}