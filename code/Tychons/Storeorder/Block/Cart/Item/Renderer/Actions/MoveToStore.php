<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Tychons\Storeorder\Block\Cart\Item\Renderer\Actions;

use Magento\Checkout\Block\Cart\Item\Renderer\Actions\Generic;
use Magento\Checkout\Block\Cart\Item\Renderer;
use Magento\Framework\View\Element\Template;

/**
 * @api
 * @since 100.0.2
 */
class MoveToStore extends Generic
{
    /**
     * @var \Magento\Checkout\Block\Cart\Item\Renderer
     */
    protected $cartproduct;

    /**
     * @param Template\Context $context
     * @param Data $cartproduct
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Renderer $cartproduct,
        array $data = []
    ) {
        $this->cartproduct = $cartproduct;
        parent::__construct($context, $data);
    }

    /**
     * Get JSON POST params for moving from cart
     *
     * @return string
     */
    public function getProductId()
    {
        $this->cartproduct->setItem($this->getItem());

        return $this->cartproduct->getProduct()->getId();
    }

    /**
     * Get JSON POST params for moving from cart
     *
     * @return string
     */
    public function getItemId()
    {
        return $this->getItem()->getId();
    }
}
