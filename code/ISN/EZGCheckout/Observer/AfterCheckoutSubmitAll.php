<?php
/**
 * @copyright Copyright © ISN, LLC. All rights reserved.
 * @author JSN
 * @version 1.0.0 20190704
 */
namespace ISN\EZGCheckout\Observer;

use Magento\Framework\Event\Observer;


class AfterCheckoutSubmitAll implements \Magento\Framework\Event\ObserverInterface {

    private $logger;
    
    /** @var $companyIntegrationHelper \ISN\EZGCheckout\Helper\EZGCompanyIntegrationHelper */
    private $companyIntegrationHelper = null;
    
    /** @var $companyIntegrationHelperFactory \ISN\EZGCheckout\Helper\EZGCompanyIntegrationHelperFactory */
    protected $companyIntegrationHelperFactory;

    public function __construct(\Psr\Log\LoggerInterface $logger,
                                \ISN\EZGCheckout\Helper\EZGCompanyIntegrationHelperFactory $companyIntegrationHelperFactory) {
        $this->logger = $logger;

        $this->companyIntegrationHelperFactory = $companyIntegrationHelperFactory;
//         $this->logger->debug("[AfterCheckoutSubmitAll->construct]");
    }
    
    public function execute(Observer $observer) {
        /** @var $quote \Magento\Quote\Model\Quote */
        $quote = $observer->getData('quote');
        if (null !== $quote){
            $quote->getBillingAddress()->addData(self::getHelper()->getBillingAddressData());
            $quote->getShippingAddress()->addData(self::getHelper()->getShippingAddressData());
            try {
                $quote->save();
            } catch (\Exception $e){
                $this->logger->error("[AfterCheckoutSubmitAll->execute] exception: ".$e->getMessage());
            }
         }
        
        /** @var \Magento\Sales\Model\Order */
        $order = $observer->getEvent()->getData('order');
        if (null !== $order){
            $order->getBillingAddress()->addData(self::getHelper()->getBillingAddressData());
            $order->getShippingAddress()->addData(self::getHelper()->getShippingAddressData());
            try {
                $order->save();
            } catch (\Exception $e){
                $this->logger->error("[AfterCheckoutSubmitAll->execute] exception: ".$e->getMessage());
            }
        }
        
        return $this;
    }
    
    
    protected function getHelper(){
        if(null === $this->companyIntegrationHelper){
            $this->companyIntegrationHelper = $this->companyIntegrationHelperFactory->create();
        }
        
        return $this->companyIntegrationHelper;
    }

    
 }