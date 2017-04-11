<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Index extends Mage_Core_Block_Template
{ 
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        $lists = Mage::getResourceModel('amgiftreg/event_collection')
            ->addCustomerFilter(Mage::getSingleton('customer/session')->getCustomerId());

        $this->setLists($lists);

        $pager = $this->getLayout()->createBlock('page/html_pager', 'amasty.giftregistry.events.pager')
            ->setCollection($lists);
        $this->setChild('pager', $pager);

        
        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($this->__('My Gift Registries'));
        }
    }


    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}