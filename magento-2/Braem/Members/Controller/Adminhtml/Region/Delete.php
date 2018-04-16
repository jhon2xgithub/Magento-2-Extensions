<?php
namespace Braem\Members\Controller\Adminhtml\Region;

class Delete extends \Braem\Members\Controller\Adminhtml\Region
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            $name = "";
            try {
                /** @var \Braem\Members\Model\Region $region */
                $region = $this->_regionBuilder->build($this->getRequest());
                $region->load($id);
                $name = $region->getName();
                $region->delete();
                $this->messageManager->addSuccess(__('The Region has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_braem_members_region_on_delete',
                    ['name' => $name, 'status' => 'success']
                );
                $resultRedirect->setPath('braem_members/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_braem_members_region_on_delete',
                    ['name' => $name, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                $resultRedirect->setPath('braem_members/*/edit', ['entity_id' => $id]);
                return $resultRedirect;
            }
        }
        // display error message
        $this->messageManager->addError(__('Region to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('braem_members/*/');
        return $resultRedirect;
    }
}
