<?php
namespace Braem\Members\Block\Adminhtml\Region\Edit\Tab;

use Magento\Framework\Data\Form;

class Region extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    protected $_logger;
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
        \Psr\Log\LoggerInterface $logger, //log injection

        array $data = []
    ) {
        $this->_logger = $logger;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Braem\Members\Model\Region $region */
        $region = $this->_coreRegistry->registry('braem_members_region');
        $regionDefault = $this->_coreRegistry->registry('braem_members_region_default');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('region_');
        $form->setFieldNameSuffix('region');

       // var_dump($region->getData());



        $form->setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'Braem\Members\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout() . '_fieldset_element_renderer'
            )
        );

        $form->setDataObject($region);
        $form->setDataObjectDefault($regionDefault);



        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Region Information'),
                'class'  => 'fieldset-wide'
            ]
        );



//        $region->getResource()->loadAllAttributes($region);
//        $attributes = $region->getResource()->getAttributesByCode();

  //      $this->_setFieldset($attributes, $fieldset);

        $fieldset->addField('name', 'text', [
            'label'    => __('Name'),
            'name'     => 'name',
            'value'    => $region->getName(),
            'required' => true,
        ]);

//        $fieldset->addField('url_key', 'text', [
//            'label'    => __('Url key'),
//            'name'     => 'url_key',
//            'value'    => $region->getUrlKey(),
//            'required' => true,
//        ]);

        if ($region->getId()) {
            $form->addField(
                'entity_id',
                'hidden',
                ['name' => 'entity_id']
            );
        }
        if ($region->getId()) {
        $form->addField(
                'store_id',
                'hidden',
                ['name' => 'store_id']
            );
        }

        $regionData = $this->_session->getData('braem_members_region_data', true);
        if ($regionData) {
            $region->addData($regionData);
        } else {
            if (!$region->getId()) {
                $region->addData($region->getDefaultValues());
            }
        }
        $form->addValues($region->getData());
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
        return __('Region');
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

    /**
     * @return void
     */
    protected function _prepareLayout()
    {

        \Magento\Framework\Data\Form::setElementRenderer(
            $this->getLayout()->createBlock(
                'Magento\Backend\Block\Widget\Form\Renderer\Element',
                $this->getNameInLayout() . '_element'
            )
        );
        \Magento\Framework\Data\Form::setFieldsetRenderer(
            $this->getLayout()->createBlock(
                'Magento\Backend\Block\Widget\Form\Renderer\Fieldset',
                $this->getNameInLayout() . '_fieldset'
            )
        );
        \Magento\Framework\Data\Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'Braem\Members\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout() . '_fieldset_element'
            )
        );
    }
}
