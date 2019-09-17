<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Test\Unit\Model\History\Source;

/**
 * Class OperationTest.
 */
class OperationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \ISN\PaymentAccount\Model\History\Source\Operation
     */
    private $operation;

    /**
     * Set up.
     *
     * @return void
     */
    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->operation = $objectManager->getObject(
            \ISN\PaymentAccount\Model\History\Source\Operation::class
        );
    }

    /**
     * Test for method getAllOptions.
     *
     * @return void
     */
    public function testGetAllOptions()
    {
        $expectedResult = array_map(
            function ($label, $value) {
                return ['value' => $value, 'label' => $label];
            },
            \ISN\PaymentAccount\Model\History\Source\Operation::getOptionArray(),
            array_keys(\ISN\PaymentAccount\Model\History\Source\Operation::getOptionArray())
        );
        $this->assertEquals($expectedResult, $this->operation->getAllOptions());
    }
}
