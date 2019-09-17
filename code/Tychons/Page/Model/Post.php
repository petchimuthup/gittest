<?php

namespace Tychons\Page\Model;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'tychons_page_post';

    protected $_cacheTag = 'tychons_page_post';

    protected $_eventPrefix = 'tychons_page_post';

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }

    protected function _construct()
    {
        $this->_init('Tychons\Page\Model\ResourceModel\Post');
    }
}