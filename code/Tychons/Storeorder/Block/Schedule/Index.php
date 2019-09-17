<?php


namespace Tychons\Storeorder\Block\Schedule;

class Index extends \Magento\Framework\View\Element\Template
{

    /**
     * schedulerFactory
     *
     * @var \Tychons\Storeorder\Controller\Schedule\Index
     */

    protected $scheduler;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Tychons\Storeorder\Controller\Schedule\Index $Scheduler,
        array $data = []
    ) {
        $this->scheduler = $Scheduler;
        parent::__construct($context, $data);
    }

    public function getSchedules()
    {
        return $this->scheduler->getActiveStoreSchedule();
    }
}
