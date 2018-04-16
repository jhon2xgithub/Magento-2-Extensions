<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Reservation\Model\Resource;

class Reservation extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('shop_reservation_post', 'id');
    }
}
