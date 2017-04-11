<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */

class Amasty_Giftregistry_Model_Share extends Varien_Object
{

	protected $_emails = array();

	protected $_errors  = array();

	protected $_vars = array();

	/**
	 * @var Amasty_GiftRegistry_Helper_Data
	 */
	protected $_helper;

	protected function _construct()
	{
		$this->_helper = Mage::helper('amgiftreg');

		$this->setTemplate(Mage::getStoreConfig('amgiftreg/email/email_template'));
		$this->setSender(Mage::getStoreConfig('amgiftreg/email/email_identity'));
	}

	/**
	 * @param string|array $emails
	 */
	public function setEmails($emails)
	{
		$this->_setEmails($emails);
	}

	/**
	 * Set customer additional message
	 * @param $message
	 */
	public function setMessage($message)
	{
		$this->_vars['message'] = nl2br(htmlspecialchars((string) $message));
	}

	/**
	 * Add variable to $vars array for Mage_Core_Model_Email_Template::sendTransactional()
	 * @param string $var	Name var
	 * @param mixed $value Value var
	 */
	public function setVar($var, $value)
	{
		$this->_vars[$var] = $value;
	}

	/**
	 * Add variables to $vars array for Mage_Core_Model_Email_Template::sendTransactional()
	 * @param array $vars
	 */
	public function setVars(array $vars)
	{
		$this->_vars = array_merge($this->_vars, $vars);
	}

	/**
	 * @param string $fileField index from array $_FILES (using $_FILES[$fileField])
	 */
	public function setEmailsFile($fileField)
	{
		if(isset($_FILES[$fileField]) &&
			$_FILES[$fileField]['name'] != '') {

			try{
				$uploader = new Amasty_Giftregistry_Model_Uploader_Csv($fileField);
				if(($fp = fopen($uploader->getTempFilePath(), 'r')) !== false) {
					while(($emails = fgetcsv($fp)) !== false) {
						$this->_setEmails($emails);
					}
					fclose($fp);
				}
			} catch (Exception $e){
				$this->_setError($this->__('Invalid file format'));
			}

		}
	}

	/**
	 * @param string|array $emails
	 */
	protected function _setEmails($emails)
	{
		if(is_null($emails)) {
			return;
		}
		if(!is_array($emails)) {
			$emails  = explode(',', $emails);
		}
		foreach($emails as $email) {
			$email = trim($email);
			if(!empty($email)) {
				$this->_emails[] = $email;
			}
		}
	}

	/**
	 * @return bool
	 * @throws Exception
	 * @throws Zend_Validate_Exception
	 */
	protected function _validate()
	{
		if (empty($this->_emails)) {
			$this->_setError($this->_helper->__('Email address can\'t be empty.'));
		}
		foreach ($this->_emails as $index => $email) {
			$email = trim($email);
			if (!Zend_Validate::is($email, 'EmailAddress')) {
				$this->_setError($this->_helper->__('Please input a valid email address.'));
				break;
			}
			$this->_emails[$index] = $email;
		}

		return !$this->hasErrors();
	}

	/**
	 * Send transactional email to recipients
	 *
	 * @param   int|null $storeId
	 * @return  boolean is error
	 */
	public function send($storeId=null)
	{
		if($this->_validate()) {
			$this->_send($storeId);
		}

		return !$this->hasErrors();
	}

	/**
	 * @param int|null $storeId
	 */
	protected function _send($storeId=null)
	{
		/* @var $emailModel Mage_Core_Model_Email_Template */
		$emailModel = Mage::getModel('core/email_template');

		foreach ($this->_emails as $email) {
			$emailModel->sendTransactional(
				$this->getTemplate(),
				$this->getSender(),
				$email,
				null,
				$this->_vars,
				$storeId
			);
		}
	}

	/**
	 * @param string $error
	 */
	protected function _setError($error)
	{
		$this->_errors[] = $error;
	}

	/**
	 * @return array of errors
	 */
	public function getErrors()
	{
		return $this->_errors;
	}

	/**
	 * @return bool is errors
	 */
	public function hasErrors()
	{
		return count($this->_errors) > 0;
	}

}