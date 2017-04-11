<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml media library uploader
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Ced_CsProduct_Block_Media_Uploader extends Mage_Adminhtml_Block_Media_Uploader
{

    protected $_config;

    public function __construct()
    {
        parent::__construct();
        $this->setId($this->getId() . '_Uploader');
        $this->setTemplate('csproduct/media/uploader.phtml');
        $this->getConfig()->setUrl(Mage::getModel('adminhtml/url')->addSessionParam()->getUrl('*/*/upload'));
        $this->getConfig()->setParams(array('form_key' => $this->getFormKey()));
        $this->getConfig()->setFileField('file');
        $this->getConfig()->setFilters(array(
            'images' => array(
                'label' => Mage::helper('adminhtml')->__('Images (.gif, .jpg, .png)'),
                'files' => array('*.gif', '*.jpg', '*.png')
            ),
            'media' => array(
                'label' => Mage::helper('adminhtml')->__('Media (.avi, .flv, .swf)'),
                'files' => array('*.avi', '*.flv', '*.swf')
            ),
            'all'    => array(
                'label' => Mage::helper('adminhtml')->__('All Files'),
                'files' => array('*.*')
            )
        ));
    } 
    
    /**
     * Retrive full uploader SWF's file URL
     * Implemented to solve problem with cross domain SWFs
     * Now uploader can be only in the same URL where backend located
     *
     * @param string url to uploader in current theme
     * @return string full URL
     */
    public function getUploaderUrl($url)
    {
    	if (!is_string($url)) {
    		$url = '';
    	}
    	$design = Mage::getDesign();
    	$theme = $design->getTheme('skin');
    	if (empty($url) || !$design->validateFile($url, array('_type' => 'skin', '_theme' => $theme))) {
    		$theme = $design->getDefaultTheme();
    	}
		
		if($design->getPackageName()=="ced")
			return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) .'skin/' .$design->getArea() . '/' . $design->getPackageName() . '/' . $theme . '/' . $url;
		else 
			return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)  .'skin/' .
		'frontend/base/default/images/ced/csproduct/'. $url;
    }
}
