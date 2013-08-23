<?php

class Holosystems_Typo3connector_Adminhtml_Typo3connectorController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('typo3connector/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Content Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function editAction() {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('typo3connector/typo3connector')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('typo3connector_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('typo3connector/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Content Manager'), Mage::helper('adminhtml')->__('Content Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('typo3connector/adminhtml_typo3connector_edit'))
                    ->_addLeft($this->getLayout()->createBlock('typo3connector/adminhtml_typo3connector_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('typo3connector')->__('Content does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {


            $model = Mage::getModel('typo3connector/typo3connector');
            $model->setData($data)
                    ->setId($this->getRequest()->getParam('id'));

            try {
                if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }

                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('typo3connector')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('typo3connector')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('typo3connector/typo3connector');

                $model->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $typo3connectorIds = $this->getRequest()->getParam('typo3connector');
        if (!is_array($typo3connectorIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($typo3connectorIds as $typo3connectorId) {
                    $typo3connector = Mage::getModel('typo3connector/typo3connector')->load($typo3connectorId);
                    $typo3connector->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($typo3connectorIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $typo3connectorIds = $this->getRequest()->getParam('typo3connector');
        if (!is_array($typo3connectorIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($typo3connectorIds as $typo3connectorId) {
                    $typo3connector = Mage::getSingleton('typo3connector/typo3connector')
                            ->load($typo3connectorId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($typo3connectorIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'typo3connector.csv';
        $content = $this->getLayout()->createBlock('typo3connector/adminhtml_typo3connector_grid')
                ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'typo3connector.xml';
        $content = $this->getLayout()->createBlock('typo3connector/adminhtml_typo3connector_grid')
                ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

}