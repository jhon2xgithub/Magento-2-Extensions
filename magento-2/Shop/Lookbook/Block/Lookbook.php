<?php
namespace Shop\Lookbook\Block;

class Lookbook extends \Magento\Framework\View\Element\Template
{   

	/**
     * @var \Crossbrowser\Newsletter\Helper\Data
     */
    protected $_helper;
    protected $_lookbookFactory;
    protected $_productRepository;
    protected $_filesystem ;
    protected $_imageFactory;
    
    protected $_coreRegistry = null;   

    /**
     * @param \Crossbrowser\Newsletter\Helper\Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Shop\Lookbook\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Shop\Lookbook\Model\LookbookFactory $lookbookFactory,
        \Magento\Framework\Filesystem $filesystem,         
        \Magento\Framework\Image\AdapterFactory $imageFactory,
 
        array $data = [])
    {
        $this->_productRepository = $productRepository;
        $this->_helper = $helper;
        $this->_lookbookFactory = $lookbookFactory;
        $this->_filesystem = $filesystem;               
        $this->_imageFactory = $imageFactory; 
        // $this->_coreRegistry = $registry;        
        parent::__construct($context, $data);

        
    }

    public function getTest()
    {
        // return $postCollection = $this->_coreRegistry->registry('skus');
    }

    public function getSkus()
    {
        // will return 'skus'
        // return $this->_coreRegistry->registry('skus');
        // return $data = $this->getRequest()->getParams();
        // echo $this->_coreRegistry->registry('test_var');  
    }

 	public function getCollection()
    {   
        // $objectManager =   \Magento\Framework\App\ObjectManager::getInstance();
        // $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        // return $result = $connection->fetchAll("SELECT * FROM `lookbook` WHERE status = 1");
    	
		return $this->_lookbookFactory->create()->getCollection();
	}

    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }
    
    public function getProductBySku($sku)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->get('Magento\Catalog\Model\Product');
        if($product->getIdBySku($sku)) {
            return $this->_productRepository->get($sku);
        } else {
            return null;
        }

    }

    // public function getUrl($route = '', $params = [])
    // {
    //     return $this->_urlBuilder->getUrl($route, $params);
    // }

    // public function getUrl($param)
    // {
    //     return $this->getUrl('shop/controller_name/action_class_name', ['param' => $param]);
    // }


    // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    // $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION'); 
    // $data = $connection->fetchAll("SELECT * FROM `lookbook_image`");

    // if(count($data[0]['name']) > 0 && !empty($data[0]['name'])){ ;
    //     $saveimage = $data[0]['name'];
    //     if(file_exists('pub/media/lookbook/'.$saveimage)){
    //         $data = ['75X75', '200X100', '1200X600'];
    //         $path  = 'pub/media/lookbook/';
    //        // $this->imageResizer($saveimage, $path, 99, $data);  
    //     }       
    // }



    // public function imageResizer($file_name, $path, $quality, $data=[]){
    //     foreach ($data as $key=>$size) {
               
    //         $dir = $path.$size.'/';
    
    //         if (!file_exists($dir)) mkdir($dir, 0755, true);  
    //         $filepath = $path.$file_name;
    //         $subPath = $dir.$file_name;    
    //         $lwitdth = explode('X',$size);                 
    //         $maxwidth = $lwitdth[0];  
    //         $maxheight = $lwitdth[1];
                   
    //         $results = $this->createqqfile($filepath, $subPath, $maxwidth, $maxheight, $quality);        
    //     } 
    // }

    // function createqqfile($filepath, $subPath, $maxwidth, $maxheight, $quality=75){   
    //     $created=false;
    //     $file_name  = pathinfo($filepath);  
    //     $format = $file_name['extension'];  

    //     // Get new dimensions
    //     $newW   = $maxwidth;    
    //     $newH   = $maxheight;
    
    //     // Resample
    //     $qqfile = imagecreatetruecolor($newW, $newH);
    //     $image = imagecreatefromstring(file_get_contents($filepath));
    //     list($width_orig, $height_orig) = getimagesize($filepath);
    //     imagecopyresampled($qqfile, $image, 0, 0, 0, 0, $newW, $newH, $width_orig, $height_orig);

    //     // Output
    //     switch (strtolower($format)) {
    //         case 'png':
    //         imagepng($qqfile, $subPath, 9);
    //         $created=true;
    //         break;

    //         case 'gif':
    //         imagegif($qqfile, $subPath);
    //         $created=true;
    //         break;
                
    //         default:
    //         imagejpeg($qqfile, $subPath, $quality);
    //         $created=true;
    //         break;
    //     }
        
    //     imagedestroy($image);
    //     imagedestroy($qqfile);
    //     return $created;    
    // }
   
   
    
}