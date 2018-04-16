<?php 
class Yourmindourwork_Storesquare_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction()
	{

		$this->_title($this->__('Yourmindourwork'))->_title($this->__('Manage Cronjob'));
	    $this->loadLayout();
	    $this->_setActiveMenu('storesquare/items');
	    $this->_addContent($this->getLayout()->createBlock('yourmindourwork_storesquare/adminhtml_status_cronjob'));
	    $this->renderLayout();   
	}   

	public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('yourmindourwork_storesquare/adminhtml_status_cronjob_grid')->toHtml()
        );
    }


}