<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Blog\Controller\Adminhtml;

/**
 * blog controller
 */
abstract class Blog extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

        /**
     * File Factory.
     *
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;


    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_resultLayoutFactory;

    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $_jsHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_massActionFilter;

    protected $_directorylist;
    protected $_filesystem;

    // protected $_imageFactory;
                   
    /**
     * Initialize Group Controller
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(    
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Helper\Js $jsHelper,
        \Magento\Ui\Component\MassAction\Filter $massActionFilter,
        \Magento\Framework\App\Filesystem\DirectoryList $_directorylist,
        \Magento\Framework\Filesystem $filesystem
        
            
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_fileFactory = $fileFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;  
        $this->_fileUploaderFactory = $uploaderFactory;
        $this->_resultLayoutFactory = $resultLayoutFactory;
        $this->_storeManager = $storeManager;
        $this->_jsHelper = $jsHelper;
        $this->_massActionFilter = $massActionFilter;
        $this->_directorylist=$_directorylist;
        $this->_filesystem = $filesystem;
        // $this->_imageFactory = $imageFactory;        

        parent::__construct($context);

    }

    /**
     * Initiate action
     *
     * @return this 
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Shop_Blog::blog')->_addBreadcrumb(__('blog'), __('blog'));
        return $this;
    }

    /**
     * Determine if authorized to perform group actions.
     * Check permission via ACL resource
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Shop_Blog::blog');
    }

    public function getResultPage()
    {
        if (is_null($this->_resultPage)) {
            $this->_resultPage = $this->resultPageFactory->create();
        }
        return $this->_resultPage;
    }

    protected function _setPageData()
    {
        $resultPage = $this->getResultPage();
        $resultPage->setActiveMenu('Shop_Blog::base');
        $resultPage->getConfig()->getTitle()->prepend((__('Blog')));

        //Add bread crumb
        $resultPage->addBreadcrumb(__('Shop'), __('Shop'));
        $resultPage->addBreadcrumb(__('Blog'), __('Manage Blogs'));

        return $this;
    }
}
