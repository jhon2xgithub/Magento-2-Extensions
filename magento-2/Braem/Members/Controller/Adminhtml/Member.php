<?php
namespace Braem\Members\Controller\Adminhtml;

abstract class Member extends \Magento\Backend\App\Action
{
    /**
     * Member Factory
     * 
     * @var \Braem\Members\Model\MemberFactory
     */
    protected $_memberFactory;

    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Result redirect factory
     * 
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $_resultRedirectFactory;

    /**
     * constructor
     * 
     * @param \Braem\Members\Model\MemberFactory $memberFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Braem\Members\Model\MemberFactory $memberFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_memberFactory         = $memberFactory;
        $this->_coreRegistry          = $coreRegistry;
        $this->_resultRedirectFactory = $resultRedirectFactory;
        parent::__construct($context);
    }

    /**
     * Init Member
     *
     * @return \Braem\Members\Model\Member
     */
    protected function _initMember()
    {
        $memberId  = (int) $this->getRequest()->getParam('member_id');
        /** @var \Braem\Members\Model\Member $member */
        $member    = $this->_memberFactory->create();
        if ($memberId) {
            $member->load($memberId);
        }
        $this->_coreRegistry->register('braem_members_member', $member);
        return $member;
    }
}
