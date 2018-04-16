<?php
/**
 * Blog
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Blog.com license that is
 * available through the world-wide-web at this URL:
 * https://www.Blog.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Blog
 * @package     Blog_Blog
 * @copyright   Copyright (c) 2016 Blog (http://www.Blog.com/)
 * @license     https://www.Blog.com/LICENSE.txt
 */
namespace Shop\Blog\Plugin;

class Topmenu
{
    public $helper;

    public function __construct(
        \Shop\Blog\Helper\Data $helper
    )
	{
        $this->helper = $helper;
    }

    public function afterGetHtml(\Magento\Theme\Block\Html\Topmenu $topmenu, $html)
    {
    	if ($this->helper->getToplinks() && $this->helper->getEnabled()){
			$blogMenu = $topmenu;
			$blogMenu->getBaseUrl();
			$blogName = $this->helper->getName()?: __('Blog');

			$html .= "<li class=\"level0 level-top ui-menu-item\">";
			$html .= "<a href=\"" . $this->helper->getBlogUrl('')
				. "\" class=\"level-top ui-corner-all\" aria-haspopup=\"true\" tabindex=\"-1\" role=\"menuitem\">
			<span class=\"ui-menu-icon ui-icon ui-icon-carat-1-e\"></span><span>"
				. $blogName . "</span></a>";
			$html .= "</li>";

		}
		return $html;
    }
}
