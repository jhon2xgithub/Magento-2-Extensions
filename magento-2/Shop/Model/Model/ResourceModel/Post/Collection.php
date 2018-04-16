<?php 

namespace Shop\Model\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'module_id';
	protected $_eventProfix = 'shop_model_post_collection';
	protected $_eventObject = 'post_collection';


	protected function _construct(){
		$this->_init('Shop\Model\Model\Post', 'Shop\Model\Model\ResourceModel\Post');
	}

	public function getSelectCountSql(){
		$countSelect = parent::getSelectCountSql();
		$countSelect->reset(\Zend_Db_Select::GROUP);
		return $countSelect;
	}

	protected function _toOptionArray($valueField = 'module_id', $labelField='name', $additional =[]){
		return parent::_toOptionArray($valueField, $labelField, $additional);
	}
}