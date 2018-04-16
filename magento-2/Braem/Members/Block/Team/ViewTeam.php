<?php

namespace Braem\Members\Block\Team;

use Magento\Framework\Registry;

class ViewTeam extends \Magento\Framework\View\Element\Template
{

    protected $coreRegistry;
    protected $_memberCollectionFactory;
    protected $_departmentModel;
    protected $_regionModel;
    protected $_imageModel;
    protected $assetRepo;
  
    protected $locale;
    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $localeResolver;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Braem\Members\Model\ResourceModel\Member\CollectionFactory $memberCollectionFactory,
        \Braem\Members\Model\Department $departmentModel,
        \Braem\Members\Model\Region $regionModel,
        \Braem\Members\Model\Member\Image $imageModel,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        Registry $registry,
        \Magento\Store\Api\Data\StoreInterface $store,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        \Magento\Framework\Locale\Resolver $locale,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->_memberCollectionFactory = $memberCollectionFactory;
        $this->_departmentModel = $departmentModel;
        $this->_regionModel = $regionModel;
        $this->_imageModel = $imageModel;
        $this->assetRepo = $assetRepo;       
        $this->locale = $locale;
        parent::__construct($context, $data);
    }

    public function _construct()
    {
        parent::_construct();  

        $teamMembers = $this->getCurrentTeam()->getSelectedMembersCollection();

        $memberDepartments = $this->getMemberDepartmentsByMembers($teamMembers);
        $memberRegions = $this->getMemberRegionsByMembers($teamMembers);

        if($this->getCurrentDepartment()){
            $teamMembersDepartment = $teamMembers->clear()->join(
                'braem_members_member_department',
                'main_table.member_id=braem_members_member_department.member_id AND braem_members_member_department.department_id='.$this->getCurrentDepartment()->getId(),
                ['position']
            );

            $this->setTeamMembersDepartment($teamMembersDepartment);
        }

        if($this->getCurrentRegion()){
            $teamMembersRegion = $teamMembers->clear()->join(
                'braem_members_member_region',
                'main_table.member_id=braem_members_member_region.member_id AND braem_members_member_region.region_id='.$this->getCurrentRegion()->getId(),
                ['position']
            );
        }

        $this->setTeamMembers($teamMembers);
        $this->setMemberDepartments($memberDepartments);
        $this->setMemberRegions($memberRegions);     
    }

    public function getCurrentTeam()
    {
        return $this->coreRegistry->registry('current_team');
    }

    public function getCurrentRegion()
    {
        return $this->coreRegistry->registry('current_region');
    }

    public function getCurrentDepartment()
    {
        return $this->coreRegistry->registry('current_department');
    }

    public function teamMember(){
        $teamMembers = $this->getCurrentTeam()->getSelectedMembersCollection();
        return $teamMember;
    }
    
    public function getMemberDepartmentsByMembers($members){

        $memberIds = array();
        foreach($members as $member){
            $memberIds[] = $member->getId();
        }

        $collection = array();
        if(count($memberIds) > 0){
            $collection = $this->_departmentModel->getSelectedDepartmentsCollection($memberIds);
        }

        return $collection;

    }

    public function getMemberRegionsByMembers($members){

        $memberIds = array();
        foreach($members as $member){
            $memberIds[] = $member->getId();
        }

        $collection = array();
        if(count($memberIds) > 0){
            $collection = $this->_regionModel->getSelectedRegionsCollection($memberIds);
        }
      
        return $collection;

    }

    public function getStoreNameById($storeId){
        $store = $this->_storeManager->getGroup($storeId);
        return $store->getName();
    }

    public function getRegion($member){
        $regions = $member->getSelectedRegionsCollection();

        // if(!empty($region)){
            foreach($regions as $region){
                // $region = $this->_regionModel->load($region->getId());
                $collection = $this->_regionModel->getRegionName($region->getId());
                // $region= $region->getId();
                // return '';
            }
        // }else {
            // $collection  = '';
        // }    

        return $collection;
    }

    public function getDepartment($member){
        $departments = $member->getSelectedDepartmentsCollection();


        foreach($departments as $department){
        
            $collection = $this->_departmentModel->getDeparmentName($department->getId());
            //var_dump(get_class($this->_departmentModel));exit;
            //$department = $this->_departmentModel->load($department->getId());
            //var_dump($department->getData());exit;
            // return '';
        }

        return $collection;
    }

    public function getDepartmentName($departmentIds){
        $collection = $this->_departmentModel->getDeparmentName($departmentIds);
        return $collection;
    }    

    public function getRegionName($regionIds){    
        $collection = $this->_regionModel->getRegionName($regionIds);
        return $collection;
    }    

    public function getImage($member){
        $imagePath = $member->getImage();

        return $this->_imageModel->getBaseUrl() . $imagePath;
    }

    public function getLanguagesList($member){
        $locales = explode(',',$member->getLanguages());
        $flags = array();
        foreach($locales as $locale){
            $flags[] = $this->getFlagPath($locale);
        }

        return array_unique($flags);
    }

    public function getFlagPath($locale = null)
    {
        $language = substr($locale,-2);

        $flagName = strtolower($language) . '.png';

        return $this->assetRepo->getUrl('MageWorx_StoreSwitcher::images/flags/' . $flagName);
    }

    public function getFilterTeamUrl(){
        return $this->getCurrentTeam()->getTeamUrl();
    }

    public function getlocalecode()
    {
        return $this->locale->getLocale();;
       
    }

}