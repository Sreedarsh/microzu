<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_Uploader_Csv extends Amasty_Giftregistry_Model_Uploader_Abstract
{
	protected function _init()
	{
		parent::_init();

		$this->setAllowedExtensions(array('csv'));
	}

	public function getTempFilePath()
	{
		return $this->_file['tmp_name'];
	}


}