<?php 

namespace Shop\Model\Model;

class Post 
	extends \Magento\Framework\Model\AbstractModel 
	implements \Magento\Framework\DataObject\IdentityInterface, \Shop\Model\Model\Api\Data\PostInterface

{

	const CACHE_TAG = 'shop_model_post';
	protected $_cacheTag = 'shop_model_post';

	protected $_eventPrefix = 'shop_model_post';

	protected function _construct(){
		$this->_init('Shop\Model\Model\ResourceModel\Post');
	}

	public function getIdentities(){
		return [self::CACHE_TAG . '_' . $this->getModuleId()];
	}

	public function getDefaultValues(){

		$values = [];
		return $values;
	}

	public function getModuleId(){}
	public function setModuleId(){}
	
	public function getName(){}
	public function setName(){}

	public function getPostContent(){}
	public function setPostContent(){}
} 
