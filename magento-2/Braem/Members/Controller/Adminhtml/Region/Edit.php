<?php
namespace Braem\Members\Controller\Adminhtml\Region;

class Edit extends \Braem\Members\Controller\Adminhtml\Region
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
     * @param \Braem\Members\Model\RegionFactory $regionFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        \Braem\Members\Controller\Adminhtml\Region\Builder $regionBuilder,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\Session $backendSession

    )
    {
        $this->_backendSession    = $backendSession;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($resultRedirectFactory, $context, $regionBuilder);
    }




    /**
     * is action allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Braem_Members::region');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        /** @var \Braem\Members\Model\Region $region */
        $region = $this->_regionBuilder->build($this->getRequest());
        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Braem_Members::region');
        $resultPage->getConfig()->getTitle()->set(__('Regions'));
        if ($id) {
            $region->load($id);
            if (!$region->getId()) {
                $this->messageManager->addError(__('This Region no longer exists.'));
                $resultRedirect = $this->_resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'braem_members/*/edit',
                    [
                        'entity_id' => $region->getId(),
                        '_current' => true
                    ]
                );
                return $resultRedirect;
            }
        }
        $title = $region->getId() ? $region->getName() : __('New Region');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $data = $this->_backendSession->getData('braem_members_region_data', true);
        if (!empty($data)) {
            $region->setData($data);
        }
        return $resultPage;
    }
}
