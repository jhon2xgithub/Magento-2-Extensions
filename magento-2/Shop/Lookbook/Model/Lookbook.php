<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Lookbook\Model;

class Lookbook extends \Magento\Framework\Model\AbstractModel
{

    const BASE_MEDIA_PATH = 'lookbook/200X100';


    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Shop\Lookbook\Model\Resource\Lookbook');
    }
}
