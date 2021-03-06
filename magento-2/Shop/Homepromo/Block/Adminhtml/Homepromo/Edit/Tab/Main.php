<?php
namespace Shop\Homepromo\Block\Adminhtml\Homepromo\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;

    /**
    * @var \Webspeaks\ProductsGrid\Helper\Data $helper
    */
    protected $helper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Shop\Homepromo\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Webspeaks\ProductsGrid\Model\Contact */
        $model = $this->_coreRegistry->registry('shop_homepromo');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('homepromo_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Promo Information')]);

        if ($model->getId()) {
            $fieldset->addField('promo_id', 'hidden', ['name' => 'promo_id']);
        }

        $fieldset->addField(
            'promo_name',
            'text',
            [
                'name' => 'promo_name',
                'label' => __('Title'),
                'title' => __('Title'),
                
            ]
        );

        // $fieldset->addField(
        //     'age',
        //     'text',
        //     [
        //         'name' => 'age',
        //         'label' => __('Age'),
        //         'title' => __('Age'),
        //         'required' => true,
        //     ]
        // );

        // $fieldset->addField(
        //     'phone',
        //     'text',
        //     [
        //         'name' => 'phone',
        //         'label' => __('Phone'),
        //         'title' => __('Phone'),
        //         'required' => true,
        //     ]
        // );

        // $fieldset->addField(
        //     'address',
        //     'textarea',
        //     [
        //         'name' => 'address',
        //         'label' => __('Address'),
        //         'title' => __('Address'),
        //         'required' => true,
        //     ]
        // );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Main');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Main');
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

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
