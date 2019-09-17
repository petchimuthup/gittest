<?php 

namespace ISN\Checkout\Plugin\Block\Checkout;

const space = ' ';

class LayoutProcessorInterceptor {
    private $logger;
    
    /** @var $companyIntegrationHelper \ISN\EZGCheckout\Helper\EZGCompanyIntegrationHelper */
    private $companyIntegrationHelper = null;
    
    /** @var $companyIntegrationHelperFactory \ISN\EZGCheckout\Helper\EZGCompanyIntegrationHelperFactory */
    protected $companyIntegrationHelperFactory;
    
    public function __construct(\Psr\Log\LoggerInterface $logger,
        \ISN\EZGCheckout\Helper\EZGCompanyIntegrationHelperFactory $companyIntegrationHelperFactory) {
            $this->logger = $logger;
            
            $this->companyIntegrationHelperFactory = $companyIntegrationHelperFactory;
            $this->logger->debug("[LayoutProcessorInterceptor->construct]");
    }
    
    
    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $processer,  $jsLayout) {
        $this->logger->debug("[LayoutProcessorInterceptor->afterProcess]");
        
        $shippingAddressData = self::getHelper()->getShippingAddressData();
        
        // Shipping Step / Shipping Address
        if (isset($shippingAddressData['attention'])){
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['address-list']['children']['ship-to-address-diplay']['children']['shipToAttention']['config']['value'] = __('attention').space.$shippingAddressData['attention'];
        }
        
        if (isset($shippingAddressData['company_name'])){
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['address-list']['children']['ship-to-address-diplay']['children']['shipToCompanyName']['config']['value'] = __('company').space.$shippingAddressData['company_name'];
        }
        
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['address-list']['children']['ship-to-address-diplay']['children']['shipToStreet']['config']['value'] = __('street').space.$shippingAddressData['street'];
        
        if (isset($shippingAddressData['city'])){
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['address-list']['children']['ship-to-address-diplay']['children']['shipToCity']['config']['value'] = __('city').space.$shippingAddressData['city'];
        }
        
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['address-list']['children']['ship-to-address-diplay']['children']['shipToRegion']['config']['value'] = __('region').space.$shippingAddressData['region'];
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['address-list']['children']['ship-to-address-diplay']['children']['shipToCountry']['config']['value'] = __('country').space.$shippingAddressData['country_id'];
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['address-list']['children']['ship-to-address-diplay']['children']['shipToPostCode']['config']['value'] =  __('postcode').space.$shippingAddressData['postcode'];
        
        if (isset($shippingAddressData['email'])){
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['address-list']['children']['ship-to-address-diplay']['children']['shipToEmail']['config']['value'] = __('email').space.$shippingAddressData['email'];
        }
        
        if (isset($shippingAddressData['telephone'])){
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['address-list']['children']['ship-to-address-diplay']['children']['shipToTelephone']['config']['value'] = __('telephone').space.$shippingAddressData['telephone'];
        }
        
        // Order Summary / Ship To / Shipping Address
        if (isset($shippingAddressData['attention'])){
            $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information']['children']['ship-to']['children']['shipToAttention']['config']['value'] = __('attention').space.$shippingAddressData['attention'];
        }
        
        if (isset($shippingAddressData['company_name'])){
            $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information']['children']['ship-to']['children']['shipToCompanyName']['config']['value'] = __('company').space.$shippingAddressData['company_name'];
        }
        
        $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information']['children']['ship-to']['children']['shipToStreet']['config']['value'] = __('street').space.$shippingAddressData['street'];
        
        if (isset($shippingAddressData['city'])){
            $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information']['children']['ship-to']['children']['shipToCity']['config']['value'] = __('city').space.$shippingAddressData['city'];
        }
        
        $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information']['children']['ship-to']['children']['shipToRegion']['config']['value'] = __('region').space.$shippingAddressData['region'];
        $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information']['children']['ship-to']['children']['shipToCountry']['config']['value'] = __('country').space.$shippingAddressData['country_id'];
        $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information']['children']['ship-to']['children']['shipToPostCode']['config']['value'] =  __('postcode').space.$shippingAddressData['postcode'];
        
        if (isset($shippingAddressData['email'])){
            $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information']['children']['ship-to']['children']['shipToEmail']['config']['value'] = __('email').space.$shippingAddressData['email'];
        }
        
        if (isset($shippingAddressData['telephone'])){
            $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information']['children']['ship-to']['children']['shipToTelephone']['config']['value'] = __('telephone').space.$shippingAddressData['telephone'];
        }
//         $shipTo = $jsLayout['components']['checkout']['children']['sidebar']['children']['shipping-information']['children']['ship-to']['children'];
//         $this->logger->debug("[LayoutProcessorInterceptor->afterProcess] shipTo keys=".implode(', ', array_keys($shipTo)));
        
        //component, displayArea, rendererTemplates
        
 //       $this->logger->debug("[LayoutProcessorInterceptor->afterProcess] shipTo=".$shipTo);
        
        return $jsLayout;
    }
    
    
    protected function getHelper(){
        if(null === $this->companyIntegrationHelper){
            $this->companyIntegrationHelper = $this->companyIntegrationHelperFactory->create();
        }
        
        return $this->companyIntegrationHelper;
    }
    
    
}