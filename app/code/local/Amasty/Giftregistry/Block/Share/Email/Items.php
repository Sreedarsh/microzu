<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Share_Email_Items extends Mage_Catalog_Block_Product_Abstract
{

	/**
	 * @var Amasty_Giftregistry_Model_Event
	 */
	protected $_event;
	/**
	 * Initialize template
	 *
	 */
	public function __construct()
	{
		$this->_event = Mage::registry('current_event');
		$this->setTemplate('amasty/amgiftreg/email/items.phtml');

		parent::__construct();
	}

	/**
	 * @return Amasty_Giftregistry_Model_Event
	 */
	public function getEvent()
	{
		return $this->_event;
	}

	/**
	 * Retrieve Product View URL
	 *
	 * @param Mage_Catalog_Model_Product $product
	 * @param array $additional
	 * @return string
	 */
	public function getProductUrl($product, $additional = array())
	{
		$additional['_store_to_url'] = true;
		return parent::getProductUrl($product, $additional);
	}

	/**
	 * Retrieve Add Item to shopping cart URL from shared wishlist
	 *
	 * @param Amasty_Giftregistry_Model_Item $item
	 * @return string
	 */
	public function getSharedItemAddToCartUrl($item)
	{
		$params = array(
			'event_id' => $this->getEvent()->getId(),
			'add_id'=> $item->getId(),
		);
		return $this->getUrl('*/gift/cart', $params);
	}
}
