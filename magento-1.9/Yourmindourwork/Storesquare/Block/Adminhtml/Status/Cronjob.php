<?php
 
 
class Yourmindourwork_Storesquare_Block_Adminhtml_Status_Cronjob extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    // We declare the content of our items container
    public function __construct()
    {
       
        // The blockGroup must match the first half of how we call the block
        $this->_blockGroup = 'yourmindourwork_storesquare';
 
        // The controller must match the second half of how we call the block
        $this->_controller = 'adminhtml_status_cronjob';
 
        $this->_headerText = Mage::helper('yourmindourwork_storesquare')->__('Storesquare Cron Job Status');
 
        parent::__construct();
        $this->_removeButton('add');

    }
}