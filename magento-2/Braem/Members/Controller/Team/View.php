<?php
namespace Braem\Members\Controller\Team;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Braem\Members\Model\TeamFactory;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Braem\Members\Model\Team\Url as UrlModel;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class View extends \Magento\Framework\App\Action\Action
{
    const BREADCRUMBS_CONFIG_PATH = 'braem_members/team/breadcrumbs';

    protected $resultForwardFactory;
    protected $teamFactory;
    protected $resultPageFactory;
    protected $coreRegistry;
    protected $urlModel;
    protected $scopeConfig;
    protected $_departmentModel;
    protected $_regionModel;

    public function __construct(
        Context $context,
        TeamFactory $teamFactory,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        Registry $coreRegistry,
        UrlModel $urlModel,
        \Braem\Members\Model\Department $departmentModel,
        \Braem\Members\Model\Region $regionModel,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->teamFactory = $teamFactory;
        $this->coreRegistry = $coreRegistry;
        $this->scopeConfig = $scopeConfig;
        $this->_departmentModel = $departmentModel;
        $this->_regionModel = $regionModel;
        parent::__construct($context);
    }

    public function execute()
    {

       $teamId = (int) $this->getRequest()->getParam('id');
       $departmentId = (int) $this->getRequest()->getParam('department');
       $regionId = (int) $this->getRequest()->getParam('region');        
       
       if($regionId){           
           $region = $this->_regionModel->load($regionId);
           $this->coreRegistry->register('current_region', $region);
       }

       if($departmentId){
         
           $department = $this->_departmentModel->load($departmentId);
           $this->coreRegistry->register('current_department', $department);
       }

       $team = $this->teamFactory->create();
       $team->load($teamId);

       $this->coreRegistry->register('current_team', $team);
       $resultPage = $this->resultPageFactory->create();

       $title = ($team->getMetaTitle()) ?: $team->getName();
       $resultPage->getConfig()->getTitle()->set($title);
       $resultPage->getConfig()->setDescription($team->getMetaDescription());
       $resultPage->getConfig()->setKeywords($team->getMetaKeywords());
       if ($this->scopeConfig->isSetFlag(self::BREADCRUMBS_CONFIG_PATH, ScopeInterface::SCOPE_STORE)) {
           /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbsBlock */
           $breadcrumbsBlock = $resultPage->getLayout()->getBlock('breadcrumbs');
           if ($breadcrumbsBlock) {
               $breadcrumbsBlock->addCrumb(
                   'home',
                   [
                       'label' => __('Home'),
                       'link'  => $this->_url->getUrl('')
                   ]
               );
               $breadcrumbsBlock->addCrumb(
                   'teams',
                   [
                       'label' => __('Teams'),
                       'link'  => '', //$this->urlModel->getListUrl()
                   ]
               );
               $breadcrumbsBlock->addCrumb(
                   'team-'.$team->getId(),
                   [
                       'label' => $team->getName()
                   ]
               );
           }
       }
       return $resultPage;
    }
}