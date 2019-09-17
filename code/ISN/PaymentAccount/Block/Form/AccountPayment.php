<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Block\Form;

/**
 * Class AccountPayment.
 */
class AccountPayment extends \Magento\Payment\Block\Form
{
    /**
     * AccountPayment order template.
     *
     * @var string
     */
    protected $_template = 'ISN_PaymentAccount::form/accountpayment.phtml';
}
