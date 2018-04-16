<?php
namespace Braem\Members\Controller\Adminhtml\Member;

class Teams extends \Braem\Members\Controller\Adminhtml\Member
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
     * @param \Braem\Members\Model\MemberFactory $teamFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Braem\Members\Model\MemberFactory $teamFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_resultLayoutFactory = $resultLayoutFactory;
        parent::__construct($teamFactory, $registry, $resultRedirectFactory, $context);
    }

    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $this->_initMember();
        $resultLayout = $this->_resultLayoutFactory->create();
        /** @var \Braem\Members\Block\Adminhtml\Member\Edit\Tab\Team $teamsBlock */
        $teamsBlock = $resultLayout->getLayout()->getBlock('member.edit.tab.team');
        if ($teamsBlock) {

            $teamsBlock->setMemberTeams($this->getRequest()->getPost('member_teams', null));
        }
        return $resultLayout;
    }
}
