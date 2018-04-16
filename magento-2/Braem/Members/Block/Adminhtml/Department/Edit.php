<?php
namespace Braem\Members\Block\Adminhtml\Department;

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
     * Initialize Department edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Braem_Members';
        $this->_controller = 'adminhtml_department';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Department'));
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
        $this->buttonList->update('delete', 'label', __('Delete Department'));
    }
    /**
     * Retrieve text for header element depending on loaded Department
     *
     * @return string
     */
    public function getHeaderText()
    {
        /** @var \Braem\Members\Model\Department $department */
        $department = $this->_coreRegistry->registry('braem_members_department');
        if ($department->getId()) {
            return __("Edit Department '%1'", $this->escapeHtml($department->getName()));
        }
        return __('New Department');
    }
}
