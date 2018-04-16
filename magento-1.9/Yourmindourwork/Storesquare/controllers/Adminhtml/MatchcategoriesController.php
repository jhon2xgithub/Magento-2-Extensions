<?php 
class Yourmindourwork_Storesquare_Adminhtml_MatchcategoriesController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction(){

		Mage::helper('yourmindourwork_storesquare')->getAllCategories();
		$this->loadLayout();
		$this->_setActiveMenu('storesquare/items_matchcategories');
		$this->_addContent(
			$this->getLayout()->createBlock('adminhtml/template')->setTemplate('storesquare/matchcategories.phtml')
			// $this->getLayout()->createBlock('adminhtml/template', 'storesquare')
		);
		$this->renderLayout();

	
	}   

}