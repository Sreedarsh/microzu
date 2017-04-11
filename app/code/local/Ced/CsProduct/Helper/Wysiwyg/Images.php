<?php 
class Ced_CsProduct_Helper_Wysiwyg_Images extends Mage_Cms_Helper_Wysiwyg_Images
{
	public function getStorageRoot()
	{
		$_path = '';
		$vendor = Mage::getSingleton('customer/session')->getVendor();
		if($vendor && $vid=$vendor->getId()){
			$_path = 'vendor_'.$vid.DS;
		}
			
		$module = Mage::app()->getRequest()->getModuleName();
		if($module == 'csproduct'){
			$path = Mage::getConfig()->getOptions()->getMediaDir()
			. DS . Mage_Cms_Model_Wysiwyg_Config::IMAGE_DIRECTORY;
			$this->_storageRoot = realpath($path);
			if (!$this->_storageRoot) {
				$this->_storageRoot = $path;
			}
			$this->_storageRoot .= DS.$_path;
		} else {
			$path = Mage::getConfig()->getOptions()->getMediaDir()
			. DS . Mage_Cms_Model_Wysiwyg_Config::IMAGE_DIRECTORY;
			$this->_storageRoot = realpath($path);
			if (!$this->_storageRoot) {
				$this->_storageRoot = $path;
			}
			$this->_storageRoot .= DS;
		}
		return $this->_storageRoot;
	}
}
?>