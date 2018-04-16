<?php 

namespace Shop\Records\Controller\Post;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Translate\Inline\StateInterface;
use Psr\Log\LoggerInterface;

class Index extends \Magento\Framework\App\Action\Action{
    /**
    * Post user question
    *
    * @return void
    * @throws \Exception
    */    

    protected $inlineTranslation;
    protected $transportBuilder;
    protected $_logLoggerInterface;
    protected $scopeConfig;

    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        LoggerInterface $logLoggerInterface,       
        array $data = [])
    {
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->_logLoggerInterface = $logLoggerInterface;
    
        parent::__construct($context,$data);
    }

    public function execute(){

        $toEmail = 'junsayjohn4@gmail.com';//$this->_helperData->getConfig('trans_email/ident_support/email');
        $sender = [
         'name' => 'john',
         'email' => 'junsayjohn32@gmail.com',
        ];

        $this->inlineTranslation->suspend();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $transport = $this->transportBuilder
           ->setTemplateIdentifier('abandonedcart_general_template1')
           ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
           ->setTemplateVars(['data' => ['subject'=>'Email test']])
           ->setFrom($sender)
           ->addTo($toEmail)
           ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
     
        return;
       
    }  
}