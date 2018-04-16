<?php
namespace Braem\Members\Block\Adminhtml\Member\Edit\Tab;

class Region extends \Magento\Backend\Block\Widget\Grid\Extended implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Region collection factory
     * 
     * @var \Braem\Members\Model\ResourceModel\Region\CollectionFactory
     */
    protected $_regionCollectionFactory;

    /**
     * Registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Region factory
     * 
     * @var \Braem\Members\Model\RegionFactory
     */
    protected $_regionFactory;

    /**
     * constructor
     * 
     * @param \Braem\Members\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Braem\Members\Model\RegionFactory $regionFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param array $data
     */
    public function __construct(
        \Braem\Members\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Braem\Members\Model\RegionFactory $regionFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        array $data = []
    )
    {
        $this->_regionCollectionFactory = $regionCollectionFactory;
        $this->_coreRegistry            = $coreRegistry;
        $this->_regionFactory           = $regionFactory;
        parent::__construct($context, $backendHelper, $data);
    }


    /**
     * Set grid params
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('region_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getMember()->getId()) {
            $this->setDefaultFilter(['in_regions'=>1]);
        }
    }

    /**
     * prepare the collection

     * @return $this
     */
    protected function _prepareCollection()
    {
        /** @var \Braem\Members\Model\ResourceModel\Region\Collection $collection */
        $collection = $this->_regionCollectionFactory->create()->addAttributeToSelect(['name','entity_id']);
        if ($this->getMember()->getId()) {
            $constraint = 'related.member_id='.$this->getMember()->getId();
        } else {
            $constraint = 'related.member_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('braem_members_member_region')),
            'related.region_id=e.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_regions',
            [
                'header_css_class'  => 'a-center',
                'type'   => 'checkbox',
                'name'   => 'in_region',
                'values' => $this->_getSelectedRegions(),
                'align'  => 'center',
                'index'  => 'entity_id'
            ]
        );
//        $this->addColumn(
//            'region_id',
//            [
//                'header' => __('ID'),
//                'sortable' => true,
//                'index' => 'entity_id',
//                'type' => 'number',
//                'header_css_class' => 'col-id',
//                'column_css_class' => 'col-id'
//            ]
//        );

        $this->addColumn(
            'title',
            [
                'header' => __('Name'),
                'index' => 'name',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );

        $this->addColumn(
            'position',
            [
                'header' => __('Position'),
                'name'   => 'position',
                'width'  => 60,
                'type'   => 'number',
                'validate_class' => 'validate-number',
                'index' => 'position',
                'editable'  => true,
            ]
        );
        return $this;
    }

    /**
     * Retrieve selected Regions

     * @return array
     */
    protected function _getSelectedRegions()
    {
        $regions = $this->getMemberRegions();
        if (!is_array($regions)) {
            $regions = $this->getMember()->getRegionsPosition();
            return array_keys($regions);
        }
        return $regions;
    }

    /**
     * Retrieve selected Regions

     * @return array
     */
    public function getSelectedRegions()
    {
        $selected = $this->getMember()->getRegionsPosition();
        if (!is_array($selected)) {
            $selected = [];
        } else {
            foreach ($selected as $key => $value) {
                $selected[$key] = ['position' => $value];
            }
        }
        return $selected;
    }

    /**
     * @param \Braem\Members\Model\Region|\Magento\Framework\Object $item
     * @return string
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/regionsGrid',
            [
                'member_id' => $this->getMember()->getId()
            ]
        );
    }

    /**
     * @return \Braem\Members\Model\Member
     */
    public function getMember()
    {
        return $this->_coreRegistry->registry('braem_members_member');
    }

//    /**
//     * @param \Magento\Backend\Block\Widget\Grid\Column $column
//     * @return $this
//     */
//    protected function _addColumnFilterToCollection($column)
//    {
//        if ($column->getId() == 'in_regions') {
//            $regionIds = $this->_getSelectedRegions();
//            if (empty($regionIds)) {
//                $regionIds = 0;
//            }
//            if ($column->getFilter()->getValue()) {
//                $this->getCollection()->addFieldToFilter('main_table.region_id', ['in'=>$regionIds]);
//            } else {
//                if ($regionIds) {
//                    $this->getCollection()->addFieldToFilter('main_table.region_id', ['nin'=>$regionIds]);
//                }
//            }
//        } else {
//            parent::_addColumnFilterToCollection($column);
//        }
//        return $this;
//    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return __('Regions');
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('braem_members/member/regions', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax only';
    }
}
