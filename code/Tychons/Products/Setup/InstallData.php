<?php

namespace Tychons\Products\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;


    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'application_id',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Application Id',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => ["test1", "test2"]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'application',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Application',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'part_number',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Part Number',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'sup_part_number',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Sup Part Number',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'manufacturer',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Manufacturer',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'cost',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Cost',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'list',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'List',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'isn_retail',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'ISN Retail',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'mapp',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Mapp',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'stocking',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Stocking',
                'input' => 'boolean',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'reorder',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Reorder',
                'input' => 'boolean',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'uom',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'UOM',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => ["EA", "Y"]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'freight_item',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Freight Item',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => ["EA", "Y", "N"]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'hazmat',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Hazmat',
                'input' => 'select',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => ["Y", "N", "EA"]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'carb',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Carb',
                'input' => 'boolean',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'ormd',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'ORMD',
                'input' => 'boolean',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'self_contained',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Self Contained',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'warranty',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Warranty',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'date_added',
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => '',
                'label' => 'Date Added',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'modify_date',
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => '',
                'label' => 'Modify Date',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'qty_on_hand',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Qty On Hand',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'upc',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'UPC',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'dropship',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Dropship',
                'input' => 'boolean',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );


        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'available_quantity',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Available Quantity',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'p65',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'P65',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'unique' => false,
                'apply_to' => '',
                'system' => 1,
                'group' => 'General',
                'option' => ['values' => [""]]
            ]
        );
    }
}