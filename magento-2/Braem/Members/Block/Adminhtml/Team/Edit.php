<?php
namespace Braem\Members\Block\Adminhtml\Team;

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
     * Initialize Team edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Braem_Members';
        $this->_controller = 'adminhtml_team';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Team'));
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
        $this->buttonList->update('delete', 'label', __('Delete Team'));
    }
    /**
     * Retrieve text for header element depending on loaded Team
     *
     * @return string
     */
    public function getHeaderText()
    {
        /** @var \Braem\Members\Model\Team $team */
        $team = $this->_coreRegistry->registry('braem_members_team');
        if ($team->getId()) {
            return __("Edit Team '%1'", $this->escapeHtml($team->getName()));
        }
        return __('New Team');
    }
}
