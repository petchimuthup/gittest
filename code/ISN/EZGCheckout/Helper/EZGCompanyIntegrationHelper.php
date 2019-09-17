<?php
/**
 * @copyright Copyright © ISN, LLC. All rights reserved.
 * @author JSN
 * @version 1.0.0 20190704
 */
namespace ISN\EZGCheckout\Helper;

class EZGCompanyIntegrationHelper {

    private $logger;
    
    /** @var Magento\Store\Model\StoreManagerInterface */
    protected $_storeManager;
    
    /** @var \Magento\Company\Model\CompanyFactory */
    protected $companyFactory;
    
    /** @var $companyISNFactory \ISN\CompanyExt\Model\CompanyISNFactory */
    protected $companyISNFactory;
    
    /** @var \Magento\Customer\Model\CustomerFactory */
    protected $customerFactory;
    
    /** @var \Tychons\StoreManager\Model\StoreSelectFactory */
    protected $storeSelectFactory;
    

    public function __construct(\Psr\Log\LoggerInterface $logger,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Tychons\StoreManager\Model\StoreSelectFactory $storeSelectFactory,
                                \Magento\Customer\Api\CustomerRepositoryInterface $customerFactory,
                                \Magento\Company\Model\CompanyFactory $companyFactory,
                                \ISN\CompanyExt\Model\CompanyISNFactory $companyISNFactory) {
        $this->logger = $logger;
//         $this->logger->debug("[EZGCompanyIntegrationHelper->construct] Entry");

        $this->_storeManager = $storeManager;
        $this->storeSelectFactory = $storeSelectFactory;
        $this->customerFactory = $customerFactory;
        $this->companyFactory = $companyFactory;
        $this->companyISNFactory = $companyISNFactory;
 /*
        $session = $_SESSION;
        
        foreach($session as $index=>$sessionVar){
            if(is_array($sessionVar)){
//                 $this->logger->debug("[EZGCompanyIntegrationHelper->construct] session[".$index."]=".implode(', ',$sessionVar));
                $this->logger->debug("[EZGCompanyIntegrationHelper->construct] session[".$index."] is array");
            } else {
                $this->logger->debug("[EZGCompanyIntegrationHelper->construct] session[".$index."]=".$sessionVar);
            }
        }
*/
    }
    
    
    public function getStore($storeId) {
         if (null !== $storeId && isset($storeId)){
            return $this->_storeManager->getStore($storeId);
        }
        
        return null;
    }  
    
    
    public function getCurrentCustomerId(){
        $this->logger->debug("[EZGCompanyIntegrationHelper->getCurrentCustomerId] Entry");
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        $customerId = $customerSession->getCustomer()->getId();
        return $customerId;
    }
    
    
    public function getActiveStoreId($customerId){
        $this->logger->debug("[EZGCompanyIntegrationHelper->getActiveStoreId] Entry");
//         $this->logger->debug("[EZGCompanyIntegrationHelper->getActiveStoreId] customerId=".$customerId);
        
//         $activeStoreId = 1;
        // Get users current store
        /** @var $activeStore \Tychons\StoreManager\Model\StoreSelect */
        $activeStore = $this->storeSelectFactory->create()->load($customerId,'customer_id');
        if (null !== $activeStore && isset($activeStore) && null !== $activeStore->getDataByKey('userstore_id')){
            $activeStoreId = $activeStore->getUserstoreId();
        }
        
        return $activeStoreId;
    }
    
    
    protected function getShippingAddressOfSelectedStore(){
        $this->logger->debug("[EZGCompanyIntegrationHelper->getShippingAddressOfSelectedStore]");
        // Get logged in user
        $customerId = self::getCurrentCustomerId();
        $customerData = $this->customerFactory->getById($customerId);
        
        // Get users current store
        $userStoreId = self::getActiveStoreId($customerId);
        
        // Get Company that equates to store
        /** @var $company \Magento\Company\Model\Company */
        $company = $this->companyFactory->create()->load($userStoreId);
        
        /** @var $companyISN \ISN\CompanyExt\Model\CompanyISN */
        $companyISN = $this->companyISNFactory->create()->load($company->getEntityId(), 'company_id');
        
        
        if (null !== $companyISN && isset($companyISN)){
            // Get address from company
            $address = [];
            $address['attention'] = $companyISN->getShipToAddressAttention();
            $address['company_name'] = $company->getCompanyName();
            $address['street'] = $companyISN->getShipToAddress1().$companyISN->getShipToAddress2();
            $address['city'] = $companyISN->getShipToAddressCity();
            $address['country_id'] = $companyISN->getShipToAddressCountry();
            $address['region_id'] = $companyISN->getShipToAddressState();
            $address['region'] = $companyISN->getShipToAddressState();
            $address['postcode'] = $companyISN->getShipToAddressZip();
            $address['email'] = $companyISN->getShipToAddressEmail();
            $address['telephone'] = $companyISN->getShipToAddressPhone();
            $address['save_in_address_book'] = 1;
             
            // Return address
            return $address;
        } 
 
        return null;
    }
    
