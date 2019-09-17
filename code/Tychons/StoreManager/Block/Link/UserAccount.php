<?php

namespace Tychons\StoreManager\Block\Link;

/**
 * Class CompanyLink.
 *
 * @api
 */
class UserAccount extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * Render block HTML.
     *
     * @return string
     */
    protected function _toHtml()
    {

        $role = $this->userRole();

        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }
        return '<li><a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a></li>';
    }

    public function getCustomerId()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customerSession = $objectManager->create('Magento\Customer\Model\Session');

        $customerId = $customerSession->getCustomer()->getId();

        if (!empty($customerId)) {
           
           return $customerId;

        }else{

            return;
        }
    }

    public function userRole()
    {

        $customerId = $this->getCustomerId();

        if (empty($customerId)) {
           
           return;

        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

        return $customer->getRoleId();

    }
}

