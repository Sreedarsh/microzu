<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Block_Adminhtml_Amgiftregistry_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('ruleGrid');
		$this->setDefaultSort('pos');
  	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('amgiftreg/event')->getCollection()->addCustomerData();//joinCustomer()->joinCustomerName();
		/*$collection = Mage::getResourceModel('amgiftreg/customer_collection')
			->addNameToSelect()
			->addAttributeToSelect('email')
			->joinEvent()
		;*/
		$this->setCollection($collection);
		return parent::_prepareCollection();
  	}

	protected function _prepareColumns()
	{
		$hlp =  Mage::helper('amgiftreg');
    
		$this->addColumn('event_id', array(
			'header'    => $hlp->__('Registry ID'),
			'align'     => 'right',
			'width'     => '50px',
			'index'     => 'event_id',
			//'filter'	=> 'event.event_id',
    	));

		$this->addColumn('event_title', array(
			'header'    => $hlp->__('Registry Title'),
			'align'     => 'left',
			'index'     => 'event_title',
			'getter'	=> 'getEventTitle',
		));

		$this->addColumn('customer_name', array(
			'header'    => $hlp->__('Registry Creator'),
			'align'     => 'left',
			'index'     => 'customer_name',
			//'getter'	=> 'getName',
		));

		$this->addColumn('email', array(
			'header'    => $hlp->__('Creator\'s Email'),
			'align'     => 'left',
			'index'		=> 'email',
		));

		$this->addColumn('event_date', array(
			'header'    => $hlp->__('Event Date'),
			'align'     => 'left',
			'index'     => 'event_date',
			'type'		=> 'datetime',
			'getter'	=> 'getEventDateTime',
		));

		$this->addColumn('action',array(
			'header'    => $hlp->__(''),
			'width'     => '50px',
			'type'      => 'action',
			'getter'     => 'getEventId',
			'actions'   => array(
				array(
					'caption' => $hlp->__('Edit'),
					'url'     => array(
						'base'=>'*/*/edit',
						//'params'=>array('store'=>$this->getRequest()->getParam('store'))
					),
					'field'   => 'id'
				)
			),
			'filter'    => false,
			'sortable'  => false,
			//'index'     => 'stores',
		));

	    return parent::_prepareColumns();
  	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getEventId()));
	}
  
 	protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }
    
    protected function _prepareMassaction()
    {
	    $this->setMassactionIdField('event_id');
	    $this->getMassactionBlock()->setFormFieldName('events');
	    
	    $actions = array(
	        'massDelete'     => 'Delete',
	    );
	    foreach ($actions as $code => $label){
	        $this->getMassactionBlock()->addItem($code, array(
	             'label'    => Mage::helper('amgiftreg')->__($label),
	             'url'      => $this->getUrl('adminhtml/amgiftregistry/' . $code),
	             'confirm'  => ($code == 'massDelete' ? Mage::helper('amgiftreg')->__('Are you sure?') : null),
	        ));        
	    }
	    return $this; 
	}
}