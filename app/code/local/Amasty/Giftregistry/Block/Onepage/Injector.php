<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Onepage_Injector extends Mage_Core_Block_Template
{
	protected $_isNeedInjection = false;
	/**
	 * @var Mage_Customer_Model_Address
	 */
	protected $_shippingAddress;

	protected function _construct()
	{
		parent::_construct();

		if($this->_loadShippingAddress())
		{
			$this->_isNeedInjection = true;
		}
	}

	protected function _loadShippingAddress()
	{
		$quote = Mage::getSingleton('checkout/cart');
		$listEventItemIds = array();
		$isData = false;
		foreach($quote->getItems() as $item) {
			/* @var $item Mage_Sales_Model_Quote_Item */
			$buyRequest = $item->getBuyRequest();
			if(!empty($buyRequest['amgiftreg_item_id'])) {
				$listEventItemIds[$buyRequest['amgiftreg_item_id']] = $buyRequest['amgiftreg_item_id'];
				$isData = true;
			}
		}
		if(!$isData){
			return false;
		}

		$isData = false;
		$eventCollection = Mage::getResourceModel('amgiftreg/item_collection')->joinEvent(array("amgiftreg.shipping_address_id"))->addFieldToFilter("item_id", array("in"=>$listEventItemIds));
		$eventCollection->getSelect()->group("main_table.event_id");
		$shippingAddressId = null;
		foreach($eventCollection as $item) {

			$shippingAddressId = $item->getShippingAddressId();
			if(empty($shippingAddressId)) {
				continue;
			}
			$isData = true;
			break;
		}
		if(!$isData){
			return false;
		}
		/* @var $shippingAddress */
		//Mage_Customer_Model_Address::TYPE_SHIPPING;
		$shippingAddress = Mage::getModel("customer/address")->load($shippingAddressId);
		$this->_shippingAddress = $shippingAddress;

		if(!$shippingAddress->getId()) {
			return false;
		}

		return true;
	}

	public function isNeedInjection()
	{
		return $this->_isNeedInjection;
	}

	public function isInjected()
	{
		/* @var $quote Mage_Checkout_Model_Cart */
		$quote = Mage::getSingleton('checkout/cart');
		//echo "<pre>";
		$listEventItemIds = array();
		$isData = false;
		foreach($quote->getItems() as $item) {
			/* @var $item Mage_Sales_Model_Quote_Item */
			$buyRequest = $item->getBuyRequest();
			if(!empty($buyRequest['amgiftreg_item_id'])) {
				$listEventItemIds[$buyRequest['amgiftreg_item_id']] = $buyRequest['amgiftreg_item_id'];
				$isData = true;
			}
		}
	}

	/**
	 * @return Mage_Customer_Model_Address
	 */
	public function getShippingAddress()
	{
		return $this->_shippingAddress;
	}

	public function getShippingAddressData()
	{
		$shippingAddressFields = array(
			'firstname',
			'lastname',
			'company',
			'city',
			'country_id',
			'region',
			'postcode',
			'telephone',
			'fax',
			'region_id',
			'street'
		);
		$data  = array();
		foreach($shippingAddressFields as $field) {
			if($field == 'street') {
				$data[$field."1"] = $this->getShippingAddress()->getData($field);
			} else {
				$data[$field] = $this->getShippingAddress()->getData($field);
			}

		}
		return $data;
	}

}