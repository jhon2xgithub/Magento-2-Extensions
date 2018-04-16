<?php 

namespace Shop\Model\Model\Api\Data;
interface PostInterface
{

	public function getModuleId();
	public function setModuleId();
	
	public function getName();
	public function setName();

	public function getPostContent();
	public function setPostContent();
}