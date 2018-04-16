<?php 
class Yourmindourwork_Storesquare_Adminhtml_CategorysaveController extends Mage_Adminhtml_Controller_Action
{
	public function saveAction()
	{	
		// this avoid header sernt errors.
		$this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($json_encode));

		foreach ($_POST['STORESQUARE_CATEGORY'] as $key => $value) {
			$data = explode(',', $value);
			$_POST['STORESQUARE_CATEGORY_ID'][$key] = $data[0];
			$_POST['STORESQUARE_NAME'][$key] = $data[1];
		}

		unset($_POST['STORESQUARE_CATEGORY']);

		$resource = Mage::getSingleton('core/resource');
		$write = $resource->getConnection('core_write');
		$table = $resource->getTableName('storesquare_tb');

		$size = count($_POST['STORESQUARE_CATEGORY_ID']);

		try {
			$i = 0;
			while ($i < $size) {
			
				$STORESQUARE_CATEGORY_ID= $_POST['STORESQUARE_CATEGORY_ID'][$i];			

				$MAGENTO_CATEGORY_ID = $_POST['MAGENTO_CATEGORY_ID'][$i];

				$write->update(
				    $table,
				    ['MAGENTO_CATEGORY_ID' => trim($MAGENTO_CATEGORY_ID)],
				    ['STORESQUARE_CATEGORY_ID 	= ?' => trim($STORESQUARE_CATEGORY_ID)]
				);
				
				// variables
				$childCategoryName = isset($_POST['STORESQUARE_NAME'][$i])? trim($_POST['STORESQUARE_NAME'][$i]): '';				
				
				$parentCategoryId = isset($MAGENTO_CATEGORY_ID)? trim($MAGENTO_CATEGORY_ID): '';	

				$getCategoryParentIdByName = $this->getCategoryParentIdByName($childCategoryName);   
				$catname = $getCategoryParentIdByName->getName();
						          
				$checksIfCategoryExistsByParentId = $this->checksIfCategoryExistsByParentId( $parentCategoryId,$childCategoryName);

				if (null == $checksIfCategoryExistsByParentId->getId()) {
			
				    if(strlen($childCategoryName) > 0 ){				   

			    		if(strlen($catname) > 0){
				    		$getCategoryParentIdByName = $this->getCategoryParentIdByName($childCategoryName);    	
				    		$this->deleteCategory($getCategoryParentIdByName->getId());
			    		}
				    	
				    	$this->createCategory($parentCategoryId, $childCategoryName);
				    	Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Category Matched.'));				    	
				    } 		    	
				}  		
			
				++$i;
			}
			
			$this->_redirect('*/adminhtml_matchcategories/');
			return;
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/adminhtml_matchcategories/');
			return;
		}		
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
	    $categories = $db_read->fetchCol("SELECT entity_id FROM " . $resource->getTableName("catalog_category_entity") . " WHERE entity_id=$id");
	    foreach ($categories as $category_id) {
	        try {
	            Mage::getModel("catalog/category")->load($category_id)->delete();
	        } catch (Exception $e) {
	            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
	        }
	    }
	}

	public function createCategory($parentId, $name){
	    try {
	        $category = Mage::getModel('catalog/category');
	        $category->setName($name);
	        $category->setUrlKey($name);
	        $category->setIsActive(1); // to make active
	        $category->setDisplayMode('PRODUCTS');
	        $category->setIsAnchor(1); // This is for active anchor
	        $category->setStoreId(Mage::app()->getStore()->getId());
	        $parentCategory = Mage::getModel('catalog/category')->load($parentId);
	        $category->setPath($parentCategory->getPath());
	        $category->save(); 
	    } catch (Exception $e) {
	      	Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
	    }       
	 
	}



}	