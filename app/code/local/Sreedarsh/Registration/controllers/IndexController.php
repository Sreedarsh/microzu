<?php

/**
 * Microzu registraion
 *
 * @category      Microzu
 * @package       Sreedarsh_Registration
 * @author Sreedarsh <sreedarsh88@gmail.com>
 */
class Sreedarsh_Registration_IndexController extends Mage_Core_Controller_Front_Action {
    
    
    public function eduAction() {
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::helper('sreedarsh_registration')->__('Educational Shopper'));
        }
        
        $this->renderLayout();
    }
   
    public function bizAction() {
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::helper('sreedarsh_registration')->__('Business Shopper'));
        }
        
        $this->renderLayout();
    }
}
