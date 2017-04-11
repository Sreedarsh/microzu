<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_GiftRegistry_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getAddUrl($product)
    {
        $url = '';
        if (Mage::getStoreConfig('amgiftreg/general/active'))
            $url =  $this->_getUrl('amgiftreg/event/addItem', array('product'=>$product->getId()));
             
        return $url;
    }

    public function getAddUrlById($productId, $eventId = null)
    {
        $url = '';
        if (Mage::getStoreConfig('amgiftreg/general/active'))
            $url =  $this->_getUrl('amgiftreg/event/addItem', array('product'=>$productId, 'event'=>$eventId));

        return $url;
    }

    public function getRegistryUrl($registryId)
    {
        $url = '';
        if (Mage::getStoreConfig('amgiftreg/general/active'))
            $url =  $this->_getUrl('amgiftreg/gift/view', array('id'=>$registryId));

        return $url;
    }


    public function getRegistryShareText($registry_id)
    {
        $shareUrl = $this->getRegistryUrl($registry_id);
        $shareText = $this->__("Hello Guys, I have a Gift Registry at %s. Thank you for the gifts.", $shareUrl);

        return $shareText;
    }

    public function getListEvents()
    {
        return Mage::getResourceModel('amgiftreg/event_collection')
            ->addCustomerFilter(Mage::getSingleton('customer/session')->getCustomerId())
            ->load();
    }

    public function getListItemPriority()
    {

        return array(
            10 => $this->__('Low'),
            20 => $this->__('Medium'),
            30 => $this->__('High'),
        );
    }

    public function getPriorityById($priorityId)
    {
        $listPriority = $this->getListItemPriority();
        return isset($listPriority[$priorityId]) ? $listPriority[$priorityId] : null;
    }

    public function escapeLike($value)
    {
        $escapeChars = array(
            "%" => "\\%",
            "_" => "\\_",
        );

        return str_replace(array_keys($escapeChars), array_values($escapeChars), $value);
    }
	/**
	 * Parse file with products
	 *
	 * @param $csvFileName
	 * @return array
	 */
	/*public function parseProductCsv($csvFileName)
	{
		$csv = new Varien_File_Csv();

		return $csv->getData(Mage::getBaseDir('media') . self::CSV_FOLDER_PATH . $csvFileName);
	}

    public function getCsvFolderPath()
    {
        return self::CSV_FOLDER_PATH;
    }*/
}