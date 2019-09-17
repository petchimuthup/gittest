<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tychons\StoreManager\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;

class Index extends \Magento\Customer\Controller\Adminhtml\Index implements HttpGetActionInterface
{
    /**
     * Customers list action
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('grid');
            return $resultForward;
        }
        $resultPage = $this->resultPageFactory->create();
        /**
         * Set active menu item
         */
        $resultPage->setActiveMenu('Magento_Customer::customer_manage');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Users'));

        /**
         * Add breadcrumb item
         */
        $resultPage->addBreadcrumb(__('Manage Users'), __('Manage Users'));
        $resultPage->addBreadcrumb(__('Manage Users'), __('Manage Users'));

        $this->_getSession()->unsCustomerData();
        $this->_getSession()->unsCustomerFormData();

        return $resultPage;
    }
}
