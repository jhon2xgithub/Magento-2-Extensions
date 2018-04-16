<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */
namespace Shop\Lookbook\Block\Adminhtml\Lookbook\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('shop_lookbook_lookbook_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Lookbook'));
    }
}
