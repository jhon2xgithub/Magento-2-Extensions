<?php
namespace Braem\Members\Block\Adminhtml\Member;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * constructor
     * 
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize Member edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'member_id';
        $this->_blockGroup = 'Braem_Members';
        $this->_controller = 'adminhtml_member';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Member'));
        $this->buttonList->add(
            'save-and-continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
        );
        $this->buttonList->update('delete', 'label', __('Delete Member'));
    }
    /**
     * Retrieve text for header element depending on loaded Member
     *
     * @return string
     */
    public function getHeaderText()
    {
        /** @var \Braem\Members\Model\Member $member */
        $member = $this->_coreRegistry->registry('braem_members_member');
        if ($member->getId()) {
            return __("Edit Member '%1'", $this->escapeHtml($member->getName()));
        }
        return __('New Member');
    }
}
