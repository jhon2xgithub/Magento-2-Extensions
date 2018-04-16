<?php
namespace Braem\Members\Block\Adminhtml\Team\Edit\Tab;

use Magento\Framework\Data\Form;

class Team extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
        /** @var \Braem\Members\Model\Team $team */
        $team = $this->_coreRegistry->registry('braem_members_team');
        $teamDefault = $this->_coreRegistry->registry('braem_members_team_default');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('team_');
        $form->setFieldNameSuffix('team');

        $form->setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'Braem\Members\Block\Adminhtml\Form\Renderer\Fieldset\Element',
                $this->getNameInLayout() . '_fieldset_element_renderer'
            )
        );

        $form->setDataObject($team);
        $form->setDataObjectDefault($teamDefault);



        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Team Information'),
                'class'  => 'fieldset-wide'
            ]
        );
        $fieldset->addField('name', 'text', [
            'label'    => __('Name'),
            'name'     => 'name',
            'value'    => $team->getName(),
            'required' => true,
        ]);


        if ($team->getId()) {
            $form->addField(
                'entity_id',
                'hidden',
                ['name' => 'entity_id']
            );
        }
        if ($team->getId()) {
            $form->addField(
                'store_id',
                'hidden',
                ['name' => 'store_id']
            );
        }

        $teamData = $this->_session->getData('braem_members_team_data', true);
        if ($teamData) {
            $team->addData($teamData);
        } else {
            if (!$team->getId()) {
                $team->addData($team->getDefaultValues());
            }
        }
        $form->addValues($team->getData());
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
        return __('Team');
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
