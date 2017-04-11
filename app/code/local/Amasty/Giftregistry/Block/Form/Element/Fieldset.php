<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Form_Element_Fieldset extends Varien_Data_Form_Element_Fieldset
{


	/**
	 * Enter description here...
	 *
	 * @return string
	 */
	public function getElementHtml()
	{
		$html = "<div class='fieldset'>\n";
		if ($this->getLegend()) {
			$html.= '<h2 class="legend">'.$this->getLegend().'</h2>'."\n";
		}
		$html .= "<ul class='form-list'>";
		$html.= $this->getChildrenHtml();
		$html .= "</ul>";
		$html .= "</div>\n";

		/*$html = '<fieldset id="'.$this->getHtmlId().'"'.$this->serialize(array('class')).'>'."\n";
		if ($this->getLegend()) {
			$html.= '<legend>'.$this->getLegend().'</legend>'."\n";
		}
		$html.= $this->getChildrenHtml();
		$html.= '</fieldset></div>'."\n";*/
		$html.= $this->getAfterElementHtml();
		return $html;
	}

	/**
	 * Enter description here...
	 *
	 * @return string
	 */
	public function getChildrenHtml()
	{
		$html = '';
		foreach ($this->getSortedElements() as $element) {
			if ($element->getType() != 'fieldset') {
				$html .= '<li>';
				$html.= $element->toHtml();
				$html .= '</li>';
			}
		}
		return $html;
	}

	/**
	 * Enter description here...
	 *
	 * @return string
	 */
	public function getDefaultHtml()
	{
		//$html = '<div><h4 class="icon-head head-edit-form fieldset-legend">'.$this->getLegend().'</h4>'."\n";
		$html = '';
		$html.= $this->getElementHtml();
		return $html;
	}
}
