<?php
class Yourmindourwork_Storesquare_Helper_Data extends Mage_Core_Helper_Abstract
{
    
    
    public function getAllCategories(){

    	$_helper = Mage::helper('catalog/category');
    	$_categories = $_helper->getStoreCategories();

    	// var_dump($_categories);
    	if (count($_categories) > 0){
    	    foreach($_categories as $_category){
    	        $_category = Mage::getModel('catalog/category')->load($_category->getId());
    	        $_subcategories = $_category->getChildrenCategories();
    	        if (count($_subcategories) > 0){
    	            echo $_category->getName();
    	            echo $_category->getId();      
    	            foreach($_subcategories as $_subcategory){
    	                 echo $_subcategory->getName();
    	                 echo $_subcategory->getId();
    	            }
    	        }
    	    }
    	}
    }
}	