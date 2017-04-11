<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Renderer_Field implements Varien_Data_Form_Element_Renderer_Interface
{
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		//$html = ( $element->getNoSpan() === true ) ? '' : '<span class="field-row">'."\n";
		$html = '<li>';
		$html.= $element->getLabelHtml();
		$html .= '<div class="input-box">';
		$html.= $element->getElementHtml();
		$html .= "</div>";
		$html .= "</li>";
		//$html.= ( $element->getNoSpan() === true ) ? '' : '</span>'."\n";
		return $html;
	}

}