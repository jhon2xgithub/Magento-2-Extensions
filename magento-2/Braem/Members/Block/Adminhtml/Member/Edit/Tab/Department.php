<?php
namespace Braem\Members\Block\Adminhtml\Member\Edit\Tab;

class Department extends \Magento\Backend\Block\Widget\Grid\Extended implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Department collection factory
     *
     * @var \Braem\Members\Model\ResourceModel\Department\CollectionFactory
     */
    protected $_departmentCollectionFactory;

    /**
     * Registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Department factory
     *
     * @var \Braem\Members\Model\DepartmentFactory
     */
    protected $_departmentFactory;

    /**
     * constructor
     *
     * @param \Braem\Members\Model\ResourceModel\Department\CollectionFactory $departmentCollectionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Braem\Members\Model\DepartmentFactory $departmentFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param array $data
     */
    public function __construct(
        \Braem\Members\Model\ResourceModel\Department\CollectionFactory $departmentCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Braem\Members\Model\DepartmentFactory $departmentFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        array $data = []
    )
    {
        $this->_departmentCollectionFactory = $departmentCollectionFactory;
        $this->_coreRegistry          = $coreRegistry;
        $this->_departmentFactory           = $departmentFactory;
        parent::__construct($context, $backendHelper, $data);
    }


    /**
     * Set grid params
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('department_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getMember()->getId()) {
            $this->setDefaultFilter(['in_departments'=>1]);
        }
    }

    /**
     * prepare the collection

     * @return $this
     */
    protected function _prepareCollection()
    {
        /** @var \Braem\Members\Model\ResourceModel\Department\Collection $collection */
        $collection = $this->_departmentCollectionFactory->create()->addAttributeToSelect(['name']);
        if ($this->getMember()->getId()) {
            $constraint = 'related.member_id='.$this->getMember()->getId();
        } else {
            $constraint = 'related.member_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('braem_members_member_department')),
            'related.department_id=e.entity_id AND '.$constraint,
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
            'in_departments',
            [
                'header_css_class'  => 'a-center',
                'type'   => 'checkbox',
                'name'   => 'in_department',
                'values' => $this->_getSelectedDepartments(),
                'align'  => 'center',
                'index'  => 'entity_id'
            ]
        );
        $this->addColumn(
            'department_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'entity_id',
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
     * Retrieve selected Departments

     * @return array
     */
    protected function _getSelectedDepartments()
    {
        $departments = $this->getMemberDepartments();
        if (!is_array($departments)) {
            $departments = $this->getMember()->getDepartmentsPosition();
            return array_keys($departments);
        }
        return $departments;
    }

    /**
     * Retrieve selected Departments

     * @return array
     */
    public function getSelectedDepartments()
    {
        $selected = $this->getMember()->getDepartmentsPosition();
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
     * @param \Braem\Members\Model\Department|\Magento\Framework\Object $item
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
            '*/*/departmentsGrid',
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
//
//    /**
//     * @param \Magento\Backend\Block\Widget\Grid\Column $column
//     * @return $this
//     */
//    protected function _addColumnFilterToCollection($column)
//    {
//        if ($column->getId() == 'in_departments') {
//            $departmentIds = $this->_getSelectedDepartments();
//            if (empty($departmentIds)) {
//                $departmentIds = 0;
//            }
//            if ($column->getFilter()->getValue()) {
//
//                $this->getCollection()->addFilter('e.entity_id',['in'=>$departmentIds]);
//
//            } else {
//                if ($departmentIds) {
//                    $this->getCollection()->addFilter('e.entity_id', ['nin'=>$departmentIds]);
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
        return __('Departments');
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
        return $this->getUrl('braem_members/member/departments', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax only';
    }
}
