<?php 

namespace Shop\Model\Model\ResourceModel;

class Post extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	protected $_date;

	public function __construct(
		\Magento\Framework\Stdlib\DateTime $date,
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	){
		$this->_date = $date;
		parent::__construct($context);

	}

	protected function _construct(){
		$this->_init('shop_model_post', 'module_id');
	}

	public function getPostNameById($id){
		$adapter = $this->getConnection();
		$select = $adapter->getSelect()
			->from($this->getMAinTable(), 'name')
			->where('module_id = :module_id');

		$binds  = ['post_id'=>(int)$id];	
		return $adapter->fetchOne($select, $binds);	
	}

	protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
	{
		$obj->setUpdateAt($this->_date->date());
		if($object->isObjectNew()){
			$object->setCreatedAt($this->_date->date());

		}
		return parent::_beforeSave($object);
	}
}


//note atop at step 4