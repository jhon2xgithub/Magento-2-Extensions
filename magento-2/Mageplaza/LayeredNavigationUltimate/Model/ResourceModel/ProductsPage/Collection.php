<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_LayeredNavigationUltimate
 * @copyright   Copyright (c) 2016 Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\LayeredNavigationUltimate\Model\ResourceModel\ProductsPage;

/**
 * ProductsLists Collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
	 * Initialize resource collection
	 *
	 * @return void
	 */
	public function _construct()
	{
		$this->_init('Mageplaza\LayeredNavigationUltimate\Model\ProductsPage', 'Mageplaza\LayeredNavigationUltimate\Model\ResourceModel\ProductsPage');
	}

	/**
	 * @param array $storeIds
	 * @param bool $withDefaultStore
	 * @return $this
	 */
	public function addStoreFilter($storeIds = [], $withDefaultStore = true)
	{
		if (!is_array($storeIds)) {
			$storeIds = [$storeIds];
		}
		if ($withDefaultStore && !in_array('0', $storeIds)) {
			array_unshift($storeIds, 0);
		}
		$where = [];
		foreach ($storeIds as $storeId) {
			$where[] = $this->_getConditionSql('store_ids', ['finset' => $storeId]);
		}

		$this->_select->where(implode(' OR ', $where));

		return $this;
	}

	/**
	 * @return $this
	 */
	public function addVisibleFilter()
	{
		$this->addFieldToFilter('status', 1);

		return $this;
	}
}
