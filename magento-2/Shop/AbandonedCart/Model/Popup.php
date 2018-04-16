<?php

namespace Shop\AbandonedCart\Model;

class Popup extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Shop\AbandonedCart\Model\ResourceModel\Popup');
    }

}