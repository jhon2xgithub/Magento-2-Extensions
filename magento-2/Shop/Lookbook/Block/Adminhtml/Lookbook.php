<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */
namespace Shop\Lookbook\Block\Adminhtml;

class Lookbook extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'lookbook';
        $this->_headerText = __('Lookbook');
        $this->_addButtonLabel = __('Add New Lookbook');
        parent::_construct();
    }


}
