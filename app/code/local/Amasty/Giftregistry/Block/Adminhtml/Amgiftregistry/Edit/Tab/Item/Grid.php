<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Adminhtml_Amgiftregistry_Edit_Tab_Item_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('itemsGrid');
		$this->setDefaultSort('event_id');

		$this->setUseAjax(true);
  	}

	public function getGridUrl()
	{
		return $this->getUrl('*/*/gridItems', array('_current'=>true));
	}

	/**
	 * @return Mage_Adminhtml_Block_Widget_Grid
	 */
	protected function _prepareCollection()
	{
		$collection = Mage::registry('amgiftreg_event')->getItemsCollection()->joinOrderedItems()->joinProductName()->joinProductEntity(array('product_sku'=>'sku'));
		$this->setCollection($collection);
		return parent::_prepareCollection();
  	}

	/**
	 * @return $this
	 * @throws Exception
	 */
	protected function _prepareColumns()
	{
		/* @var $hlp Amasty_GiftRegistry_Helper_Data */
		$hlp =  Mage::helper('amgiftreg');
    
		$this->addColumn('item_id', array(
			'header'    => $hlp->__('ID'),
			'align'     => 'right',
			'width'     => '50px',
			'index'     => 'item_id',
    	));

		$this->addColumn('priority', array(
			'header'    => $hlp->__('Priority'),
			'align'     => 'left',
			'index'     => 'priority',
			'type'		=> 'options',
			'options' => $hlp->getListItemPriority(),
			/*'filter'	=> array(
				'type' => 'range',
			),*/
		));

		$this->addColumn('product_name', array(
			'header'    => $hlp->__('Product Name'),
			'align'     => 'left',
			'index'		=> 'product_name',
			//'getter'	=> 'getProductName',
		));

		$this->addColumn('product_sku', array(
			'header'    => $hlp->__('SKU'),
			'align'     => 'left',
			'index'		=> 'product_sku',
			//'getter'	=> 'getProductSku',
		));

		$this->addColumn('comments', array(
			'header'    => $hlp->__('Customer Comments'),
			'align'     => 'left',
			'index'     => 'comments',
		));

		$this->addColumn('qty', array(
			'header'    => $hlp->__('Desired'),
			//'align'     => 'left',
			'index'     => 'qty',
			'type'		=> 'number',
			//'id'     => 'qty',
			'editable'     => true,
			//'type'		=> 'input',
			//'getter'	=> 'getQty()',
			'renderer'  => 'amgiftreg/adminhtml_renderer_input'
		));

		$this->addColumn('ordered', array(
			'header'    => $hlp->__('Ordered'),
			'align'     => 'left',
			'type'		=> 'number',
			'getter'	=> 'getCountReceived',
			'filter'	=> false,
		));

		$this->addColumn('created_at', array(
			'header'    => $hlp->__('Added At'),
			'align'     => 'left',
			'index'     => 'created_at',
			'type'		=> 'date',
			'filter_condition_callback'
			=> array($this, '_filterCreatedCondition'),
		));

		$this->addColumn('action',array(
			'header'    => $hlp->__(''),
			'width'     => '50px',
			'type'      => 'action',
			'getter'     => 'getId',
			'actions'   => array(
				array(
					'caption' => $hlp->__('Delete'),
					'url'     => array(
						'base'=>'*/*/deleteItem',
					),
					'field'   => 'item_id'
				)
			),
			'filter'    => false,
			'sortable'  => false,
			//'index'     => 'stores',
		));

	    return parent::_prepareColumns();
  	}

	/**
	 * @param $row
	 *
	 * @return string
	 */
	public function getRowUrl($row)
	{
		//return $this->getUrl('adminhtml/amgiftregistry/edit', array('id' => $row->getId()));
		return '';
	}

	/**
	 * @return $this
	 */
 	protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        return parent::_afterLoadCollection();
    }

	/**
	 * @param $collection
	 * @param $column
	 */
    protected function _filterCreatedCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addFieldToFilter('main_table.created_at', $value);
    }
}