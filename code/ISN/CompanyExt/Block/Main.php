<?php
namespace ISN\CompanyExt\Block;

use Magento\Framework\App\ResourceConnection;

class Main extends \Magento\Framework\View\Element\Template {
    protected $companyISNFactory;
    protected $resource;
    protected $connection;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ResourceConnection $resource,
        \ISN\CompanyExt\Model\CompanyISNFactory $companyISNFactory){
        $this->companyISNFactory = $companyISNFactory;
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        parent::__construct($context);
    }

    public function insertMultiple($table, $data)
    {
        try {
            $tableName = $this->resource->getTableName($table);
            return $this->connection->insertMultiple($tableName, $data);
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Cannot insert data.'));
        }
    }

    function _prepareLayout(){
        $companyisn = $this->companyISNFactory->create();

        //save
        //$companyisn->setData('customer_number', '12');

        //read
      /*  $companyisn = $companyisn->load(2);
        var_dump($companyisn->getData('customer_number'));
        echo "</br>";
        var_dump($companyisn->getData('company_isn_id'));
        echo "</br>";

        //read collection
        $collection = $companyisn->getCollection();
        foreach($collection as $item){
            echo "</br>";
            var_dump(('company_isn_id: ' . $item->getId()));
            echo "</br>";
            var_dump($item->getData());
        }
        exit;*/
    }
}
