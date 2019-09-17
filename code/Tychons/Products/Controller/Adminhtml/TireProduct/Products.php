<?php

namespace Tychons\Products\Controller\Adminhtml\TireProduct;

use Magento\Framework\App\Filesystem\DirectoryList;

class Products extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;

    protected $csv;

    protected $_filesystem;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->csv = $csv;
        $this->_filesystem = $filesystem;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $importfile = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath() . "import/tire/products.csv";

        if (!isset($importfile))
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file attempt.'));

        $csvData = $this->csv->getData($importfile);

        foreach ($csvData as $row => $data) {
            if ($row > 0) {

                $this->ImportProduct($data);

            }
        }


        return $this->resultPageFactory->create();
    }

    public function ImportProduct($data)
    {

        $app_id = $data[0];

        $application = $data[1];

        $active_item = $data[2];

        $part_number = $data[3];

        $sup_part_number = $data[4];

        $title = $data[5];

        $image = $data[6];

        $manufacturer = $data[7];

        $short_desc = $data[8];

        $long_desc = $data[9];

        $cost = $data[10];

        $list = $data[11];

        $isn_retail = $data[12];

        $mapp = $data[13];

        $category = $data[14];

        $sub_category = $data[15];

        $class = $data[16];

        $shipweight = $data[17];

        $length = $data[18];

        $width = $data[19];

        $height = $data[20];

        $stocking = $data[21];

        $reorder = $data[22];

        $uom = $data[23];

        $freight_item = $data[24];

        $hazmat = $data[25];

        $carb = $data[26];

        $ormd = $data[27];

        $self_contained = $data[28];

        $warranty = $data[29];

        $date_added = $data[30];

        $modifiy_date = $data[31];

        $qty_on_hand = $data[32];

        $upc = $data[33];

        $image_name = $data[34];

        $dropship = $data[35];

        $avail_qty = $data[36];

        $ath = $data[37];

        $atl = $data[38];

        $dal = $data[39];

        $dem = $data[40];

        $frh = $data[41];

        $ftw = $data[42];

        $lak = $data[43];

        $nje = $data[44];

        $phx = $data[45];

        $sea = $data[46];

        $det = $data[47];

        $p65 = $data[48];

        $image_path = $this->getImagePath($image, $image_name);

        //Get Object Manager Instance
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $product = $objectManager->create('Magento\Catalog\Model\Product');
        $product->setName($title);
        $product->setTypeId('simple');
        $product->setAttributeSetId(4);
        $product->setSku($part_number);
        $product->setWebsiteIds(array(1));
        $product->setVisibility(4);
        $product->setPrice(100);
        $product->setLength($length);
        $product->setWidth($width);
        $product->setHeight($height);
        $product->addImageToMediaGallery($image_path, array('image', 'small_image', 'thumbnail'), false, false);
        $product->setStockData(array(
                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                'manage_stock' => 1, //manage stock
                'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                'max_sale_qty' => 2, //Maximum Qty Allowed in Shopping Cart
                'is_in_stock' => 1, //Stock Availability
                'qty' => 100 //qty
            )
        );

        $product->save();

        $this->CreateLog($part_number, $title);


    }

    public function getImagePath($imageurl, $imagename)
    {

        $imagepath = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath() . "import/image/" . $imagename;

        file_put_contents($imagepath, file_get_contents($imageurl));

        return $imagepath;
    }

    public function CreateLog($sku, $product_name)
    {

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/productstatus.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);

        $logger->info("Added product sku is" . $sku . "and name" . $product_name);

        return true;

    }


}