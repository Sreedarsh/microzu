<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog fieldset element renderer
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class  Ced_CsProduct_Block_Widget_Form_Renderer_Fieldset_Element
    extends Mage_Adminhtml_Block_Catalog_Form_Renderer_Fieldset_Element
{
    /**
     * Initialize block template
     */
    protected function _construct()
    {
        $this->setTemplate('csproduct/widget/form/renderer/fieldset/element.phtml');
    }
    
    /**
     * Retrieve label of attribute scope
     *
     * GLOBAL | WEBSITE | STORE
     *
     * @return string
     */
    public function getScopeLabel()
    {
    	$html = '';
    	$attribute = $this->getElement()->getEntityAttribute();
    	if (!$attribute || $attribute->getFrontendInput()=='gallery') {
    		return $html;
    	}
    
    	/*
    	 * Check if the current attribute is a 'price' attribute. If yes, check
    	 * the config setting 'Catalog Price Scope' and modify the scope label.
    	 */
    	$isGlobalPriceScope = false;
    	if ($attribute->getFrontendInput() == 'price') {
    		$priceScope = Mage::getStoreConfig('catalog/price/scope');
    		if ($priceScope == 0) {
    			$isGlobalPriceScope = true;
    		}
    	}
    
    	if ($attribute->isScopeGlobal() || $isGlobalPriceScope) {
    		$html .= Mage::helper('adminhtml')->__('[GLOBAL]');
    	} elseif ($attribute->isScopeWebsite()) {
    		$html .= Mage::helper('adminhtml')->__('[WEBSITE]');
    	} elseif ($attribute->isScopeStore()) {
    		$html .= Mage::helper('adminhtml')->__('[STORE VIEW]');
    	}
    
    	return $html;
    }
}
