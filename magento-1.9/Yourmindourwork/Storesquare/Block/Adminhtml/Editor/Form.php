<?php 

class Yourmindourwork_Storesquare_Block_Adminhtml_Editor_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form   = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save'),
            'method'    => 'post'
        ));

        $fieldset   = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('yourmindourwork_storesquare')->__("Some Information"),
            'class'     => 'fieldset-wide',
        ));

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper('yourmindourwork_storesquare')->__('Name'),
            'title'     => Mage::helper('yourmindourwork_storesquare')->__('Title'),
            'required'  => true,
        ));

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config');
        $fieldset->addField('description', 'editor', array(
            'name'      => 'description',
            'label'     => Mage::helper('yourmindourwork_storesquare')->__('Description'),
            'title'     => Mage::helper('yourmindourwork_storesquare')->__('Description'),
            'style'     => 'height: 600px;',
            'wysiwyg'   => true,
            'required'  => false,
            'config'    => $wysiwygConfig
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}