<?php
namespace Mageplaza\Blog\Controller\Adminhtml\Post;

/**
 * Class Products
 * @package Mageplaza\Blog\Controller\Adminhtml\Post
 */
class Products extends \Mageplaza\Blog\Controller\Adminhtml\Post
{
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

	/**
	 * Products constructor.
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Mageplaza\Blog\Model\PostFactory $productFactory
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
	 */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Mageplaza\Blog\Model\PostFactory $productFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($productFactory, $registry, $context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->initPost();
        $resultLayout = $this->resultLayoutFactory->create();
        $productsBlock = $resultLayout->getLayout()->getBlock('post.edit.tab.product');
        if ($productsBlock) {
            $productsBlock->setPostProducts($this->getRequest()->getPost('post_products', null));
        }
        return $resultLayout;
    }
}
