<?php
namespace Shop\AbandonedCart\Model\ResourceModel;

class Listcart extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

	public function __construct(
	 	\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
	    // $this->_date         = $date;
	    // $this->_eventManager = $eventManager;
	    parent::__construct($context);
	    // $this->_memberRegionTable = $this->getTable('braem_members_member_region');
	    // $this->_memberDepartmentTable = $this->getTable('braem_members_member_department');
	    // $this->_memberTeamTable = $this->getTable('braem_members_member_team');
	}


	/**
	 * Initialize resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
	    // $this->_init('shop_abandonedcart_listcart', 'listcart_id');
	    $this->_init('quote_item', 'item_id');
	}

}	