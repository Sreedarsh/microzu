<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_View_Head extends Mage_Core_Block_Template
{
	/**
	 * @var Amasty_Giftregistry_Model_Event
	 */
	protected $_event;

	/**
	 * @var int
	 */
	protected $_customerId = null;

	protected function _prepareLayout()
	{
		parent::_prepareLayout();

		$this->_event = Mage::registry('current_event');
	}
	/**
	 * @return Amasty_Giftregistry_Model_Event
	 */
	public function getEvent()
	{
		return $this->_event;
	}

	public function getFacebookTitle()
	{
		return $this->getEvent()->getData('event_title');
	}

	/**
	 * @return string
	 */
	public function getFacebookDescription()
	{
		return Mage::helper('amgiftreg')->getRegistryShareText($this->getEvent()->getId());
	}
}