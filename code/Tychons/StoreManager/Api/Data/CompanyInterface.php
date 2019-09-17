<?php


namespace Tychons\StoreManager\Api\Data;

interface CompanyInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const DEFAULT_LOCATION = 'default_location';
    const FIRSTNAME = 'firstname';
    const LASTNAME = 'lastname';
    const ROLE = 'role';
    const COMPANY_NAME = 'company_name';
    const CONF_PASSWORD = 'conf_password';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const COMPANY_ID = 'company_id';
    const CUSTOMER_ID = 'customer_id';

    /**
     * Get company_id
     * @return string|null
     */
    public function getCompanyId();

    /**
     * Set company_id
     * @param string $companyId
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     */
    public function setCompanyId($companyId);

    /**
     * Get firstname
     * @return string|null
     */
    public function getFirstname();

    /**
     * Set firstname
     * @param string $firstname
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     */
    public function setFirstname($firstname);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Tychons\StoreManager\Api\Data\CompanyExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Tychons\StoreManager\Api\Data\CompanyExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Tychons\StoreManager\Api\Data\CompanyExtensionInterface $extensionAttributes
    );

    /**
     * Get lastname
     * @return string|null
     */
    public function getLastname();

    /**
     * Set lastname
     * @param string $lastname
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     */
    public function setLastname($lastname);

    /**
     * Get email
     * @return string|null
     */
    public function getEmail();

    /**
     * Set email
     * @param string $email
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     */
    public function setEmail($email);

    /**
     * Get password
     * @return string|null
     */
    public function getPassword();

    /**
     * Set password
     * @param string $password
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     */
    public function setPassword($password);

    /**
     * Get conf_password
     * @return string|null
     */
    public function getConfPassword();

    /**
     * Set conf_password
     * @param string $confPassword
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     */
    public function setConfPassword($confPassword);

    /**
     * Get default_location
     * @return string|null
     */
    public function getDefaultLocation();

    /**
     * Set default_location
     * @param string $defaultLocation
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     */
    public function setDefaultLocation($defaultLocation);

    /**
     * Get role
     * @return string|null
     */
    public function getRole();

    /**
     * Set role
     * @param string $role
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     */
    public function setRole($role);

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customerId
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get company_name
     * @return string|null
     */
    public function getCompanyName();

    /**
     * Set company_name
     * @param string $companyName
     * @return \Tychons\StoreManager\Api\Data\CompanyInterface
     */
    public function setCompanyName($companyName);
}
