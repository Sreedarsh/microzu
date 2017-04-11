<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Popular extends Mage_Core_Block_Template
{
	protected $_popularGifts = null;
	public function getPopularGifts()
	{
		/*if(is_null($this->_popularGifts)){
			$this->_popularGifts = Mage::getResourceModel("amgiftreg/item_collection")->addProducts();
		}
		return $this->_popularGifts;*/
		/* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
		$collection = Mage::getModel('catalog/product')->getResourceCollection()
			//->addIdFilter($ids)
			->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes());
		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);

		$collection
			->getSelect()
			->join(
				array('item' => Mage::getResourceModel("amgiftreg/item")->getTable("amgiftreg/item")),
				'item.product_id = e.entity_id',
				array('count_products'=>'COUNT(*)')
			)
			->group('item.product_id')
			->order('count_products DESC')
			->limit(3);

		return $collection;
	}
}