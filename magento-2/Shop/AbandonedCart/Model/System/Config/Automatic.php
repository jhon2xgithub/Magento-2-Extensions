<?php

namespace Shop\AbandonedCart\Model\System\Config;

class Automatic{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array(
            array('value' => 1, 'label' => __('Specific')),
            array('value' => 2, 'label' => __('Automatic'))
        );
        return $options;
    }
}