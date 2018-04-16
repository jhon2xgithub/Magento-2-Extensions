<?php
namespace Braem\Members\Controller\Adminhtml;

abstract class Department extends \Magento\Backend\App\Action
{

    protected $_departmentBuilder;


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
     * @param \Braem\Members\Model\DepartmentFactory $departmentFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        //\Braem\Members\Model\DepartmentFactory $departmentFactory,
        //\Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        Department\Builder $departmentBuilder
    )
    {
        //$this->_departmentFactory           = $departmentFactory;
        //$this->_coreRegistry          = $coreRegistry;
        $this->_resultRedirectFactory = $resultRedirectFactory;
        $this->_departmentBuilder = $departmentBuilder;

        parent::__construct($context);
    }

}
