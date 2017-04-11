<?php

/**
 * Hire A Technician
 *
 * @category      Sreedarsh
 * @package       Sreedarsh_Hat
 * @author Sreedarsh <sreedarsh88@gmail.com>
 */
class Sreedarsh_Hat_Block_Hireme extends Mage_Core_Block_Template {

    public function getTech() {
        $collection = Mage::getModel('membership/member')->getCollection()->addFieldToFilter('status', 1);
        
        return $collection;
    }

    public function getIndividual($user) {
        $technician = Mage::getModel('membership/member')->load($user);

        return $technician;
    }

    public function getPackage($memberid) {
        $mempackage_model = Mage::getModel('membership/memberpackage');
        $member_load = $mempackage_model->load($memberid, 'member_id');
        return $member_load->getPackageId();
    }

    public function getDetails($member_id) {

        $model = Mage::getModel('customer/customer')->load($member_id);
        $address = Mage::getModel('customer/address')->load($model->getDefaultBilling());
        $customer_pic = $model->getAvatarImage();
        $verified = $model->getVerifiedMember();
        $customer_tel = $address->getTelephone();
        return array($customer_tel, $customer_pic, $verified);
    }

    public function getProfileinfo() {
        $model = Mage::getModel('customer/customer')->load($this->getRequest()->getParam('id'));
        return $model;
    }

    public function retriveParentList() {
        $collection = Mage::getModel('sreedarsh_hat/parent')->getCollection();
        return $collection;
    }

    public function retriveChildList($parent_id) {
        $collection = Mage::getModel('sreedarsh_hat/child')->getCollection()
                ->addFieldToFilter('parent_id', $parent_id);

        return $collection;
    }

    public function retriveTechlist($id) {
        $param = $this->getRequest()->getParam('cat');
        $collection = Mage::getModel('sreedarsh_hat/tech')->getCollection()
                ->addFieldToFilter('child_id', $param);

        return $collection;
    }

    public function retriveAllTech() {
        $collection = Mage::getModel('sreedarsh_hat/tech')->getCollection();
        return $collection;
    }

    public function customer() {
        $customer = Mage::getModel('customer/customer');

        return $customer;
    }

    public function getCount($id) {

        $model = Mage::getModel('sreedarsh_hat/tech')->getCollection()->addFieldToFilter('child_id', $id);
        return $model->count();
    }

    public function getCatname($id) {
        $model = Mage::getModel('sreedarsh_hat/child')->load($id);
        return $model->getName();
    }

}
