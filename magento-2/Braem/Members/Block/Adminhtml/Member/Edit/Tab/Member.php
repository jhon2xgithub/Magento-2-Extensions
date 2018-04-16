<?php
namespace Braem\Members\Block\Adminhtml\Member\Edit\Tab;

class Member extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Store options
     * 
     * @var \Braem\Members\Model\Member\Source\Store
     */
    protected $_storeOptions;

    protected $_languagesOptions;

    /**
     * constructor
     * 
     * @param \Braem\Members\Model\Member\Source\Store $storeOptions
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Braem\Members\Model\Member\Source\Store $storeOptions,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Config\Model\Config\Source\Locale $languagesOptions,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    )
    {
        $this->_storeOptions = $storeOptions;
        $this->_languagesOptions = $languagesOptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Braem\Members\Model\Member $member */
        $member = $this->_coreRegistry->registry('braem_members_member');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('member_');
        $form->setFieldNameSuffix('member');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Member Information'),
                'class'  => 'fieldset-wide'
            ]
        );
        $fieldset->addType('image', 'Braem\Members\Block\Adminhtml\Member\Helper\Image');
        if ($member->getId()) {
            $fieldset->addField(
                'member_id',
                'hidden',
                ['name' => 'member_id']
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
            'image',
            'image',
            [
                'name'  => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
            ]
        );
        $fieldset->addField(
            'phone',
            'text',
            [
                'name'  => 'phone',
                'label' => __('Phone'),
                'title' => __('Phone'),
            ]
        );
        $fieldset->addField(
            'email',
            'text',
            [
                'name'  => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
            ]
        );
        $fieldset->addField(
            'store',
            'select',
            [
                'name'  => 'store',
                'label' => __('Store'),
                'title' => __('Store'),
                'required' => false,
                'values' => array_merge(['' => ''], $this->_storeOptions->toOptionArray()),
            ]
        );

        $fieldset->addField(
            'languages',
            'multiselect',
            [
                'name'  => 'languages',
                'label' => __('Languages'),
                'title' => __('Languages'),
                'required' => true,
                'values' => $this->_languagesOptions->toOptionArray(),
            ]
        );


        $memberData = $this->_session->getData('braem_members_member_data', true);
        if ($memberData) {
            $member->addData($memberData);
        } else {
            if (!$member->getId()) {
                $member->addData($member->getDefaultValues());
            }
        }
        $form->addValues($member->getData());
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
        return __('Member');
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
