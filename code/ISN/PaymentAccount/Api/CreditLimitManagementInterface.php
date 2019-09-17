<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Api;

/**
 * Credit Limit management interface.
 *
 * @api
 * @since 100.0.0
 */
interface CreditLimitManagementInterface
{
    /**
     * Returns data on the credit limit for a specified company.
     *
     * @param int $companyId
     * @return \ISN\PaymentAccount\Api\Data\CreditLimitInterface
     */
    public function getCreditByCompanyId($companyId);
}
