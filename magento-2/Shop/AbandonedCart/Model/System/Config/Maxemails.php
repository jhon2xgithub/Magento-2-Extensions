<?php

namespace Shop\AbandonedCart\Model\System\Config;

class Maxemails{
    public function toOptionArray()
    {
        $options = array();
        for ($i = 0; $i < \Shop\AbandonedCart\Model\Config::MAXTIMES_NUM; $i++) {
            $options[] = array('value' => $i, 'label' => $i + 1);
        }
        return $options;
    }
}