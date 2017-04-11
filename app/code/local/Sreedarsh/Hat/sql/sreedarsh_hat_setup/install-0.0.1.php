<?php

/**
 * Hire A Technician
 *
 * @category      Sreedarsh
 * @package       Sreedarsh_Hat
 * @author Sreedarsh <sreedarsh88@gmail.com>
 */
$installer = new Mage_Customer_Model_Entity_Setup('core_setup');

$installer->startSetup();

$vCustomerEntityType = $installer->getEntityTypeId('customer');
$vCustAttributeSetId = $installer->getDefaultAttributeSetId($vCustomerEntityType);
$vCustAttributeGroupId = $installer->getDefaultAttributeGroupId($vCustomerEntityType, $vCustAttributeSetId);

$installer->addAttribute('customer', 'avatar_image', array(
        'label' => 'Profile Image',
        'input' => 'file',
        'type'  => 'varchar',
        'forms' => array('customer_account_edit','customer_account_create','adminhtml_customer','checkout_register'),
        'required' => 0,
    	'visible'  => 1,
        'user_defined' => 1,
));

$installer->addAttribute('customer', 'resume', array(
        'label' => 'Technician resume',
        'input' => 'file',
        'type'  => 'varchar',
        'forms' => array('customer_account_edit','customer_account_create','adminhtml_customer','checkout_register'),
        'required' => 0,
    'visible'           => 1,
        'user_defined' => 1,
));

$installer->addAttribute('customer', 'verified_member', array(
'type' => 'int',
'input' => 'select',
'label' => 'Verified Member?',
'global' => 1,
'visible' => 1,
'required' => 0,
'user_defined' => 1,
'default' => '0',
'visible_on_front' => 1,
'source' => 'eav/entity_attribute_source_boolean',
));

$installer->addAttribute('customer', 'profile_desc', array(
'type' => 'varchar',
'input' => 'text',
'label' => 'Profile Description',
'global' => 1,
'visible'  => true,
'required' => 0,
'user_defined' => 1,
'default' => '0',
'visible_on_front' => 1,
'sort_order'        => 40
));


if (version_compare(Mage::getVersion(), '1.4.2', '>=')) {
    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'verified_member')
            ->setData('used_in_forms', array('adminhtml_customer'))
            ->save();
    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'profile_desc')
            ->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit'))
            ->save();
}


$installer->addAttribute('customer', 'member_category', array(
'type' => 'varchar',
'input' => 'select',
'label' => 'Technician category',
'global' => 1,
'visible' => 1,
'required' => 0,
'user_defined' => 1,
'default' => '0',
'visible_on_front' => 1,
'source' => 'sreedarsh_hat/attribute_source_type',
));

if (version_compare(Mage::getVersion(), '1.4.2', '>=')) {
    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'member_category')
            ->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit'))
            ->save();
}



$installer->addAttributeToGroup($vCustomerEntityType, $vCustAttributeSetId, $vCustAttributeGroupId, 'avatar_image', 0);

$aAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'avatar_image');
$aAttribute->setData('used_in_forms', array('customer_account_edit','customer_account_create','adminhtml_customer','checkout_register'));

$installer->addAttributeToGroup($vCustomerEntityType, $vCustAttributeSetId, $vCustAttributeGroupId, 'resume', 0);
$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'resume');
$oAttribute->setData('used_in_forms', array('customer_account_edit','customer_account_create','adminhtml_customer','checkout_register'));

$aAttribute->save();
$oAttribute->save();



$installer->endSetup();
