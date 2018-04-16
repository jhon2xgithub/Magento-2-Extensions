<?php
namespace Shop\AbandonedCart\Model\ResourceModel\Listcart;



class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
	    $this->_init('Shop\AbandonedCart\Model\Listcart', 'Shop\AbandonedCart\Model\ResourceModel\Listcart');
	    // $this->_init('Magento\Quote\Model\Quote', 'Magento\Quote\Model\ResourceModel\Quote');
	}

	protected function _initSelect()
    {
        parent::_initSelect();
 
        $this->getSelect()->joinLeft(
            ['customer_review_quote' => $this->getTable('quote')], //2nd table name by which you want to join mail table
            'main_table.quote_id = customer_review_quote.entity_id',
            '*' // common column which available in both table 
             	// '*' define that you want all column of 2nd table. if you want some particular column then you can define as ['column1','column2']
        )->group('main_table.quote_id');
    }
}	