<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Blog\Model\Resource;

class Blog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('shop_blog_post', 'id');
    }
}
