<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_GiftController extends Mage_Core_Controller_Front_Action
{
	/**
	 * list and search events
	 *
	 * TODO: Выводить только будущие события
	 */
	public function listAction()
	{
		$eventTitle = null;
		if($this->getRequest()->getQuery('event_title')) {
			$eventTitle = $this->getRequest()->getQuery('event_title');
		}
		Mage::register('filter_event_title', $eventTitle);
		$this->loadLayout()->renderLayout();
	}

	/**
	 * view event
	 */
	public function viewAction()
	{
		$event = $this->_loadModel();
		if(!$event) {
			return;
		}
		if($event->getPassword() && !Mage::getSingleton("amgiftreg/session")->getData('password_entry_' . $event->getId())){
			$this->_redirect('*/*/password', array('id'=>$event->getId()));
		}
		Mage::register('current_event', $event);

		$this->loadLayout()->renderLayout();
	}

	/**
	 * Password form for protected event
	 */
	public function passwordAction()
	{
		$event = $this->_loadModel();
		if(!$event) {
			return;
		}
		$helper = Mage::helper('amgiftreg');
		$password = $this->getRequest()->getParam('password');
		if($password){
			if($password == $event->getPassword()) {
				Mage::getSingleton("amgiftreg/session")->setData(
					'password_entry_' . $event->getId(), '1'
				);
				$this->_redirect('*/*/view', array('id'=>$event->getId()));
			} else {
				Mage::getSingleton('core/session')->addError($helper->__("Password incorrect!"));
				//$this->_redirect('*/*/password', array('id'=>$event->getId()));
			}
		}


		Mage::register('current_event', $event);

		$this->loadLayout()->renderLayout();
	}

	/**
	 * @return false|Amasty_Giftregistry_Model_Event
	 */
	protected function _loadModel()
	{
		$event = Mage::getModel('amgiftreg/event');
		$id = $this->getRequest()->getParam('id');
		if ($id){
			$event->load($id);
		}

		if($event->isObjectNew() || $event->getSearchable() == 0) {
			$this->_redirect('*/*/list');
			return false;
		}
		return $event;
	}

	/**
	 * Add to cart items from event
	 * @throws Exception
	 */
	public function cartAction()
	{
		$messages           = array();
		$urls               = array();

		$eventId = $this->getRequest()->getParam('event_id');
		$event = Mage::getModel('amgiftreg/event')->load($eventId);
		if (!$event->getId()) {
			$this->_redirect('*/*');
			return;
		}

		$selectedIds = $this->getRequest()->getParam('cb');
		if(!is_array($selectedIds)) {
			$selectedIds = array();
		}

		$addId = $this->getRequest()->getParam('add_id');
		if(!empty($addId)) {
			$selectedIds[] = $addId;
		}

		$isSelected = count($selectedIds) > 0;
		$requestQty = $this->getRequest()->getParam('qty');
		if(!is_array($requestQty)) {
			$requestQty = array();
		}
		/* @var $quote Mage_Checkout_Model_Cart */
		$quote = Mage::getSingleton('checkout/cart');
		foreach ($event->getItems() as $item) {
			if ($isSelected && !in_array($item->getId(), $selectedIds))
				continue;
			try {
				$qty = $item->getQty();
				if(isset($requestQty[$item->getId()])) {
					$qty = $requestQty[$item->getId()];
				}
				$product = Mage::getModel('catalog/product')
					->load($item->getProductId())
					->setQty(max(0.01, $qty));

				$req = unserialize($item->getBuyRequest());
				$req['qty'] = $product->getQty();
				$req['amgiftreg_item_id'] = $item->getId();

				$quote->addProduct($product, $req);

			}
			catch (Exception $e) {
				$url = Mage::getSingleton('checkout/session')
					->getRedirectUrl(true);

				if ($url) {
					$url = Mage::getModel('core/url')
						->getUrl('catalog/product/view', array(
							'id' => $item->getProductId(),
							'list_next' => 1
						));

					$urls[]         = $url;
					$messages[]     = $e->getMessage();
				}
				else {
					Mage::getSingleton('customer/session')->addNotice($e->getMessage());
					$this->_redirect('*/*/view', array('id'=>$event->getId()));
					return;
				}
			}
		}
		$quote->save();

		if ($urls) {
			Mage::getSingleton('checkout/session')->addNotice(array_shift($messages));
			$this->getResponse()->setRedirect(array_shift($urls));

			Mage::getSingleton('checkout/session')->setAmgiftregPendingUrls($urls);
			Mage::getSingleton('checkout/session')->setAmgiftregPendingMessages($messages);
		}
		else {
			//$this->_redirectToCart();
			$this->_redirect('checkout/cart');
		}
	}
}