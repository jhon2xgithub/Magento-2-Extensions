<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Reservation\Model\Resource\Reservation;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Shop\Reservation\Model\Reservation', 'Shop\Reservation\Model\Resource\Reservation');
    }
}
