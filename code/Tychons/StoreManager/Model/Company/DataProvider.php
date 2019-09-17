<?php


namespace Tychons\StoreManager\Model\Company;

use Magento\Framework\App\Request\DataPersistorInterface;

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $loadedData;

    protected $collection;

    protected $dataPersistor;


    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
        }
        $data = $this->dataPersistor->get('tychons_storeuser_user');
        
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('tychons_storeuser_user');
        }

        //krishna added this

        foreach ($this->loadedData as $key => &$customer) {

                $customerId = $customer['entity_id'];

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

                $customers = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);

                $customer['role'] = $customers->getRoleId();

                $customer['password'] = $customers->getPassword();

                $customer['conf_password'] = $customers->getConfPassword();

                $customer['status'] = $customers->getStatus();

                $customer['store_id'] = $this->currentStoreId();

         }

        //krishna ends

        return $this->loadedData;
    }

    public function currentStoreId()
    {
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $session = $objectManager->get("Magento\Framework\Session\SessionManagerInterface");

        $getStoreId = $session->getUserStoreId();

        if(!empty($getStoreId)){

            return $getStoreId;

        }else{

            return;
        }

    }
}