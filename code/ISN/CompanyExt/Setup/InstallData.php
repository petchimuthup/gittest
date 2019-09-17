<?php


namespace ISN\CompanyExt\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\SalesSequence\Model\Builder;
use Magento\SalesSequence\Model\Config as SequenceConfig;
use ISN\CompanyExt\Model\CompanyISNFactory;

class InstallData implements InstallDataInterface {
    private $companyISNFactory;
    private $sequenceBuilder;
    private $sequenceConfig;

    public function __construct(
        CompanyISNFactory $companyISNFactory,
        Builder $sequenceBuilder,
        SequenceConfig $sequenceConfig
    ) {
        $this->companyISNFactory = $companyISNFactory;
        $this->sequenceBuilder = $sequenceBuilder;
        $this->sequenceConfig = $sequenceConfig;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context){

        $companyISNSeeding = $this->companyISNFactory->create();

    }

}