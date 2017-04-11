<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_OrderedItems extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * @return Amasty_Giftregistry_Model_Event
     */
    public function getEvent()
    {
        return Mage::registry('current_event');
    }

}