<?php 

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    Ced
 * @package     Ced_CsProduct
 * @author 		CedCommerce Core Team <coreteam@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Bundle selection product block
 *
 * @category   Ced
 * @package    Ced_CsProduct
 * @author 	   CedCommerce Core Team <coreteam@cedcommerce.com>
 */

class Ced_CsProduct_Block_Edit_Tab_Bundle_Option_Search extends Mage_Bundle_Block_Adminhtml_Catalog_Product_Edit_Tab_Bundle_Option_Search
{

    protected function _prepareLayout()
    {
        $this->setChild(
            'vgrid',
            $this->getLayout()->createBlock('csproduct/edit_tab_bundle_option_search_grid')
        );
        return parent::_prepareLayout();
    }
    
    protected function _beforeToHtml()
    {
    	parent::_beforeToHtml();
    	$this->unsetChild('grid');
    	$this->getChild('vgrid')->setIndex($this->getIndex())
    	->setFirstShow($this->getFirstShow());
    
    	return $this;
    }
}
