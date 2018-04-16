<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

namespace Shop\Lookbook\Controller\Adminhtml\Lookbook;

class NewAction extends \Shop\Lookbook\Controller\Adminhtml\Lookbook
{

    public function execute()
    {
        $this->_forward('edit');
    }
}
