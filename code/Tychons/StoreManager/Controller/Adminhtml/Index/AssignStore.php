<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tychons\StoreManager\Controller\Adminhtml\Index;
 
class AssignStore extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Customer compare grid
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
       
        $this->initCurrentCustomer();
        $resultLayout = $this->resultLayoutFactory->create();
        return $resultLayout;
    }
 
 
}