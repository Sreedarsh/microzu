<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Sreedarsh_Registration_Model_Observer{
    public function onlyOne(Varien_Event_Observer $observer){
        $product = $observer->getProduct();
        
        if ($product->getTypeId() != 'virtual') {
            return $this;
        }
        $flag = false;
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        foreach ($quote->getAllItems() as $item) {
            if ($item->getProductType() == 'virtual') {
                $flag = true;
                break;
            } 
        }
        if($flag){
            Mage::throwException('You can purchase only singles membership at a time.');
         
        }
       
        
    }
}

