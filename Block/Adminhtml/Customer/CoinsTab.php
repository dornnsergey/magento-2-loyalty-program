<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Block\Adminhtml\Customer;

use Dorn\Loyalty\Helper\Data;
use Magento\Ui\Component\Layout\Tabs\TabWrapper;
use Magento\Framework\View\Element\Context;

class CoinsTab extends TabWrapper
{
    public function __construct(
        Context $context,
        private Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function canShowTab(): bool
    {
        return $this->helper->isModuleEnabled();
    }

    public function getTabTitle(): \Magento\Framework\Phrase|string
    {
        return __('Customer Coins');
    }

    public function isAjaxLoaded(): bool
    {
        return true;
    }

    public function getTabUrl(): string
    {
        return $this->getUrl('coins/customer/grid', ['_current' => true]);
    }
}