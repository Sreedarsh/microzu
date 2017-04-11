<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_Mysql4_Event extends Mage_Core_Model_Mysql4_Abstract
{
	protected function _construct()
	{
		$this->_init('amgiftreg/event', 'event_id');
	}

	public function getLastEventId($customerId)
	{
		$select = $this->_getReadAdapter()->select()
			->from($this->getMainTable(), 'event_id')
			->where('customer_id = ?',  $customerId)
			->order('event_id DESC') // for comatibility
			->limit(1);
		return $this->_getReadAdapter()->fetchOne($select);
	}
	/*
		public function clearDefault($customerId)
		{
			$bind  = array('is_default'      => 0);
			$where = array('customer_id = ?' => $customerId);
			$this->_getWriteAdapter()->update($this->getMainTable(), $bind, $where);
		}*/

}