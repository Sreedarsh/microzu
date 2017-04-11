<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_EventController extends Mage_Core_Controller_Front_Action
{
	private $_customerId = 0;

	public function preDispatch()
	{
		parent::preDispatch();
		if (!Mage::getStoreConfigFlag('amgiftreg/general/active')) {
			$this->norouteAction();
			return;
		}
		/**
		 * @var $session Mage_Customer_Model_Session
		 */
		$session = Mage::getSingleton('customer/session');
		if (!$session->authenticate($this)) {
			$this->setFlag('', 'no-dispatch', true);
			if(!$session->getBeforeAmgiftregUrl()) {
				$session->setBeforeAmgiftregUrl($this->_getRefererUrl());
			}
			if ($this->getRequest()->isPost()){
				//store custom options
				$productId = $this->getRequest()->getParam('product');
				if ($productId){
					$params[$productId] = $this->getRequest()->getParams();
					$session->setAmgiftregParams($params);
				}
			}
			$session->addError($this->__('Please, login or create account to be used gift registry'));
		}
		$this->_customerId = $session->getCustomer()->getId();
	}


	/**
	 * Highlight menu and render layout
	 */
	private function _renderLayoutWithMenu()
	{
		$this->loadLayout();
		$this->_initLayoutMessages('customer/session');
		$navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
		if ($navigationBlock) {
			$navigationBlock->setActive('amgiftreg/event');
		}
		$this->renderLayout();
	}

	/**
	 * Show event of all customer's events
	 */
	public function indexAction()
	{
		$this->_renderLayoutWithMenu();
	}

	protected function _loadEvent(){

		$id = $this->getRequest()->getParam('id');
		$event = Mage::getModel('amgiftreg/event');

		if ($id){
			$event->load($id);
		}

		return $event;
	}

	protected function _registerEvent()
	{
		$event = $this->_loadEvent();

		if($event->isObjectNew()) {
			$this->_redirect('*/*/');
			return false;
		}
		Mage::register('current_event', $event);

		return true;
	}


	public function viewAction()
	{
		if(!$this->_registerEvent()) {
			return;
		}

		$this->_renderLayoutWithMenu();
	}

	public function receivedAction()
	{
		if(!$this->_registerEvent()) {
			return;
		}
		$this->_renderLayoutWithMenu();
	}

	public function shareAction()
	{
		if(!$this->_registerEvent()) {
			return;
		}
		$this->_renderLayoutWithMenu();
	}

	/**
	 * Show event's title and items
	 */
	public function editAction()
	{
		$event = Mage::getModel('amgiftreg/event');
		$id = $this->getRequest()->getParam('id');
		if ($id){
			$event->load($id);
			if ($event->getCustomerId() != $this->_customerId){
				$this->_redirect('*/*/');
				return;
			}
		}
		Mage::register('current_event', $event);

		$this->_renderLayoutWithMenu();
	}

	/**
	 * Save event details
	 */
	public function saveAction()
	{
		if (!$this->_validateFormKey()) {
			$this->_redirect('*/*/');
			return ;
		}
		$id     = $this->getRequest()->getParam('id');
		$event   = Mage::getModel('amgiftreg/event');
		if ($id){
			$event->load($id);
			if ($event->getCustomerId() != $this->_customerId){
				$this->_redirect('*/*/');
				return;
			}
		}

		$data = $this->getRequest()->getPost();
		if(isset($data['event_time']) && is_array($data['event_time'])) {
			$data['event_time'] = implode(":", array_map('trim', $data['event_time']));
			if($data['event_time'] == '::') {
				$data['event_time'] = NULL;
			}
		}

		if ($data) {
			$event->setData($data)->setId($id);
			try {
				$event->setCustomerId($this->_customerId);
				$event->setCreatedAt(date('Y-m-d H:i:s'));
				$event->save();
				Mage::getSingleton('customer/session')->addSuccess(Mage::helper('amgiftreg')->__('Gift registry has been successfully saved'));
				Mage::getSingleton('customer/session')->setEventFormData(false);

				$productId = Mage::getSingleton('amgiftreg/session')->getAddProductId();
				Mage::getSingleton('amgiftreg/session')->setAddProductId(null);
				if ($productId){
					$this->_redirect('*/*/addItem', array('product' => $productId, 'event'=>$event->getId()));
					return;
				}
				$this->_redirect('*/*/edit', array('id' => $event->getId()));
				return;

			} catch (Exception $e) {
				Mage::getSingleton('customer/session')->addError($e->getMessage());
				Mage::getSingleton('customer/session')->setEventFormData($data);
				$this->_redirect('*/*/', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		Mage::getSingleton('customer/session')->addError(Mage::helper('amgiftreg')->__('Unable to find gift registry for saving'));
		$this->_redirect('*/*/');
	}

	/**
	 * Delete event
	 */
	public function removeAction()
	{
		$id     = (int)$this->getRequest()->getParam('id');
		$event   = Mage::getModel('amgiftreg/event')->load($id);

		if ($event->getCustomerId() == $this->_customerId){
			try {
				// test !!!
				$event->delete();
				Mage::getSingleton('customer/session')->addSuccess($this->__('Gift registry has been successfully removed'));
			} catch (Exception $e) {
				Mage::getSingleton('customer/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}


	/**
	 * Delete a product from a event
	 */
	public function removeItemAction()
	{
		$id    = (int) $this->getRequest()->getParam('id');

		$item  = Mage::getModel('amgiftreg/item');
		$item->load($id);
		if (!$item->getId()){
			$this->_redirect('*/*/');
		}

		$event = Mage::getModel('amgiftreg/event');
		$event->load($item->getEventId());
		if ($event->getCustomerId() != $this->_customerId){
			$this->_redirect('*/*/');
			return;
		}

		try {
			$item->delete();
			Mage::getSingleton('customer/session')->addSuccess($this->__('Product has been successfully removed from the gift registry'));
		}
		catch (Exception $e) {
			Mage::getSingleton('customer/session')->addError($this->__('There was an error while removing item from the gift registry: %s', $e->getMessage()));
		}
		$this->_redirect('*/*/view', array('id' => $event->getId()));

	}


	/**
	 * Get request for "add to gift registry" action
	 *
	 * @return  Varien_Object
	 */
	protected function _getProductRequest()
	{
		$requestInfo = $this->getRequest()->getParams();

		$params = Mage::getSingleton('customer/session')->getAmgiftregParams();
		if ($params && key($params) == $this->getRequest()->getParam('product')){
			$requestInfo = current($params);
			Mage::getSingleton('customer/session')->setAmgiftregParams(null);
		}

		if ($requestInfo instanceof Varien_Object) {
			$request = $requestInfo;
		}
		elseif (is_numeric($requestInfo)) {
			$request = new Varien_Object();
			$request->setQty($requestInfo);
		}
		else {
			$request = new Varien_Object($requestInfo);
		}

		if (!$request->hasQty()) {
			$request->setQty(1);
		}

		return $request;
	}

	/**
	 * Add product(s) to the event
	 */
	public function addItemAction()
	{
		$session    = Mage::getSingleton('customer/session');

		$productId  = $this->getRequest()->getParam('product');

		$event      = Mage::getModel('amgiftreg/event');
		$eventId    = $this->getRequest()->getParam('event');

		if (!$eventId){ //get default - last
			$eventId = $event->getLastEventId($this->_customerId);
			$countEvents = $event->getCollection()->addFieldToFilter('customer_id', array("eq" => $this->_customerId))->getSize();
			if($countEvents > 1) {
				$product = Mage::getModel('catalog/product')
					->setStoreId(Mage::app()->getStore()->getId())
					->load($productId);
				$url = $product->getProductUrl();

				Mage::getSingleton('checkout/session')->addNotice($this->__('Please specify gift registry'));
				$this->getResponse()->setRedirect($url);
				return;
			}
		}

		if (!$eventId) { //create new
			Mage::getSingleton('amgiftreg/session')->setAddProductId($productId);
			$this->_redirect('*/*/edit/');
			return;
		}


		$event->load($eventId);

		if ($event->getCustomerId() == $this->_customerId){

			try {
				$product = Mage::getModel('catalog/product')
					->setStoreId(Mage::app()->getStore()->getId())
					->load($productId);
				$request = $this->_getProductRequest();

				if ($product->getTypeId() == 'grouped'){
					$cnt = 0; //subproduct count
					if ($request && !empty($request['super_group'])) {
						foreach ($request['super_group'] as $subProductId => $qty){
							if (!$qty)
								continue;

							$request = new Varien_Object();
							$request->setProduct($subProductId);
							$request->setQty($qty);

							$subProduct = Mage::getModel('catalog/product')
								->setStoreId(Mage::app()->getStore()->getId())
								->load($subProductId);

							// check if params are valid
							$customOptions = $subProduct->getTypeInstance()->prepareForCart($request, $subProduct);

							// string == error during prepare cycle
							if (is_string($customOptions)) {
								$session->setRedirectUrl($product->getProductUrl());
								Mage::throwException($customOptions);
							}

							$event->addItem($subProductId, $customOptions);

							$cnt++;
						}
					}

					if (!$cnt) {
						$session->setRedirectUrl($product->getProductUrl());
						Mage::throwException($this->__('Please specify the product(s) quantity'));
					}

				}
				else { //if product is not grouped
					// check if params are valid
					$customOptions = $product->getTypeInstance()->prepareForCart($request, $product);

					// string == error during prepare cycle
					if (is_string($customOptions)) {
						$session->setRedirectUrl($product->getProductUrl());
						Mage::throwException($customOptions);
					}


					$event->addItem($productId, $customOptions);
				}

				$referer = $session->getBeforeAmgiftregUrl();

				if ($referer){
					$session->setBeforeAmgiftregUrl(null);
				}
				else {
					$referer = $this->_getRefererUrl();
				}



				$message = $this->__('Product has been successfully added to the gift registry. Click <a href="%s">here</a> to continue shopping', $referer);

				$session->setRedirectUrl($product->getProductUrl());
				$session->addSuccess($message);

				$this->_redirect('*/*/view/', array('id'=>$eventId));
				return;

			}
			catch (Exception $e) {
				$url =  $session->getRedirectUrl(true);
				if ($url) {
					Mage::getSingleton('checkout/session')->addNotice($e->getMessage());
					$this->getResponse()->setRedirect($url);
				}
				else {
					$session->addError($this->__('There was an error while adding item to the gift registry: %s', $e->getMessage()));
				}
			}

		}
		//$this->_redirect('*/*/');
	}

	/**
	 * Save event's items
	 */
	public function updateAction()
	{
		if (!$this->_validateFormKey()) {
			$this->_redirect('*/*/');
			return;
		}

		$eventId = $this->getRequest()->getParam('event_id');

		$event  = Mage::getModel('amgiftreg/event');
		$event->load($eventId);
		if ($event->getCustomerId() != $this->_customerId){
			$this->_redirect('*/*/');
			return;
		}

		$post = $this->getRequest()->getPost();
		if ($post && isset($post['qty']) && is_array($post['qty'])) {
			foreach ($post['qty'] as $itemId => $qty) {
				$item = Mage::getModel('amgiftreg/item')->load($itemId);
				if ($item->getEventId() != $eventId) {
					continue;
				}
				try {
					if (!$qty)
						$item->delete();
					else
					{
						$item->setQty(max(0.01, intVal($qty)));

						$newEventId = isset($post['moveto'][$itemId]) ? $post['moveto'][$itemId] : 0;
						if ($newEventId){
							$item->setEventId($newEventId);
						}

						if(isset($post['priority'][$itemId])) {
							$item->setPriority($post['priority'][$itemId]);
						}

						if(isset($post['comments'][$itemId])) {
							$item->setComments($post['comments'][$itemId]);
						}

						


						$item->save();
					}
				}
				catch (Exception $e) {
					Mage::getSingleton('customer/session')->addError(
						$this->__('Can not save item: %s.', $e->getMessage())
					);
				}
			}
			Mage::getSingleton('customer/session')->addSuccess($this->__('Quantities have been successfully updated'));
		}
		$this->_redirect('*/*/view', array('id'=>$eventId));
	}




	/**
	 * Share Gift Registry to emails
	 *
	 * @return Mage_Core_Controller_Varien_Action|void
	 */
	public function sendAction()
	{
		if (!$this->_validateFormKey()) {
			return $this->_redirect('*/*/');
		}

		$eventId = $this->getRequest()->getParam('event_id');

		$event  = Mage::getModel('amgiftreg/event');
		$event->load($eventId);
		if ($event->getCustomerId() != $this->_customerId){
			$this->_redirect('*/*/');
			return;
		}
		Mage::register('current_event', $event);
		/**
		 * @var $shareModel Amasty_Giftregistry_Model_Share
		 */
		$shareModel = Mage::getModel('amgiftreg/share');
		$shareModel->setEmails($this->getRequest()->getPost('emails'));
		$shareModel->setEmailsFile('emails_csv');
		$shareModel->setMessage($this->getRequest()->getPost('message'));




		$translate = Mage::getSingleton('core/translate');
		/* @var $translate Mage_Core_Model_Translate */
		$translate->setTranslateInline(false);

		try {
			$shareModel->setVars(array(
				'customer'       => Mage::getSingleton('customer/session')->getCustomer(),
				'salable'        => $event->isSalable() ? 'yes' : '',
				'items'          => $this->getLayout()->createBlock('amgiftreg/share_email_items')->toHtml(),
				'addAllLink'     => Mage::getUrl('*/gift/cart', array('event_id' => $event->getId())),
				'viewOnSiteLink' => Mage::helper('amgiftreg')->getRegistryUrl($event->getId()),
			));



			$shareModel->send();
			//var_dump($shareModel->getErrors());
			//die;

			if($shareModel->hasErrors()) {
				foreach($shareModel->getErrors() as $error) {
					Mage::getSingleton('customer/session')->addError($error);
				}
				Mage::getSingleton('amgiftreg/session')->setSharingForm($this->getRequest()->getPost());
				$this->_redirect('*/*/share', array('id' => $event->getId()));
				return;
			}


			$translate->setTranslateInline(true);
			Mage::dispatchEvent('amgiftreg_share', array('event' => $event));
			Mage::getSingleton('customer/session')->addSuccess(
				$this->__('Your Gift registry has been shared.')
			);
			$this->_redirect('*/*/share', array('id' => $event->getId()));
		}
		catch (Exception $e) {
			$translate->setTranslateInline(true);

			Mage::getSingleton('amgiftreg/session')->addError($e->getMessage());
			Mage::getSingleton('amgiftreg/session')->setSharingForm($this->getRequest()->getPost());
			$this->_redirect('*/*/share', array('id' => $event->getId()));
		}
	}
}