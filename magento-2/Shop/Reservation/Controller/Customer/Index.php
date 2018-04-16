<?php
namespace Shop\Reservation\Controller\Customer;

class Index extends \Magento\Framework\App\Action\Action {


 //    protected $resultPageFactory;

	// /**
 //     * Constructor
 //     *
 //     * @param \Magento\Framework\App\Action\Context  $context
 //     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
 //     */
 //    public function __construct(
 //        \Magento\Framework\App\Action\Context $context,  
 //        \Magento\Framework\View\Result\PageFactory $resultPageFactory      
 //    ) {
       
 //        parent::__construct($context);
 //        $this->resultPageFactory = $resultPageFactory;
        
 //    }


	public function execute() {

	    $this->_view->loadLayout();
	    $this->_view->renderLayout();
	}

}