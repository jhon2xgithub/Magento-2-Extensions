<?php
namespace Braem\Members\Controller\Adminhtml\Region;

class Save extends \Braem\Members\Controller\Adminhtml\Region
{
    /**
     * Backend session
     *
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * JS helper
     *
     * @var \Magento\Backend\Helper\_regionBuilder
     */
    protected $_jsHelper;

    /**
     * constructor
     *
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Magento\Backend\Helper\Js $jsHelper
     * @param \Braem\Members\Model\RegionFactory $regionFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Backend\Helper\Js $jsHelper,

        \Braem\Members\Model\RegionFactory $regionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context,
        \Braem\Members\Controller\Adminhtml\Region\Builder $regionBuilder
    )
    {
        $this->_backendSession = $backendSession;
        $this->_jsHelper       = $jsHelper;
        parent::__construct($resultRedirectFactory, $context, $regionBuilder);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('region');

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $region = $this->_regionBuilder->build($this->getRequest());

            $region->setData($data);

            /**
             * Check "Use Default Value" checkboxes values
             */
            $useDefaults = $this->getRequest()->getPost('use_default');
            if ($useDefaults) {
                foreach ($useDefaults as $attributeCode) {
                    $region->setData($attributeCode, false);
                }
            }
            
            $members = $this->getRequest()->getPost('members', -1);
            if ($members != -1) {
                $region->setMembersData($this->_jsHelper->decodeGridSerializedInput($members));
            }
            $this->_eventManager->dispatch(
                'braem_members_region_prepare_save',
                [
                    'region' => $region,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $region->save();
                $this->messageManager->addSuccess(__('The Region has been saved.'));
                $this->_backendSession->setBraemMembersRegionData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'braem_members/*/edit',
                        [
                            'entity_id' => $region->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('braem_members/*/');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Region.'));
            }
            $this->_getSession()->setBraemMembersRegionData($data);
            $resultRedirect->setPath(
                'braem_members/*/edit',
                [
                    'entity_id' => $region->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('braem_members/*/');
        return $resultRedirect;
    }
}