    protected function getCompanyBillingAddress(){
        $this->logger->debug("[EZGCompanyIntegrationHelper->getCompanyBillingAddress]");
        // Get logged in user
        $customerId = self::getCurrentCustomerId();
        
        // Get users current store
        $userStoreId = self::getActiveStoreId($customerId);
        
        // Get Company that equates to store
        $company = $this->companyFactory->create()->load($userStoreId);
        
        // Get address from company
        $address = [];
        $address['companyName'] = $company->getCompanyName();
        $address['street'] = $company->getStreet();
        $address['city'] = $company->getCity();
        $address['country_id'] = $company->getCountryId();
        $address['region_id'] = $company->getRegionId();
        $address['region'] = $company->getRegion();
        $address['postcode'] = $company->getPostCode();
        $address['email'] = $company->getCompanyEmail();
        $address['telephone'] = $company->getTelePhone();
        $address['save_in_address_book'] = 1;

        // Return address
        return $address;
    }
    
    public function getBillingAddressData(){
        return self::getCompanyBillingAddress();
    }
    
    public function getShippingAddressData(){
        return self::getShippingAddressOfSelectedStore();
    }

    public function copyAddress($address2, $address){
        $address2->setFirstname($address['firstname']);
        $address2->setLastname($address['lastname']);
        $address2->setCompanyName($address['companyName']);
        $address2->setStreet($address['street']);
        $address2->setCity($address['city']);
        $address2->setRegionId($address['region_id']);
        $address2->setCountryId($address['country_id']);
        $address2->setRegion($address['region']);       
        $address2->setPostcode($address['postcode']);
        $address2->setEmail($address['email']);
        $address2->setTelephone($address['telephone']);

        return $address2;
    }

    public function getShippingAddressForQuote(){
        $address = self::getShippingAddressOfSelectedStore();
        
        /** @var \Magento\Quote\Model\Quote\Address */
        $address2 = self::copyAddress($this->quoteAddressFactory->create()->setAddressType('shipping'), $address);
        $address2->setLastname('Shipper');
        $address2->setCustomerAddressId(null);
        
        return $address2;
    }
    
    public function getBillingAddressForQuote(){
        $address = self::getShippingAddressOfSelectedStore();
        
        /** @var \Magento\Quote\Model\Quote\Address */
        $address2 = self::copyAddress($this->quoteAddressFactory->create()->setAddressType('billing'), $address);
        $address2->setLastname('Billings');
        $address2->setCustomerAddressId(null);
        
        return $address2;
    }

    public function getShippingAddressForOrder(){
        $address = self::getShippingAddressOfSelectedStore();

        /** @var Magento\Sales\Model\Order\Address */
        $address2 = self::copyAddress($this->quoteAddressFactory->create()->setAddressType('shipping'), $address);
        $address2->setLastname('Shipper');
        $address2->setCustomerAddressId(null);
        
        $address2->setShouldIgnoreValidation(true);
        
        return $address2;
    }

    /**
     * Recursive discovery tool
     * @param unknown $thing
     * @param unknown $name
     * @return string JSON break down of the thing...
     */
    public function breakItDownForMe($thing, $name){
        $output = '';
        
        if (null !== $thing){
            if (is_array($thing)){
                if (null !== $name && trim($name)){
                    $output = $output.'"'.$name.'":{';
                } else {
                    $output = $output.'"":{';
                }
                foreach ($thing as $key=>$subthing){
                    $output = $output.self::breakItDownForMe($subthing, $key);
                }
                $output = $output.'}';
                
            } else {
                if (is_object($thing)){
                    if (null !== $name && trim($name)){
                        $output = $output.'"'.$name.'":"class='.get_class($thing).'"';
                    } else {
                        $output = $output.'"":"class='.get_class($thing).'"';
                    }
                } else {
                    if (null !== $name && trim($name)){
                        $output = $output.'"'.$name.'":"'.$thing.'"';
                    } else {
                        $output = $output.'"":"'.$thing.'"';
                    }
                }
            }
        } else {
            if (null !== $name && trim($name)){
                $output = $output.'"'.$name.'":"null"';
            } else {
                $output = $output.'"":"null"';
            }
        }
        
        
        return $output;
    }
}