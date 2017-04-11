<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Model_Event extends Mage_Core_Model_Abstract
{
    protected $imagePath = 'amasty_giftregistry';

    protected $_customer = null;

    protected function _construct()
    {
        $this->_init('amgiftreg/event');
    }

    /**
     * @return Amasty_Giftregistry_Model_Item[]
     */
    public function getItems()
    {
        $items = $this->getData('items');
        if (is_null($items)) {
            $collection = Mage::getResourceModel('amgiftreg/item_collection')
                ->addFieldToFilter('main_table.event_id', $this->getId())
                ->joinOrderedItems()
                ->setOrder('item_id')
                ->load();

            $productIds = array();
            foreach ($collection as $item) {
                $productIds[] = $item->getProductId();
            }
        
            $products = $this->_getProductsArray($productIds);
       
            $items = array();
            foreach ($collection as $item) {
                if (isset($products[$item->getProductId()])){
                    $item->setProduct($products[$item->getProductId()]);
                    $items[] = $item;
                }
            } 
            $this->setData('items', $items);
        }
        return $items;  
    }

    /**
     * @return Amasty_Giftregistry_Model_Mysql4_Item_Collection
     */
    public function getItemsCollection()
    {
        return Mage::getResourceModel('amgiftreg/item_collection')
            ->addFieldToFilter('main_table.event_id', $this->getId())
            ->setOrder('item_id');
    }

    /**
     * @return Amasty_Giftregistry_Model_Mysql4_OrderedItem_Collection
     */
    public function getOrderedItemsCollection()
    {
        return Mage::getResourceModel('amgiftreg/orderedItem_collection')
            ->joinEvent()
            ->joinOrder()
            ->addFieldToFilter('amgiftreg.event_id', $this->getId());
    }

    public function getEventDateTime()
    {
        $date = $this->getEventDate()." ".$this->getEventTime();
        return $date == " " ? null : $date;
    }

    public function getOrderedItems()
    {
        $receivedItems = $this->getData('orderedItems');

        if(is_null($receivedItems)) {
            $collection = Mage::getResourceModel('amgiftreg/orderedItem_collection')
                ->joinEvent()
                ->joinOrder()
                ->addFieldToFilter('amgiftreg.event_id', $this->getId())
                ->setOrder('ordered_item_id')
                ->load();

            $productIds = array();
            $customerIds = array();
            foreach ($collection as $item) {
                $productIds[] = $item->getProductId();
                $customerIds[] = $item->getCustomerId();
            }

            $products = $this->_getProductsArray($productIds);
            $customers = $this->_getCustomersArray($customerIds);

            $receivedItems = array();
            foreach ($collection as $item) {
                if (isset($products[$item->getProductId()])){
                    $item->setProduct($products[$item->getProductId()]);
                    if(isset($customers[$item->getCustomerId()])) {
                        $item->setCustomer($customers[$item->getCustomerId()]);
                    } else {
                        $item->setCustomer(new Varien_Object(array('name'=>$item->getCustomerName())));
                    }
                    $receivedItems[] = $item;
                }
            }
            $this->setData('receivedItems', $receivedItems);
        }

        return $receivedItems;
    }
    


    public function getLastEventId($customerId)
    {
         return $this->_getResource()->getLastEventId($customerId);
    }
    
    public function addItem($productId, $customOptions)
    {
        /* @var $item Amasty_Giftregistry_Model_Item */
        $item = Mage::getModel('amgiftreg/item')
            ->setProductId($productId)
            ->setEventId($this->getId())
            ->setQty(1);
              
        if ($customOptions) {
             foreach ($customOptions as $product) {
                 $options = $product->getCustomOptions();
                 foreach ($options as $option) {
                    if ($option->getProductId() == $productId && $option->getCode() == 'info_buyRequest'){
                        $v = unserialize($option->getValue());
                        
                        $qty = isset($v['qty']) ? max(0.01, $v['qty']) : 1;
                        $item->setQty($qty);
                        
                        // to be able to compare request in future
                        $unusedVars = array('list', 'qty', 'list_next', 'related_product');
                        foreach($unusedVars as $k){
                            if (isset($v[$k])){
                                unset($v[$k]);
                            }
                        }
                        $item->setBuyRequest(serialize($v));
                    }
                 }
            }
        } 
         
        // check if we already have the same item in the list.
        // if yes - set it's id to the current item
        $id = $item->findDuplicate();
        if ($id) {
            $newQty = Mage::getModel('amgiftreg/item')->load($id)->getQty();
            $newQty += $item->getQty();
            $newQty = max(0.01, $newQty);
            $item->setQty($newQty);
            $item->setId($id);
        }           
        else {
            $item->setCreatedAt(date('Y-m-d H:i:s'));
        }
        $item->save();
        return $item;
    }

    /**
     * Retrieve wishlist has salable item(s)
     *
     * @return bool
     */
    public function isSalable()
    {
        foreach ($this->getItems() as $item) {
            if($item->getProduct()->getIsSalable()){
                return true;
            }
        }
        return false;
    }


    protected function _getProductsArray($productIds)
    {
        $productIds = array_unique($productIds);

        $collection = Mage::getModel('catalog/product')->getResourceCollection()
            ->addIdFilter($productIds)
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes());
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);

        $collection->load();

        $products = array();
        foreach ($collection as $prod) {
            $products[$prod->getId()] = $prod;
        }

        return $products;
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

    protected function _beforeSave()
    {
        if ($this->getData('delete_image')) {
            $this->unsImage();
        }
        if($this->getShippingAddressId() == '') {
            $this->setShippingAddressId(NULL);
        }
        try {
            $uploader = new Amasty_Giftregistry_Model_Uploader_Image('image');
            $uploader->setAllowRenameFiles(true);
            $this->setImage($uploader);
        } catch (Exception $e) {
        }
        return parent::_beforeSave();
    }

    public function getImagePath()
    {
        return Mage::getBaseDir('media') . DS . $this->imagePath . DS;
    }


    public function setImage($image)
    {
        if ($image instanceof Varien_File_Uploader) {
            $this->unsImage();  // Delete old image
            $newImageName = uniqid()."_".$image->getRealCorrectFileName();
            $image->save($this->getImagePath(), $newImageName);
            $image = $image->getUploadedFileName();
        }
        $this->setData('event_image_path', $image);
        return $this;
    }

    public function getImage()
    {
        if ($image = $this->getData('event_image_path')) {
            return Mage::getBaseUrl('media') . $this->imagePath . DS . $image;
        } else {
            return '';
        }
    }


    public function unsImage()
    {
        $image = $this->getOrigData('event_image_path');
        if(!$image) {
            return $this;
        }
        $image = $this->getImagePath() . $image;
        if (file_exists($image) && is_file($image)) {
            unlink($image);
        }
        $this->setData('event_image_path', '');
        return $this;
    }

    /**
     * @return Mage_Customer_Model_Customer|Mage_Core_Model_Abstract|mixed
     */
    public function getCustomer()
    {
        if(is_null($this->_customer)) {
            $this->_customer = Mage::getModel("customer/customer")->load($this->getCustomerId());
        }
        return $this->_customer;
    }

    /**
     * @param Mage_Customer_Model_Customer|Mage_Core_Model_Abstract|mixed $customer
     *
     * @return $this
     */
    public function setCustomer($customer)
    {
        $this->_customer = $customer;

        return $this;
    }



    /*public function onEavCollectionBeforeLoad($observer) {
        if(!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return;
        }
        $collection = $observer->getCollection();
        //var_dump($collection);
        if($collection instanceof Mage_Customer_Model_Resource_Address_Collection) {
            /* @var $quote Mage_Checkout_Model_Cart * /
            $quote = Mage::getSingleton('checkout/cart');
            //echo "<pre>";
            $listEventItemIds = array();
            $isData = false;
            foreach($quote->getItems() as $item) {
                /* @var $item Mage_Sales_Model_Quote_Item * /
                $buyRequest = $item->getBuyRequest();
                if(!empty($buyRequest['amgiftreg_item_id'])) {
                    $listEventItemIds[$buyRequest['amgiftreg_item_id']] = $buyRequest['amgiftreg_item_id'];
                    $isData = true;
                }
            }

            if(!$isData) {
                return;
            }

            $isData = false;
            $eventCollection = Mage::getResourceModel('amgiftreg/item_collection')->joinEvent(array("amgiftreg.shipping_address_id"))->addFieldToFilter("item_id", array("in"=>$listEventItemIds));
            $eventCollection->getSelect()->group("main_table.event_id");
            //var_dump($collection);
            $listShippingAddressIds = array();
            foreach($eventCollection as $item) {

                $shippingAddressId = $item->getShippingAddressId();
                if(empty($shippingAddressId)) {
                    continue;
                }
                //var_dump($item->getShippingAddressId());
                $listShippingAddressIds[$item->getShippingAddressId()] = $item->getShippingAddressId();
                $isData = true;
            }
            if(!$isData) {
                return;
            }


            $addShippingAddresses = implode(",", $listShippingAddressIds);
            $collection->getSelect()->orWhere("`e`.`entity_id` IN({$addShippingAddresses}) AND `e`.`entity_type_id` = '2'");
            //echo $collection->getSelectSql(true);
            //die;
        }
    }*/


}
