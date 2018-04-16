<?php
namespace Braem\Members\Model;

/**
 * @method Member setName($name)
 * @method Member setImage($image)
 * @method Member setPhone($phone)
 * @method Member setEmail($email)
 * @method Member setStore($store)
 * @method Member setLanguages($languages)
 * @method mixed getName()
 * @method mixed getImage()
 * @method mixed getPhone()
 * @method mixed getEmail()
 * @method mixed getStore()
 * @method mixed getLanguages()
 * @method Member setCreatedAt(\string $createdAt)
 * @method string getCreatedAt()
 * @method Member setUpdatedAt(\string $updatedAt)
 * @method string getUpdatedAt()
 * @method Member setRegionsData(array $data)
 * @method Member setDepartmentsData(array $data)
 * @method Member setTeamsData(array $data)
 * @method array getRegionsData()
 * @method array getDepartmentsData()
 * @method array getTeamsData()
 * @method Member setIsChangedRegionList(\bool $flag)
 * @method Member setIsChangedDepartmentList(\bool $flag)
 * @method Member setIsChangedTeamList(\bool $flag)
 * @method bool getIsChangedRegionList()
 * @method bool getIsChangedDepartmentList()
 * @method bool getIsChangedTeamList()
 * @method Member setAffectedRegionIds(array $ids)
 * @method Member setAffectedDepartmentIds(array $ids)
 * @method Member setAffectedTeamIds(array $ids)
 * @method bool getAffectedRegionIds()
 * @method bool getAffectedDepartmentIds()
 * @method bool getAffectedTeamIds()
 */
class Member extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     * 
     * @var string
     */
    const CACHE_TAG = 'braem_members_member';

    /**
     * Cache tag
     * 
     * @var string
     */
    protected $_cacheTag = 'braem_members_member';

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'braem_members_member';

    /**
     * Region Collection
     * 
     * @var \Braem\Members\Model\ResourceModel\Region\Collection
     */
    protected $_regionCollection;

    /**
     * Department Collection
     * 
     * @var \Braem\Members\Model\ResourceModel\Department\Collection
     */
    protected $_departmentCollection;

    /**
     * Team Collection
     * 
     * @var \Braem\Members\Model\ResourceModel\Team\Collection
     */
    protected $_teamCollection;

    /**
     * Region Collection Factory
     * 
     * @var \Braem\Members\Model\ResourceModel\Region\CollectionFactory
     */
    protected $_regionCollectionFactory;

    /**
     * Department Collection Factory
     * 
     * @var \Braem\Members\Model\ResourceModel\Department\CollectionFactory
     */
    protected $_departmentCollectionFactory;

    /**
     * Team Collection Factory
     * 
     * @var \Braem\Members\Model\ResourceModel\Team\CollectionFactory
     */
    protected $_teamCollectionFactory;

    /**
     * constructor
     * 
     * @param \Braem\Members\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory
     * @param \Braem\Members\Model\ResourceModel\Department\CollectionFactory $departmentCollectionFactory
     * @param \Braem\Members\Model\ResourceModel\Team\CollectionFactory $teamCollectionFactory
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Braem\Members\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        \Braem\Members\Model\ResourceModel\Department\CollectionFactory $departmentCollectionFactory,
        \Braem\Members\Model\ResourceModel\Team\CollectionFactory $teamCollectionFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->_regionCollectionFactory     = $regionCollectionFactory;
        $this->_departmentCollectionFactory = $departmentCollectionFactory;
        $this->_teamCollectionFactory       = $teamCollectionFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Braem\Members\Model\ResourceModel\Member');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
    /**
     * @return array|mixed
     */
    public function getRegionsPosition()
    {
        if (!$this->getId()) {
            return array();
        }
        $array = $this->getData('regions_position');
        if (is_null($array)) {
            $array = $this->getResource()->getRegionsPosition($this);
            $this->setData('regions_position', $array);
        }
        return $array;
    }

    /**
     * @return \Braem\Members\Model\ResourceModel\Region\Collection
     */
    public function getSelectedRegionsCollection()
    {
        if (is_null($this->_regionCollection)) {
            $collection = $this->_regionCollectionFactory->create();
            $collection->getSelect()->join(
                'braem_members_member_region',
                'e.entity_id=braem_members_member_region.region_id AND braem_members_member_region.member_id='.$this->getId(),
                ['position']
            );

            $collection->getSelect()->assemble();
            $collection->getSelect()->__toString();     

            file_put_contents('./log_getSelectedRegionsCollection_'.date("j.n.Y").'.txt', $collection->getSelect(), FILE_APPEND);

            
            $this->_regionCollection = $collection;
        }


        return $this->_regionCollection;
    }
    /**
     * @return array|mixed
     */
    public function getDepartmentsPosition()
    {
        if (!$this->getId()) {
            return array();
        }
        $array = $this->getData('departments_position');
        if (is_null($array)) {
            $array = $this->getResource()->getDepartmentsPosition($this);
            $this->setData('departments_position', $array);
        }
        return $array;
    }

    /**
     * @return \Braem\Members\Model\ResourceModel\Department\Collection
     */
    public function getSelectedDepartmentsCollection()
    {
        if (is_null($this->_departmentCollection)) {
            $collection = $this->_departmentCollectionFactory->create();
            $collection->getSelect()->join(
                'braem_members_member_department',
                'e.entity_id=braem_members_member_department.department_id AND braem_members_member_department.member_id='.$this->getId(),
                ['position']
            );

            $collection->getSelect()->assemble();
            $collection->getSelect()->__toString();     

            file_put_contents('./log_getSelectedDepartmentsCollection_'.date("j.n.Y").'.txt', $collection->getSelect(), FILE_APPEND);


            $this->_departmentCollection = $collection;
        }
        return $this->_departmentCollection;
    }
    /**
     * @return array|mixed
     */
    public function getTeamsPosition()
    {
        if (!$this->getId()) {
            return array();
        }
        $array = $this->getData('teams_position');
        if (is_null($array)) {
            $array = $this->getResource()->getTeamsPosition($this);
            $this->setData('teams_position', $array);
        }
        return $array;
    }

    /**
     * @return \Braem\Members\Model\ResourceModel\Team\Collection
     */
    public function getSelectedTeamsCollection()
    {
        if (is_null($this->_teamCollection)) {
            $collection = $this->_teamCollectionFactory->create();
            $collection->join(
                'braem_members_member_team',
                'main_table.team_id=braem_members_member_team.team_id AND braem_members_member_team.member_id='.$this->getId(),
                ['position']
            );
            $this->_teamCollection = $collection;
        }
        return $this->_teamCollection;
    }
}
