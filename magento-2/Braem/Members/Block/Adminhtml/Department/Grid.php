<?php
namespace Braem\Members\Block\Adminhtml\Department;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    protected $_departmentFactory;
    protected $moduleManager;



    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory,
        \Braem\Members\Model\Department $departmentFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    )
    {
        $this->_setsFactory = $setsFactory;
        $this->_departmentFactory = $departmentFactory;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    }
    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {

        parent::_prepareCollection();

        $store = $this->_getStore();
        $collection = $this->_departmentFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        );

        if ($store->getId()) {
            //$collection->setStoreId($store->getId());
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                'name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                Store::DEFAULT_STORE_ID
            );
            $collection->joinAttribute(
                'custom_name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'status',
                'catalog_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
        }

        $this->setCollection($collection);

        return $this;
    }

}