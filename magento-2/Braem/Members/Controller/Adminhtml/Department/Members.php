<?php
namespace Braem\Members\Controller\Adminhtml\Department;

class Members extends \Braem\Members\Controller\Adminhtml\Department
{
    /**
     * Result layout factory
     *
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_resultLayoutFactory;

    /**
     * constructor
     *
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Braem\Members\Model\DepartmentFactory $memberFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Braem\Members\Model\DepartmentFactory $departmentFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        Builder $departmentBuilder
    )
    {
        $this->_resultLayoutFactory = $resultLayoutFactory;
        parent::__construct($resultRedirectFactory, $context,$departmentBuilder);
    }

    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $this->_departmentBuilder->build($this->getRequest());

        $resultLayout = $this->_resultLayoutFactory->create();
        /** @var \Braem\Members\Block\Adminhtml\Department\Edit\Tab\Member $membersBlock */
        $membersBlock = $resultLayout->getLayout()->getBlock('department.edit.tab.member');
        if ($membersBlock) {
            $membersBlock->setDepartmentMembers($this->getRequest()->getPost('department_members', null));
        }
        return $resultLayout;
    }
}
