<?php

namespace Tychons\Page\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Post extends AbstractDb
{

    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('tychons_page_post', 'post_id');
    }

}