<?php
namespace Braem\Members\Block\Adminhtml\Member\Edit\Tab;

class Team extends \Magento\Backend\Block\Widget\Grid\Extended implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Team collection factory
     * 
     * @var \Braem\Members\Model\ResourceModel\Team\CollectionFactory
     */
    protected $_teamCollectionFactory;

    /**
     * Registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Team factory
     * 
     * @var \Braem\Members\Model\TeamFactory
     */
    protected $_teamFactory;

    /**
     * constructor
     * 
     * @param \Braem\Members\Model\ResourceModel\Team\CollectionFactory $teamCollectionFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Braem\Members\Model\TeamFactory $teamFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param array $data
     */
    public function __construct(
        \Braem\Members\Model\ResourceModel\Team\CollectionFactory $teamCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Braem\Members\Model\TeamFactory $teamFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        array $data = []
    )
    {
        $this->_teamCollectionFactory = $teamCollectionFactory;
        $this->_coreRegistry          = $coreRegistry;
        $this->_teamFactory           = $teamFactory;
        parent::__construct($context, $backendHelper, $data);
    }


    /**
     * Set grid params
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('team_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getMember()->getId()) {
            $this->setDefaultFilter(['in_teams'=>1]);
        }
    }

    /**
     * prepare the collection

     * @return $this
     */
    protected function _prepareCollection()
    {

        /** @var \Braem\Members\Model\ResourceModel\Team\Collection $collection */
        $collection = $this->_teamCollectionFactory->create()->addAttributeToSelect(['name']);
        if ($this->getMember()->getId()) {
            $constraint = 'related.member_id='.$this->getMember()->getId();
        } else {
            $constraint = 'related.member_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('braem_members_member_team')),
            'related.team_id=e.entity_id AND '.$constraint,
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
            'in_teams',
            [
                'header_css_class'  => 'a-center',
                'type'   => 'checkbox',
                'name'   => 'in_team',
                'values' => $this->_getSelectedTeams(),
                'align'  => 'center',
                'index'  => 'entity_id'
            ]
        );
        $this->addColumn(
            'team_id',
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
     * Retrieve selected Teams

     * @return array
     */
    protected function _getSelectedTeams()
    {
        $teams = $this->getMemberTeams();
        if (!is_array($teams)) {
            $teams = $this->getMember()->getTeamsPosition();
            return array_keys($teams);
        }
        return $teams;
    }

    /**
     * Retrieve selected Teams

     * @return array
     */
    public function getSelectedTeams()
    {
        $selected = $this->getMember()->getTeamsPosition();
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
     * @param \Braem\Members\Model\Team|\Magento\Framework\Object $item
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
            '*/*/teamsGrid',
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
//        if ($column->getId() == 'in_teams') {
//            $teamIds = $this->_getSelectedTeams();
//            if (empty($teamIds)) {
//                $teamIds = 0;
//            }
//            if ($column->getFilter()->getValue()) {
//
//                $this->getCollection()->addFilter('e.entity_id',['in'=>$teamIds]);
//
//            } else {
//                if ($teamIds) {
//                    $this->getCollection()->addFilter('e.entity_id', ['nin'=>$teamIds]);
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
        return __('Teams');
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
        return $this->getUrl('braem_members/member/teams', ['_current' => true]);
    }

    /**
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax only';
    }
}
