<?php
namespace Braem\Members\Model\ResourceModel;

class Member extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Date model
     * 
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Region relation model
     * 
     * @var string
     */
    protected $_memberRegionTable;

    /**
     * Department relation model
     * 
     * @var string
     */
    protected $_memberDepartmentTable;

    /**
     * Team relation model
     * 
     * @var string
     */
    protected $_memberTeamTable;

    /**
     * Event Manager
     * 
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

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
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        $this->_date         = $date;
        $this->_eventManager = $eventManager;
        parent::__construct($context);
        $this->_memberRegionTable = $this->getTable('braem_members_member_region');
        $this->_memberDepartmentTable = $this->getTable('braem_members_member_department');
        $this->_memberTeamTable = $this->getTable('braem_members_member_team');
    }


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('braem_members_member', 'member_id');
    }

    /**
     * Retrieves Member Name from DB by passed id.
     *
     * @param string $id
     * @return string|bool
     */
    public function getMemberNameById($id)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->getMainTable(), 'name')
            ->where('member_id = :member_id');
        $binds = ['member_id' => (int)$id];
        return $adapter->fetchOne($select, $binds);
    }
    /**
     * before save callback
     *
     * @param \Magento\Framework\Model\AbstractModel|\Braem\Members\Model\Member $object
     * @return $this
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
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
     * @param \Magento\Framework\Model\AbstractModel|\Braem\Members\Model\Member $object
     * @return $this
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $this->_saveRegionRelation($object);
        $this->_saveDepartmentRelation($object);
        $this->_saveTeamRelation($object);
        return parent::_afterSave($object);
    }

    /**
     * @param \Braem\Members\Model\Member $member
     * @return array
     */
    public function getRegionsPosition(\Braem\Members\Model\Member $member)
    {
        $select = $this->getConnection()->select()->from(
            $this->_memberRegionTable,
            ['region_id', 'position']
        )
        ->where(
            'member_id = :member_id'
        );
        $bind = ['member_id' => (int)$member->getId()];
        return $this->getConnection()->fetchPairs($select, $bind);
    }

    /**
     * @param \Braem\Members\Model\Member $member
     * @return $this
     */
    protected function _saveRegionRelation(\Braem\Members\Model\Member $member)
    {
        $member->setIsChangedRegionList(false);
        $id = $member->getId();
        $regions = $member->getRegionsData();
        if ($regions === null) {
            return $this;
        }
        $oldRegions = $member->getRegionsPosition();
        $insert = array_diff_key($regions, $oldRegions);
        $delete = array_diff_key($oldRegions, $regions);
        $update = array_intersect_key($regions, $oldRegions);
        $_update = array();
        foreach ($update as $key=>$settings) {
            if (isset($oldRegions[$key]) && $oldRegions[$key] != $settings['position']) {
                $_update[$key] = $settings;
            }
        }
        $update = $_update;
        $adapter = $this->getConnection();
        if (!empty($delete)) {
            $condition = ['region_id IN(?)' => array_keys($delete), 'member_id=?' => $id];
            $adapter->delete($this->_memberRegionTable, $condition);
        }
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $regionId => $position) {
                $data[] = [
                    'member_id' => (int)$id,
                    'region_id' => (int)$regionId,
                    'position' => (int)$position['position']
                ];
            }
            $adapter->insertMultiple($this->_memberRegionTable, $data);
        }
        if (!empty($update)) {
            foreach ($update as $regionId => $position) {
                $where = ['member_id = ?' => (int)$id, 'region_id = ?' => (int)$regionId];
                $bind = ['position' => (int)$position['position']];
                $adapter->update($this->_memberRegionTable, $bind, $where);
            }
        }
        if (!empty($insert) || !empty($delete)) {
            $regionIds = array_unique(array_merge(array_keys($insert), array_keys($delete)));
            $this->_eventManager->dispatch(
                'braem_members_member_change_regions',
                ['member' => $member, 'region_ids' => $regionIds]
            );
        }
        if (!empty($insert) || !empty($update) || !empty($delete)) {
            $member->setIsChangedRegionList(true);
            $regionIds = array_keys($insert + $delete + $update);
            $member->setAffectedRegionIds($regionIds);
        }
        return $this;
    }

    /**
     * @param \Braem\Members\Model\Member $member
     * @return array
     */
    public function getDepartmentsPosition(\Braem\Members\Model\Member $member)
    {
        $select = $this->getConnection()->select()->from(
            $this->_memberDepartmentTable,
            ['department_id', 'position']
        )
        ->where(
            'member_id = :member_id'
        );
        $bind = ['member_id' => (int)$member->getId()];
        return $this->getConnection()->fetchPairs($select, $bind);
    }

    /**
     * @param \Braem\Members\Model\Member $member
     * @return $this
     */
    protected function _saveDepartmentRelation(\Braem\Members\Model\Member $member)
    {
        $member->setIsChangedDepartmentList(false);
        $id = $member->getId();
        $departments = $member->getDepartmentsData();
        if ($departments === null) {
            return $this;
        }
        $oldDepartments = $member->getDepartmentsPosition();
        $insert = array_diff_key($departments, $oldDepartments);
        $delete = array_diff_key($oldDepartments, $departments);
        $update = array_intersect_key($departments, $oldDepartments);
        $_update = array();
        foreach ($update as $key=>$settings) {
            if (isset($oldDepartments[$key]) && $oldDepartments[$key] != $settings['position']) {
                $_update[$key] = $settings;
            }
        }
        $update = $_update;
        $adapter = $this->getConnection();
        if (!empty($delete)) {
            $condition = ['department_id IN(?)' => array_keys($delete), 'member_id=?' => $id];
            $adapter->delete($this->_memberDepartmentTable, $condition);
        }
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $departmentId => $position) {
                $data[] = [
                    'member_id' => (int)$id,
                    'department_id' => (int)$departmentId,
                    'position' => (int)$position['position']
                ];
            }
            $adapter->insertMultiple($this->_memberDepartmentTable, $data);
        }
        if (!empty($update)) {
            foreach ($update as $departmentId => $position) {
                $where = ['member_id = ?' => (int)$id, 'department_id = ?' => (int)$departmentId];
                $bind = ['position' => (int)$position['position']];
                $adapter->update($this->_memberDepartmentTable, $bind, $where);
            }
        }
        if (!empty($insert) || !empty($delete)) {
            $departmentIds = array_unique(array_merge(array_keys($insert), array_keys($delete)));
            $this->_eventManager->dispatch(
                'braem_members_member_change_departments',
                ['member' => $member, 'department_ids' => $departmentIds]
            );
        }
        if (!empty($insert) || !empty($update) || !empty($delete)) {
            $member->setIsChangedDepartmentList(true);
            $departmentIds = array_keys($insert + $delete + $update);
            $member->setAffectedDepartmentIds($departmentIds);
        }
        return $this;
    }

    /**
     * @param \Braem\Members\Model\Member $member
     * @return array
     */
    public function getTeamsPosition(\Braem\Members\Model\Member $member)
    {
        $select = $this->getConnection()->select()->from(
            $this->_memberTeamTable,
            ['team_id', 'position']
        )
        ->where(
            'member_id = :member_id'
        );
        $bind = ['member_id' => (int)$member->getId()];
        return $this->getConnection()->fetchPairs($select, $bind);
    }

    /**
     * @param \Braem\Members\Model\Member $member
     * @return $this
     */
    protected function _saveTeamRelation(\Braem\Members\Model\Member $member)
    {
        $member->setIsChangedTeamList(false);
        $id = $member->getId();
        $teams = $member->getTeamsData();
        if ($teams === null) {
            return $this;
        }
        $oldTeams = $member->getTeamsPosition();
        $insert = array_diff_key($teams, $oldTeams);
        $delete = array_diff_key($oldTeams, $teams);
        $update = array_intersect_key($teams, $oldTeams);
        $_update = array();
        foreach ($update as $key=>$settings) {
            if (isset($oldTeams[$key]) && $oldTeams[$key] != $settings['position']) {
                $_update[$key] = $settings;
            }
        }
        $update = $_update;
        $adapter = $this->getConnection();
        if (!empty($delete)) {
            $condition = ['team_id IN(?)' => array_keys($delete), 'member_id=?' => $id];
            $adapter->delete($this->_memberTeamTable, $condition);
        }
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $teamId => $position) {
                $data[] = [
                    'member_id' => (int)$id,
                    'team_id' => (int)$teamId,
                    'position' => (int)$position['position']
                ];
            }
            $adapter->insertMultiple($this->_memberTeamTable, $data);
        }
        if (!empty($update)) {
            foreach ($update as $teamId => $position) {
                $where = ['member_id = ?' => (int)$id, 'team_id = ?' => (int)$teamId];
                $bind = ['position' => (int)$position['position']];
                $adapter->update($this->_memberTeamTable, $bind, $where);
            }
        }
        if (!empty($insert) || !empty($delete)) {
            $teamIds = array_unique(array_merge(array_keys($insert), array_keys($delete)));
            $this->_eventManager->dispatch(
                'braem_members_member_change_teams',
                ['member' => $member, 'team_ids' => $teamIds]
            );
        }
        if (!empty($insert) || !empty($update) || !empty($delete)) {
            $member->setIsChangedTeamList(true);
            $teamIds = array_keys($insert + $delete + $update);
            $member->setAffectedTeamIds($teamIds);
        }
        return $this;
    }
}
