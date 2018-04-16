<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Blog\Model\Resource\Blog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Shop\Blog\Model\Blog', 'Shop\Blog\Model\Resource\Blog');
    }
}
