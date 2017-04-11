<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Share extends Mage_Catalog_Block_Product_Abstract
{
	/**
	 * Entered Data cache
	 *
	 * @param array
	 */
	protected  $_enteredData = null;

	/**
	 * @return Amasty_Giftregistry_Model_Event
	 */
	public function getEvent()
	{
		return Mage::registry('current_event');
	}


	/**
	 * Retrieve Send Form Action URL
	 * @param array $params Url params for getUrl( , $params)
	 *
	 * @return string
	 */
	public function getSendUrl($params = array())
	{
		return $this->getUrl('*/*/send', $params);
	}


	/**
	 * Retrieve Entered Data by key
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getEnteredData($key, $default = null)
	{
		if (is_null($this->_enteredData)) {
			$this->_enteredData = Mage::getSingleton('amgiftreg/session')
				->getData('sharing_form', true);
		}

		if (!$this->_enteredData || !isset($this->_enteredData[$key])) {
			return $default;
		}
		else {
			return $this->escapeHtml($this->_enteredData[$key]);
		}
	}

	/**
	 * Retrieve back button url
	 *
	 * @return string
	 */
	public function getBackUrl()
	{
		return $this->getUrl('*/*/index');
	}

}