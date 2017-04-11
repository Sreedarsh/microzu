<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_List extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();

        /* @var $eventCollection Amasty_Giftregistry_Model_Mysql4_Event_Collection */
        $eventCollection =  Mage::getResourceModel('amgiftreg/event_collection');
        $eventCollection->addFilter('searchable', 1);
        $eventCollection->addFieldToFilter('event_date', array('from'=>date('Y-m-d')));
        $eventTitle = Mage::registry('filter_event_title');

        if($eventTitle) {
            $eventTitle = Mage::helper('amgiftreg')->escapeLike($eventTitle);
            $eventCollection->addFieldToFilter(
                'event_title',
                array(
                    'like'=> "%".$eventTitle."%"
                )
            );
        }

        $eventCollection->setOrder('created_at', 'desc');


        $this->setEvents($eventCollection);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($this->getTitle());
        }

        $pager = $this->getLayout()->createBlock('page/html_pager', 'amasty.giftregistry.events.pager')
            ->setCollection($this->getEvents());
        $this->setChild('pager', $pager);
        $this->getEvents()->load();
        return $this;

    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->__('Gift Registries');
    }
}