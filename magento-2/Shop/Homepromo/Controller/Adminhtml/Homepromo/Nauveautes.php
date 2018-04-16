<?php
/* for loading the products grid, but it needs layout xml to call */
namespace Shop\Homepromo\Controller\Adminhtml\Homepromo;
use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
class Nauveautes extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_resultLayoutFactory;
    /**
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param Action\Context $context
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->_resultLayoutFactory = $resultLayoutFactory;
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return true;
    }
    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('homepromo.edit.tab.nauveautes')
                     ->setInProducts($this->getRequest()->getPost('promo_products', null));
        return $resultLayout;
    }
}