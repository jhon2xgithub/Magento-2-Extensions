<?php
namespace Braem\Members\Block\Adminhtml\Region;

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
     * Initialize Region edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Braem_Members';
        $this->_controller = 'adminhtml_region';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Region'));
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
        $this->buttonList->update('delete', 'label', __('Delete Region'));
    }
    /**
     * Retrieve text for header element depending on loaded Region
     *
     * @return string
     */
    public function getHeaderText()
    {
        /** @var \Braem\Members\Model\Region $region */
        $region = $this->_coreRegistry->registry('braem_members_region');
        if ($region->getId()) {
            return __("Edit Region '%1'", $this->escapeHtml($region->getName()));
        }
        return __('New Region');
    }
}
