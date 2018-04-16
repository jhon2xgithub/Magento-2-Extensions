<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Homepromo\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Homepromo extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
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
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Shop\Homepromo\Model\ResourceModel\Homepromo');
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

    public function getProducts(\Shop\Homepromo\Model\Homepromo $object)
    {
        $tbl = $this->getResource()->getTable(\Shop\Homepromo\Model\ResourceModel\Homepromo::TBL_ATT_PRODUCT);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['product_id']
        )
        ->where(
            'contact_id = ?',
            (int)$object->getId()
        );
        return $this->getResource()->getConnection()->fetchCol($select);
    }
}
