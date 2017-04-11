<?php

/**
 * Microzu registraion
 *
 * @category      Microzu
 * @package       Sreedarsh_Registration
 * @author Sreedarsh <sreedarsh88@gmail.com>
 */
$installer = new Mage_Customer_Model_Entity_Setup('core_setup');

$installer->startSetup();




$installer->addAttribute('customer', 'shopper_type', array(
'type' => 'varchar',
'input' => 'text',
'label' => 'Shopper Type',
'global' => 1,
"visible"  => true,
'required' => 0,
'user_defined' => 1,
'default' => '0',
'visible_on_front' => 1,
'sort_order'        => 40
));



$installer->addAttribute('customer', 'organization', array(
		'visible'      	=> true,
		'type'          => 'int',
		'label'         => 'Organization',
		'input'         => 'select',
		'source'        => 'sreedarsh_registration/system_config_source_org',
		'required'      => true,
		'user_defined'  => false,

));

if (version_compare(Mage::getVersion(), '1.4.2', '>=')) {
    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'shopper_type')           
            ->setData('used_in_forms', array('adminhtml_customer'))
            ->save();
    
     Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'organization')           
            ->setData('used_in_forms', array('adminhtml_customer'))
            ->save();
}




$installer->endSetup();