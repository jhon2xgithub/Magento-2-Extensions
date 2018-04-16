<?php
namespace Braem\Members\Model\Member\Source;

class Store implements \Magento\Framework\Option\ArrayInterface
{
    protected $_storeManager;

    public function __construct(\Magento\Store\Model\StoreManager $storeModel){
        $this->_storeManager = $storeModel;
    }

    public function toOptionArray()
    {
        $stores = $this->_storeManager->getGroups();
        $storesArray = [];
        $storesArray[] = [
            'value' => '',
            'label' => __("--Select a Store--")
        ];
        foreach ($stores as $store) {
            $storesArray[] = [
                //'value' => $store->getDefaultStoreId(),
                'value' => $store->getId(),
                'label' => $store->getName()
            ];
        }


        return $storesArray;
    }
}
