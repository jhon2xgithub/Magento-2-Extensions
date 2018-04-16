<?php

namespace Shop\AbandonedCart\Model\ResourceModel\Popup;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Shop\AbandonedCart\Model\Popup', 'Shop\AbandonedCart\Model\ResourceModel\Popup');
    }

}