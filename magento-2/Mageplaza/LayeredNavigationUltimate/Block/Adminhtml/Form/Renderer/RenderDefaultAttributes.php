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
 * @package     Mageplaza_LayeredNavigationUltimate
 * @copyright   Copyright (c) 2017 Mageplaza (http://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

namespace Mageplaza\LayeredNavigationUltimate\Block\Adminhtml\Form\Renderer;

/**
 * Class RenderDefaultAttributes
 * @package Mageplaza\LayeredNavigationUltimate\Block\Adminhtml\Form\Renderer
 */
class RenderDefaultAttributes extends \Magento\Backend\Block\Widget\Form\Renderer\Fieldset\Element implements \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
	/** @var string Template */
	protected $_template = 'Mageplaza_LayeredNavigationUltimate::form/renderer/default_attributes.phtml';

	/**
	 * @var \Mageplaza\LayeredNavigationUltimate\Helper\Data
	 */
	public $helperData;

	/**
	 * RenderDefaultAttributes constructor.
	 * @param \Mageplaza\LayeredNavigationUltimate\Helper\Data $helperData
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param array $data
	 */
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Mageplaza\LayeredNavigationUltimate\Helper\Data $helperData,
		array $data = []
	)
	{
		$this->helperData = $helperData;

		parent::__construct($context, $data);
	}

	/**
	 * render custom form element
	 * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
	 * @return string
	 */
	public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
	{
		$this->_element = $element;
		$html           = $this->toHtml();

		return $html;
	}

	/**
	 * get attributes  array
	 * @return array
	 */
	public function getAllAttributes()
	{
		return $this->helperData->getAllAttributes();
	}

	/**
	 * get attribute options
	 * @param $attCode
	 * @return array
	 */
	public function getAttributeOptions($attCode)
	{
		return $this->helperData->getAttributeOptions($attCode);
	}

	/**
	 * get products page by id
	 * @param $id
	 * @return \Mageplaza\LayeredNavigationUltimate\Model\ProductsPage | null
	 */
	public function getPageById($id)
	{
		return $this->helperData->getPageById($id);
	}
}
