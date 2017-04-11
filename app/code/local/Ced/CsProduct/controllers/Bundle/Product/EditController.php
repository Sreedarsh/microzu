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
require_once(Mage::getModuleDir('controllers','Ced_CsProduct').DS.'VproductsController.php');

class Ced_CsProduct_Bundle_Product_EditController extends Ced_CsProduct_VproductsController
{
	public function formAction()
    {
        $product = $this->_initProduct();
        echo $this->getLayout()->createBlock('csproduct/edit_tab_bundle', 'admin.product.bundle.items')
                ->setProductId($product->getId())
                ->toHtml();
    }
}
