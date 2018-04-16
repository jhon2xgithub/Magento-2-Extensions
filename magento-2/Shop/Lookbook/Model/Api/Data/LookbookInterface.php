<?php
namespace Shop\Lookbook\Model\Api\Data;
interface LookbookInterface
{
	public function getName();
	public function setName();
	
	public function getImage();
	public function setImage();
	
	public function getContent();
	public function setContent();
	
	public function getHotspots();
	public function setHotspots();

	public function getPosition();
	public function setPosition();

	public function getStatus();
	public function setStatus();

	public function getCreatedAt();
	public function setCreatedAt();
}