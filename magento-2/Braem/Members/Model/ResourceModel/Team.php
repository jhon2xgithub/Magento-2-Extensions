<?php
namespace Braem\Members\Model\ResourceModel;

use Magento\Catalog\Model\ResourceModel\AbstractResource;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;

class Team extends AbstractResource
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
    protected $_teamMemberTable;

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
        $this->_teamMemberTable = $this->getTable('braem_members_member_team');
    }


//    /**
//     * Initialize resource model
//     *
//     * @return void
//     */
//    protected function _construct()
//    {
//        $this->_init('braem_members_team', 'team_id');
//    }

    /**
     * Entity type getter and lazy loader
     *
     * @return \Magento\Eav\Model\Entity\Type
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Braem\Members\Model\Team::ENTITY);
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
     * Retrieves Team Name from DB by passed id.
     *
     * @param string $id
     * @return string|bool
     */
    public function getTeamNameById($id)
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
     * @param \Magento\Framework\Model\AbstractModel|\Braem\Members\Model\Team $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\DataObject $object)
    {
        $object->setUpdatedAt($this->_date->date());
        if ($object->isObjectNew()) {
            $object->setCreatedAt($this->_date->date());
        }

        $urlKey = $object->getData('url_key');
        if ($urlKey == '') {
            $urlKey = $object->getName();
        }
        $urlKey = $object->formatUrlKey($urlKey);
        $object->setUrlKey($urlKey);
        $validKey = false;
        while (!$validKey) {
            if ($this->getIsUniqueTeamToStores($object)) {
                $validKey = true;
            } else {
                $parts = explode('-', $urlKey);
                $last = $parts[count($parts) - 1];
                if (!is_numeric($last)) {
                    $urlKey = $urlKey.'-1';
                } else {
                    $suffix = '-'.($last + 1);
                    unset($parts[count($parts) - 1]);
                    $urlKey = implode('-', $parts).$suffix;
                }
                $object->setData('url_key', $urlKey);
            }
        }

        return parent::_beforeSave($object);
    }

    public function getIsUniqueTeamToStores(AbstractModel $object)
    {
        if ($this->_storeManager->hasSingleStore() || !$object->hasStores()) {
            $stores = [Store::DEFAULT_STORE_ID];
        } else {
            $stores = (array)$object->getData('stores');
        }
        $select = $this->getLoadByUrlKeySelect($object->getData('url_key'), $stores);
        if ($object->getId()) {
            $select->where('braem_members_team_varchar.entity_id <> ?', $object->getId());
        }
        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }
        return true;
    }

    protected function getLoadByUrlKeySelect($urlKey, $store, $isActive = null)
    {
        $select = $this->getConnection()
            ->select()
            ->from(['eav_attribute' => 'eav_attribute'])
            ->join(
                ['braem_members_team_varchar' => $this->getTable('braem_members_team_varchar')],
                'eav_attribute.attribute_id = braem_members_team_varchar.attribute_id',
                []
            )
            ->join(
                ['eav_entity_type' => $this->getTable('eav_entity_type')],
                'eav_entity_type.entity_type_code = \'members_team\' AND eav_entity_type.entity_type_id = eav_attribute.entity_type_id',
                []
            )
            ->where(
                'braem_members_team_varchar.value = ?',
                $urlKey
            )
            ->where(
                'braem_members_team_varchar.store_id IN (?)',
                $store
            );

        /*if (!is_null($isActive)) {
            $select->where('author.is_active = ?', $isActive);
        }*/
        return $select;
    }

    /**
     * after save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\Braem\Members\Model\Team $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\DataObject $object)
    {
        $this->_saveMemberRelation($object);
        return parent::_afterSave($object);
    }

    /**
     * @param \Braem\Members\Model\Team $team
     * @return array
     */
    public function getMembersPosition(\Braem\Members\Model\Team $team)
    {
        $select = $this->getConnection()->select()->from(
            $this->_teamMemberTable,
            ['member_id', 'position']
        )
        ->where(
            'team_id = :team_id'
        );
        $bind = ['team_id' => (int)$team->getId()];
        return $this->getConnection()->fetchPairs($select, $bind);
    }

    /**
     * @param \Braem\Members\Model\Team $team
     * @return $this
     */
    protected function _saveMemberRelation(\Braem\Members\Model\Team $team)
    {
        $team->setIsChangedMemberList(false);
        $id = $team->getId();
        $members = $team->getMembersData();
        if ($members === null) {
            return $this;
        }
        $oldMembers = $team->getMembersPosition();
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
            $condition = ['member_id IN(?)' => array_keys($delete), 'team_id=?' => $id];
            $adapter->delete($this->_teamMemberTable, $condition);
        }
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $memberId => $position) {
                $data[] = [
                    'team_id' => (int)$id,
                    'member_id' => (int)$memberId,
                    'position' => (int)$position['position']
                ];

            }
            $adapter->insertMultiple($this->_teamMemberTable, $data);
        }
        if (!empty($update)) {
            foreach ($update as $memberId => $position) {
                $where = ['team_id = ?' => (int)$id, 'member_id = ?' => (int)$memberId];
                $bind = ['position' => (int)$position['position']];
                $adapter->update($this->_teamMemberTable, $bind, $where);
            }
        }
        if (!empty($insert) || !empty($delete)) {
            $memberIds = array_unique(array_merge(array_keys($insert), array_keys($delete)));
            $this->_eventManager->dispatch(
                'braem_members_team_change_members',
                ['team' => $team, 'member_ids' => $memberIds]
            );
        }
        if (!empty($insert) || !empty($update) || !empty($delete)) {
            $team->setIsChangedMemberList(true);
            $memberIds = array_keys($insert + $delete + $update);
            $team->setAffectedMemberIds($memberIds);
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

    public function checkUrlKey($urlKey, $storeId)
    {
        $stores = [Store::DEFAULT_STORE_ID, $storeId];
        $select = $this->getLoadByUrlKeySelect($urlKey, $stores, 1);
        $select->reset(\Zend_Db_Select::COLUMNS)
            ->columns('braem_members_team_varchar.entity_id')
            ->order('braem_members_team_varchar.store_id DESC')
            ->limit(1);
        return $this->getConnection()->fetchOne($select);
    }



}
