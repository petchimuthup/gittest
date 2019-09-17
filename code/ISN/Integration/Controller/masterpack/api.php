<?php
namespace ISN\Integration\Controller\masterpack;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;

class api extends \Magento\Framework\App\Action\Action
{
    protected $_scopeConfig;
    protected $httpClientFactory;
    protected $_customerSession;
    protected $logger;
    /** @var \Tychons\StoreManager\Model\StoreSelectFactory */
    protected $storeSelectFactory;
    /** @var $companyISNFactory \ISN\CompanyExt\Model\CompanyISNFactory */
    protected $companyISNFactory;
    /** @var \Magento\Company\Model\CompanyFactory */
    protected $companyFactory;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;



    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Psr\Log\LoggerInterface $loggerInterface,
        \Magento\Framework\App\Action\Context $context,
        \Tychons\StoreManager\Model\StoreSelectFactory $storeSelectFactory,
        \ISN\CompanyExt\Model\CompanyISNFactory $companyISNFactory,
        \Magento\Company\Model\CompanyFactory $companyFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory)
    {
        $this->_scopeConfig = $scopeConfig;;
        $this->httpClientFactory = $httpClientFactory;;
        $this->_customerSession = $customerSession->create();
        $this->logger = $loggerInterface;
        $this->storeSelectFactory = $storeSelectFactory;
        $this->companyISNFactory = $companyISNFactory;
        $this->companyFactory = $companyFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function dispatch(RequestInterface $request){
        if(!$this->_objectManager->get(\Magento\Customer\Model\Session::class)->authenticate())
            return null;
        return parent::dispatch($request);
    }

    public function execute(){
        $route = $this->getRequest()->getRouteName();
        $getParm = $this->getRequest()->getParams();
        $postParm = file_get_contents("php://input");
        return $this->$route($postParm, $getParm);
    }

    public function connect2WebService($url, $data, $port, $method){
        $client = $this->httpClientFactory->create();
        $client->setUri($url);
        $client->getUri()->setPort($port);
        $client->setConfig(['timeout' => 300]);
        $client->setHeaders(['Content-Type: application/json', 'Accept: application/json']);
        $client->setMethod($method);
        $client->setRawData($data);
        try{
            /** @var \Magento\Framework\Controller\Result\Json $resultJson */
            $resultJson = $this->resultJsonFactory->create();
            $resultJson->setData(json_decode($client->request()->getBody(), true));
            return $resultJson;
        } catch (\Exception $ex){
            $this->logger->error(__METHOD__ . "Error: " . $ex);
            return null;
        }
    }

    public function connect2CrystalReport($url, $data, $port, $method){
        $client = $this->httpClientFactory->create();
        $client->setUri($url);
        $client->getUri()->setPort($port);
        $client->setConfig(['timeout' => 300]);
        $client->setHeaders(['Content-Type: application/pdf;charset=UTF-8', 'content-disposition: inline; filename="Default ISNI Invoice-MPLIVE.pdf"']);
        $client->setMethod($method);

        try{
            return $client->request()->getBody();
        } catch (\Exception $ex){
            $this->logger->error(__METHOD__ . "Error: " . $ex);
            return null;
        }
    }

    public function GetCreditDocument ($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $parmArray = json_decode(json_encode($getParm), true);
            $Request["CreditNumber"] = $parmArray["CreditNumber"];
            $Request["Account"] = $account;

            $creditDoc_url = $this->_scopeConfig->getValue("crystal_report/general/CREDIT_DOCUMENT_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalEndPoint = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalPassword = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_PASSWORD", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalUser = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_USER", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalDocumentId = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_CREDIT_DOCUMENT_ID", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);

            $method =  \Zend_Http_Client::GET;
            $parmArray = array($crystalEndPoint, $crystalPassword, $parmArray["CreditNumber"], $crystalDocumentId, $crystalUser);
            $CreditDocument_url = msgfmt_format(msgfmt_create("en_US",$creditDoc_url), $parmArray);

            header('Content-Type: application/pdf;charset=UTF-8');
            header('content-disposition: inline; filename="Default ISNI Invoice-MPLIVE.pdf"');
            echo $this->connect2CrystalReport($CreditDocument_url, json_decode(json_encode($Request), true), $port, $method);
            return;
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function GetStatementDocument($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $parmArray = json_decode(json_encode($getParm), true);
            $Request["DocumentId"] = $parmArray["DocumentId"];
            $Request["Account"] = $account;

            $statmentDoc_url = $this->_scopeConfig->getValue("crystal_report/general/STATEMENT_DOCUMENT_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalEndPoint = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalPassword = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_PASSWORD", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalUser = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_USER", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalDocumentId = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_STATEMENT_DOCUMENT_ID", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);

            $method =  \Zend_Http_Client::GET;
            $parmArray = array($crystalEndPoint, $crystalPassword, $parmArray["DocumentId"], $parmArray["Account"], $crystalDocumentId, $crystalUser);
            $StatmentDocument_url = msgfmt_format(msgfmt_create("en_US",$statmentDoc_url), $parmArray);

            header('Content-Type: application/pdf;charset=UTF-8');
            header('content-disposition: inline; filename="Default ISNI Invoice-MPLIVE.pdf"');
            echo $this->connect2CrystalReport($StatmentDocument_url, json_decode(json_encode($Request), true), $port, $method);
            return;
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function GetInvoiceDocument($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $parmArray = json_decode(json_encode($getParm), true);
            $Request["InvoiceNumber"] = $parmArray["InvoiceNumber"];
            $Request["Company"] = "ISNI";
            $Request["Account"] = $account;
            $Request["StartDate"] = null;
            $Request["EndDate"] = null;
            $Request["OrderNumber"] = null;

            $invoiceDoc_url = $this->_scopeConfig->getValue("crystal_report/general/INVOICE_DOCUMENT_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalEndPoint = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalPassword = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_PASSWORD", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalUser = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_USER", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $crystalDocumentId = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_INVOICE_DOCUMENT_ID", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("crystal_report/general/CRYSTAL_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);

            $method =  \Zend_Http_Client::GET;
            $parmArray = array($crystalEndPoint, $crystalPassword, $parmArray["InvoiceNumber"], $crystalDocumentId, $crystalUser);
            $InvoiceDocument_url = msgfmt_format(msgfmt_create("en_US",$invoiceDoc_url), $parmArray);

            header('Content-Type: application/pdf;charset=UTF-8');
            header('content-disposition: inline; filename="Default ISNI Invoice-MPLIVE.pdf"');
            echo $this->connect2CrystalReport($InvoiceDocument_url, json_decode(json_encode($Request), true), $port, $method);
            return;
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }



    public function GetPurchaseHistory($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $PurchaseHistoryRequest["Company"] = "ISN";
            $PurchaseHistoryRequest["TradingPartner"] = "HYBRIS";
            $PurchaseHistoryRequest["OrderNumber"] = "ISN";
            $PurchaseHistoryRequest["AccountNumber"] = strtoupper($account);
            $PurchaseHistoryRequest["RequestType"] = "S";
            $PurchaseHistoryRequest["SearchType"] = "I";
            $PurchaseHistoryRequest["Search"] = "ISN";
            $PurchaseHistoryRequest["From"] = date("m/d/Y", strtotime('-30 days'));
            $PurchaseHistoryRequest["To"] = date("m/d/Y");
            $PurchaseHistoryRequest["PageSize"] = 3;
            $PurchaseHistoryRequest["PageNumber"] = 1;

            $productOrderHistorySummary_url = $this->_scopeConfig->getValue("order_history/general/ORDER_HEADER_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::POST;

            header('Content-Type: application/json');
            $jsonArray = $this->connect2WebService($productOrderHistorySummary_url, json_encode($PurchaseHistoryRequest), $port, $method);
            $count = count($jsonArray);
            if($count == 0)
                return null;
            else {
                if ($count > 3) {
                    $threeArray = array_chunk($jsonArray, 3);
                    return json_encode($threeArray);
                } else{
                    return $jsonArray;
                }
            }
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function GetOrderHeader($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $OrderHeaderRequest['Company'] = 'ISN';
            $OrderHeaderRequest['TradingPartner'] = 'HYBRIS';
            $OrderHeaderRequest['OrderNumber'] = 'ISN';
            $OrderHeaderRequest['AccountNumber'] = strtoupper($account);
            $OrderHeaderRequest['SearchType'] = null;
            $OrderHeaderRequest['Search'] = null;
            $OrderHeaderRequest['From'] = null;
            $OrderHeaderRequest['To'] = null;
            $OrderHeaderRequest['PageSize'] = 50;
            $OrderHeaderRequest['PageNumber'] = 1;

            $parmArray = json_decode($postParm, true);

            if ($parmArray['RequestType'] != null && $parmArray['RequestType'] != "")
                $OrderHeaderRequest["RequestType"] = "S";
            else
                $OrderHeaderRequest["RequestType"] = "O";

            $productOrderHeader_url =  $port = $this->_scopeConfig->getValue("order_history/general/ORDER_HEADER_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::POST;

            header('Content-Type: application/json');
            return $this->connect2WebService($productOrderHeader_url, json_encode($OrderHeaderRequest), $port, $method);
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function GetOrderDetail($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if($account == null)
                return null;

            $OrderDetailRequest["Company"] = "ISN";
            $OrderDetailRequest["TradingPartner"] = "HYBRIS";
            $OrderDetailRequest["OrderNumber"] = "ISN";
            $OrderDetailRequest["AccountNumber"] = strtoupper($account);
            $OrderDetailRequest["From"] = null;
            $OrderDetailRequest["To"] = null;
            $OrderDetailRequest["PageSize"] = 50;
            $OrderDetailRequest["PageNumber"] = 1;

            $parmArray = json_decode(json_encode($postParm), true);
            $parmValues = $parmArray["orderNumber"];
            $parmValuesArray = explode("*", $parmValues);
            $lRequest["Search"] = $parmValuesArray[count($parmValuesArray)-1];
            if ($OrderDetailRequest["Search"] != null && $OrderDetailRequest["Search"] != "") {
                $OrderDetailRequest["SearchType"] = "S";
                $OrderDetailRequest["RequestType"] = "O";
            } else
                $OrderDetailRequest["RequestType"] = "O";

            $productOrderDetail_url = $this->_scopeConfig->getValue("order_history/general/ORDER_DETAIL_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::POST;

            header('Content-Type: application/json');
            return $this->connect2WebService($productOrderDetail_url, json_encode($OrderDetailRequest), $port, $method);
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function GetInvoiceHeader($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $InvoiceHeaderRequest["Company"] = "ISN";
            $InvoiceHeaderRequest["Account"] = $account;
            $InvoiceHeaderRequest["StartDate"] = null;
            $InvoiceHeaderRequest["EndDate"] = null;
            $InvoiceHeaderRequest["OrderNumber"] = null;

            $parmArray = json_decode($postParm, true);
            if(empty($parmArray["InvoiceNumber"]))
                $InvoiceHeaderRequest["InvoiceNumber"] = null;
            else
                $InvoiceHeaderRequest["InvoiceNumber"] = $parmArray["InvoiceNumber"];

            if(empty($parmArray["StartDate"]))
                $InvoiceHeaderRequest["StartDate"] = null;
            else
                $InvoiceHeaderRequest["StartDate"] = $parmArray["StartDate"];

            if(empty($parmArray["EndDate"]))
                $InvoiceHeaderRequest["EndDate"] = null;
            else
                $InvoiceHeaderRequest["EndDate"] = $parmArray["EndDate"];

            if(empty($parmArray["OrderNumber"]))
                $InvoiceHeaderRequest["OrderNumber"] = null;
            else
                $InvoiceHeaderRequest["OrderNumber"] = $parmArray["OrderNumber"];

            $invoiceHeader_url = $this->_scopeConfig->getValue("order_history/general/INVOICEH_HEADER_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::POST;

            header('Content-Type: application/json');
            return $this->connect2WebService($invoiceHeader_url, json_encode($InvoiceHeaderRequest), $port, $method);
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function GetInvoiceDetail($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;
            $parmArray = json_decode($postParm, true);
            $InvoiceDetailRequest["InvoiceNumber"] = $parmArray["InvoiceNumber"];
            $InvoiceDetailRequest["account"] = $account;
            $parmArray = array($InvoiceDetailRequest["InvoiceNumber"], $InvoiceDetailRequest["account"]);

            $invoiceDetail_url = $this->_scopeConfig->getValue("order_history/general/INVOICE_DETAIL_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $invoiceDetail_url_final = msgfmt_format(msgfmt_create("en_US",$invoiceDetail_url), $parmArray);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::GET;

            header('Content-Type: application/json');
            return  $this->connect2WebService($invoiceDetail_url_final, json_encode($InvoiceDetailRequest), $port, $method);
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function GetCreditHeader($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $parmArray = json_decode($postParm, true);
            $CreditHeaderRequest['Company'] = 'ISNI';
            $CreditHeaderRequest['Account'] = $account;

            if(empty($parmArray["StartDate"]))
                $CreditHeaderRequest['StartDate'] = null;
            else
                $CreditHeaderRequest['StartDate'] = $parmArray["StartDate"];

            if(empty($parmArray["EndDate"]))
                $CreditHeaderRequest['EndDate'] = null;
            else
                $CreditHeaderRequest['EndDate'] = $parmArray["EndDate"];

            if(empty($parmArray["CreditNumber"]))
                $CreditHeaderRequest['CreditNumber'] = null;
            else
                $CreditHeaderRequest['CreditNumber'] = $parmArray["CreditNumber"];

            $creditHeader_url = $this->_scopeConfig->getValue("order_history/general/CREDIT_HEADER_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::POST;

            header('Content-Type: application/json');
            return $this->connect2WebService($creditHeader_url, json_encode($CreditHeaderRequest), $port, $method);
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function GetCreditDetail($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $parmArray = json_decode($postParm, true);
            $CreditDetailRequest["CreditNumber"] = $parmArray["CreditNumber"];
            $CreditDetailRequest["account"] = $this->getActiveAccount();
            $parmArray = array($CreditDetailRequest["CreditNumber"], $CreditDetailRequest["account"]);

            $creditDetail_url = $this->_scopeConfig->getValue("order_history/general/CREDIT_DETAIL_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::GET;
            $creditDetail_url_final = msgfmt_format(msgfmt_create("en_US",$creditDetail_url), $parmArray);

            header('Content-Type: application/json');
            return $this->connect2WebService($creditDetail_url_final, json_encode($CreditDetailRequest), $port, $method);
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function Statements($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $StatementsRequest["account"] = $account;

            $statements_url = $this->_scopeConfig->getValue("order_history/general/STATEMENTS_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::GET;

            $statements_url_final = msgfmt_format(msgfmt_create("en_US",$statements_url), array($account));

            header('Content-Type: application/json');
            return $this->connect2WebService($statements_url_final, json_encode($StatementsRequest), $port, $method);
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function GetBackorderSummary($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $BackorderSummaryRequest["Company"] = "ISN";
            $BackorderSummaryRequest["TradingPartner"] = "HYBRIS";
            $BackorderSummaryRequest["OrderNumber"] = null;
            $BackorderSummaryRequest["AccountNumber"] = strtoupper($account);
            $BackorderSummaryRequest["RequestType"] = "O";
            $BackorderSummaryRequest["SearchType"] = null;
            $BackorderSummaryRequest["Search"] = null;
            $BackorderSummaryRequest["From"] = null;
            $BackorderSummaryRequest["To"] = null;
            $BackorderSummaryRequest["PageSize"] = 50;
            $BackorderSummaryRequest["PageNumber"] = 1;

            $backorderSummary_url =  $this->_scopeConfig->getValue("order_history/general/BACKORDER_SUMMARY_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::GET;

            header('Content-Type: application/json');
            return $this->connect2WebService($backorderSummary_url, json_encode($BackorderSummaryRequest), $port, $method);
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function GetPurchaseDetail($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $parmArray = json_decode($postParm, true);
            $PurchaseDetailRequest["Company"] = "ISNI";
            $PurchaseDetailRequest["Account"] = $account;

            if(empty($parmArray["From_dt"]))
                $PurchaseDetailRequest["From_dt"] = null;
            else
                $PurchaseDetailRequest["From_dt"] = $parmArray["From_dt"];

            if(empty($parmArray["To_dt"]))
                $PurchaseDetailRequest["To_dt"] = null;
            else
                $PurchaseDetailRequest["To_dt"] = $parmArray["To_dt"];

            if(empty($parmArray["Region"]))
                $PurchaseDetailRequest["Region"] = null;
            else
                $PurchaseDetailRequest["Region"] = $parmArray["Region"];

            if(empty($parmArray["Store_Number"]))
                $PurchaseDetailRequest["Store_Number"] = null;
            else
                $PurchaseDetailRequest["Store_Number"] = $parmArray["Store_Number"];

            if(empty($parmArray["Part_Number"]))
                $PurchaseDetailRequest["Part_Number"] = null;
            else
                $PurchaseDetailRequest["Part_Number"] = $parmArray["Part_Number"];

            if(empty($parmArray["Category"]))
                $PurchaseDetailRequest["Category"] = null;
            else
                $PurchaseDetailRequest["Category"] = $parmArray["Category"];

            if(empty($parmArray["Vendor"]))
                $PurchaseDetailRequest["Vendor"] = null;
            else
                $PurchaseDetailRequest["Vendor"] = $parmArray["Vendor"];

            $PurchaseDetail_url =  $this->_scopeConfig->getValue("order_history/general/PURCHASE_DETAIL_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::POST;

            header('Content-Type: application/json');
            return $this->connect2WebService($PurchaseDetail_url, json_encode($PurchaseDetailRequest), $port, $method);
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }


    public function GetPurchaseSummary($postParm, $getParm){
        try {
            $account = $this->getActiveAccount();
            if ($account == null)
                return null;

            $parmArray = json_decode($postParm, true);
            $GetPurchaseSummaryRequest["Company"] = "ISNI";
            $GetPurchaseSummaryRequest["Account"] = $account;

            if(empty($parmArray["From_dt"]))
                $GetPurchaseSummaryRequest["From_dt"] = null;
            else
                $GetPurchaseSummaryRequest["From_dt"] = $parmArray["From_dt"];

            if(empty($parmArray["To_dt"]))
                $GetPurchaseSummaryRequest["To_dt"] = null;
            else
                $GetPurchaseSummaryRequest["To_dt"] = $parmArray["To_dt"];

            if(empty($parmArray["Region"]))
                $GetPurchaseSummaryRequest["Region"] = null;
            else
                $GetPurchaseSummaryRequest["Region"] = $parmArray["Region"];

            if(empty($parmArray["Store_Number"]))
                $GetPurchaseSummaryRequest["Store_Number"] = null;
            else
                $GetPurchaseSummaryRequest["Store_Number"] = $parmArray["Store_Number"];

            if(empty($parmArray["Part_Number"]))
                $GetPurchaseSummaryRequest["Part_Number"] = null;
            else
                $GetPurchaseSummaryRequest["Part_Number"] = $parmArray["Part_Number"];

            if(empty($parmArray["Category"]))
                $GetPurchaseSummaryRequest["Category"] = null;
            else
                $GetPurchaseSummaryRequest["Category"] = $parmArray["Category"];

            if(empty($parmArray["Vendor"]))
                $GetPurchaseSummaryRequest["Vendor"] = null;
            else
                $GetPurchaseSummaryRequest["Vendor"] = $parmArray["Vendor"];

            $PurchaseSummary_url =  $this->_scopeConfig->getValue("order_history/general/PURCHASE_SUMMARY_ENDPOINT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $port = $this->_scopeConfig->getValue("order_history/general/WEBSERVICE_PORT", \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $method =  \Zend_Http_Client::POST;

            header('Content-Type: application/json');
            return $this->connect2WebService($PurchaseSummary_url, json_encode($GetPurchaseSummaryRequest), $port, $method);
        }
        catch(\Exception $ex) {
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }


    public function getCurrentUserId(){
        try {
            $customer_data = $this->_customerSession->getCustomerData();
            $customerId = $customer_data->getId();
            if (empty($customerId))
                return null;
            else
                return $customerId;
        }
        catch (\Exception $ex){
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function getActiveStoreId($customerId){
        try {
            /** @var $activeStore \Tychons\StoreManager\Model\StoreSelect */
            $activeStore = $this->storeSelectFactory->create()->load($customerId, 'customer_id');
            $activeStoreId = null;

            if (null !== $activeStore && isset($activeStore) && null !== $activeStore->getDataByKey('userstore_id'))
                $activeStoreId = $activeStore->getUserstoreId();

            return $activeStoreId;
        }
        catch (\Exception $ex){
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }

    public function getActiveAccount(){
        try {
             $customerId = self::getCurrentUserId();
             $userStoreId = self::getActiveStoreId($customerId);

             /** @var $company \Magento\Company\Model\Company */
            $company = $this->companyFactory->create()->load($userStoreId);

            /** @var $companyISN \ISN\CompanyExt\Model\CompanyISN */
            $companyISN = $this->companyISNFactory->create()->load($company->getEntityId(), 'company_id');

            if (null !== $companyISN && isset($companyISN))
               return $companyISN->getCustomerNumber() . "_account";

            return null;
        }
        catch (\Exception $ex){
            $this->logger->error(__METHOD__ . " Error: " . $ex);
            return null;
        }
    }
}