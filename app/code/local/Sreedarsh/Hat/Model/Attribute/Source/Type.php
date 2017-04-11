<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sreedarsh_Hat_Model_Attribute_Source_Type extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    protected $_options = array();

 
    public function getAllOptions()
    {
        
      $collection = Mage::getModel('sreedarsh_hat/child')->getCollection();  
      $this->_options[] = array(
                    'label' => 'Please select',
                    'value' =>  ''
                ); 
      
      foreach($collection as $option){
          
          
              $this->_options[] = array(
                    'label' => $option->getName(),
                    'value' =>  $option->getId()
                ); 
        }  
        
        return $this->_options;
    }
 
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
}