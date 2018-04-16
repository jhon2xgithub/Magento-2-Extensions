<?php
namespace Braem\Members\Controller\Adminhtml\Department;

class Edit extends \Braem\Members\Controller\Adminhtml\Department
{
    /**
     * Backend session
     *
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * Page factory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Result JSON factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * constructor
     *
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Braem\Members\Model\DepartmentFactory $departmentFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        \Braem\Members\Controller\Adminhtml\Department\Builder $departmentBuilder,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\Session $backendSession

    )
    {
        $this->_backendSession    = $backendSession;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($resultRedirectFactory, $context, $departmentBuilder);
    }




    /**
     * is action allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Braem_Members::department');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        /** @var \Braem\Members\Model\Department $department */
        $department = $this->_departmentBuilder->build($this->getRequest());
        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Braem_Members::department');
        $resultPage->getConfig()->getTitle()->set(__('Departments'));
        if ($id) {
            $department->load($id);
            if (!$department->getId()) {
                $this->messageManager->addError(__('This Department no longer exists.'));
                $resultRedirect = $this->_resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'braem_members/*/edit',
                    [
                        'entity_id' => $department->getId(),
                        '_current' => true
                    ]
                );
                return $resultRedirect;
            }
        }
        $title = $department->getId() ? $department->getName() : __('New Department');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $data = $this->_backendSession->getData('braem_members_department_data', true);
        if (!empty($data)) {
            $department->setData($data);
        }
        return $resultPage;
    }
}
