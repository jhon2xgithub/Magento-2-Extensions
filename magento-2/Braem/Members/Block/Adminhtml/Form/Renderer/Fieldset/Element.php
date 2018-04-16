<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Catalog fieldset element renderer
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Braem\Members\Block\Adminhtml\Form\Renderer\Fieldset;

class Element extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element
{
    /**
     * Initialize block template
     */
    protected $_template = 'Braem_Members::member/form/renderer/fieldset/element.phtml';

    public function getDataObject()
    {
        return $this->getElement()->getForm()->getDataObject();
    }

    public function getDataObjectDefault()
    {
        return $this->getElement()->getForm()->getDataObjectDefault();
    }





    /**
     * Check default value usage fact
     *
     * @return bool
     */
    public function usedDefault()
    {


        $dataObject = $this->getDataObject();

        $dataObjectDefault = $this->getDataObjectDefault();

        $attributeCode = $this->getElement()->getId();

        if($dataObject->getData($attributeCode) == $dataObjectDefault->getData($attributeCode)){
            return true;
        }else{
            return false;
        }

    }


    public function canDisplayUseDefault(){
        if($this->getRequest()->getParam('store')){
            return true;
        }
        return false;
    }

    public function checkFieldDisable()
    {
        if ($this->canDisplayUseDefault() && $this->usedDefault()) {
            $this->getElement()->setDisabled(true);
        }
        return $this;
    }

    public function getScopeLabel()
    {
        return '[STORE VIEW]';
    }
}
