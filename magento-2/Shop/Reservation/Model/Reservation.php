<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Reservation\Model;

class Reservation extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Shop\Reservation\Model\Resource\Reservation');
    }
}
