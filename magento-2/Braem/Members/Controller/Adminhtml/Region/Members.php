<?php
namespace Braem\Members\Controller\Adminhtml\Region;

class Members extends \Braem\Members\Controller\Adminhtml\Region
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
     * @param \Braem\Members\Model\RegionFactory $memberFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Braem\Members\Model\RegionFactory $regionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        Builder $regionBuilder
    )
    {
        $this->_resultLayoutFactory = $resultLayoutFactory;
        parent::__construct($resultRedirectFactory, $context,$regionBuilder);
    }

    /**
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $this->_regionBuilder->build($this->getRequest());

        $resultLayout = $this->_resultLayoutFactory->create();
        /** @var \Braem\Members\Block\Adminhtml\Region\Edit\Tab\Member $membersBlock */
        $membersBlock = $resultLayout->getLayout()->getBlock('region.edit.tab.member');
        if ($membersBlock) {
            $membersBlock->setRegionMembers($this->getRequest()->getPost('region_members', null));
        }
        return $resultLayout;
    }
}
