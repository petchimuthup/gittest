<?php

namespace Tychons\StoreManager\Ui\Component\Listing\Column;

class CompanyActions extends \Magento\Ui\Component\Listing\Columns\Column
{

    protected $urlBuilder;
    const URL_PATH_DETAILS = 'companyuser/company/details';
    const URL_PATH_EDIT = 'companyuser/company/edit';
    const URL_PATH_DELETE = 'companyuser/company/delete';

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
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['entity_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'id' => $item['entity_id'],
                                    'store_id' => $this->currentStoreId()
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'id' => $item['entity_id'],
                                    'store_id' => $this->currentStoreId()
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete "${ $.$data.name }"'),
                                'message' => __('Are you sure you wan\'t to delete a "${ $.$data.name }" record?')
                            ]
                        ]
                    ];
                }
            }
        }

        
        return $dataSource;
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
