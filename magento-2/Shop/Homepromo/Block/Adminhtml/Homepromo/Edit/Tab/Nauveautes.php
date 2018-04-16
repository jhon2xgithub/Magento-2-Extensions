<?php
namespace Shop\Homepromo\Block\Adminhtml\Homepromo\Edit\Tab;
use Shop\Homepromo\Model\NauveautesFactory;
class Nauveautes extends \Magento\Backend\Block\Widget\Grid\Extended
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
     * Catalog config
     *
     * @var \Magento\Catalog\Model\Config
     */
    protected $_catalogConfig;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;
    protected $resultLayoutFactory;
    // protected $_abstractProduct;
    /**
     * \Magento\Framework\View\Result\LayoutFactory  used to call block method or acceing block class
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
        NauveautesFactory $homepromoFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory, 
        \Magento\Catalog\Block\Product\Context $productContext,
        array $data = []
    ) {
        $this->homepromoFactory = $homepromoFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_objectManager = $objectManager;
        $this->registry = $registry;
        $this->productFactory = $productFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_resultLayoutFactory = $resultLayoutFactory;
        // $this->_abstractProduct = $abstractProduct;
        $this->_catalogConfig = $productContext->getCatalogConfig();
        parent::__construct($context, $backendHelper, $data);
    }
    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('nauveautesGrid');
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
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
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

        $collection = $this->productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());      
   
        $collection = $this->_addProductPrices($collection)
            ->addStoreFilter()           
            ->addAttributeToSort('created_at', 'desc');
          
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function _addProductPrices(
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
    ) {
        return $collection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->addUrlRewrite();
    }
    
    /**
     * @return $this
     */
    protected function _prepareColumns()
    {

        $model = $this->_objectManager->get('\Shop\Homepromo\Model\Homepromo');
        $this->addColumn(
            'in_product',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_product',
                'align' => 'center',
                'index' => 'entity_id',
                'values' => $this->_getSelectedProducts(),
            ]
        );
        $this->addColumn(
            'entity_id',
            [
                'header' => __('Product ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'index' => 'name',
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
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'index' => 'price',
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
        return $this->getUrl('*/*/nauveautesgrid', ['_current' => true]);
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
            $selected = [];
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