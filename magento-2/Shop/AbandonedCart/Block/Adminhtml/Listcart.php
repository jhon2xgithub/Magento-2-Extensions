<?php
namespace Shop\AbandonedCart\Block\Adminhtml;

class Listcart extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_listcart';
        $this->_blockGroup = 'Shop_AbandonedCart';
        $this->_headerText = __('Listcart');
        $this->_addButtonLabel = __('Create New Listcart');
        parent::_construct();
    }
}
