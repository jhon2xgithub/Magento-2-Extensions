<?php
namespace Braem\Members\Controller\Adminhtml\Member;

class Regions extends \Braem\Members\Controller\Adminhtml\Member
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
     * @param \Braem\Members\Model\MemberFactory $regionFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Braem\Members\Model\MemberFactory $regionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_resultLayoutFactory = $resultLayoutFactory;
        parent::__construct($regionFactory, $registry, $resultRedirectFactory, $context);
    }

    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $this->_initMember();
        $resultLayout = $this->_resultLayoutFactory->create();
        /** @var \Braem\Members\Block\Adminhtml\Member\Edit\Tab\Region $regionsBlock */
        $regionsBlock = $resultLayout->getLayout()->getBlock('member.edit.tab.region');
        if ($regionsBlock) {
            $regionsBlock->setMemberRegions($this->getRequest()->getPost('member_regions', null));
        }
        return $resultLayout;
    }
}
