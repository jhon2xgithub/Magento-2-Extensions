<?php
namespace Braem\Members\Block\Adminhtml;

class Region extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_region';
        $this->_blockGroup = 'Braem_Members';
        $this->_headerText = __('Regions');
        $this->_addButtonLabel = __('Create New Region');
        parent::_construct();
    }
}
