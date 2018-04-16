<?php
namespace Braem\Members\Block\Adminhtml\Department\Edit\Tab;

class Member extends \Magento\Backend\Block\Widget\Grid\Extended implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Member collection factory
     *
     * @var \Braem\Members\Model\ResourceModel\Member\CollectionFactory
     */
    protected $_memberCollectionFactory;

    /**
     * Registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Member factory
     *
     * @var \Braem\Members\Model\MemberFactory
     */
    protected $_memberFactory;

    /**
     * constructor
     *
     * @param \Braem\Members\Model\ResourceModel\Member\CollectionFactory $memberCollectionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Braem\Members\Model\MemberFactory $memberFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param array $data
     */
    public function __construct(
        \Braem\Members\Model\ResourceModel\Member\CollectionFactory $memberCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Braem\Members\Model\MemberFactory $memberFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        array $data = []
    )
    {
        $this->_memberCollectionFactory = $memberCollectionFactory;
        $this->_coreRegistry            = $coreRegistry;
        $this->_memberFactory           = $memberFactory;
        parent::__construct($context, $backendHelper, $data);
    }


    /**
     * Set grid params
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('member_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getDepartment()->getId()) {
            $this->setDefaultFilter(['in_members'=>1]);
        }
    }

    /**
     * prepare the collection

     * @return $this
     */
    protected function _prepareCollection()
    {
        /** @var \Braem\Members\Model\ResourceModel\Member\Collection $collection */
        $collection = $this->_memberCollectionFactory->create();
        if ($this->getDepartment()->getId()) {
            $constraint = 'related.department_id='.$this->getDepartment()->getId();
        } else {
            $constraint = 'related.department_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('braem_members_member_department')),
            'related.member_id=main_table.member_id AND '.$constraint,
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
            'in_members',
            [
                'header_css_class'  => 'a-center',
                'type'   => 'checkbox',
                'name'   => 'in_member',
                'values' => $this->_getSelectedMembers(),
                'align'  => 'center',
                'index'  => 'member_id'
            ]
        );
        $this->addColumn(
            'member_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'member_id',
                'type' => 'number',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

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
     * Retrieve selected Members

     * @return array
     */
    protected function _getSelectedMembers()
    {
        $members = $this->getDepartmentMembers();
        if (!is_array($members)) {
            $members = $this->getDepartment()->getMembersPosition();
            return array_keys($members);
        }

        return $members;
    }

    /**
     * Retrieve selected Members

     * @return array
     */
    public function getSelectedMembers()
    {
        $selected = $this->getDepartment()->getMembersPosition();
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
     * @param \Braem\Members\Model\Member|\Magento\Framework\Object $item
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
            '*/*/membersGrid',
            [
                'department_id' => $this->getDepartment()->getId()
            ]
        );
    }

    /**
     * @return \Braem\Members\Model\Department
     */
    public function getDepartment()
    {
        return $this->_coreRegistry->registry('braem_members_department');
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_members') {
            $memberIds = $this->_getSelectedMembers();
            if (empty($memberIds)) {
                $memberIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('main_table.member_id', ['in'=>$memberIds]);
            } else {
                if ($memberIds) {
                    $this->getCollection()->addFieldToFilter('main_table.member_id', ['nin'=>$memberIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return __('Members');
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
        return $this->getUrl('braem_members/department/members', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax only';
    }
}
