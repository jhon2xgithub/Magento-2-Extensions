<?php
namespace Shop\Lookbook\Helper;
 
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var array
     */
    protected $_systemConfiguration;
    protected $_directorylist;
    protected $_filesystem;
    protected $_imageFactory;

    public $_mediaUrl;
    protected $productFactory;
	protected $_storeManager;
	protected $_productRepository;
	       
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $_directorylist,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Filesystem $filesystem,         
        \Magento\Framework\Image\AdapterFactory $imageFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
       \Magento\Catalog\Model\ProductRepository $productRepository		
           
    ) {
	
		$this->_productRepository = $productRepository;
		$this->_directorylist=$_directorylist;
        $this->_imageFactory = $imageFactory;      
        $this->productFactory = $productFactory;    
        $this->_filesystem = $filesystem;               
        $this->_imageFactory = $imageFactory;     
		$this->_storeManager = $storeManager;
        parent::__construct($context);
        $this->_systemConfiguration = $this->scopeConfig->getValue('shop_lookbook', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
   		
		$this->_mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
	

    public function getSkusCustomer(){
        return 'asff';
    }
	
	public function lookbookbaseurl(){
		
		return $this->_storeManager->getStore()->getBaseUrl();
	}	
	
	public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }
    
    public function getProductBySku($sku)
    {
        return $this->_productRepository->get($sku);
    }


	public function getEnabled()
	{
		return $this->_systemConfiguration['general']['enable'];
	}

    public function getMinSlide()
    {
        return $this->_systemConfiguration['general']['min_slide'];
    }

    public function getMaxSlide()
    {
        return $this->_systemConfiguration['general']['max_slide'];
    }
    
    public function getMinImageWidth()
    {
        return $this->_systemConfiguration['general']['min_image_width'];
    }

    public function getMinImageHeight()
    {
        return $this->_systemConfiguration['general']['min_image_height'];
    }

    public function getMaxImageWidth(){

        return $this->_systemConfiguration['general']['max_image_width'];
    }

    public function getMaxImageHeight(){

        return $this->_systemConfiguration['general']['max_image_height'];
    }

    public function getEffects(){

        return $this->_systemConfiguration['general']['effects'];
    }


    public function getNavigation(){
       
        return $this->_systemConfiguration['general']['navigation'];
    }

    public function getNavigationHover(){

        return $this->_systemConfiguration['general']['navigation_hover'];
    }

    public function getThumbnails(){

        return $this->_systemConfiguration['general']['thumbnails'];
    }

    public function getPause(){
        
        return $this->_systemConfiguration['general']['pause'];
    }

    public function getTransitionDuration(){

        return $this->_systemConfiguration['general']['transition_duration'];
    }
 
    public function getMaxUploadFilesize(){

        return $this->_systemConfiguration['general']['max_upload_filesize'];
    }

    public function  getAllowedExtensions(){

        return $this->_systemConfiguration['general']['allowed_extensions'];
    }
    
    public function getResizedUrl($imgUrl){

        $imgPath = $this->splitImageValue($imgUrl, "path");
        $imgName = $this->splitImageValue($imgUrl, "name");
        
        $imgUrl = str_replace(DIRECTORY_SEPARATOR, "/", $imgPath);
        return $this->_mediaUrl. $imgUrl . "/" . $imgName;        
        
    }

    public function getToplinks()
    {
        return $this->_systemConfiguration['general']['toplinks'];
    }    

    public function getName()
    {
        return $this->_systemConfiguration['general']['name'];
    }    

    public function getLookbookUrl($code)
    {
        $lookbookUrl = $this->_systemConfiguration['general']['url_prefix'] ?: self::DEFAULT_URL_PREFIX;
        return $this->_getUrl($lookbookUrl . '/' . $code);
    }

    /**
     * Splits images Path and Name
     *
     * Path=lookbook/
     * Name=example.jpg
     *
     * @param string $imageValue
     * @param string $attr
     * @return string
     */
    public function splitImageValue($imageValue,$attr="name"){
        $imArray=explode("/",$imageValue);
 
        $name=$imArray[count($imArray)-1];
        $path=implode("/",array_diff($imArray,array($name)));
        if($attr=="path"){
            return $path;
        }
        else
            return $name; 
    }

     /**
     * Splits images Path and Name
     *
     * img_path=lookbook/example.jpg
     *
     * @param string $img_path
     * @return array('width'=>$width, 'height'=>$height)
     */
    public function getImageDimensions($img_path){
   	
		//create image factory...
			$imageObj = $this->_imageFactory->create();
			$imageObj->open($img_path);
			$width = $imageObj->getOriginalWidth();         
			$height = $imageObj->getOriginalHeight();
			$result = array('width'=>$width, 'height'=>$height);
        return $result;
    }

    public function getProductUrls($hotspots_json){
        if ($hotspots_json=='') return '';
        $decoded_array = $hotspots_json;
        return $decoded_array;
    } 


    // pass imagename, width and height
    public function resize($image, $width = null, $height = null)
    {
        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('lookbook/').$image;

        // $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('lookbook/resized/'.$width.'/').$image;         
        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('lookbook/').$image;         
            
        //create image factory...
        $imageResize = $this->_imageFactory->create();         
        $imageResize->open($absolutePath);
        $imageResize->constrainOnly(TRUE);         
        $imageResize->keepTransparency(TRUE);         
        $imageResize->keepFrame(FALSE);         
        $imageResize->keepAspectRatio(TRUE);         
        $imageResize->resize($width,$height);  
        //destination folder                
        $destination = $imageResized ;    
        //save image      
        $imageResize->save($destination);         

        // $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'lookbook/resized/'.$width.'/'.$image;
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'lookbook/';
                
        return $resizedURL;
    } 
    
    
    public function resizeImage($image,$thumb_width= null,$thumb_height= null,$Quality)
    {
        // $SrcImage = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('lookbook/').$image;
        // $DestImage = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('lookbook/resized/').$image;   
        $SrcImage = "C:\xampp\htdocs\shop_2.0.2\pub\media\lookbook\{$image}";
        $DestImage = "C:\xampp\htdocs\shop_2.0.2\pub\media\lookbook\resized\{$image}";

        list($width,$height,$type) = getimagesize($SrcImage);
        switch(strtolower(image_type_to_mime_type($type)))
        {
            case 'image/gif':
                $NewImage = imagecreatefromgif($SrcImage);
                break;
            case 'image/png':
                $NewImage = imagecreatefrompng($SrcImage);
                break;
            case 'image/jpeg':
                $NewImage = imagecreatefromjpeg($SrcImage);
                break;
            default:
                return false;
                break;
        }
        $original_aspect = $width / $height;
        $positionwidth = 0;
        $positionheight = 0;
        if($original_aspect > 1)    {
            $new_width = $thumb_width;
            $new_height = $new_width/$original_aspect;
            while($new_height > $thumb_height) {
                $new_height = $new_height - 0.001111;
                $new_width  = $new_height * $original_aspect;
                while($new_width > $thumb_width) {
                    $new_width = $new_width - 0.001111;
                    $new_height = $new_width/$original_aspect;
                }

            }
        } else {
            $new_height = $thumb_height;
            $new_width = $new_height/$original_aspect;
            while($new_width > $thumb_width) {
                $new_width = $new_width - 0.001111;
                $new_height = $new_width/$original_aspect;
                while($new_height > $thumb_height) {
                    $new_height = $new_height - 0.001111;
                    $new_width  = $new_height * $original_aspect;
                }
            }
        }
        if($width < $new_width && $height < $new_height){
            $new_width = $width;
            $new_height = $height;
            $positionwidth = ($thumb_width - $new_width) / 2;
            $positionheight = ($thumb_height - $new_height) / 2;
        }elseif($width < $new_width && $height > $new_height){
            $new_width = $width;
            $positionwidth = ($thumb_width - $new_width) / 2;
            $positionheight = 0;
        }elseif($width > $new_width && $height < $new_height){
            $new_height = $height;
            $positionwidth = 0;
            $positionheight = ($thumb_height - $new_height) / 2;
        } elseif($width > $new_width && $height > $new_height){
            if($new_width < $thumb_width) {
                $positionwidth = ($thumb_width - $new_width) / 2;
            } elseif($new_height < $thumb_height) {
                $positionheight = ($thumb_height - $new_height) / 2;
            }
        }
        $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
        /********************* FOR WHITE BACKGROUND  *************************/
            //$white = imagecolorallocate($thumb, 255,255,255);
            //imagefill($thumb, 0, 0, $white);
        if(imagecopyresampled($thumb, $NewImage,$positionwidth, $positionheight,0, 0, $new_width, $new_height, $width, $height)) {
            if(imagejpeg($thumb,$DestImage,$Quality)) {
                imagedestroy($thumb);
                return true;
            }
        }
    }

       
}   