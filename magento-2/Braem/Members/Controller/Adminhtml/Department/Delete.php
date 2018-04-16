<?php
namespace Braem\Members\Controller\Adminhtml\Department;

class Delete extends \Braem\Members\Controller\Adminhtml\Department
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
                /** @var \Braem\Members\Model\Department $department */
                $department = $this->_departmentBuilder->build($this->getRequest());
                $department->load($id);
                $name = $department->getName();
                $department->delete();
                $this->messageManager->addSuccess(__('The Department has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_braem_members_department_on_delete',
                    ['name' => $name, 'status' => 'success']
                );
                $resultRedirect->setPath('braem_members/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_braem_members_department_on_delete',
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
        $this->messageManager->addError(__('Department to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('braem_members/*/');
        return $resultRedirect;
    }
}
