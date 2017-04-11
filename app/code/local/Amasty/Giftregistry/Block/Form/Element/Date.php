<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Form_Element_Date extends Varien_Data_Form_Element_Date//Varien_Data_Form_Element_Abstract
{
	protected function _getDefaultParams()
	{
		$outputFormat = $this->getFormat();
		if (empty($outputFormat)) {
			throw new Exception('Output format is not specified. Please, specify "format" key in constructor, or set it using setFormat().');
		}
		$displayFormat = Varien_Date::convertZendToStrFtime($outputFormat, true, (bool)$this->getTime());

		return array(
			"inputField" => $this->getHtmlId(),
			"ifFormat" => $displayFormat,
			"showsTime" =>  $this->getTime() ? 'true' : 'false',
			"button" => $this->getHtmlId()."_trig",
			"align" => "Bl",
			"singleClick" => "true"
		);
	}

	protected function _getAllCalendarSetupParams()
	{
		return array_merge($this->_getDefaultParams(), (array) $this->getData('calendarSetupParams'));
	}

	public function getElementHtml()
	{
		$this->addClass('input-text');

		$html = sprintf(
			'<input name="%s" id="%s" value="%s" %s style="width:110px !important;" />'
			.' <img src="%s" alt="" class="v-middle" id="%s_trig" title="%s" style="%s" />',
			$this->getName(), $this->getHtmlId(), $this->_escape($this->getValue()), $this->serialize($this->getHtmlAttributes()),
			$this->getImage(), $this->getHtmlId(), 'Select Date', ($this->getDisabled() ? 'display:none;' : '')
		);
		$html = sprintf(
			' <img src="%s" alt="" class="v-middle" id="%s_trig" title="%s" style="%s;margin: 3px;" />'.
			'<input name="%s" id="%s" value="%s" %s style="width:110px !important;float:left;margin: -2px;" />',
			$this->getImage(), $this->getHtmlId(), 'Select Date', ($this->getDisabled() ? 'display:none;' : ''),
			$this->getName(), $this->getHtmlId(), $this->_escape($this->getValue()), $this->serialize($this->getHtmlAttributes())
		);
		//var_dump($this->getData('calendarSetupParams'));
		$calendarSetupParams = json_encode((array) $this->_getAllCalendarSetupParams());
		/*$html .= sprintf('
            <script type="text/javascript">
            //<![CDATA[
            	console.log(JSON.parse(\'%s\'));
                Calendar.setup(JSON.parse(\'%s\'));
            //]]>
            </script>',
			$calendarSetupParams,
			$calendarSetupParams
		);*/

		$outputFormat = $this->getFormat();
		if (empty($outputFormat)) {
			throw new Exception('Output format is not specified. Please, specify "format" key in constructor, or set it using setFormat().');
		}
		$displayFormat = Varien_Date::convertZendToStrFtime($outputFormat, true, (bool)$this->getTime());

		$html .= sprintf('
            <script type="text/javascript">
            //<![CDATA[
                Calendar.setup({
                    inputField: "%s",
                    ifFormat: "%s",
                    showsTime: "%s",
                    button: "%s_trig",
                    align: "Bl",
                    singleClick : true,
                    min: new Date("2014-10-10")
                });
            //]]>
            </script>',
			$this->getHtmlId(), $displayFormat,
			$this->getTime() ? 'false' : 'false', $this->getHtmlId()
		);

		$html .= $this->getAfterElementHtml();

		return $html;
	}
}
