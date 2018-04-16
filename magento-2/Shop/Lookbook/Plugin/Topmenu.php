<?php
/**
 * Lookbook
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Lookbook.com license that is
 * available through the world-wide-web at this URL:
 * https://www.Lookbook.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Lookbook
 * @package     Lookbook_Lookbook
 * @copyright   Copyright (c) 2016 Lookbook (http://www.Lookbook.com/)
 * @license     https://www.Lookbook.com/LICENSE.txt
 */
namespace Shop\Lookbook\Plugin;

class Topmenu
{
    public $helper;

    public function __construct(
        \Shop\Lookbook\Helper\Data $helper
    )
	{
        $this->helper = $helper;
    }

    public function afterGetHtml(\Magento\Theme\Block\Html\Topmenu $topmenu, $html)
    {
    	if ($this->helper->getToplinks() && $this->helper->getEnabled()){
			$LookbookMenu = $topmenu;
			$LookbookMenu->getBaseUrl();
			$LookbookName = $this->helper->getName()?: __('Lookbook');

			$html .= "<li class=\"level0 level-top ui-menu-item\">";
			$html .= "<a href=\"" . $this->helper->getLookbookUrl('')
				. "\" class=\"level-top ui-corner-all\" aria-haspopup=\"true\" tabindex=\"-1\" role=\"menuitem\">
			<span class=\"ui-menu-icon ui-icon ui-icon-carat-1-e\"></span><span>"
				. $LookbookName . "</span></a>";
			$html .= "</li>";

		}
		return $html;
    }
}
