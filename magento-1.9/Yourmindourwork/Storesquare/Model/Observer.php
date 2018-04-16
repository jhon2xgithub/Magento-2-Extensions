<?php 

class Yourmindourwork_Storesquare_Model_Observer {

    public function exportProductToJson(){
       	
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
    }


}