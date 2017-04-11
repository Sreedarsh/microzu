<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Form_MultiFile extends Varien_Data_Form_Element_File
{
	/**
	 * @param array $attributes
	 */
	public function __construct($attributes = array())
	{
		parent::__construct($attributes);
	}

	/**
	 * @return string
	 */
	protected function _getInput(){
		$input = '<input {id} {value} name="'.$this->getName()
			.'[]" '.$this->serialize($this->getHtmlAttributes()).'/>';
		return $input;
	}

	/**
	 * @return string
	 */
	public function getElementHtml()
	{
		$replace = array(
			'{id}' => 'id="'.$this->getHtmlId().'"',
			'{value}' => 'value="'.$this->getEscapedValue().'"',
		);
		$input = str_replace(array_keys($replace), array_values($replace), $this->_getInput());
		$html = $input."\n";
		$html.= $this->getAfterElementHtml();
		return $html;
	}

	/**
	 * @return string
	 */
	public function getAfterElementHtml()
	{
		$afterHtml = "<button id='btn_add_file_field' onclick='amgiftreg_add_file_field(this);return false;'>+</button>";

		//$input = $this->_getInput();
		$js = sprintf('
			<script type="text/javascript">
            //<![CDATA[
                function amgiftreg_add_file_field(btn){
					var inputHtml = \'%s\';
					inputHtml = inputHtml.replace("{id}", "");
					inputHtml = inputHtml.replace("{value}", "");
					var input = document.createElement("span");
					input.innerHTML = "<br>" + inputHtml;
					//insertAfter(input, btn);
					btn.parentNode.insertBefore(input, null);
					//console.log($(btn));
					//$("btn_add_file_field").after(input);
                }
            //]]>
            </script>
		',
		$this->_getInput()
		);
		return $afterHtml.$this->getData('after_element_html').$js;
	}


}