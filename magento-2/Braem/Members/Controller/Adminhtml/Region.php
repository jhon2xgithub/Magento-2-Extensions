<?php
namespace Braem\Members\Controller\Adminhtml;

abstract class Region extends \Magento\Backend\App\Action
{

    protected $_regionBuilder;


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
     * @param \Braem\Members\Model\RegionFactory $regionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        Region\Builder $regionBuilder
    )
    {
        $this->_resultRedirectFactory = $resultRedirectFactory;
        $this->_regionBuilder = $regionBuilder;

        parent::__construct($context);
    }
}

