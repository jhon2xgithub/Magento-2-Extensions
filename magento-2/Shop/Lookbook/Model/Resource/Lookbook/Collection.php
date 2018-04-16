<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Lookbook\Model\Resource\Lookbook;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Shop\Lookbook\Model\Lookbook', 'Shop\Lookbook\Model\Resource\Lookbook');
    }
}
