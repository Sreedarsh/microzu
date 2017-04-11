<?php

/**
 * Hire A Technician
 *
 * @category      Sreedarsh
 * @package       Sreedarsh_Hat
 * @author Sreedarsh <sreedarsh88@gmail.com>
 */
class Sreedarsh_Hat_Model_Observer {

    public function groupPackage($observer) {
        try {

            $customer = $observer->getEvent()->getCustomer();

            if ($customer->getShopperType() == 'Business Shopper') {
                $customer->setData('group_id', 4);
            } else if ($customer->getShopperType() == 'Educational Shopper') {
                $customer->setData('group_id', 5);
            }
            $customer->save();
        } catch (Exception $e) {
            
        }
    }

    public function techCategory($observer) {

        $customer = $observer->getEvent()->getCustomer();


        if(($customer->getMemberCategory() > 0) || ($customer->getMemberCategory() != '')){
        
        
                if ((!$customer->isConfirmationRequired()) && $customer->getConfirmation()) {
            $customer->setConfirmation(null)->save(); // here is the second save
            $customer->setIsJustConfirmed(true);
        }
        $model = Mage::getModel('sreedarsh_hat/tech');
        $cus_id = $customer->getId();
        
        $technician = $model->load($cus_id,'customer_id');
    
        
        if($technician->getId()){
            $update_data = array('child_id' => $customer->getMemberCategory());
        $save = $model->load($cus_id,'customer_id')->addData($update_data); 
        $save->save();
        }
        else{
        $data = array('child_id' => $customer->getMemberCategory(), 'customer_id' => $customer->getId());
        $save = $model->setData($data);
        $save->save();
         }
       }
 
    }

}
