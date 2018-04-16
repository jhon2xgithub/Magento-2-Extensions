<?php
namespace Braem\Members\Controller\Adminhtml\Member;

class Save extends \Braem\Members\Controller\Adminhtml\Member
{
    /**
     * Upload model
     * 
     * @var \Braem\Members\Model\Upload
     */
    protected $_uploadModel;

    /**
     * Image model
     * 
     * @var \Braem\Members\Model\Member\Image
     */
    protected $_imageModel;

    /**
     * Backend session
     * 
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * JS helper
     * 
     * @var \Magento\Backend\Helper\Js
     */
    protected $_jsHelper;

    /**
     * constructor
     * 
     * @param \Braem\Members\Model\Upload $uploadModel
     * @param \Braem\Members\Model\Member\Image $imageModel
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Magento\Backend\Helper\Js $jsHelper
     * @param \Braem\Members\Model\MemberFactory $memberFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Braem\Members\Model\Upload $uploadModel,
        \Braem\Members\Model\Member\Image $imageModel,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Backend\Helper\Js $jsHelper,
        \Braem\Members\Model\MemberFactory $memberFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    )
    {
        $this->_uploadModel    = $uploadModel;
        $this->_imageModel     = $imageModel;
        $this->_backendSession = $backendSession;
        $this->_jsHelper       = $jsHelper;
        parent::__construct($memberFactory, $registry, $resultRedirectFactory, $context);
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('member');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->_filterData($data);
            $member = $this->_initMember();
            $member->setData($data);
            $image = $this->_uploadModel->uploadFileAndGetName('image', $this->_imageModel->getBaseDir(), $data);
            $member->setImage($image);
            $regions = $this->getRequest()->getPost('regions', -1);
            if ($regions != -1) {
                $member->setRegionsData($this->_jsHelper->decodeGridSerializedInput($regions));
            }
            $departments = $this->getRequest()->getPost('departments', -1);
            if ($departments != -1) {
                $member->setDepartmentsData($this->_jsHelper->decodeGridSerializedInput($departments));
            }
            $teams = $this->getRequest()->getPost('teams', -1);
            if ($teams != -1) {
                $member->setTeamsData($this->_jsHelper->decodeGridSerializedInput($teams));
            }
            $this->_eventManager->dispatch(
                'braem_members_member_prepare_save',
                [
                    'member' => $member,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $member->save();
                $this->messageManager->addSuccess(__('The Member has been saved.'));
                $this->_backendSession->setBraemMembersMemberData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'braem_members/*/edit',
                        [
                            'member_id' => $member->getId(),
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
                $this->messageManager->addException($e, __('Something went wrong while saving the Member.'));
            }
            $this->_getSession()->setBraemMembersMemberData($data);
            $resultRedirect->setPath(
                'braem_members/*/edit',
                [
                    'member_id' => $member->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('braem_members/*/');
        return $resultRedirect;
    }

    /**
     * filter values
     *
     * @param array $data
     * @return array
     */
    protected function _filterData($data)
    {
        if (isset($data['languages'])) {
            if (is_array($data['languages'])) {
                $data['languages'] = implode(',', $data['languages']);
            }
        }

        return $data;
    }

}
