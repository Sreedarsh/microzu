<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Form_Element_Time extends Varien_Data_Form_Element_Time
{
	protected $_defaultTemplate = '{hour} : {minute} : {second}';

	/**
	 * @return string
	 */
	public function getElementHtml()
	{

		$this->addClass('select');

		$emptyValues = $this->getData('emptyValues');
		if($emptyValues === true) {
			$emptyValues = '';
		}

		if(is_string($emptyValues)){
			$emptyValues = array(
				'hour' => $emptyValues,
				'minute' => $emptyValues,
				'second' => $emptyValues,
			);
		}

		$values = array(
			'hour' => 0,
			'minute' => 0,
			'second' => 0,
		);

		foreach($emptyValues as $field=>$emptyValue) {
			$values[$field] = '';
		}


		if( $value = $this->getValue() ) {
			$fieldValues = explode(':', $value);
			if( is_array($fieldValues) && count($fieldValues) == 3 ) {
				list($values['hour'], $values['minute'], $values['second']) = $fieldValues;
			}
		}


		$template = $this->getDataSetDefault('template', $this->_defaultTemplate);


		$html = '<input type="hidden" id="' . $this->getHtmlId() . '" />';
		$hourHtml = '<select name="'. $this->getName() . '" '.$this->serialize($this->getHtmlAttributes()).' style="width:40px">'."\n";
		if(isset($emptyValues['hour']) && $emptyValues['hour'] !== false) {
			$hourHtml .= '<option value="" '. ( ($values['hour'] === '') ? 'selected="selected"' : '' ) .'>' . $emptyValues['hour'] . '</option>';
		}
		for( $i=0;$i<24;$i++ ) {
			$hour = str_pad($i, 2, '0', STR_PAD_LEFT);
			$hourHtml.= '<option value="'.$hour.'" '. ( ($values['hour'] === $hour) ? 'selected="selected"' : '' ) .'>' . $hour . '</option>';
		}
		$hourHtml.= '</select>'."\n";


		$minuteHtml = '<select name="'. $this->getName() . '" '.$this->serialize($this->getHtmlAttributes()).' style="width:40px">'."\n";
		if(isset($emptyValues['minute']) && $emptyValues['minute'] !== false) {
			$minuteHtml .= '<option value="" '. ( ($values['minute'] === '') ? 'selected="selected"' : '' ) .'>' . $emptyValues['minute'] . '</option>';
		}
		for( $i=0;$i<60;$i++ ) {
			$hour = str_pad($i, 2, '0', STR_PAD_LEFT);
			$minuteHtml.= '<option value="'.$hour.'" '. ( ($values['minute'] === $hour) ? 'selected="selected"' : '' ) .'>' . $hour . '</option>';
		}
		$minuteHtml.= '</select>'."\n";

		$secondHtml = '<select name="'. $this->getName() . '" '.$this->serialize($this->getHtmlAttributes()).' style="width:40px">'."\n";
		if(isset($emptyValues['second']) && $emptyValues['second'] !== false) {
			$secondHtml .= '<option value="" '. ( ($values['second'] === '') ? 'selected="selected"' : '' ) .'>' . $emptyValues['second'] . '</option>';
		}
		for( $i=0;$i<60;$i++ ) {
			$hour = str_pad($i, 2, '0', STR_PAD_LEFT);
			$secondHtml.= '<option value="'.$hour.'" '. ( ($values['second'] === $hour) ? 'selected="selected"' : '' ) .'>' . $hour . '</option>';
		}
		$secondHtml.= '</select>'."\n";

		$html .= str_replace(array('{hour}', '{minute}', '{second}'), array($hourHtml, $minuteHtml, $secondHtml), $template);
		$html.= $this->getAfterElementHtml();
		return $html;
	}
}