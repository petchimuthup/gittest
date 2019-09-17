<?php
namespace ISN\CompanyExt\Controller\loading;
use mysql_xdevapi\Exception;
use Magento\Company\Api\Data\CompanyInterface;
class csv extends \Magento\Framework\App\Action\Action 
{
    /**
     * pagefactory instance
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * company interface
     * @var \Magento\Company\Api\Data\CompanyInterfaceFactory
     */
    protected $companyDataFactory;
    /**
     * @var \ISN\CompanyExt\Model\CompanyISNFactory
     */
    protected $CompanyisnFactory;
    /**
     * company repository
     * @var \Magento\Company\Api\CompanyRepositoryInterface
     */
    protected $companyRepository;
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * dataobject helper
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;
    /**
     * superuser
     * @var \Magento\Company\Model\CompanySuperUserGet
     */
    protected $superUser;
    /**
     * directory list
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directory_list;
    /**
     * logger
     * @var \ISN\CompanyExt\Logger\Logger
     */
    protected $logger;
    /**
     * read csv file
     * @var \Magento\Framework\File\Csv
     */
    protected $csv;
    const PUB_DIR = 'pub';
    const COMPANY_CSV = 'company.csv';
    /**
     * @param \Magento\Company\Api\CompanyRepositoryInterface $companyRepository
     * @param \ISN\CompanyExt\Logger\Logger $loggerInterface
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Company\Model\CompanySuperUserGet $superUser
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Company\Api\AclInterface $userRoleManagement
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \ISN\CompanyExt\Logger\Logger $loggerInterface,
        \Magento\Company\Model\CompanySuperUserGet $superUser,
        \Magento\Company\Api\Data\CompanyInterfaceFactory $companyDataFactory,
        \ISN\CompanyExt\Model\CompanyISNFactory $CompanyisnFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Company\Api\CompanyRepositoryInterface $companyRepository,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ){
        $this->resultPageFactory = $resultPageFactory;
        $this->companyDataFactory = $companyDataFactory;
        $this->CompanyisnFactory  = $CompanyisnFactory;
        $this->customerRepository = $customerRepository;
        $this->companyRepository = $companyRepository;
        $this->logger = $loggerInterface;
        $this->directory_list = $directoryList;
        $this->superUser = $superUser;
        $this->csv = $csv;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context);
    }
    public function execute()
    {
        //get the full file path of company.csv file
        $companyFile = $this->getCsvPath(self::COMPANY_CSV);
        //read the company.csv into array
        $company = $this->readCVSFile($companyFile);
        //get the magento required company array data
        $compayData = $this->getCompanyData($company);
        /*//print_r($compayData[1]);
        $datas = array(
            "company_name" => "krishna",
            "company_email" => "krishnac@gmail.com",
            "street" => ["test"],
            "city" => "test",
            "postcode" => "12345",
            "telephone" => "656546454",
            "country_id" => "US",
            "region_id" => "12",
            "firstname" => "krishna",
            "lastname" => "krish",
            "website_id" => "1",
            "email" => "krishnac@gmail.com",
            "customer_group_id" => "1"
        );*/
        //get the company_isn table required array data
        $compayIsnData = $this->getCompanyIsnData($company);
        try 
        {
            foreach ($compayData as $key => $company) 
            {
                // create/update magento company
                $companyId = $this->createCompany($company);

                //set company id for company isn table
                $compayIsnData[$key]['company_id'] = $companyId;
                // create/update custom isn company
                $this->createIsnCompany($compayIsnData[$key]);
            }
        }catch(\Exception $ex) {
            $this->logger->error("ImportCompany::execute error: " . $ex);
            return null;
        }
    }
    public function getCsvPath($fileName) 
    {
        return $this->directory_list->getPath(self::PUB_DIR).'/'.$fileName;
    }
    public function readCVSFile($file) 
    {
        try {
            if (!isset($file))
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
            $csvdata = $this->csv->getData($file);
            unset($csvdata[0]);
            $this->logger->info("company.csv file readed success");
            return array_values($csvdata);
        }catch(\Exception $ex){
            $this->logger->error("ImportCompany::readCVSFile error: " . $ex);
        }
    }
    public function createCompany($companyData) 
    {
        try {
            $customer = $this->superUser->getUserForCompanyAdmin($companyData);
            $customerId = $customer->getId();
            $checkcompany = $this->customerRepository->getById($customerId);
        if ($checkcompany->getExtensionAttributes() !== null && $checkcompany->getExtensionAttributes()->getCompanyAttributes() !== null && $checkcompany->getExtensionAttributes()->getCompanyAttributes()->getCompanyId()) {
                $companyId = $checkcompany->getExtensionAttributes()->getCompanyAttributes()->getCompanyId();
            }
            if(isset($companyId) !== null && !empty($companyId)){
                $company = $this->companyRepository->get((int)$companyId);
                $this->setCompanyRequestData($company, $companyData);
                $company->setSuperUserId($customer->getId());
                $this->companyRepository->save($company);
                $id = $company->getId();
                $email = $company->getCompanyEmail();
                $this->logger->info('company has been updated with id of '.$id.'and email of'.$email);
            }else{
                $company = $this->companyDataFactory->create();
                $this->setCompanyRequestData($company, $companyData);
                $company->setSuperUserId($customer->getId());
                $this->companyRepository->save($company);
                $id = $company->getId();
                $email = $company->getCompanyEmail();
                $this->logger->info('company has been created with id of '.$id.'and email of'.$email);
            }
            return $id;
        }catch (\Exception $ex) {
            $this->logger->info("ImportCompany::insertCompanyTable error: " . $ex);
        }
    }
    public function createIsnCompany($companyIsnData) 
    {
        if(!count($companyIsnData)){
            $this->logger->info("no records found in company.csv file");
        }
        try {
            $companyIsnFactory = $this->CompanyisnFactory->create()->load($companyIsnData['company_id'],"company_id");
            $id = $companyIsnFactory->getId();
            //update table if records found
            if ($id) {
                $companyIsnData['company_isn_id'] = $id;
                $updateData = $companyIsnFactory->setData($companyIsnData)->save();
                $isnId = $updateData->getId();
                $companyId = $updateData->getCompanyId();
                $this->logger->info('Company isn records updated for the store of'.$companyId.' and isn id of'.$isnId);
            }else{
                //add records to table if records not found
                $addData = $companyIsnFactory->setData($companyIsnData)->save();
                $isnId = $addData->getId();
                $companyId = $addData->getCompanyId();
                $this->logger->info('Company isn records inserted for the store of'.$companyId.' and isn id of'.$isnId);
            }
            return true;
        }catch (\Exception $ex) {
            $this->logger->info("ImportCompany::insertCompanyTable error: " . $ex);
        }
    }
    public function setCompanyRequestData(CompanyInterface $company, array $data) 
    {
        //$this->logger->debug(__METHOD__ );
        $this->dataObjectHelper->populateWithArray(
            $company,
            $data,
            \Magento\Company\Api\Data\CompanyInterface::class
        );
        return $company;
    }
    public function getCompanyData(array $datas) 
    {
        if(!count($datas)){
            $this->logger->info("company csv file is empty!");
        }
        $companyData = [];
        foreach ($datas as $key => $data) 
        {
            $companyData[] =
            [
                "company_name" => $data[16],
                "company_email" => $data[15],
                "street" => [$data[13]],
                "city" => $data[12],
                "postcode" => $data[11],
                "telephone" => $data[8],
                "country_id" => "US",
                "region_id" => "12",
                "firstname" => $data[0],
                "lastname" => $data[1],
                "website_id" => "1",
                "email" => $data[15],
                "customer_group_id" => "1"
            ];
        }
        return $companyData;
    }
    public function getCompanyIsnData(array $datas) 
    {
        if(!count($datas)){
            $this->logger->info("company csv file is empty!");
        }
        $companyIsnData = [];
        foreach ($datas as $key => $data) 
        {
            $companyIsnData[] = 
            [
                "parent_company_id" => 1,
                "is_active" => 1,
                "customer_number" => $data[0],
                "parent_customer_number" => $data[1],
                "po_number_required" => $data[2],
                "credit_allow" => $data[3],
                "account_allow" => $data[4],
                "creation_time" => date('m/d/Y h:i:s a', time()),
                "update_time" => date('m/d/Y h:i:s a', time()),
                "customer_price_group" => $data[5],
                "customer_group" => $data[6],
                "ship_to_address_attention" => $data[7],
                "ship_to_address_phone" => $data[8],
                "ship_to_address_country" => $data[9],
                "ship_to_address_state" => $data[10],
                "ship_to_address_zip" => $data[11],
                "ship_to_address_city" => $data[12],
                "ship_to_address_1" => $data[13],
                "ship_to_address_2" => $data[14],
                "ship_to_address_email" => $data[15],
                "website_id" => 1
            ];
        }
        return $companyIsnData;
    }
}
