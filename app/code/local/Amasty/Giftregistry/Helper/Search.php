<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Helper_Search extends Mage_Core_Helper_Abstract
{
	/**
     * Maximum query length
     */
	const MAX_QUERY_LEN  = 200;

	/**
	 * Is a maximum length cut
	 *
	 * @var bool
	 */
	protected $_isMaxLength = false;

	/**
	 * Query string
	 *
	 * @var string
	 */
	protected $_queryText;

	/**
	 * Retrieve search query text
	 *
	 * @return string
	 */
	public function getQueryText()
	{
		if (!isset($this->_queryText)) {
			$this->_queryText = $this->_getRequest()->getParam('event_title');
			if ($this->_queryText === null) {
				$this->_queryText = '';
			} else {
				/* @var $stringHelper Mage_Core_Helper_String */
				$stringHelper = Mage::helper('core/string');
				$this->_queryText = is_array($this->_queryText) ? ''
					: $stringHelper->cleanString(trim($this->_queryText));

				$maxQueryLength = $this->getMaxQueryLength();
				if ($maxQueryLength !== '' && $stringHelper->strlen($this->_queryText) > $maxQueryLength) {
					$this->_queryText = $stringHelper->substr($this->_queryText, 0, $maxQueryLength);
					$this->_isMaxLength = true;
				}
			}
		}
		return $this->_queryText;
	}

	/**
	 * Retrieve HTML escaped search query
	 *
	 * @return string
	 */
	public function getEscapedQueryText()
	{
		return $this->escapeHtml($this->getQueryText());
	}

	/**
	 * Retrieve maximum query length
	 *
	 * @return int
	 */
	public function getMaxQueryLength()
	{
		return self::MAX_QUERY_LEN;
	}
}