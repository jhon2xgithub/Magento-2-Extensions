<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Homepromo\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Ideescadeaux extends \Magento\Framework\Model\AbstractModel
{

    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'ws_products_grid';

    /**
     * @var string
     */
    protected $_cacheTag = 'ws_products_grid';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'ws_products_grid';

    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('Shop\Homepromo\Model\ResourceModel\Ideescadeaux');
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getProducts(\Shop\Homepromo\Model\Ideescadeaux $object)
    {
        $tbl = $this->getResource()->getTable(\Shop\Homepromo\Model\ResourceModel\Ideescadeaux::TBL_ATT_PRODUCT);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['product_id']
        )
        ->where(
            'promo_id = ?',
            (int)$object->getId()
        );
        return $this->getResource()->getConnection()->fetchCol($select);
    }
}
