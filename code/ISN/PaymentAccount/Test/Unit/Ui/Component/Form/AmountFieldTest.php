<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Ui\Component\Form;

/**
 * Unit test for AmountField.
 */
class AmountFieldTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \ISN\PaymentAccount\Ui\Component\Form\AmountField
     */
    private $amountField;

    /**
     * @var \ISN\PaymentAccount\Api\CreditDataProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $creditDataProvider;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $priceCurrency;

    /**
     * @var \ISN\PaymentAccount\Model\WebsiteCurrency|\PHPUnit_Framework_MockObject_MockObject
     */
    private $websiteCurrency;

    /**
     * @var \Magento\Framework\View\Element\UiComponentFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $uiComponentFactory;

    /**
     * @var \Magento\Framework\View\Element\UiComponentInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $wrappedComponent;

    /**
     * @var \Magento\Framework\View\Element\UiComponent\ContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $context;

    /**
     * @var \Magento\Directory\Model\Currency|\PHPUnit_Framework_MockObject_MockObject
     */
    private $currencyFormatter;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->creditDataProvider = $this->getMockBuilder(\ISN\PaymentAccount\Api\CreditDataProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->priceCurrency = $this->getMockBuilder(\Magento\Framework\Pricing\PriceCurrencyInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->websiteCurrency = $this->getMockBuilder(\ISN\PaymentAccount\Model\WebsiteCurrency::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->context = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\ContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->wrappedComponent = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponentInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->uiComponentFactory = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponentFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->currencyFormatter = $this->getMockBuilder(\Magento\Directory\Model\Currency::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->amountField = $objectManager->getObject(
            \ISN\PaymentAccount\Ui\Component\Form\AmountField::class,
            [
                'context' => $this->context,
                'creditDataProvider' => $this->creditDataProvider,
                'priceCurrency' => $this->priceCurrency,
                'websiteCurrency' => $this->websiteCurrency,
                'uiComponentFactory' => $this->uiComponentFactory,
                'currencyFormatter' => $this->currencyFormatter
            ]
        );
        $this->amountField->setData(
            'config',
            [
                'formElement' => '1',
                'defaultFieldValue' => 0
            ]
        );
    }

    /**
     * Test method for prepare.
     *
     * @return void
     */
    public function testPrepareWithCredit()
    {
        $formattedDefaultFieldValue = '0.00';
        $contextComponent = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\ContextInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->uiComponentFactory->expects($this->once())->method('create')->willReturn($this->wrappedComponent);
        $this->wrappedComponent->expects($this->once())->method('getContext')->willReturn($contextComponent);
        $this->context->expects($this->atLeastOnce())->method('getRequestParam')->with('id')->willReturn(1);
        $creditData = $this->getMockBuilder(\ISN\PaymentAccount\Api\Data\CreditDataInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $creditData->expects($this->atLeastOnce())->method('getCurrencyCode')->willReturn('USD');
        $this->creditDataProvider->expects($this->once())->method('get')->with(1)->willReturn($creditData);
        $this->priceCurrency->expects($this->once())->method('getCurrencySymbol')->willReturn('$');
        $processor = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\Processor::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->context->expects($this->atLeastOnce())->method('getProcessor')->willReturn($processor);
        $this->currencyFormatter->expects($this->atLeastOnce())->method('formatTxt')
            ->willReturn($formattedDefaultFieldValue);
        $this->amountField->prepare();
        $expected = [
            'addbefore' => '$',
            'value' => $formattedDefaultFieldValue
        ];

        $this->assertEquals($expected, $this->amountField->getData('config'));
    }

    /**
     * Test method for prepare.
     *
     * @return void
     */
    public function testPrepareWithoutCredit()
    {
        $formattedDefaultFieldValue = '0.00';
        $contextComponent = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\ContextInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->uiComponentFactory->expects($this->once())->method('create')->willReturn($this->wrappedComponent);
        $this->wrappedComponent->expects($this->once())->method('getContext')->willReturn($contextComponent);
        $this->context->expects($this->atLeastOnce())->method('getRequestParam')->with('id')->willReturn(1);
        $creditData = $this->getMockBuilder(\ISN\PaymentAccount\Api\Data\CreditDataInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $creditData->expects($this->atLeastOnce())->method('getCurrencyCode')->willReturn(null);
        $this->creditDataProvider->expects($this->once())->method('get')->with(1)->willReturn($creditData);
        $this->priceCurrency->expects($this->once())->method('getCurrencySymbol')->willReturn('$');
        $processor = $this->getMockBuilder(\Magento\Framework\View\Element\UiComponent\Processor::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->context->expects($this->atLeastOnce())->method('getProcessor')->willReturn($processor);
        $this->currencyFormatter->expects($this->atLeastOnce())->method('formatTxt')
            ->willReturn($formattedDefaultFieldValue);
        $this->amountField->prepare();
        $expected = [
            'addbefore' => '$',
            'value' => $formattedDefaultFieldValue
        ];

        $this->assertEquals($expected, $this->amountField->getData('config'));
    }
}
