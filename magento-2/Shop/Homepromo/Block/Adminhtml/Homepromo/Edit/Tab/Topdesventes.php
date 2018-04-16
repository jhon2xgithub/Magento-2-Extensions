<?php
namespace Shop\Homepromo\Block\Adminhtml\Homepromo\Edit\Tab;
use Shop\Homepromo\Model\TopdesventesFactory;
class Topdesventes extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Webkul\Hello\Model\GridFactory
     */
    protected $productFactory;
    protected $_resourceFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;
    /**
     * Contact factory
     *
     * @var homepromoFactory
     */
    protected $homepromoFactory;
    /**
     * @var  \Magento\Framework\Registry
     */
    protected $registry;
    protected $_objectManager = null;
    /**
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Registry $registry
     * @param homepromoFactory $attachmentFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        TopdesventesFactory $homepromoFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Reports\Model\ResourceModel\Report\Collection\Factory $resourceFactory,
        array $data = []
    ) {
        $this->homepromoFactory = $homepromoFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_objectManager = $objectManager;
        $this->registry = $registry;
        $this->productFactory = $productFactory;
        $this->_resourceFactory = $resourceFactory;
        parent::__construct($context, $backendHelper, $data);
    }
    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('topdesventesGrid');
        $this->setDefaultSort('promo_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('promo_id')) {
            $this->setDefaultFilter(array('in_product' => 1));
        }
    }
    /**
     * add Column Filter To Collection
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_product') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('product_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('product_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
    /**
     * prepare collection
     */
    protected function _prepareCollection()
    {        
  
        $resourceCollection = $this->_resourceFactory->create('Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection');        
        $this->setCollection($resourceCollection);
        return parent::_prepareCollection();
    }
    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_product',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_product',
                'align' => 'center',
                'index' => 'product_id',
                'values' => $this->_getSelectedProducts(),
            ]
        );
        $this->addColumn(
            'product_id',
            [
                'header' => __('Product ID'),
                'type' => 'number',
                'index' => 'product_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'product_name',
            [
                'header' => __('Name'),
                'index' => 'product_name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'sku',
            [
                'header' => __('Sku'),
                'index' => 'sku',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'product_price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'index' => 'product_price',
                'width' => '50px',
            ]
        );
        return parent::_prepareColumns();
    }
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/topdesventesgrid', ['_current' => true]);
    }
    /**
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }
    protected function _getSelectedProducts()
    {
        $homepromo = $this->getPromo();
        return $homepromo->getProducts($homepromo);
    }
    /**
     * Retrieve selected products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        $homepromo = $this->getPromo();
        $selected = $homepromo->getProducts($homepromo);
        if (!is_array($selected)) {
            $getSelectedProductscted = [];
        }
        return $selected;
    }
    protected function getPromo()
    {
        $homepromoId = $this->getRequest()->getParam('promo_id');
        $homepromo   = $this->homepromoFactory->create();
        if ($homepromoId) {
            $homepromo->load($homepromoId);
        }
        return $homepromo;
    }
    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return true;
    }
}