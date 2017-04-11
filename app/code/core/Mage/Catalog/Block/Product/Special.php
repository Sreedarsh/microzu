<?php
class Mage_Catalog_Block_Product_Special extends Mage_Catalog_Block_Product_List
{
     protected function _getSpecialCollection()
    {
        $_productCollection = Mage::getModel('catalog/product')
                        ->getCollection()->addAttributeToSelect('*');
                   

$todayDate = date('m/d/y');
$tomorrow = mktime(0, 0, 0, date('m'), date('d'), date('y'));
$tomorrowDate = date('m/d/y', $tomorrow); 
$_productCollection->addAttributeToFilter('special_from_date', array('date' => true, 'to' => $todayDate))
->addAttributeToFilter('special_to_date', array('or'=> array(
0 => array('date' => true, 'from' => $tomorrowDate),
1 => array('is' => new Zend_Db_Expr('null')))
), 'left'); 
return $_productCollection;
    }
}