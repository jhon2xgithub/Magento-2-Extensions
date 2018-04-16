<?php
 
 
class Yourmindourwork_Storesquare_Block_Adminhtml_Manage_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

	public function __construct()
    {

    	// echo __METHOD__;
    	// die();
        parent::__construct();
                  
        $this->_objectId = 'id';
        $this->_blockGroup = 'yourmindourwork_storesquare';
        $this->_controller = 'adminhtml_manage_edit_form';
         
        $this->_updateButton('save', 'label', Mage::helper('yourmindourwork_storesquare')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('yourmindourwork_storesquare')->__('Delete'));
         
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);
    }
 
    public function getHeaderText()
    {
        return Mage::helper('yourmindourwork_storesquare')->__('My Form Container');
    }
}