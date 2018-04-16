<?php
/**
 * Copyright © 2015 Shop. All rights reserved.
 */

// @codingStandardsIgnoreFile

namespace Shop\Lookbook\Block\Adminhtml\Lookbook\Edit\Tab;


use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Shop\Lookbook\Model\System\Config\Status;


class Main extends Generic implements TabInterface
{


    // protected $pathurls = $this->getUrl('shop_lookbook/lookbook/uploader');

	/**
     * @var \Shop\Lookbook\Model\System\Config\Status
     */
    protected $_lookbookStatus;
 
   /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
     * @param Status $newsStatus
     * @param array $data
     */
    public function __construct(
		Context $context,
		Registry $registry,
        FormFactory $formFactory,
		Status $lookbookStatus,
		array $data = []) 
    {    
        $this->_lookbookStatus = $lookbookStatus;
        parent::__construct($context, $registry, $formFactory, $data);
  
    }
 	
		
    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_shop_lookbook_lookbook');
		
		$isElementDisabled = false;
		
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('lookbook_'); // ex: 'sku' text becomes -> 'lookbook_sku' 
        $fieldset = $form->addFieldset(
            'base_fieldset', [
                'legend' => __('Lookbook Information')]);

        if ($model->getId()) {
            $fieldset->addField(
                'id', 
                'hidden', [
                    'name' => 'id'
                    ]
                );
        }
		
        
		$fieldset->addField(
            'sku',  
            'text',
            [
				'name' => 'sku', 
				'label' => __('Group Product SKU'), 
				'title' => __('Group Product SKU'), 
				'required' => true,
				'note' => 'Choose existing product group sku to avoid errors.',
			]
        );
		
        $fieldset->addField(
            'name',
            'text',
            [
				'name' => 'name', 
				'label' => __('Name'), 
				'title' => __('Name'), 
				'required' => true
			]
        );
		
		$fieldset->addField(
            'position',
            'text',
            [
				'name' => 'position', 
				'label' => __('Order'), 
				'title' => __('Order')
			]
        );
		
		$fieldset->addField(
            'status',
            'select',
            [
                'name'      => 'status',
                'label'     => __('Status'),
                'options' =>$this->_lookbookStatus->toOptionArray()       
                 
            ]
        );

        // $fieldset->addField(
        //     'field_to_hide', 
        //     'text', 
        //     [
                                    
        //         'name'  =>  $this->getUrl('shop_lookbook/lookbook/uploader'),
        //         'label'     => __('Test'),
        //     ]
        // );
		
		$selectField =  $fieldset->addField(
			'photo',
			'image',
			[
				'name'        => 'photo',
				'label'       => __('Upload File'),
				'title'       => __('Upload File'),
                'required' => true,
				//'onclick' => 'startAjax();',
                'note' => 'Allow image type: jpg, jpeg, gif, png',
                
			]
		);

        // if you want to use onclik or onchange when uploading file
        // $selectField->setAfterElementHtml(
        //    '<script type="text/javascript">'.
        //     "    
        //     function startAjax() {

        //         jQuery.ajax({
        //             url: 'http://127.0.0.1/lookbook/admin/shop_lookbook/lookbook/uploader/key/240990e131914b0a78bb22c92595bd9b74fe79c43132bf350df7b6ceb389b53f/',
        //             type: 'post',                       
        //             data: {
        //                 image: this.value,
        //             },
        //             success: function(data){
        //                 console.log(data);

        //             }                    
        //         });
        //     }         
          
        //     ".
        //     '</script>'
        // );

		
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Lookbook Information');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Lookbook Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
