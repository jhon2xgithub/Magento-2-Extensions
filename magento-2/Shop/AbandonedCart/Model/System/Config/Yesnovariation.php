<?php

namespace Shop\AbandonedCart\Model\System\Config;

class Yesnovariation{
    /**
     * @var \Shop\AbandonedCart\Helper\Data
     */
    protected $_helper;
    /**
     * @param \Shop\AbandonedCart\Helper\Data $helper
     */
    public function __construct(
        \Shop\AbandonedCart\Helper\Data $helper
    )
    {
        $this->_helper = $helper;
    }
    public function toOptionArray()
    {
        // $code = Mage::getSingleton('adminhtml/config_data')->getStore();
        // $storeId = Mage::getModel('core/store')->load($code)->getId();
        $storeId = null;
        $hasCoupon = $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::SEND_COUPON);
        if ($hasCoupon) {
            $active = -$this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::MAXTIMES);
        } else {
            $active = $this->_helper->getConfig(\Shop\AbandonedCart\Model\Config::MAXTIMES);
        }
        $options = array(
            array('value' => 0, 'label' => __('No')),
            array('value' => ($active+($hasCoupon ? -1 :1)), 'label' => __('Yes'))
        );
        return $options;
    }

}