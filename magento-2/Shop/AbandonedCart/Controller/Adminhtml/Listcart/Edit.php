<?php
namespace Shop\AbandonedCart\Controller\Adminhtml\Listcart;

class Edit extends \Shop\AbandonedCart\Controller\Adminhtml\Listcart
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
     * @param \Shop\AbandonedCart\Model\ListcartFactory $listcartFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        \Shop\AbandonedCart\Controller\Adminhtml\Listcart\Builder $listcartBuilder,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Backend\Model\Session $backendSession

    )
    {
        $this->_backendSession    = $backendSession;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($resultRedirectFactory, $context, $listcartBuilder);
    }




    /**
     * is action allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Shop_AbandonedCart::listcart');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        /** @var \Shop\AbandonedCart\Model\Listcart $listcart */
        $listcart = $this->_listcartBuilder->build($this->getRequest());
        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Shop_AbandonedCart::listcart');
        $resultPage->getConfig()->getTitle()->set(__('Listcarts'));
        if ($id) {
            $listcart->load($id);
            if (!$listcart->getId()) {
                $this->messageManager->addError(__('This Listcart no longer exists.'));
                $resultRedirect = $this->_resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'abandonedcart/*/edit',
                    [
                        'entity_id' => $listcart->getId(),
                        '_current' => true
                    ]
                );
                return $resultRedirect;
            }
        }
        $title = $listcart->getId() ? $listcart->getName() : __('New Listcart');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $data = $this->_backendSession->getData('abandonedcart_listcart_data', true);
        if (!empty($data)) {
            $listcart->setData($data);
        }
        return $resultPage;
    }
}
