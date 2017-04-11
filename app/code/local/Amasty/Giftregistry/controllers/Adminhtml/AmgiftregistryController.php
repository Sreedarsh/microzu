<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2016 Amasty (https://www.amasty.com)
 * @package Amasty_Giftregistry
 */
class Amasty_Giftregistry_Adminhtml_AmgiftregistryController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_title($this->__('Gift Registries'));
        $this->_addContent(
            $this->getLayout()->createBlock('amgiftreg/adminhtml_amgiftregistry')
        );
        $this->renderLayout();
    }

    public function editAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = Mage::getModel('amgiftreg/event')->load($id);

        if (!$model->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('amgiftreg')->__('Record does not exist')
            );
            $this->_redirect('*/*/');
            return;
        }
        Mage::register('amgiftreg_event', $model);

        $this->loadLayout();
        $this->_title($this->__('Edit'));


        $this->_addContent(
            $this->getLayout()->createBlock(
                'amgiftreg/adminhtml_amgiftregistry_edit'
            )
        );
        $this->_addLeft($this->getLayout()->createBlock('amgiftreg/adminhtml_amgiftregistry_edit_tabs'));

        $this->renderLayout();
    }

    public function saveAction()
    {
        /** @var $model Amasty_Giftregistry_Model_Event */
        $id = (int)$this->getRequest()->getParam('id');
        $model = Mage::getModel('amgiftreg/event')->load($id);

        if (!$model->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('amgiftreg')->__('Record does not exist')
            );
            $this->_redirect('*/*/');
            return;
        }

        $postQty = $this->getRequest()->getParam('qty');
        if(is_array($postQty)) {
            $itemIds = array_keys($postQty);
            $collection = $model->getItemsCollection()->addFieldToFilter('item_id', array("in"=>$itemIds));

            foreach($collection as $item) {
                if(!isset($postQty[$item->getId()])) {
                    continue;
                }
                $qty = $postQty[$item->getId()];
                if ($qty == 0)
                    $item->delete();
                else
                {
                    $item->setQty(max(0.01, intVal($qty)));
                    $item->save();
                }
            }

        }

        //die;

        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Quantities have been successfully updated'));

        $this->_redirect('*/*/');
    }


    public function massDeleteAction()
    {
        $deleteIds = $this->getRequest()->getParam('events');
        if(!is_array($deleteIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftreg')->__('Please select event(s).'));
        } else {
            try {
                $eventCollection = Mage::getModel('amgiftreg/event')->getCollection()->addFieldToFilter("event_id", array("in"=>$deleteIds));
                foreach($eventCollection as $event) {
                    $event->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d record(s) were deleted.', count($deleteIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    public function deleteItemAction()
    {
        $deleteId = $this->getRequest()->getParam('item_id');
        $eventId = null;

        if(!$deleteId) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgiftreg')->__('Please select item.'));
        } else {
            try {
                $item = Mage::getModel('amgiftreg/item')->load($deleteId);
                $eventId = $item->getEventId();
                $item->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('amgiftreg')->__('Item successfully deleted.')
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        if(!is_null($eventId)) {
            $this->_redirect('*/*/edit', array('id'=>$eventId));
        } else {
            $this->_redirect('*/*/index');
        }
    }


    public function gridOrdersAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = Mage::getModel('amgiftreg/event')->load($id);

        if (!$model->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('amgiftreg')->__('Record does not exist')
            );
            $this->_redirect('*/*/');
            return;
        }
        Mage::register('amgiftreg_event', $model);

        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('amgiftreg/adminhtml_amgiftregistry_edit_tab_order_grid')->toHtml()
        );

        // http://gift.sumrak.p53m.sty/index.php/admin/amgiftregistry/grid/id/8/key/9cd66326a286f0d5a6cd8cae11490aab/
    }

    public function gridItemsAction()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = Mage::getModel('amgiftreg/event')->load($id);

        if (!$model->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('amgiftreg')->__('Record does not exist')
            );
            $this->_redirect('*/*/');
            return;
        }
        Mage::register('amgiftreg_event', $model);

        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('amgiftreg/adminhtml_amgiftregistry_edit_tab_item_grid')->toHtml()
        );

        // http://gift.sumrak.p53m.sty/index.php/admin/amgiftregistry/grid/id/8/key/9cd66326a286f0d5a6cd8cae11490aab/
    }


}