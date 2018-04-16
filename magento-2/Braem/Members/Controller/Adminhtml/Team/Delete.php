<?php
namespace Braem\Members\Controller\Adminhtml\Team;

class Delete extends \Braem\Members\Controller\Adminhtml\Team
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
                /** @var \Braem\Members\Model\Team $team */
                $team = $this->_teamBuilder->build($this->getRequest());
                $team->load($id);
                $name = $team->getName();
                $team->delete();
                $this->messageManager->addSuccess(__('The Team has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_braem_members_team_on_delete',
                    ['name' => $name, 'status' => 'success']
                );
                $resultRedirect->setPath('braem_members/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_braem_members_team_on_delete',
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
        $this->messageManager->addError(__('Team to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('braem_members/*/');
        return $resultRedirect;
    }
}
