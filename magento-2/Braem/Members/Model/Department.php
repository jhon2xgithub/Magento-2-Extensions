<?php
namespace Braem\Members\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Catalog\Model\Product\Type;

/**
 * @method Department setName($name)
 * @method mixed getName()
 * @method Department setCreatedAt(\string $createdAt)
 * @method string getCreatedAt()
 * @method Department setUpdatedAt(\string $updatedAt)
 * @method string getUpdatedAt()
 * @method Department setMembersData(array $data)
 * @method array getMembersData()
 * @method Department setIsChangedMemberList(\bool $flag)
 * @method bool getIsChangedMemberList()
 * @method Department setAffectedMemberIds(array $ids)
 * @method bool getAffectedMemberIds()
 */
class Department extends \Magento\Catalog\Model\AbstractModel implements \Magento\Framework\Api\CustomAttributesDataInterface
{

    protected $_catalogProductType;

    protected $_typeInstance = null;

    protected $_resource;

    const ENTITY = 'members_department';

    /**
     * Product Store Id
     */
    const STORE_ID = 'store_id';


    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'braem_members_department';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'braem_members_department';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'braem_members_department';

    /**
     * Member Collection
     *
     * @var \Braem\Members\Model\ResourceModel\Member\Collection
     */
    protected $_memberCollection;
    protected $_departmentCollection;

    /**
     * Member Collection Factory
     *
     * @var \Braem\Members\Model\ResourceModel\Member\CollectionFactory
     */
    protected $_memberCollectionFactory;

    /**
     * @var \Magento\Framework\Api\MetadataServiceInterface
     */
    protected $metadataService;

    /**
     * List of attributes in ProductInterface
     * @var array
     */
    protected $interfaceAttributes = [
        'name'
    ];

    /**
     * constructor
     *
     * @param \Braem\Members\Model\ResourceModel\Member\CollectionFactory $memberCollectionFactory
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Braem\Members\Model\ResourceModel\Member\CollectionFactory $memberCollectionFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Catalog\Api\CategoryAttributeRepositoryInterface $metadataService,
        \Magento\Framework\Registry $registry,
        \Braem\Members\Model\ResourceModel\Department $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
    
        array $data = []
    )
    {
        $this->_memberCollectionFactory = $memberCollectionFactory;
        $this->metadataService = $metadataService;
      
       
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $storeManager,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Retrieve product attributes
     * if $groupId is null - retrieve all product attributes
     *
     * @param int  $groupId   Retrieve attributes of the specified group
     * @param bool $skipSuper Not used
     * @return \Magento\Eav\Model\Entity\Attribute\AbstractAttribute[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getAttributes()
    {
        $productAttributes = $this->getTypeInstance()->getSetAttributes();

        return $productAttributes;
    }

    public function getSetAttributes()
    {
        $setAttributes = $this->getResource()
            ->loadAllAttributes($this)
        ;

        return $setAttributes;
    }


    public function getTypeInstance()
    {
        if ($this->_typeInstance === null) {
            $this->_typeInstance = $this;
        }
        return $this->_typeInstance;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Braem\Members\Model\ResourceModel\Department');
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
    public function getMembersPosition()
    {
        if (!$this->getId()) {
            return array();
        }
        $array = $this->getData('members_position');
        if (is_null($array)) {
            $array = $this->getResource()->getMembersPosition($this);
            $this->setData('members_position', $array);
        }
        return $array;
    }

    /**
     * @return \Braem\Members\Model\ResourceModel\Member\Collection
     */
    public function getSelectedMembersCollection()
    {
        if (is_null($this->_memberCollection)) {
            $collection = $this->_memberCollectionFactory->create();
            $collection->join(
                'braem_members_member_department',
                'main_table.member_id=braem_members_member_department.member_id AND braem_members_member_department.department_id='.$this->getId(),
                ['position']
            );
            $this->_memberCollection = $collection;
        }
        return $this->_memberCollection;
    }

    /**
     * Get an attribute value.
     *
     * @param string $attributeCode
     * @return \Magento\Framework\Api\AttributeInterface|null
     */
    public function getCustomAttribute($attributeCode){

    }

    /**
     * @return \Braem\Members\Model\ResourceModel\Member\Collection
     */
    public function getSelectedDepartmentsCollection($memberIds)
    {

        $collection = $this->getCollection()->addFieldToSelect('entity_id');
        $collection->getSelect()->distinct()->joinLeft(
            'braem_members_member_department',
            'e.entity_id=braem_members_member_department.department_id AND braem_members_member_department.member_id IN (\''. implode('\',\'',$memberIds) . '\')',
            ['position']
        );        

        // $collection->getSelect()->assemble();
        // $collection->getSelect()->__toString();     

        // file_put_contents('./log_getSelectedDepartmentsCollection_'.date("j.n.Y").'.txt', $collection->getSelect(), FILE_APPEND);
       

        return $collection;

    }

    public function getDeparmentName($departmentIds){
        
        // $connection  = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        // $collection = $connection->fetchAll("SELECT `value` FROM `braem_members_department_varchar` WHERE `entity_id` = $memberIds");
        
        // $connection = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        // $collection = $connection->getTableName('braem_members_department_varchar');
        // $query = "SELECT `value` FROM `braem_members_department_varchar` WHERE `entity_id` = $memberIds";
        // $connection->query($query);
        //  $collection = $this->getCollection()->addFieldToSelect('value')->addFieldToFilter('entity_id', ['eq'=>$memberIds]);

        // $collection->getSelect()->assemble();
        // $collection->getSelect()->__toString();     

        // file_put_contents('./log_getDeparmentName_'.date("j.n.Y").'.txt', $collection->getSelect(), FILE_APPEND);
        // return $collection;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
     
        //Select Data from table
        $sql = "SELECT DISTINCT `value`AS `department_name` FROM `braem_members_department_varchar` WHERE `entity_id` = $departmentIds";
        $collection = $connection->fetchRow($sql); 
        return $collection;
    }


    /**
     * Retrieve Store Id
     *
     * @return int
     */
    public function getStoreId()
    {
        if ($this->hasData(self::STORE_ID)) {
            return $this->getData(self::STORE_ID);
        }
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * Get collection instance
     *
     * @return object
     */
    public function getResourceCollection()
    {
        $collection = parent::getResourceCollection();
        $collection->setStoreId($this->getStoreId());
        return $collection;
    }


}
