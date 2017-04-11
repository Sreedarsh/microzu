<?php

/**
 * Hire A Technician
 *
 * @category      Sreedarsh
 * @package       Sreedarsh_Hat
 * @author Sreedarsh <sreedarsh88@gmail.com>
 */
class Sreedarsh_Hat_IndexController extends Mage_Core_Controller_Front_Action {
    
    
    public function indexAction() {
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::helper('sreedarsh_hat')->__('Hire A Technician'));
        }
        
        $this->renderLayout();
    }
   
    
}
