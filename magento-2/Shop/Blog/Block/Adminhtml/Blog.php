<?php

namespace Shop\Blog\Block\Adminhtml;

class Blog extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'blog';
        $this->_headerText = __('Blog');
        // $this->_addButtonLabel = __('Add New Blog');
        parent::_construct();
    }

}
