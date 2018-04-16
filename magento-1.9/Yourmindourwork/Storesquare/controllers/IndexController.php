<?php
 
class Yourmindourwork_Storesquare_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {	



        $json_array = [];
        $productCollection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');     

        foreach ($productCollection as $product){        

            $json_array[] = $product->getData();
        }

        $filename = "export_products_" . date('Y-m-d H:i:s') . ".json";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/json");
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile("$filename");
        
        echo json_encode($json_array, JSON_PRETTY_PRINT);  

        //database read adapter 
        $read = Mage::getSingleton('core/resource')->getConnection('core_read'); 

        $customers = $read->query("update cron_schedule set is_exported =1 where job_code= 'yourmindourwork_storesquare' and finished_at = {$NOW()}");

        
        exit;


        // $id = 1; // $this->getRequest()->getParam('id');

        // if($id) {
        //     $_category = Mage::getModel('catalog/category')->load($id);
        //     $product = Mage::getModel('catalog/product');

        //     //load the category's products as a collection
        //     $_productCollection = $product->getCollection()
        //         ->addAttributeToSelect('*')
        //         ->addCategoryFilter($_category)
        //         ->load();

        //     // build an array for conversion
        //     $json_products = array();
        //     foreach ($_productCollection as $_product) {
        //         $_product->getData();
        //         $json_products[] = array(
        //                     'name' => ''.$helper->htmlEscape($_product->getName()).'',
        //                     'url' => ''.$_product->getProductUrl().'',
        //                     'description' => ''.nl2br($_product->getShortDescription()).'',
        //                     'price' => ''.$_product->getFormatedPrice().'');
        //     }

        //     $data = json_encode($items);

        //     echo $data;
        // } 
    	// $json_array = [];
    	// $productCollection = Mage::getModel('cron/schedule')->getCollection();   	

    	// foreach ($productCollection as $product){   	 

    	//     $json_array[] = $product->getData();
    	// }

     //    echo '<pre>';
     //    print_r($json_array);

    	// $filename = "export_products_" . date('Y-m-d H:i:s') . ".json";

    	// header("Content-Disposition: attachment; filename=\"$filename\"");
    	// header("Content-Type: application/json");
    	// header("Pragma: no-cache");
    	// header("Expires: 0");
    	// readfile("$filename");
    	
    	// echo json_encode($json_array, JSON_PRETTY_PRINT);  

    	// exit;

        // Mage::helper('yourmindourwork_storesquare')->getAllCategories();

        // $str = "Kleding & Schoenen";

        // str_replace(' ', '-', strtolower($name));
        // $parentId = '4';
        //  try{
        //     $category = Mage::getModel('catalog/category');
        //     $category->setName('Kleding & Schoenen');
        //     $category->setUrlKey('kleding-&-schoenen');
        //     $category->setIsActive(1);
        //     $category->setDisplayMode('PRODUCTS');
        //     $category->setIsAnchor(1); //for active anchor
        //     $category->setStoreId(Mage::app()->getStore()->getId());
        //     $parentCategory = Mage::getModel('catalog/category')->load($parentId);
        //     $category->setPath($parentCategory->getPath());
        //     $category->save();
        // } catch(Exception $e) {
        //     print_r($e);
        // }
        // $id = 14;

       
        // $categoryName = "Cadeaus & Inspiratie";
        // $parentId = 5;

        // $_category = Mage::getResourceModel('catalog/category_collection')
        //     ->addFieldToFilter('name', $categoryName)
        //     ->getFirstItem();
        // $categoryId = $_category->getId();  
        // $this->deleteCategory($categoryId);    
        // $this->createCategory($parentId, $categoryName);


        // $this->deleteCategory(308);


        // $childCategoryName = 'Kleding & Schoenen';
        // $getCategoryParentIdByName = $this->getCategoryParentIdByName($childCategoryName);   
        // echo $getCategoryParentIdByName->getId(). ' - ' .$getCategoryParentIdByName->getParentId(). ' - ' .$getCategoryParentIdByName->getName();

        // echo '<br/>';  

        // $parentCategoryId = 5;
        // $childCategoryName = 'Speelgoed & Hobby';            
        // $checksIfCategoryExistsByParentId = $this->checksIfCategoryExistsByParentId( $parentCategoryId,$childCategoryName);

        // if (null !== $checksIfCategoryExistsByParentId->getId()) {
        //     echo "Found Category: " . $checksIfCategoryExistsByParentId->getData('name');
        // } else {
        //     echo "Category not found";
        //     //adds 
        //     //and delete 
        // }    
       
       
  
      
    }

    // checks the parent id of the category by name
    public function getCategoryParentIdByName($childCategoryName){
        $parentCategory = Mage::getModel('catalog/category')->load($parentCategoryId);
        return $childCategory = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToFilter('is_active', true)            
            ->addAttributeToFilter('name', $childCategoryName)
            ->getFirstItem();
    }

    // check if category exists in specific parent forlder by id
    public function checksIfCategoryExistsByParentId( $parentCategoryId,$childCategoryName){
        $parentCategory = Mage::getModel('catalog/category')->load($parentCategoryId);
        return $childCategory = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToFilter('is_active', true)
            ->addIdFilter($parentCategory->getChildren())
            ->addAttributeToFilter('name', $childCategoryName)
            ->getFirstItem();       
    }       
    
    public function deleteCategory($id){
        $resource = Mage::getSingleton('core/resource');
        $db_read = $resource->getConnection('core_read');
        Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
        $categories = $db_read->fetchCol("SELECT entity_id FROM " . $resource->getTableName("catalog_category_entity") . " WHERE entity_id=$id ORDER BY entity_id DESC");
        foreach ($categories as $category_id) {
            try {
                Mage::getModel("catalog/category")->load($category_id)->delete();
            } catch (Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
    }

    // public function deleteCategory($categoryName){
    //     $_category = Mage::getResourceModel('catalog/category_collection')
    //         ->addFieldToFilter('name', $categoryName)
    //         ->getFirstItem();
    //     $categoryId = $_category->getId(); 

    //     try{
    //         Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
    //         Mage::getModel("catalog/category")->load($categoryId)->delete();
            
    //     }catch(Exception $e){
           
    //     }
    // }


    public function createCategory($parentId, $name){
        try {
            $category = Mage::getModel('catalog/category');
            $category->setName($name);
            $category->setUrlKey(str_replace(' ', '-', strtolower($name)));
            $category->setIsActive(1); // to make active
            $category->setDisplayMode('PRODUCTS');
            $category->setIsAnchor(1); // This is for active anchor
            $category->setStoreId(Mage::app()->getStore()->getId());
            $parentCategory = Mage::getModel('catalog/category')->load($parentId);
            $category->setPath($parentCategory->getPath());
            $category->save(); 
        } catch (Exception $e) {
          
        }       
     
    }

 
   
}