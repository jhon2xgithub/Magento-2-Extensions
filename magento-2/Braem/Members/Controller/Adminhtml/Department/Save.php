<?php
namespace Braem\Members\Controller\Adminhtml\Department;

class Save extends \Braem\Members\Controller\Adminhtml\Department
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
     * @param \Braem\Members\Model\DepartmentFactory $departmentFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Backend\Helper\Js $jsHelper,

        \Braem\Members\Model\DepartmentFactory $departmentFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        \Braem\Members\Controller\Adminhtml\Department\Builder $departmentBuilder
    )
    {
        $this->_backendSession = $backendSession;
        $this->_jsHelper       = $jsHelper;
        parent::__construct($resultRedirectFactory, $context, $departmentBuilder);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('department');

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $department = $this->_departmentBuilder->build($this->getRequest());

            $department->setData($data);



            /**
             * Check "Use Default Value" checkboxes values
             */
            $useDefaults = $this->getRequest()->getPost('use_default');
            if ($useDefaults) {
                foreach ($useDefaults as $attributeCode) {
                    $department->setData($attributeCode, false);
                }
            }


            $members = $this->getRequest()->getPost('members', -1);
            if ($members != -1) {
                $department->setMembersData($this->_jsHelper->decodeGridSerializedInput($members));
            }
            $this->_eventManager->dispatch(
                'braem_members_department_prepare_save',
                [
                    'department' => $department,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $department->save();
                $this->messageManager->addSuccess(__('The Department has been saved.'));
                $this->_backendSession->setBraemMembersDepartmentData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'braem_members/*/edit',
                        [
                            'entity_id' => $department->getId(),
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
                $this->messageManager->addException($e, __('Something went wrong while saving the Department.'));
            }
            $this->_getSession()->setBraemMembersDepartmentData($data);
            $resultRedirect->setPath(
                'braem_members/*/edit',
                [
                    'entity_id' => $department->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('braem_members/*/');
        return $resultRedirect;
    }
}
