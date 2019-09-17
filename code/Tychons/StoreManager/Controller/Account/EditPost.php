<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Tychons\StoreManager\Controller\Account;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\AddressRegistry;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Customer\Model\AuthenticationInterface;
use Magento\Customer\Model\Customer\Mapper;
use Magento\Customer\Model\EmailNotificationInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerRegistry;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\InvalidEmailOrPasswordException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\UserLockedException;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\Phrase;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class EditPost
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EditPost extends AbstractAccount implements CsrfAwareActionInterface, HttpPostActionInterface
{
    /**
     * Form code for data extractor
     */
    const FORM_DATA_EXTRACTOR_CODE = 'customer_account_edit';

    /**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var CustomerRegistry
     */
    protected $_customerRegistry;

    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @var CustomerExtractor
     */
    protected $customerExtractor;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var \Magento\Customer\Model\EmailNotificationInterface
     */
    private $emailNotification;

    /**
     * @var AuthenticationInterface
     */
    private $authentication;

    /**
     * @var Mapper
     */
    private $customerMapper;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var AddressRegistry
     */
    private $addressRegistry;

    /**
     * @var SessionManagerInterface
     */
    private $_coreSession;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param CustomerRepositoryInterface $customerRepository
     * @param Validator $formKeyValidator
     * @param CustomerExtractor $customerExtractor
     * @param Escaper|null $escaper
     * @param AddressRegistry|null $addressRegistry
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        CustomerRepositoryInterface $customerRepository,
        CustomerRegistry $customerRegistry,
        EncryptorInterface $encryptor,
        Validator $formKeyValidator,
        CustomerExtractor $customerExtractor,
        ?Escaper $escaper = null,
        SessionManagerInterface $coreSession,
        AddressRegistry $addressRegistry = null
    ) {
        parent::__construct($context);
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->customerRepository = $customerRepository;
        $this->_customerRegistry = $customerRegistry;
        $this->encryptor = $encryptor;
        $this->formKeyValidator = $formKeyValidator;
        $this->customerExtractor = $customerExtractor;
        $this->_coreSession = $coreSession;
        $this->escaper = $escaper ?: ObjectManager::getInstance()->get(Escaper::class);
        $this->addressRegistry = $addressRegistry ?: ObjectManager::getInstance()->get(AddressRegistry::class);
    }

    /**
     * Get authentication
     *
     * @return AuthenticationInterface
     */
    private function getAuthentication()
    {

        if (!($this->authentication instanceof AuthenticationInterface)) {
            return ObjectManager::getInstance()->get(
                \Magento\Customer\Model\AuthenticationInterface::class
            );
        } else {
            return $this->authentication;
        }
    }

    /**
     * Get email notification
     *
     * @return EmailNotificationInterface
     * @deprecated 100.1.0
     */
    private function getEmailNotification()
    {
        if (!($this->emailNotification instanceof EmailNotificationInterface)) {
            return ObjectManager::getInstance()->get(
                EmailNotificationInterface::class
            );
        } else {
            return $this->emailNotification;
        }
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/edit');

        return new InvalidRequestException(
            $resultRedirect,
            [new Phrase('Invalid Form Key. Please refresh the page.')]
        );
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return null;
    }

    /**
     * Change customer email or password action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $validFormKey = $this->formKeyValidator->validate($this->getRequest());

        if ($validFormKey && $this->getRequest()->isPost()) {
            $currentCustomerDataObject = $this->getCustomerDataObject($this->session->getCustomerId());
            $customerCandidateDataObject = $this->populateNewCustomerDataObject(
                $this->_request,
                $currentCustomerDataObject
            );

            try {

                //krishna added this

                //get custom attributes

                $customerId = $this->session->getCustomerId();

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

                $custom = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

                $userStore = $custom->getUserstoreId();

                $userRole = $custom->getRoleId();

                $userPass = $custom->getPassword();

                $firsttimeLogin = $custom->getFirsttimeLogin();

                $userCon = $custom->getConfPassword();

                //set session
                $this->_coreSession->start();
                $this->_coreSession->setUserStoreId($userStore);
                $this->_coreSession->setRoleId($userRole);
                $this->_coreSession->setPass($userPass);
                $this->_coreSession->setConPass($userCon);
                $this->_coreSession->setFirsttimeLogin($firsttimeLogin);

                //get session

                $userStore = $this->_coreSession->getUserStoreId();

                $userRole = $this->_coreSession->getRoleId();

                $userPass = $this->_coreSession->getPass();

                $userCon = $this->_coreSession->getConPass();

                $firsttimeLogin = $this->_coreSession->getFirsttimeLogin();

                //krishna end

                // whether a customer enabled change email option
                //$this->processChangeEmailRequest($currentCustomerDataObject);

                // whether a customer enabled change password option
                //$isPasswordChanged = $this->changeCustomerPassword($currentCustomerDataObject->getEmail());

                // No need to validate customer address while editing customer profile
                //$this->disableAddressValidation($customerCandidateDataObject);

                //$this->customerRepository->save($customerCandidateDataObject);

                //krishna added 

                $email = $this->getRequest()->getParam('email');

                if(!$email){

                    $email = $customerCandidateDataObject->getEmail();
                }

                $uppassword = $this->getRequest()->getParam("password");

                $change_password = $this->getRequest()->getParam("change_password");

                if(!isset($change_password)){

                     $firstname = $this->getRequest()->getParam("firstname");

                     $lastname = $this->getRequest()->getParam("lastname");

                     $updatesCustomer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

                     $updatesCustomer->setFirstName($firstname);

                     $updatesCustomer->setLastName($lastname);

                     $updatesCustomer->save();

                     $this->messageManager->addSuccess(__('You saved the account information.'));

                     return $resultRedirect->setPath('customer/account');


                }

                $change_email = $this->getRequest()->getParam("change_email");

                $validate = $this->getAuthentication()->authenticate(
                    $customerId,
                    $this->getRequest()->getPost('current_password')
                );

                if($validate && isset($change_password) && isset($change_email))
                {

                        $updateCustomer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

                        $updateCustomer->setEmail($email);

                        $updateCustomer->setFirsttimeLogin(1);

                        $updateCustomer->setUserstoreId($userStore);

                        $updateCustomer->setRoleId($userRole);

                        $updateCustomer->setPassword($uppassword);

                        $updateCustomer->setConfPassword($uppassword);

                        $updateCustomer->save();

                        if ($updateCustomer) 
                        {


                            if (!$firsttimeLogin && $updateCustomer->getFirsttimeLogin() == 1) {

                                $this->messageManager->addSuccess(__('Your password has been updated!'));
                                
                                return $resultRedirect->setPath('/');
                            }
                           
                            $this->messageManager->addSuccess(__('You saved the account information.'));

                            return $resultRedirect->setPath('customer/account');
                        }

                }elseif ($validate && isset($change_password)){


                        $updateCustomer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

                        $updateCustomer->setUserstoreId($userStore);

                        $updateCustomer->setFirsttimeLogin(1);

                        $updateCustomer->setRoleId($userRole);

                        $updateCustomer->setPassword($uppassword);

                        $updateCustomer->setConfPassword($uppassword);

                        $updateCustomer->save();

                        if ($updateCustomer) 
                        {

                            if (!$firsttimeLogin && $updateCustomer->getFirsttimeLogin() == 1) {

                                $this->messageManager->addSuccess(__('Your password has been updated!'));
                                
                                return $resultRedirect->setPath('/');
                            }
                           
                            $this->messageManager->addSuccess(__('You saved the account information.'));

                            return $resultRedirect->setPath('customer/account');
                        }

                        $this->messageManager->addException($e, __("The password doesn't match this account. Verify the password and try again."));

                        return $resultRedirect->setPath('customer/account');

                }elseif ($validate && isset($change_email)){

                        $updateCustomer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

                        $updateCustomer->setEmail($email);

                        $updateCustomer->setFirsttimeLogin(1);

                        $updateCustomer->save();

                        if ($updateCustomer) 
                        {
                           
                            if (!$firsttimeLogin && $updateCustomer->getFirsttimeLogin() == 1) {

                                $this->messageManager->addSuccess(__('Your password has been updated!'));
                                
                                return $resultRedirect->setPath('/');
                            }

                            $this->messageManager->addSuccess(__('You saved the account information.'));

                            return $resultRedirect->setPath('customer/account');
                        }

                       
                }else{


                         $this->messageManager->addException($e, __("The password doesn't match this account. Verify the password and try again."));

                        return $resultRedirect->setPath('customer/account');
                }


                $this->_coreSession->unsUserStoreId($userStore);
                $this->_coreSession->unsRoleId($userRole);
                $this->_coreSession->unsPass($userPass);
                $this->_coreSession->unsConPass($userCon);
                $this->_coreSession->unsFirsttimeLogin($firsttimeLogin);
                //krishna end
                $this->getEmailNotification()->credentialsChanged(
                    $customerCandidateDataObject,
                    $currentCustomerDataObject->getEmail(),
                    $isPasswordChanged
                );
                $this->dispatchSuccessEvent($customerCandidateDataObject);
                $this->messageManager->addSuccess(__('You saved the account information.'));
                return $resultRedirect->setPath('customer/account');
            } catch (InvalidEmailOrPasswordException $e) {
                $this->messageManager->addErrorMessage($this->escaper->escapeHtml($e->getMessage()));
            } catch (UserLockedException $e) {
                $message = __(
                    'The account sign-in was incorrect or your account is disabled temporarily. '
                    . 'Please wait and try again later.'
                );
                $this->session->logout();
                $this->session->start();
                $this->messageManager->addError($message);
                return $resultRedirect->setPath('customer/account/login');
            } catch (InputException $e) {
                $this->messageManager->addErrorMessage($this->escaper->escapeHtml($e->getMessage()));
                foreach ($e->getErrors() as $error) {
                    $this->messageManager->addErrorMessage($this->escaper->escapeHtml($error->getMessage()));
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('We can\'t save the customer.'));
            }

            $this->session->setCustomerFormData($this->getRequest()->getPostValue());
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/edit');
        return $resultRedirect;
    }

    /**
     * Account editing action completed successfully event
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $customerCandidateDataObject
     * @return void
     */
    private function dispatchSuccessEvent(\Magento\Customer\Api\Data\CustomerInterface $customerCandidateDataObject)
    {
        $this->_eventManager->dispatch(
            'customer_account_edited',
            ['email' => $customerCandidateDataObject->getEmail()]
        );
    }

    /**
     * Get customer data object
     *
     * @param int $customerId
     *
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    private function getCustomerDataObject($customerId)
    {
        return $this->customerRepository->getById($customerId);
    }

    /**
     * Create Data Transfer Object of customer candidate
     *
     * @param \Magento\Framework\App\RequestInterface $inputData
     * @param \Magento\Customer\Api\Data\CustomerInterface $currentCustomerData
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    private function populateNewCustomerDataObject(
        \Magento\Framework\App\RequestInterface $inputData,
        \Magento\Customer\Api\Data\CustomerInterface $currentCustomerData
    ) {
        $attributeValues = $this->getCustomerMapper()->toFlatArray($currentCustomerData);
        $customerDto = $this->customerExtractor->extract(
            self::FORM_DATA_EXTRACTOR_CODE,
            $inputData,
            $attributeValues
        );
        $customerDto->setId($currentCustomerData->getId());
        if (!$customerDto->getAddresses()) {
            $customerDto->setAddresses($currentCustomerData->getAddresses());
        }
        if (!$inputData->getParam('change_email')) {
            $customerDto->setEmail($currentCustomerData->getEmail());
        }

        return $customerDto;
    }

    /**
     * Change customer password
     *
     * @param string $email
     * @return boolean
     * @throws InvalidEmailOrPasswordException|InputException
     */
    protected function changeCustomerPassword($email)
    {
        $isPasswordChanged = false;
        if ($this->getRequest()->getParam('change_password')) {
            $currPass = $this->getRequest()->getPost('current_password');
            $newPass = $this->getRequest()->getPost('password');
            $confPass = $this->getRequest()->getPost('password_confirmation');
            if ($newPass != $confPass) {
                throw new InputException(__('Password confirmation doesn\'t match entered password.'));
            }

            $isPasswordChanged = $this->customerAccountManagement->changePassword($email, $currPass, $newPass);
        }

        return $isPasswordChanged;
    }

    /**
     * Process change email request
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface $currentCustomerDataObject
     * @return void
     * @throws InvalidEmailOrPasswordException
     * @throws UserLockedException
     */
    private function processChangeEmailRequest(\Magento\Customer\Api\Data\CustomerInterface $currentCustomerDataObject)
    {
        if ($this->getRequest()->getParam('change_email')) {
            // authenticate user for changing email
            try {
                $this->getAuthentication()->authenticate(
                    $currentCustomerDataObject->getId(),
                    $this->getRequest()->getPost('current_password')
                );
            } catch (InvalidEmailOrPasswordException $e) {
                throw new InvalidEmailOrPasswordException(
                    __("The password doesn't match this account. Verify the password and try again.")
                );
            }
        }
    }

    /**
     * Get Customer Mapper instance
     *
     * @return Mapper
     *
     * @deprecated 100.1.3
     */
    private function getCustomerMapper()
    {
        if ($this->customerMapper === null) {
            $this->customerMapper = ObjectManager::getInstance()->get(\Magento\Customer\Model\Customer\Mapper::class);
        }
        return $this->customerMapper;
    }

    /**
     * Disable Customer Address Validation
     *
     * @param CustomerInterface $customer
     * @throws NoSuchEntityException
     */
    private function disableAddressValidation($customer)
    {
        foreach ($customer->getAddresses() as $address) {
            $addressModel = $this->addressRegistry->retrieve($address->getId());
            $addressModel->setShouldIgnoreValidation(true);
        }
    }
}
