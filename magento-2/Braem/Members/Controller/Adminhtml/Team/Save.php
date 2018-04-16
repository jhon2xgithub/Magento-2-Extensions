<?php
namespace Braem\Members\Controller\Adminhtml\Team;

class Save extends \Braem\Members\Controller\Adminhtml\Team
{
    /**
     * Backend session
     * 
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * JS helper
     * 
     * @var \Magento\Backend\Helper\Js
     */
    protected $_jsHelper;

    /**
     * constructor
     * 
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Magento\Backend\Helper\Js $jsHelper
     * @param \Braem\Members\Model\TeamFactory $teamFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Backend\Helper\Js $jsHelper,

        \Braem\Members\Model\TeamFactory $teamFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        \Braem\Members\Controller\Adminhtml\Team\Builder $teamBuilder
    )
    {
        $this->_backendSession = $backendSession;
        $this->_jsHelper       = $jsHelper;
        parent::__construct($resultRedirectFactory, $context, $teamBuilder);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('team');

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $team = $this->_teamBuilder->build($this->getRequest());

            $team->setData($data);



            /**
             * Check "Use Default Value" checkboxes values
             */
            $useDefaults = $this->getRequest()->getPost('use_default');
            if ($useDefaults) {
                foreach ($useDefaults as $attributeCode) {
                    $team->setData($attributeCode, false);
                }
            }


            $members = $this->getRequest()->getPost('members', -1);
            if ($members != -1) {
                $team->setMembersData($this->_jsHelper->decodeGridSerializedInput($members));
            }
            $this->_eventManager->dispatch(
                'braem_members_team_prepare_save',
                [
                    'team' => $team,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $team->save();
                $this->messageManager->addSuccess(__('The Team has been saved.'));
                $this->_backendSession->setBraemMembersTeamData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'braem_members/*/edit',
                        [
                            'entity_id' => $team->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('braem_members/*/');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Team.'));
            }
            $this->_getSession()->setBraemMembersTeamData($data);
            $resultRedirect->setPath(
                'braem_members/*/edit',
                [
                    'entity_id' => $team->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('braem_members/*/');
        return $resultRedirect;
    }
}
