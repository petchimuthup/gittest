<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Ui\Component\History;

use ISN\PaymentAccount\Model\ResourceModel\History\CollectionFactory;
use ISN\PaymentAccount\Api\CreditDataProviderInterface;
use Magento\Framework\App\RequestInterface;
use ISN\PaymentAccount\Model\HistoryFactory;

/**
 * Class DataProvider.
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var \ISN\PaymentAccount\Api\CreditDataProviderInterface
     */
    private $creditDataProvider;

    /**
     * @var \ISN\PaymentAccount\Model\CreditDetails\CustomerProvider
     */
    private $customerProvider;

    /**
     * DataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param CreditDataProviderInterface $creditDataProvider
     * @param \ISN\PaymentAccount\Model\CreditDetails\CustomerProvider $customerProvider
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        CreditDataProviderInterface $creditDataProvider,
        \ISN\PaymentAccount\Model\CreditDetails\CustomerProvider $customerProvider,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->request = $request;
        $this->collection = $collectionFactory->create();
        $this->creditDataProvider = $creditDataProvider;
        $this->customerProvider = $customerProvider;
    }

    /**
     * Get data.
     *
     * @return array
     */
    public function getData()
    {
        $creditId = $this->getCreditIdByCompanyId();
        $this->getCollection()->addFieldToFilter('main_table.company_credit_id', ['eq' => $creditId]);
        return parent::getData();
    }

    /**
     * Get related History by current company.
     *
     * @return array
     */
    public function getCreditIdByCompanyId()
    {
        if ($this->customerProvider->getCurrentUserCredit()) {
            return $this->customerProvider->getCurrentUserCredit()->getId();
        }

        if (!$this->request->getParam('id')) {
            return 0;
        }

        $credit = $this->creditDataProvider->get($this->request->getParam('id'));

        return (int)$credit->getId();
    }
}
