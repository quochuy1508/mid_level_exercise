<?php

namespace Magenest\EavModel\Controller\Adminhtml\Merchant;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Delete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Cms::page_delete';

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
{
    // check if we know what should be deleted
    $id = $this->getRequest()->getParam('entity_id');
    /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
    $resultRedirect = $this->resultRedirectFactory->create();

    if ($id) {
        $title = "";
        try {
            // init model and delete
            $model = $this->_objectManager->create(\Magenest\EavModel\Model\Merchant::class);
            $model->load($id);

            $model->delete();

            // display success message
            $this->messageManager->addSuccessMessage(__('The merchant has been deleted.'));

            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            // display error message
            $this->messageManager->addErrorMessage($e->getMessage());
            // go back to edit form
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
        }
    }

    // display error message
    $this->messageManager->addErrorMessage(__('We can\'t find a merchant to delete.'));

    // go to grid
    return $resultRedirect->setPath('*/*/');
}
}
