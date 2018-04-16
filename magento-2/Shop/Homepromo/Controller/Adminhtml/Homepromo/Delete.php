<?php
namespace Shop\Homepromo\Controller\Adminhtml\Homepromo;
use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
class Delete extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    /*
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('promo_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('Shop\Homepromo\Model\Homepromo');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The contact has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['promo_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a promo to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}