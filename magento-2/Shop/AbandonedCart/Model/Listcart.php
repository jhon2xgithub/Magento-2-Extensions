<?php
namespace Shop\AbandonedCart\Model;

class Listcart extends \Magento\Framework\Model\AbstractModel
{

	/**
	 * Cache tag
	 * 
	 * @var string
	 */
	const CACHE_TAG = 'shop_abandonedcart_listcart';

	/**
	 * Cache tag
	 * 
	 * @var string
	 */
	protected $_cacheTag = 'shop_abandonedcart_listcart';

	public function __construct(	
	    \Magento\Framework\Model\Context $context,
	    \Magento\Framework\Registry $registry,
	    \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
	    \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
	    array $data = []
	)
	{

	    parent::__construct($context, $registry, $resource, $resourceCollection, $data);
	}


	/**
	 * Initialize resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
	    $this->_init('Shop\AbandonedCart\Model\ResourceModel\Listcart');
	    // $this->_init('Magento\Quote\Model\ResourceModel\Quote');
	}

	/**
	 * Get identities
	 *
	 * @return array
	 */
	public function getIdentities()
	{
	    return [self::CACHE_TAG . '_' . $this->getId()];
	}

	/**
	 * get entity default values
	 *
	 * @return array
	 */
	public function getDefaultValues()
	{
	    $values = [];

	    return $values;
	}

}
