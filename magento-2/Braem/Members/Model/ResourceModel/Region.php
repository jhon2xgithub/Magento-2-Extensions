<?php
namespace Braem\Members\Model\ResourceModel;

use Magento\Catalog\Model\ResourceModel\AbstractResource;

class Region extends AbstractResource
{
    /**
     * Date model
     *
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Member relation model
     *
     * @var string
     */
    protected $_regionMemberTable;

    /**
     * Event Manager
     *
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * Store id
     *
     * @var int
     */
    protected $_storeId = null;

    /**
     * Retrieve default entity attributes
     *
     * @return string[]
     */
    protected function _getDefaultAttributes()
    {
        return ['entity_id', 'created_at', 'updated_at', 'name'];
    }


    /**
     * constructor
     *
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Factory $modelFactory,
        \Magento\Eav\Model\Entity\Context $context,
        $data = []
    )
    {
        $this->_date         = $date;
        $this->_eventManager = $eventManager;
        parent::__construct(
            $context,
            $storeManager,
            $modelFactory,
            $data
        );
        $this->_regionMemberTable = $this->getTable('braem_members_member_region');
    }


    /**
     * Entity type getter and lazy loader
     *
     * @return \Magento\Eav\Model\Entity\Type
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Braem\Members\Model\Region::ENTITY);
        }
        return parent::getEntityType();
    }

    /**
     * wrapper for main table getter
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getMainTable()
    {
        return $this->getEntityTable();
    }

    /**
     * Retrieves Region Name from DB by passed id.
     *
     * @param string $id
     * @return string|bool
     */
    public function getRegionNameById($id)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'name')
            ->where('entity_id = :entity_id');
        $binds = ['entity_id' => (int)$id];
        return $adapter->fetchOne($select, $binds);
    }
    /**
     * before save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\Braem\Members\Model\Region $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\DataObject $object)
    {
        $object->setUpdatedAt($this->_date->date());
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->_date->date());
        }
        return parent::_beforeSave($object);
    }
    /**
     * after save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\Braem\Members\Model\Region $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\DataObject $object)
    {
        $this->_saveMemberRelation($object);
        return parent::_afterSave($object);
    }

    /**
     * @param \Braem\Members\Model\Region $region
     * @return array
     */
    public function getMembersPosition(\Braem\Members\Model\Region $region)
    {
        $select = $this->getConnection()->select()->from(
            $this->_regionMemberTable,
            ['member_id', 'position']
        )
            ->where(
                'region_id = :region_id'
            );
        $bind = ['region_id' => (int)$region->getId()];
        return $this->getConnection()->fetchPairs($select, $bind);
    }

    /**
     * @param \Braem\Members\Model\Region $region
     * @return $this
     */
    protected function _saveMemberRelation(\Braem\Members\Model\Region $region)
    {
        $region->setIsChangedMemberList(false);
        $id = $region->getId();
        $members = $region->getMembersData();
        if ($members === null) {
            return $this;
        }
        $oldMembers = $region->getMembersPosition();
        $insert = array_diff_key($members, $oldMembers);
        $delete = array_diff_key($oldMembers, $members);
        $update = array_intersect_key($members, $oldMembers);
        $_update = array();
        foreach ($update as $key=>$settings) {
            if (isset($oldMembers[$key]) && $oldMembers[$key] != $settings['position']) {
                $_update[$key] = $settings;
            }
        }
        $update = $_update;
        $adapter = $this->getConnection();
        if (!empty($delete)) {
            $condition = ['member_id IN(?)' => array_keys($delete), 'region_id=?' => $id];
            $adapter->delete($this->_regionMemberTable, $condition);
        }
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $memberId => $position) {
                $data[] = [
                    'region_id' => (int)$id,
                    'member_id' => (int)$memberId,
                    'position' => (int)$position['position']
                ];
            }
            $adapter->insertMultiple($this->_regionMemberTable, $data);
        }
        if (!empty($update)) {
            foreach ($update as $memberId => $position) {
                $where = ['region_id = ?' => (int)$id, 'member_id = ?' => (int)$memberId];
                $bind = ['position' => (int)$position['position']];
                $adapter->update($this->_regionMemberTable, $bind, $where);
            }
        }
        if (!empty($insert) || !empty($delete)) {
            $memberIds = array_unique(array_merge(array_keys($insert), array_keys($delete)));
            $this->_eventManager->dispatch(
                'braem_members_region_change_members',
                ['region' => $region, 'member_ids' => $memberIds]
            );
        }
        if (!empty($insert) || !empty($update) || !empty($delete)) {
            $region->setIsChangedMemberList(true);
            $memberIds = array_keys($insert + $delete + $update);
            $region->setAffectedMemberIds($memberIds);
        }
        return $this;
    }

    /**
     * Set store Id
     *
     * @param integer $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * Return store id
     *
     * @return integer
     */
    public function getStoreId()
    {
        if ($this->_storeId === null) {
            return $this->_storeManager->getStore()->getId();
        }
        return $this->_storeId;
    }

    /**
     * Get default attribute source model
     *
     * @return string
     */
    public function getDefaultAttributeSourceModel()
    {
        return 'Magento\Eav\Model\Entity\Attribute\Source\Table';
    }
}
