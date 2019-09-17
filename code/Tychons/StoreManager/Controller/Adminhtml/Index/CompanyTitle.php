<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tychons\StoreManager\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Company\Controller\Adminhtml\Index as CompanyAction;

/**
 * Companies list. Needs to be accessible by POST because of filtering.
 */
class CompanyTitle extends CompanyAction implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * Companies groups list
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Company::company_index');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Stores'));
        $resultPage->addBreadcrumb(__('Manage Stores'), __('Manage Stores'));
        return $resultPage;
    }
}
