<?php
namespace Braem\Members\Controller\Adminhtml\Member;

class Delete extends \Braem\Members\Controller\Adminhtml\Member
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->_resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('member_id');
        if ($id) {
            $name = "";
            try {
                /** @var \Braem\Members\Model\Member $member */
                $member = $this->_memberFactory->create();
                $member->load($id);
                $name = $member->getName();
                $member->delete();
                $this->messageManager->addSuccess(__('The Member has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_braem_members_member_on_delete',
                    ['name' => $name, 'status' => 'success']
                );
                $resultRedirect->setPath('braem_members/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_braem_members_member_on_delete',
                    ['name' => $name, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                $resultRedirect->setPath('braem_members/*/edit', ['member_id' => $id]);
                return $resultRedirect;
            }
        }
        // display error message
        $this->messageManager->addError(__('Member to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('braem_members/*/');
        return $resultRedirect;
    }
}
