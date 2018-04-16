<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Homepromo\Model\ResourceModel\Topdesventes;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{	
	/**
     * @var string
     */
    protected $_idFieldName = 'promo_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Shop\Homepromo\Model\Topdesventes', 'Shop\Homepromo\Model\ResourceModel\Topdesventes');
    }
}
