<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_Blog
 * @copyright   Copyright (c) 2016 Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */
namespace Mageplaza\Blog\Block\Adminhtml\Topic\Edit\Tab;

/**
 * Class Topic
 * @package Mageplaza\Blog\Block\Adminhtml\Topic\Edit\Tab
 */
class Topic extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Wysiwyg config
     *
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    public $wysiwygConfig;

    /**
     * Country options
     *
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    public $booleanOptions;

	/**
	 * @var \Mageplaza\Blog\Model\Config\Source\MetaRobots
	 */
    public $metaRobotsOptions;

	/**
	 * @var \Magento\Store\Model\System\Store
	 */
    public $systemStore;

	/**
	 * Topic constructor.
	 * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
	 * @param \Magento\Config\Model\Config\Source\Yesno $booleanOptions
	 * @param \Mageplaza\Blog\Model\Config\Source\MetaRobots $metaRobotsOptions
	 * @param \Magento\Store\Model\System\Store $systemStore
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Framework\Data\FormFactory $formFactory
	 * @param array $data
	 */
    public function __construct(
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Config\Model\Config\Source\Yesno $booleanOptions,
        \Mageplaza\Blog\Model\Config\Source\MetaRobots $metaRobotsOptions,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
    
        $this->wysiwygConfig     = $wysiwygConfig;
        $this->booleanOptions    = $booleanOptions;
        $this->metaRobotsOptions = $metaRobotsOptions;
        $this->systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Mageplaza\Blog\Model\Topic $topic */
        $topic = $this->_coreRegistry->registry('mageplaza_blog_topic');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('topic_');
        $form->setFieldNameSuffix('topic');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Topic Information'),
                'class'  => 'fieldset-wide'
            ]
        );
        if ($topic->getId()) {
            $fieldset->addField(
                'topic_id',
                'hidden',
                ['name' => 'topic_id']
            );
        }
        $fieldset->addField(
            'name',
            'text',
            [
                'name'  => 'name',
                'label' => __('Name'),
                'title' => __('Name'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'description',
            'editor',
            [
                'name'  => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'config'    => $this->wysiwygConfig->getConfig()
            ]
        );
        $fieldset->addField(
            'store_ids',
            'multiselect',
            [
                'name'  => 'store_ids',
                'label' => __('Store Views'),
                'title' => __('Store Views'),
                'note' => __('Select Store Views'),
                'values' => $this->systemStore->getStoreValuesForForm(false, true),
            ]
        );
        $fieldset->addField(
            'enabled',
            'select',
            [
                'name'  => 'enabled',
                'label' => __('Enabled'),
                'title' => __('Enabled'),
                'values' => $this->booleanOptions->toOptionArray(),
            ]
        );
        $fieldset->addField(
            'url_key',
            'text',
            [
                'name'  => 'url_key',
                'label' => __('URL Key'),
                'title' => __('URL Key'),
            ]
        );
        $fieldset->addField(
            'meta_title',
            'text',
            [
                'name'  => 'meta_title',
                'label' => __('Meta Title'),
                'title' => __('Meta Title'),
            ]
        );
        $fieldset->addField(
            'meta_description',
            'textarea',
            [
                'name'  => 'meta_description',
                'label' => __('Meta Description'),
                'title' => __('Meta Description'),
            ]
        );
        $fieldset->addField(
            'meta_keywords',
            'textarea',
            [
                'name'  => 'meta_keywords',
                'label' => __('Meta Keywords'),
                'title' => __('Meta Keywords'),
            ]
        );
        $fieldset->addField(
            'meta_robots',
            'select',
            [
                'name'  => 'meta_robots',
                'label' => __('Meta Robots'),
                'title' => __('Meta Robots'),
                'values' => $this->metaRobotsOptions->toOptionArray(),
            ]
        );

        $topicData = $this->_session->getData('mageplaza_blog_topic_data', true);
        if ($topicData) {
            $topic->addData($topicData);
        } else {
            if (!$topic->getId()) {
                $topic->addData($topic->getDefaultValues());
            }
        }
        $form->addValues($topic->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Topic');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}