<?php 
class Ced_CsProduct_Block_Adminhtml_Product_Renderer_Categories extends  Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_element;

	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		return $this->_toHtml();
		
	}

}
