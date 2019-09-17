<?php

namespace ISN\PurchaseOrderDetails\Block;

class Main extends \Magento\Framework\View\Element\Template
{
    /**
     * resultPageFactory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Session
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * LoggerInterface
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    
    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */

    public function __construct(
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $data = [])
    {
        $this->logger = $loggerInterface;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
    }
}
