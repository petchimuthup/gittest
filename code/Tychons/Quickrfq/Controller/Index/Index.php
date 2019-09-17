<?php

namespace Tychons\Quickrfq\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Tychons\Quickrfq\Controller\Index
{

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
