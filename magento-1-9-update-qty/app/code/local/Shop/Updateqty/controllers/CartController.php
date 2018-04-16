		<?php

/**
 *  Checkout_CartController
 */
require_once Mage::getModuleDir('controllers', 'Mage_Checkout') . DS . 'CartController.php';

class Shop_Updateqty_CartController extends Mage_Checkout_CartController {

    public function updateQtyPostAction()
    {	
    	
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*/');
            return;
        }

        $updateAction = (string)$this->getRequest()->getParam('update_cart_action');

        switch ($updateAction) {
            case 'empty_cart':
                $this->_emptyShoppingCart();
                break;
            case 'update_qty':
                $this->_updateShoppingCart();
                break;
            default:
                $this->_updateShoppingCart();
        }    

        $quote = Mage::getModel('checkout/session')->getQuote();
        $quoteData= $quote->getData();
        $grandTotal=$quoteData['grand_total'];
        $g_total = Mage::helper('checkout')->formatPrice($grandTotal); 

       	echo json_encode(['message'=>'update successfully', 'qty'=>$_POST, 'grand_total'=>$g_total]);
    }

    public function formatRowTotalMiniAction(){

    	echo Mage::helper('checkout')->formatPrice($_POST['row_total']);      
 
    }
      

}