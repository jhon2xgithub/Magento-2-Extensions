<?php
 
 
class Yourmindourwork_Storesquare_Block_Adminhtml_Manage_Categories extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    // We declare the content of our items container
    public function __construct()
    {
       
        // The blockGroup must match the first half of how we call the block
        $this->_blockGroup = 'yourmindourwork_storesquare';
 
        // The controller must match the second half of how we call the block
        $this->_controller = 'adminhtml_manage_categories';
 
        $this->_headerText = Mage::helper('yourmindourwork_storesquare')->__('Manage Categories');
 
        parent::__construct();
        $this->_removeButton('add');

    }
}