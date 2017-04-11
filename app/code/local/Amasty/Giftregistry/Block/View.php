<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_View extends Mage_Core_Block_Template
{
    /**
     * @var Amasty_Giftregistry_Model_Event
     */
    protected $_event;

    /**
     * @var int
     */
    protected $_customerId = null;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->_event = Mage::registry('current_event');

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($this->getTitle());
        }
        /*if ($postedData = Mage::getSingleton('amgiftreg/session')->getEventFormData(true)) {
            $this->_event->setData($postedData);
        }*/

        $session = Mage::getSingleton('customer/session');
        $this->_customerId = $session->getCustomer()->getId();



        // Add breadcrumbs
        $this->addBreadCrumbs();
    }

    protected function addBreadCrumbs()
    {
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb('home', array(
                'label'=>Mage::helper('catalogsearch')->__('Home'),
                'title'=>Mage::helper('catalogsearch')->__('Go to Home Page'),
                'link'=>Mage::getBaseUrl()
            ))->addCrumb('amgiftreg_list', array(
                'label'=>Mage::helper('amgiftreg')->__('Gift Registries'),
                'title'=>Mage::helper('amgiftreg')->__('Gift Registries'),
                'link' => $this->getUrl('amgiftreg/gift/list')
            ))->addCrumb('amgiftreg_item', array(
                'label'=>$this->getTitle(),
            ))
            ;
        }
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if ($title = $this->getEvent()->getData('event_title')) {
            return $title;
        }
        if ($this->getEvent()->getId()) {
            $title = Mage::helper('amgiftreg')->__('Gift Registry Details');
        }
        else {
            $title = Mage::helper('amgiftreg')->__('Create New Registry');
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
     * @return int
     */
    public function getCustomerId() {
        return $this->_customerId;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getActionName()
    {
        return $this->getRequest()->getActionName();
    }
}