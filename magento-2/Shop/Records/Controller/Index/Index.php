<?php 

namespace Shop\Records\Controller\Index;


class Index extends \Magento\Framework\App\Action\Action
{

	protected $_resultPageFactory;

	/**
	* Recipient email config path
	*/
	// const XML_PATH_EMAIL_RECIPIENT = 'test/email/send_email';
	/**
	* @var \Magento\Framework\Mail\Template\TransportBuilder
	*/
	protected $_transportBuilder;
	 
	/**
	* @var \Magento\Framework\Translate\Inline\StateInterface
	*/
	protected $inlineTranslation;
	 
	/**
	* @var \Magento\Framework\App\Config\ScopeConfigInterface
	*/
	protected $scopeConfig;
	 
	/**
	* @var \Magento\Store\Model\StoreManagerInterface
	*/
	protected $storeManager;
	/**
	* @var \Magento\Framework\Escaper
	*/
	protected $_escaper;
	/**
	* @param \Magento\Framework\App\Action\Context $context
	* @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
	* @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
	* @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	* @param \Magento\Store\Model\StoreManagerInterface $storeManager
	*/
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
		\Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Escaper $escaper
       
    ) {
        $this->_resultPageFactory = $resultPageFactory;

        $this->_transportBuilder = $transportBuilder;
		$this->inlineTranslation = $inlineTranslation;
		$this->scopeConfig = $scopeConfig;
		$this->storeManager = $storeManager;
		$this->_escaper = $escaper;
       
        parent::__construct($context);
    }

	public function execute(){

		// return $this->_resultPageFactory->create();

		$this->sendEmail();
		
	}


	public function sendEmail(){

	
		$this->inlineTranslation->suspend();
		try {
		
			$error = false;
	 
			$sender = [
				'name' => 'John',
				'email' => 'junsayjohn32@gmail.com',
			];
			 
			$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
			$transport = $this->_transportBuilder->setTemplateIdentifier('mymodule_email_template') // this code we have mentioned in the email_templates.xml
			->setTemplateOptions(
				[
				'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
				'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
				]
			)
			->setTemplateVars(['data' => $postObject])
			->setFrom($sender)
			->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
			->getTransport();
			 
			$transport->sendMessage(); ;
			$this->inlineTranslation->resume();
			$this->messageManager->addSuccess(
				__('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
			);
			$this->_redirect('*/*/');
			return;
		} catch (\Exception $e) {
			$this->inlineTranslation->resume();
			$this->messageManager->addError(
				__('We can\'t process your request right now. Sorry, that\'s all we know.'.$e->getMessage())
			);
			$this->_redirect('*/*/');
			return;
		}
	}
}