<?php
namespace Tychons\StoreManager\Observer;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
class CustomerSaveAfter implements ObserverInterface
{
protected $_request;
protected $_layout;
protected $_objectManager = null;
protected $_customerGroup;
private $logger;
protected $_customerRepositoryInterface;
protected $_customerRegistry;
protected $encryptor;

/**
* @param \Magento\Framework\ObjectManagerInterface $objectManager
*/
public function __construct(
    \Magento\Framework\View\Element\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectManager,
    \Magento\Customer\Model\CustomerRegistry $customerRegistry,
    \Magento\Framework\Encryption\EncryptorInterface $encryptor,
    \Psr\Log\LoggerInterface $logger,
    \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
){
    $this->_layout = $context->getLayout();
    $this->_request = $context->getRequest();
    $this->_objectManager = $objectManager;
    $this->logger = $logger;
    $this->encryptor = $encryptor;
    $this->_customerRegistry = $customerRegistry;
    $this->_customerRepositoryInterface = $customerRepositoryInterface;
}

/**
* @param \Magento\Framework\Event\Observer $observer
* @return void
*/
public function execute(EventObserver $observer)
{
    //$this->logger->info(' --jafar123--');
    $event = $observer->getEvent();

	$customer = $observer->getEvent()->getCustomer();

	$customerId = $observer->getEvent()->getCustomer()->getId();

    $post = $this->_request->getPostValue();

    $password = $post['customer']['password'];

    if(!empty($password)){

		$customer = $this->_customerRepositoryInterface->getById($customerId);
		$customerSecure = $this->_customerRegistry->retrieveSecureData($customer->getId());
		$customerSecure->setRpToken(null);
		$customerSecure->setRpTokenCreatedAt(null);
		$customerSecure->setPasswordHash($this->encryptor->getHash($password, true));
		$this->_customerRepositoryInterface->save($customer);

    }

   }

 }