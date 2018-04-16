<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Blog\Model;

class Blog extends \Magento\Framework\Model\AbstractModel
{

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Shop\Blog\Model\Resource\Blog');
    }
}
