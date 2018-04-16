<?php
namespace Braem\Members\Controller\Adminhtml\Team;

class Members extends \Braem\Members\Controller\Adminhtml\Team
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
     * @param \Braem\Members\Model\TeamFactory $memberFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Braem\Members\Model\TeamFactory $teamFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        Builder $teamBuilder
    )
    {
        $this->_resultLayoutFactory = $resultLayoutFactory;
        parent::__construct($resultRedirectFactory, $context,$teamBuilder);
    }

    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $this->_teamBuilder->build($this->getRequest());

        $resultLayout = $this->_resultLayoutFactory->create();
        /** @var \Braem\Members\Block\Adminhtml\Team\Edit\Tab\Member $membersBlock */
        $membersBlock = $resultLayout->getLayout()->getBlock('team.edit.tab.member');
        if ($membersBlock) {
            $membersBlock->setTeamMembers($this->getRequest()->getPost('team_members', null));
        }
        return $resultLayout;
    }
}
