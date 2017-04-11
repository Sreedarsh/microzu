<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Adminhtml_Amgiftregistry_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id'; 
        $this->_blockGroup = 'amgiftreg';
        $this->_controller = 'adminhtml_amgiftregistry';
    }

    public function getHeaderText()
    {
        $header = "";
        $model = Mage::registry('amgiftreg_event');
        if ($model->getId()){
            $header = Mage::helper('amgiftreg')->__('%s %s Gift Registry', $model->getCustomer()->getName(), $model->getEventTitle());
        }
        return $header;
    }
}