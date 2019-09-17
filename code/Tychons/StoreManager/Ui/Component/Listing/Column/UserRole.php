<?php


namespace Tychons\StoreManager\Ui\Component\Listing\Column;



class UserRole extends \Magento\Ui\Component\Listing\Columns\Column
{

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) 
            {

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($item['entity_id']);

                if ($customer->getRoleId() == 1) {
                    
                    $role = "Admin";
                }elseif ($customer->getRoleId() == 2) {
                    
                    $role = "Manager";

                }else{

                    $role = "Employee";
                }

                $item[$this->getData('name')] = $role;
            }
        }

        return $dataSource;
    }
}
