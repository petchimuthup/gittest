<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Ui\Component\History;

/**
 * Class DataProviderTest.
 */
class DataProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var \ISN\PaymentAccount\Api\CreditDataProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditDataProvider;

    /**
     * @var \ISN\PaymentAccount\Model\HistoryFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $historyFactory;

    /**
     * @var \ISN\PaymentAccount\Model\ResourceModel\History\Collection
     */
    private $collection;

    /**
     * @var \ISN\PaymentAccount\Ui\Component\History\DataProvider
     */
    private $dataProvider;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->request = $this->createMock(
            \Magento\Framework\App\RequestInterface::class
        );
        $this->creditDataProvider = $this->createMock(
            \ISN\PaymentAccount\Api\CreditDataProviderInterface::class
        );
        $this->historyFactory = $this->createPartialMock(
            \ISN\PaymentAccount\Model\HistoryFactory::class,
            ['create']
        );
        $this->collection = $this->createMock(
            \ISN\PaymentAccount\Model\ResourceModel\History\Collection::class
        );
        $collectionFactory = $this->createPartialMock(
            \ISN\PaymentAccount\Model\ResourceModel\History\CollectionFactory::class,
            ['create']
        );
        $collectionFactory->expects($this->once())->method('create')->willReturn($this->collection);

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->dataProvider = $objectManager->getObject(
            \ISN\PaymentAccount\Ui\Component\History\DataProvider::class,
            [
                'request' => $this->request,
                'creditDataProvider' => $this->creditDataProvider,
                'historyFactory' => $this->historyFactory,
                'collectionFactory' => $collectionFactory,
            ]
        );
    }

    /**
     * Test for getData method.
     *
     * @param int|null $companyId
     * @param int $creditId
     * @param int $paramInvocations
     * @param int $creditInvocations
     * @return void
     * @dataProvider getDataDataProvider
     */
    public function testGetData($companyId, $creditId, $paramInvocations, $creditInvocations)
    {
        $result = ['collection data'];
        $this->request->expects($this->exactly($paramInvocations))
            ->method('getParam')->with('id')->willReturn($companyId);
        $creditData = $this->createMock(\ISN\PaymentAccount\Api\Data\CreditDataInterface::class);
        $this->creditDataProvider->expects($this->exactly($creditInvocations))
            ->method('get')->with($companyId)->willReturn($creditData);
        $creditData->expects($this->exactly($creditInvocations))->method('getId')->willReturn($creditId);
        $this->collection->expects($this->once())->method('addFieldToFilter')
            ->with('main_table.company_credit_id', ['eq' => $creditId])->willReturnSelf();
        $this->collection->expects($this->once())->method('toArray')->willReturn($result);
        $this->assertEquals($result, $this->dataProvider->getData());
    }

    /**
     * Data provider for testGetData.
     *
     * @return array
     */
    public function getDataDataProvider()
    {
        return [
            [1, 2, 2, 1],
            [null, 0, 1, 0],
        ];
    }
}
