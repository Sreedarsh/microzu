<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_Mysql4_Event_Collection extends Amasty_Giftregistry_Model_Mysql4_AbstractCollection
{
    protected $_joinCustomerWithName = false;
    public function _construct()
    {
        $this->_init('amgiftreg/event');
    }
    
    public function addCustomerFilter($customerId)
    {
        $this->addFieldToFilter('customer_id', $customerId);
        return $this;
    }

    public function joinCustomer()
    {
        $this->getSelect()->join(
            array('customer' => $this->getTable('customer/entity')),
            'customer.entity_id = main_table.customer_id',
            array('email'=>'customer.email')
        );

        return $this;
    }


    public function addCustomerData()
    {
        $customerEntity         = Mage::getResourceSingleton('customer/customer');
        $attrFirstname          = $customerEntity->getAttribute('firstname');
        $attrFirstnameId        = (int) $attrFirstname->getAttributeId();
        $attrFirstnameTableName = $attrFirstname->getBackend()->getTable();

        $attrLastname           = $customerEntity->getAttribute('lastname');
        $attrLastnameId         = (int) $attrLastname->getAttributeId();
        $attrLastnameTableName  = $attrLastname->getBackend()->getTable();

        $attrEmail       = $customerEntity->getAttribute('email');
        $attrEmailTableName = $attrEmail->getBackend()->getTable();

        $adapter = $this->getSelect()->getAdapter();
        $customerName = $adapter->getConcatSql(array('cust_fname.value', 'cust_lname.value'), ' ');
        $this->getSelect()
            ->joinInner(
                array('cust_email' => $attrEmailTableName),
                'cust_email.entity_id = main_table.customer_id',
                array('email' => 'cust_email.email')
            )
            ->joinInner(
                array('cust_fname' => $attrFirstnameTableName),
                implode(' AND ', array(
                    'cust_fname.entity_id = main_table.customer_id',
                    $adapter->quoteInto('cust_fname.attribute_id = ?', (int)$attrFirstnameId),
                )),
                array('firstname' => 'cust_fname.value')
            )
            ->joinInner(
                array('cust_lname' => $attrLastnameTableName),
                implode(' AND ', array(
                    'cust_lname.entity_id = main_table.customer_id',
                    $adapter->quoteInto('cust_lname.attribute_id = ?', (int)$attrLastnameId)
                )),
                array(
                    'lastname'      => 'cust_lname.value',
                    'customer_name' => $customerName
                )
            );

        $this->_joinedFields['customer_name'] = $customerName;
        $this->_map['fields']['customer_name'] = $customerName;
        $this->_joinedFields['email']         = 'cust_email.email';

        return $this;
    }

    public function joinCustomerWithName()
    {
        $this->_joinCustomerWithName = true;
        return $this;
    }



    /**
     * Proces loaded collection data
     *
     * @return Varien_Data_Collection_Db
     */
    protected function _afterLoadData()
    {
        if($this->_joinCustomerWithName) {
            $customerIds = array();
            foreach ($this as $item) {
                $customerIds[] = $item->getCustomerId();

            }

            $customers = $this->_getCustomersArray($customerIds);

            foreach ($this as $item) {
                if (isset($customers[$item->getCustomerId()])){
                    $item->setCustomer($customers[$item->getCustomerId()]);
                    $item->setCustomerName($customers[$item->getCustomerId()]->getName());
                    $item->setCustomerEmail($customers[$item->getCustomerId()]->getEmail());
                }

            }
        }
        return $this;
    }

    protected function _getCustomersArray($customerIds)
    {
        $customerIds = array_unique($customerIds);

        /* @var $collection Mage_Customer_Model_Resource_Customer_Collection */
        $collection = Mage::getModel('customer/customer')->getResourceCollection()
            ->addNameToSelect()
            ->addFieldToFilter('entity_id', array('in' => $customerIds ) );

        //$collection->addFieldToFilter('entity_id', array('in' => $customerIds ));

        $collection->load();

        $customers = array();
        foreach ($collection as $item) {
            $customers[$item->getId()] = $item;
        }

        return $customers;
    }
}