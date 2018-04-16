<?php 
class Yourmindourwork_Storesquare_Block_Adminhtml_Editor extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup      = 'yourmindourwork_storesquare';
        $this->_controller      = 'adminhtml';
        $this->_mode            = 'editor';
        $this->_updateButton('save', 'label', Mage::helper('yourmindourwork_storesquare')->__("Submit Form"));

        /*use the following code if you need saveAndContinueEdit button

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('yourmindourwork_storesquare')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";*/
    }

    public function getHeaderText()
    {
        return Mage::helper('yourmindourwork_storesquare')->__("Manage Categories");
    }

    /**
     * Get form save URL
     *
     * @deprecated
     * @see getFormActionUrl()
     * @return string
     */
    public function getSaveUrl()
    {
        $this->setData('form_action_url', 'save');
        return $this->getFormActionUrl();
    }
}