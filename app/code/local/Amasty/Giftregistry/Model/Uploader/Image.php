<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_Uploader_Image extends Amasty_Giftregistry_Model_Uploader_Abstract
{
	protected function _init()
	{
		parent::_init();

		$this->setAllowedExtensions(array('jpg','jpeg','gif','png'));
	}


}