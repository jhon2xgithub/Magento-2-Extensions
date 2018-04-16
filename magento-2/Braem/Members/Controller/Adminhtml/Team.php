<?php
namespace Braem\Members\Controller\Adminhtml;

abstract class Team extends \Magento\Backend\App\Action
{

    protected $_teamBuilder;


    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Result redirect factory
     * 
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $_resultRedirectFactory;

    /**
     * constructor
     * 
     * @param \Braem\Members\Model\TeamFactory $teamFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        //\Braem\Members\Model\TeamFactory $teamFactory,
        //\Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        Team\Builder $teamBuilder
    )
    {
        //$this->_teamFactory           = $teamFactory;
        //$this->_coreRegistry          = $coreRegistry;
        $this->_resultRedirectFactory = $resultRedirectFactory;
        $this->_teamBuilder = $teamBuilder;

        parent::__construct($context);
    }




//
//    /**
//     * Init Team
//     *
//     * @return \Braem\Members\Model\Team
//     */
//    protected function _initTeam()
//    {
//        $teamId  = (int) $this->getRequest()->getParam('entity_id');
//        /** @var \Braem\Members\Model\Team $team */
//        $team    = $this->_teamFactory->create();
//        if ($teamId) {
//            $team->load($teamId);
//        }
//        $this->_coreRegistry->register('braem_members_team', $team);
//        return $team;
//    }
}
