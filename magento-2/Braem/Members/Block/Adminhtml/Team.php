<?php
namespace Braem\Members\Block\Adminhtml;

class Team extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_team';
        $this->_blockGroup = 'Braem_Members';
        $this->_headerText = __('Teams');
        $this->_addButtonLabel = __('Create New Team');
        parent::_construct();
    }
}
