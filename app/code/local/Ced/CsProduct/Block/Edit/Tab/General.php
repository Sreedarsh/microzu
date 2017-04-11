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
 * Product attributes tab
 *
 * @category   Ced
 * @package    Ced_CsProduct
 * @author 	   CedCommerce Core Team <coreteam@cedcommerce.com>
 */

class Ced_CsProduct_Block_Edit_Tab_General extends Mage_Adminhtml_Block_Catalog_Form
{
	
	/**
	 * Class constructor
	 *
	 */
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('csmarketplace/widget/form.phtml');
		$this->setDestElementId('edit_form');
		$this->setShowGlobalIcon(false);
		
	}
	
	/**
	 * Preparing global layout
	 *
	 * You can redefine this method in child classes for changin layout
	 *
	 * @return Mage_Core_Block_Abstract
	 */
	protected function _prepareLayout()
	{
		parent::_prepareLayout();
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
		}
		Varien_Data_Form::setElementRenderer(
            $this->getLayout()->createBlock('csmarketplace/widget_form_renderer_element')
        );
        Varien_Data_Form::setFieldsetRenderer(
            $this->getLayout()->createBlock('csproduct/widget_form_renderer_fieldset')
        );
        Varien_Data_Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock('csproduct/widget_form_renderer_fieldset_element')
        );
        
        if (Mage::helper('catalog')->isModuleEnabled('Mage_Cms')
        		&& Mage::getSingleton('cms/wysiwyg_config')->isEnabled()
        ) {
        	$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
	
		return $this ;
	}

    /**
     * Prepare form before rendering HTML
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
    	$taxarray = array('0'=>'--Please Select--','1'=>'None');
    	$taxes = Mage::getModel('tax/class')->getCollection()
    	->addFieldToFilter('class_type',array('eq'=>'PRODUCT'))->getData();
    	$_product=$this->getVproduct()?$this->getVproduct():Mage::registry('current_product');
    	foreach ($taxes as $tax)
    	{
    		$taxarray[]=$tax['class_name'];
    	}
    		
    	
    	//if ($group = $this->getGroup()) {
    		$form = new Varien_Data_Form();
    		$form->setDataObject(Mage::registry('product'));
    		
    		
    		$fieldset = $form->addFieldset('basic',
    				array(
    						'legend'=>Mage::helper('catalog')->__('Basic'),
    						'class'=>'fieldset-wide',
    				));
    		$fieldset->addField('name', 'text',
    				array(
    						'label' => 'Product Name',
    						'class' => 'required-entry input-text form-control',
    						'required' => true,
    						'name' => 'name',
    				));
    		$fieldset->addField('sku', 'text',
    				array(
    						'label' => 'SKU',
    						'class' => 'form-control required-entry input-text',
    						'required' => true,
    						'name' => 'sku',
    				))->setAfterElementHtml('
 						  <script>
						jced("#sku").change(function(){  									   		
					   		var sku=jced("#sku").val();
							var skulength=sku.length;
    						if(skulength==0)
    						{
								alert("SKU can\'t be empty");
								jced("#skuavailable").css("display","none");
								jced("#skunotavailable").css("display","none");
							}
    						else
    						{
    							jced.ajax({
									url: "'.Mage::getUrl("csmarketplace/vproducts/checkSku",array("id"=>$_product->getId(),"_secure"=>false,"_nosid"=>true)).'",
									type: "POST",
									data: {sku:sku},
									dataType: "html",
									success:function($data){
										$data=JSON.parse($data);
										if($data.result==1)
										{
                                           var l=document.getElementsByClassName("skuavailable");
    						              
		        							if(l.length>0)
				    						{ 
				    							jced(".skuavailable").remove();
				    						}
				    						if(document.getElementById("sku-error-msg skunotavailable"))
				    						{
				    							jced(".skunotavailable").remove();
				    						}
											var spanTag = document.createElement("span");
			    							spanTag.id = "sku-success-msg skuavailable";
			        
			        						spanTag.className ="sku-success-msg skuavailable";
											spanTag.innerHTML = "SKU Available";
			        
			        						jced(".hor-scroll > li:nth-child(2)").append(spanTag);
										}
										else
										{
    						                var l=document.getElementsByClassName("skuavailable");  
	    									if(l.length>0)
				    						{
				    							jced(".skuavailable").remove();
				    						}
				    						var not=document.getElementsByClassName("skunotavailable");  
                                            if(not.length>0)
				    						{
				    							jced(".skunotavailable").remove();
				    						}
			    							var spanTag = document.createElement("span");
			    							spanTag.id = "sku-error-msg skunotavailable";
			        
			        						spanTag.className = "sku-error-msg skunotavailable";
											spanTag.innerHTML = "SKU Already Exist";
			        
			        						jced(".hor-scroll > li:nth-child(2)").append(spanTag);
    						
											
										}
									}
								});
    						}
    						});	
					   
		      </script>');
    				
    				
    				
    				
    		
    	
    		$fieldset->addField('description', 'editor',
    				array(
    						'label' => 'Description',
    						'class' => 'required-entry',
    						'required' => true,
    						'name' => 'description',
    						'force_load' => true,
    						'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(
        						array( 'add_widgets' => false, 'add_variables' => false, 'add_images' => false,  
        						'files_browser_window_url'=> $this->getBaseUrl().'csproduct/cms_wysiwyg_images/index/')),
    				));
    		$fieldset->addField('short_description', 'editor',
    				array(
    						'label' => 'Short Description',
    						'class' => 'required-entry',
    						'required' => true,
    						'name' => 'short_description',
    						'wysiwyg'   => true,
    						'config' => Mage::getSingleton('cms/wysiwyg_config')->getConfig(
        						array( 'add_widgets' => false, 'add_variables' => false, 'add_images' => false,  
        						'files_browser_window_url'=> $this->getBaseUrl().'csproduct/cms_wysiwyg_images/index/')),
    				));
    		$fieldset->addField('weight', 'text',
    				array(
    						'label' => 'Weight',
    						'class' => 'form-control required-entry validate-number validate-zero-or-greater validate-number-range number-range-0-99999999.9999 input-text',
    						'required' => true,
    						'name' => 'weight',
    				));
    		$fieldset->addField('stock_data[is_in_stock]', 'select',
    				array(
    						'label' => 'Stock Availibility',
    						'class' => 'form-control select',
    						'values' => array(
                                '-1'=>'--Please Select--',
                                '1' => array(
                                                'value'=> '1',
                                                'label' => 'In Stock'    
                                           ),
                                '0' => array(
                                                'value'=> '2',
                                                'label' => 'Out of Stock'   
                                           )),                                         
                                 
    						'name' => 'stock_data[is_in_stock]'
    						
    				));
    		$fieldset->addField('stock_item[qty]', 'text',
    				array(
    						'label' => 'Stock',
    						'class' => 'form-control required-entry validate-number input-text',
    						'required' => true,
    						'name' => 'stock_data[qty]',
    				));
    		$fieldset->addField('tax_class_id', 'select',
    				array(
    						'label' => 'Tax Class',
    						'class' => 'form-control validate-select select',
    						'required' => true,
    						'name' => 'tax_class_id',
    						'values' => $taxarray,
    				));
    		$fieldset->addField('price', 'text',
    				array(
    						'label' => 'Price',
    						'class' => 'form-control required-entry validate-number validate-zero-or-greater  input-text',
    						'required' => true,
    						'name' => 'price',
    				));
    		$fieldset->addField('special_price', 'text',
    				array(
    						'label' => 'Special Price',
    						'class' => 'form-control input-text validate-number validate-zero-or-greater ',
    						'required' => false,
    						'name' => 'special_price',
    				));
    		 $fieldset->addField('stock_data[manage_stock]', 'hidden', 
    		 		array(
                			'name'  => 'tax_calculation_rate_id',
                			'value' => '1'
            ));
    		 $abc = $fieldset->addField('images', 'text', array(
    		 		'name'      => 'images',
    		 		'label'     => 'Product Images',
    		 ));
    		 $fieldRenderer = Mage::getBlockSingleton('csproduct/adminhtml_product_renderer_categories')->setTemplate('csproduct/edit/form/media.phtml');
    		 $fieldRenderer->setForm($form);
    		 $abc->setRenderer($fieldRenderer);
    		
    		$attributes = $this->getGroupAttributes();
    		if(empty($attributes))
    		{
    			$product = $this->getProduct();
    			$setId = $product->getAttributeSetId();
    			$attributes = Mage::getModel('catalog/product_attribute_api')->items($setId);
    			
    		}
    		
    		//$attributes = Mage::getModel('catalog/product_attribute_api')->items('4');
    		//print_r($attributes);die;
    		$prev_attribute = array('name','description','short_description','sku','weight','price','tax_class_id');
    		foreach($attributes as $key => $value)
    		{
    			
    			if (in_array($value['code'], $prev_attribute)) 
    			{
    				continue;
    			}
    			else
    			{
    				$attribute = Mage::getResourceModel('catalog/eav_attribute')->load($value['attribute_id']);
    				
    				if($attribute->getIsRequired())
    				{
    					
    					if(($attribute->getApplyTo() == '')  || in_array('simple',$attribute->getApplyTo()))
    					{
    						//die('aaaa');
	    					if(in_array('simple',$attribute->getApplyTo()))
	    					{
								$fieldType = $attribute->getFrontend()->getInputType();
	    						$element = $fieldset->addField($attribute->getAttributeCode(), $fieldType,
			    					array(
			    							'name'      => $attribute->getAttributeCode(),
			    							'label'     => $attribute->getFrontend()->getLabel(),
			    							'class'     => $attribute->getFrontend()->getClass(),
			    							'required'  => $attribute->getIsRequired(),
			    							'note'      => $attribute->getNote(),
			    						)
					    			)
					    			->setEntityAttribute($attribute);
	    						$element->setAfterElementHtml($this->_getAdditionalElementHtml($element));
	    						$inputType = $fieldType;
	    						if ($inputType == 'select') {
	    							if($attribute->getAttributeCode()=="msrp_enabled"){
	    								$options=array();
	    								$options=$attribute->getSource()->getAllOptions(true, true);
	    								foreach ($options as $key => $value){
	    									if($value['value']==Mage_Catalog_Model_Product_Attribute_Source_Msrp_Type_Enabled::MSRP_ENABLE_USE_CONFIG){
	    										unset($options[$key]);
	    										break;
	    									}
	    								}
	    								$element->setValues($options);
	    							}
	    							else if($attribute->getAttributeCode()=="msrp_display_actual_price_type"){
	    								$options=array();
	    								$options=$attribute->getSource()->getAllOptions(true, true);
	    								foreach ($options as $key => $value){
	    									if($value['value']==Mage_Catalog_Model_Product_Attribute_Source_Msrp_Type_Price::TYPE_USE_CONFIG){
	    										unset($options[$key]);
	    										break;
	    									}
	    								}
	    								$element->setValues($options);
	    							}
	    							else
	    							{
	    								
	    								//print_r(Mage::getSingleton('eav/config')
	    								//->getAttribute(Mage_Catalog_Model_Product::ENTITY, $attribute->getAttributeCode())->getSource()->getAllOptions(true, true));die;
	    								$element->setValues(Mage::getSingleton('eav/config')
	    								->getAttribute(Mage_Catalog_Model_Product::ENTITY, $attribute->getAttributeCode())->getSource()->getAllOptions(true, true));
	    							}
	    						} else if ($inputType == 'multiselect') {
	    							$element->setValues($attribute->getSource()->getAllOptions(false, true));
	    							$element->setCanBeEmpty(true);
	    						} else if ($inputType == 'date') {
	    							$element->setImage($this->getSkinUrl('images/calendar.gif'));
	    							$element->setFormat(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
	    						} else if ($inputType == 'datetime') {
	    							$element->setImage($this->getSkinUrl('images/calendar.gif'));
	    							$element->setTime(true);
	    							$element->setStyle('width:50%;');
	    							$element->setFormat(
	    									Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
	    							);
	    						} else if ($inputType == 'multiline') {
	    							$element->setLineCount($attribute->getMultilineCount());
	    						}
	    					
	    					}
    					}
    				}
    			}
    		}
          
    		$values = Mage::registry('product')->getData();
    		if (array_key_exists('stock_item', $values)) {
    			
    			$values['stock_item[qty]']= $values['stock_item']['qty'];
    			$values['stock_data[is_in_stock]'] = '1';
    		}
    		$form->setValues($values);
    		$form->setFieldNameSuffix('product');
    		$this->setForm($form);
    	//}
    	
    }
    
    
    /**
     * Set Fieldset to Form
     *
     * @param array $attributes attributes that are to be added
     * @param Varien_Data_Form_Element_Fieldset $fieldset
     * @param array $exclude attributes that should be skipped
     */
    protected function _setFieldset($attributes, $fieldset, $exclude=array())
    {
    	$this->_addElementTypes($fieldset);
    	foreach ($attributes as $attribute) {
    		
    		
    		$attribute = Mage::getModel('eav/entity_attribute')->load($attribute['attribute_id']);
    		/* @var $attribute Mage_Eav_Model_Entity_Attribute */
    		if (!$attribute || ($attribute->hasIsVisible() && !$attribute->getIsVisible())) {
    			continue;
    		}
    		if ( ($inputType = $attribute->getFrontend()->getInputType())
    				&& !in_array($attribute->getAttributeCode(), $exclude)
    				&& ('media_image' != $inputType)
    		) {
    					
    			$fieldType      = $inputType;
    			
    			$attribute->setStoreId($this->getRequest()->getParam('store',0));
    			$element = $fieldset->addField($attribute->getAttributeCode(), $fieldType,
    					array(
    							'name'      => $attribute->getAttributeCode(),
    							'label'     => $attribute->getFrontend()->getLabel(),
    							'class'     => $attribute->getFrontend()->getClass(),
    							'required'  => $attribute->getIsRequired(),
    							'note'      => $attribute->getNote(),
    					)
    			)
    			->setEntityAttribute($attribute);
    
    			$element->setAfterElementHtml($this->_getAdditionalElementHtml($element));
    
    			if ($inputType == 'select') {
    				
    				
    					$element->setValues($attribute->getSource()->getAllOptions(true, true));
    			} else if ($inputType == 'multiselect') {
    				$element->setValues($attribute->getSource()->getAllOptions(false, true));
    				$element->setCanBeEmpty(true);
    			} else if ($inputType == 'date') {
    				$element->setImage($this->getSkinUrl('images/calendar.gif'));
    				$element->setFormat(Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
    			} else if ($inputType == 'datetime') {
    				$element->setImage($this->getSkinUrl('images/calendar.gif'));
    				$element->setTime(true);
    				$element->setStyle('width:50%;');
    				$element->setFormat(
    						Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
    				);
    			} else if ($inputType == 'multiline') {
    				$element->setLineCount($attribute->getMultilineCount());
    			}
    		}
    	}
    }
    

    /**
     * Retrieve predefined additional element types
     *
     * @return array
     */
    protected function _getAdditionalElementTypes()
    {
        $result = array(
    			'price'    => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_price'),
    			'gallery'  => Mage::getConfig()->getBlockClassName('csproduct/edit_form_gallery'),
    			'image'    => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_image'),
    			'boolean'  => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_boolean'),
        		'textarea' => Mage::getConfig()->getBlockClassName('adminhtml/catalog_helper_form_wysiwyg'),
        		'weight'   => Mage::getConfig()->getBlockClassName('adminhtml/catalog_product_helper_form_weight')
        		
    	);
    
    	$response = new Varien_Object();
    	$response->setTypes(array());
    	Mage::dispatchEvent('adminhtml_catalog_product_edit_element_types', array('response' => $response));
    	foreach ($response->getTypes() as $typeName => $typeClass) {
    		$result[$typeName] = $typeClass;
    	}
    
    	return $result;
    }

    /**
     * Enter description here...
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getAdditionalElementHtml($element)
    {	
    	return '';
    }
    
    /**
     * Retrive product object from object if not from registry
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
    	if (!($this->getData('product') instanceof Mage_Catalog_Model_Product)) {
    		$this->setData('product', Mage::registry('product'));
    	}
    	return $this->getData('product');
    }
    
    public function getCategoriesArray() {
    
    	$categoriesArray = Mage::getModel('catalog/category')
    	->getCollection()
    	->addAttributeToSelect('name')
    	->addAttributeToSort('path', 'asc')
    	->load()
    	->toArray();
    
    	$categories = array();
    	foreach ($categoriesArray as $categoryId => $category) {
    		if (isset($category['name']) && isset($category['level'])) {
    			$categories[] = array(
    					'label' => $category['name'],
    					'level'  =>$category['level'],
    					'value' => $categoryId
    			);
    		}
    	}
    
    	return $categories;
    }
    
   
}
