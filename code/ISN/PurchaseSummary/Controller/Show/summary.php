<?php

namespace ISN\PurchaseSummary\Controller\Show;

use Magento\Framework\App\RequestInterface;

class Summary extends \Magento\Framework\App\Action\Action
{

    /**
     * resultPageFactory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;


    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * 
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    public function dispatch(RequestInterface $request){
        if(!$this->_objectManager->get(\Magento\Customer\Model\Session::class)->authenticate())
            $this->_actionFlag->set('', 'no-dispatch', true);
        return parent::dispatch($request);
    }

    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
