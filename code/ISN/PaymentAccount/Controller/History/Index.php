<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ISN\PaymentAccount\Controller\History;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;

/**
 * Class Index.
 */
class Index extends \ISN\PaymentAccount\Controller\AbstractAction implements HttpGetActionInterface
{
    /**
     * View company credit balance history.
     *
     * @return \Magento\Framework\View\Result\Page
     * @throws \InvalidArgumentException
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('Company Credit'));
        $navigationBlock = $resultPage->getLayout()
            ->getBlock('customer-account-navigation-company-credit-history-link');

        if ($navigationBlock) {
            $navigationBlock->setActive('company_credit/history');
        }

        return $resultPage;
    }
}
