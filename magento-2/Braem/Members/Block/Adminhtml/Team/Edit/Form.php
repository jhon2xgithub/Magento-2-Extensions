<?php
namespace Braem\Members\Block\Adminhtml\Team\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

//    /**
//     * @return void
//     */
//    protected function _prepareLayout()
//    {
//
//        \Magento\Framework\Data\Form::setElementRenderer(
//            $this->getLayout()->createBlock(
//                'Magento\Backend\Block\Widget\Form\Renderer\Element',
//                $this->getNameInLayout() . '_element'
//            )
//        );
//        \Magento\Framework\Data\Form::setFieldsetRenderer(
//            $this->getLayout()->createBlock(
//                'Magento\Backend\Block\Widget\Form\Renderer\Fieldset',
//                $this->getNameInLayout() . '_fieldset'
//            )
//        );
//        \Magento\Framework\Data\Form::setFieldsetElementRenderer(
//            $this->getLayout()->createBlock(
//                'Braem\Members\Block\Adminhtml\Form\Renderer\Fieldset\Element',
//                $this->getNameInLayout() . '_fieldset_element'
//            )
//        );
//    }
}
