<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_Uploader_Abstract extends Varien_File_Uploader
{

	function __construct($fileId)
	{
		parent::__construct($fileId);

		$this->_init();
	}

	protected function _init()
	{

	}


	public function getRealCorrectFileName()
	{
		return self::getCorrectFileName($this->_file['name']);
	}
}