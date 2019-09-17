<?php

namespace Tychons\StoreManager\Setup;

use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'userstore_id', [
            'type' => 'varchar',
            'label' => 'Assign Store',
            'input' => 'multiselect',
            'source' => 'Tychons\StoreManager\Model\Customer\Attribute\Source\StoreId',
            'required' => false,
            'visible' => true,
            'position' => 30,
            'system' => false,
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend'
        ]);
        
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'userstore_id')
        ->addData(['used_in_forms' => [
                'adminhtml_customer'
            ]
        ]);
        $attribute->save();

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'role_id', [
            'type' => 'int',
            'label' => 'Select Role',
            'input' => 'select',
            'source' => 'Tychons\StoreManager\Model\Customer\Attribute\Source\RoleId',
            'required' => false,
            'visible' => true,
            'position' => 35,
            'system' => false,
            'backend' => ''
        ]);
        
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'role_id')
        ->addData(['used_in_forms' => [
                'adminhtml_customer'
            ]
        ]);
        $attribute->save();

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'password', [
            'type' => 'varchar',
            'label' => 'Password',
            'input' => 'text',
            'source' => '',
            'required' => false,
            'visible' => true,
            'position' => 333,
            'system' => false,
            'backend' => ''
        ]);
        
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'password')
        ->addData(['used_in_forms' => [
                'adminhtml_customer'
            ]
        ]);
        $attribute->save();

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'conf_password', [
            'type' => 'varchar',
            'label' => 'conf_password',
            'input' => 'text',
            'source' => '',
            'required' => false,
            'visible' => true,
            'position' => 340,
            'system' => false,
            'backend' => ''
        ]);
        
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'conf_password')
        ->addData(['used_in_forms' => [
                'adminhtml_customer'
            ]
        ]);

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'user_active_store', [
            'type' => 'varchar',
            'label' => 'conf_password',
            'input' => 'text',
            'source' => '',
            'required' => false,
            'visible' => false,
            'position' => 340,
            'system' => false,
            'backend' => ''
        ]);


        
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'user_active_store')
        ->addData(['used_in_forms' => [
                'adminhtml_customer'
            ]
        ]);

        $attribute->save();

        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'firsttime_login', [
            'type' => 'varchar',
            'label' => 'First Time Login',
            'input' => 'text',
            'source' => '',
            'default' => '0',
            'required' => false,
            'visible' => false,
            'position' => 340,
            'system' => false,
            'backend' => ''
        ]);

        
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'firsttime_login')
        ->addData(['used_in_forms' => [
                'adminhtml_customer'
            ]
        ]);

        $attribute->save();
    }
}