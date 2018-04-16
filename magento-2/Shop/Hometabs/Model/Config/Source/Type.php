<?php 

namespace Shop\Hometabs\Model\Config\Source;

class Type implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
	  return [
	    ['value' => 'all_products', 'label' => __('All Products')],
	    ['value' => 'new_products', 'label' => __('New Products')]
	  ];
	}
}