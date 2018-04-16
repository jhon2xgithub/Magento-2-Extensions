<?php
namespace Braem\Members\Block\Adminhtml;

class Department extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_department';
        $this->_blockGroup = 'Braem_Members';
        $this->_headerText = __('Departments');
        $this->_addButtonLabel = __('Create New Department');
        parent::_construct();
    }
}
