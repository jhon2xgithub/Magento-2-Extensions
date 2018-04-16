<?php

namespace Shop\AbandonedCart\Model\System\Config;

class Unit{
    public function toOptionArray()
    {
        $options = array(
            array('value' => \Shop\AbandonedCart\Model\Config::IN_DAYS, 'label' => __('Days')),
            array('value' => \Shop\AbandonedCart\Model\Config::IN_HOURS, 'label' =>__('Hours'))
        );
        return $options;
    }
}