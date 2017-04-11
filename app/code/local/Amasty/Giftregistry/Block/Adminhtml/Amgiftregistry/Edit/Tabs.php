<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Adminhtml_Amgiftregistry_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ruleTabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('amgiftreg')->__('Registry Information'));
    }

    protected function _beforeToHtml()
    {
        $tabs = array(            
			'item'    	=> 'Items',
			'order'    => 'Orders',
        );
        
        foreach ($tabs as $code => $label){
            $label = Mage::helper('amgiftreg')->__($label);
            $content = $this->getLayout()->createBlock('amgiftreg/adminhtml_amgiftregistry_edit_tab_' . $code)
                ->setTitle($label)
                ->toHtml();
                
            $this->addTab($code, array(
                'label'     => $label,
                'content'   => $content,
            ));
        }
        
        return parent::_beforeToHtml();
    }
}