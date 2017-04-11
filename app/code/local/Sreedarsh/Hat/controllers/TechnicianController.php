<?php

/**
 * Hire A Technician
 *
 * @category      Sreedarsh
 * @package       Sreedarsh_Hat
 * @author Sreedarsh <sreedarsh88@gmail.com>
 */
class Sreedarsh_Hat_TechnicianController extends Mage_Core_Controller_Front_Action {
    
    
    public function indexAction() {
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::helper('sreedarsh_hat')->__('Technician'));
        }
       
        
        $this->renderLayout();
    }
    
    
    public function categoryAction(){
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::helper('sreedarsh_hat')->__('Category'));
        }
       
        
        $this->renderLayout();
    }
    
    public function skillAction(){
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::helper('sreedarsh_hat')->__('Select a Technician'));
        }
       
        
        $this->renderLayout();
    }
    
     public function fulllistAction(){
        $this->loadLayout();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::helper('sreedarsh_hat')->__('Select a Technician'));
        }
       
        
        $this->renderLayout();
    }
    
    
   
    
}

