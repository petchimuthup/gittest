<?php

namespace Tychons\Storeorder\Ui\Component\Listing\Column;

use \Magento\Sales\Api\OrderRepositoryInterface;
use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Company\Model\CompanyFactory;
use \Magento\Framework\Api\SearchCriteriaBuilder;
 
class CompanyName extends Column
{
 
    protected $_orderRepository;

    protected $_searchCriteria;

    protected $_companyfactory;
 
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        OrderRepositoryInterface $orderRepository,
        CompanyFactory $companyFactory,
        SearchCriteriaBuilder $criteria,
        array $components = [], array $data = [])
    {
        $this->_orderRepository = $orderRepository;
        $this->_companyfactory = $companyFactory;
        $this->_searchCriteria  = $criteria;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
 
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as & $item) {

                $order  = $this->_orderRepository->get($item["entity_id"]);
 
                $companyId = $order->getCompanyId();

                $this->getCompanyName($companyId);
 
                $item[$this->getData('name')] = $this->getCompanyName($companyId);
            }
        }

        return $dataSource;
    }

    public function getCompanyName($companyId)
    {

        $company = $this->_companyfactory->create()->load($companyId);

        if ($company->getId()) {
            
            return $company->getCompanyName();

        }else{

            return;
        }
    }
}
