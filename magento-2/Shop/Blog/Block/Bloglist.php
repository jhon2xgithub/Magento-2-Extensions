<?php
namespace Shop\Blog\Block;

class Bloglist extends \Magento\Framework\View\Element\Template
{   

	/**
     * @var \Shop\Blog\Helper\Data
     */
    protected $_resource;
    protected $_helper;
    protected $_blogFactory;
    protected $_blogCollectionFactory;
   
    /**
     * @param \Shop\Blog\Helper\Data $helper
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Backend\Block\Template\Context $context,   
        \Shop\Blog\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        \Shop\Blog\Model\BlogFactory $blogFactory,
        \Shop\Blog\Model\Resource\Blog\CollectionFactory $blogCollectionFactory,
        array $data = [])
    {
        $this->_resource = $resource;
        $this->_helper = $helper;
        $this->_blogFactory = $blogFactory;
        $this->_blogCollectionFactory = $blogCollectionFactory;

        parent::__construct($context, $data);        
    }

    /**
     * For enable module at system configuration
     *
     */
    public function getEnabled(){
        return $this->_helper->getEnabled();
    }

    /**
     * Retrieve url
     *
     */
    protected function getFeedUrl(){
        return $this->_helper->getFeedUrl(); 
    }

    /**
     * Retrieve blog collection
     *
     */
    public function getCollection(){    
        return $posts = $this->_blogCollectionFactory
            ->create()
            // ->setPageSize($this->_helper->getLimitPost())
            ->setPageSize(0)
            ->getData();
    }

    /**
     * Retrieve write connection instance
     *
     */
    protected function _getConnection(){
        $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');;
        $this->_connection= $this->_resources->getConnection();
        return $this->_connection;
    }

    /**
     * checks if data already exists
     *
     */
    protected function validatePost($attribute){      
        
        $connection = $this->_getConnection();
        $select = $connection->select()->from(
            ['m'=> $this->_resource->getTableName('shop_blog_post')],
            ['title']
            )->where(
                'm.title = ?',
                $attribute
            );
        return $selectData[] = $connection->fetchAll($select);
    }

    /**
     * get updated data from rsfeed
     *
     */
    public function rssfeed(){

        $feedUrl = $this->_helper->getFeedUrl();
       
        $rawFeed = file_get_contents($feedUrl); 
        $feed = new \SimpleXmlElement($rawFeed);
        $posts = $feed->channel->item;

        foreach ($posts as $bloginfo){   
            $title = $bloginfo->title;
            if(!$this->validatePost($title)){
                $data = [
                    'title'=>$title,
                    'link'=>$bloginfo->link,
                    'comments'=>$bloginfo->comments,
                    'pubDate'=>$bloginfo->pubDate,
                    'description'=>$bloginfo->description,
                    'post-id' => $bloginfo->{'post-id'},
                    'status' => 1
                ];                
               
                $model = $this->_blogFactory
                ->create()
                ->setData($data)            
                ->save();      
            }
        }
    
    }

} 