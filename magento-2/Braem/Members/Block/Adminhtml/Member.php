<?php
namespace Braem\Members\Block\Adminhtml;

class Member extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_member';
        $this->_blockGroup = 'Braem_Members';
        $this->_headerText = __('Members');
        $this->_addButtonLabel = __('Create New Member');
        parent::_construct();
    }
}
