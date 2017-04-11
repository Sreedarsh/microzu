<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_GiftRegistry_Block_Adminhtml_Amgiftregistry extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_amgiftregistry';
        $this->_blockGroup = 'amgiftreg';
        $this->_headerText = Mage::helper('amgiftreg')->__('Gift Registries');
        //$this->_addButtonLabel = Mage::helper('amlanding')->__('');
        parent::__construct();
    }

    public function getButtonsHtml($area = null)
    {
        $this->removeButton('add');
        parent::getButtonsHtml($area);
    }
}