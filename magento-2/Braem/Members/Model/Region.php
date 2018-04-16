<?php
namespace Braem\Members\Model;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\Catalog\Model\Product\Type;

/**
 * @method Region setName($name)
 * @method mixed getName()
 * @method Region setCreatedAt(\string $createdAt)
 * @method string getCreatedAt()
 * @method Region setUpdatedAt(\string $updatedAt)
 * @method string getUpdatedAt()
 * @method Region setMembersData(array $data)
 * @method array getMembersData()
 * @method Region setIsChangedMemberList(\bool $flag)
 * @method bool getIsChangedMemberList()
 * @method Region setAffectedMemberIds(array $ids)
 * @method bool getAffectedMemberIds()
 */
class Region extends \Magento\Catalog\Model\AbstractModel implements \Magento\Framework\Api\CustomAttributesDataInterface
{

    protected $_catalogProductType;

    protected $_typeInstance = null;

    const ENTITY = 'members_region';

    const STORE_ID = 'store_id';


    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'braem_members_region';

    /**
     * Cache tag
     *
     * @var string
     */
    protected $_cacheTag = 'braem_members_region';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'braem_members_region';

    /**
     * Member Collection
     *
     * @var \Braem\Members\Model\ResourceModel\Member\Collection
     */
    protected $_memberCollection;

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
        \Braem\Members\Model\ResourceModel\Region $resource = null,
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
        $this->_init('Braem\Members\Model\ResourceModel\Region');
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
                'braem_members_member_region',
                'main_table.member_id=braem_members_member_region.member_id AND braem_members_member_region.region_id='.$this->getId(),
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
    public function getSelectedRegionsCollection($memberIds)
    {
        $collection = $this->getCollection()->addFieldToSelect('entity_id');
        $collection->getSelect()->distinct()->joinLeft(
            'braem_members_member_region',
            'e.entity_id=braem_members_member_region.region_id AND braem_members_member_region.member_id IN (\''. implode('\',\'',$memberIds) . '\')',
            ['position']
        );  

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

    public function getRegionName($regionIds){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
       
        //Select Data from table
        $sql = "SELECT DISTINCT `value`AS `region_name` FROM `braem_members_region_varchar` WHERE `entity_id` = $regionIds";
        $collection = $connection->fetchRow($sql); 
        return $collection;
    }


}
