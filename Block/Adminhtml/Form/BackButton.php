<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Block\Adminhtml\Form;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton implements ButtonProviderInterface
{
    public function __construct(
        private UrlInterface $urlBuilder
    ) {
    }

    public function getButtonData(): array
    {
        return [
            'label'      => __('Back'),
            'on_click'   => sprintf("location.href = '%s';", $this->urlBuilder->getUrl('customer')),
            'class'      => 'back',
            'sort_order' => 10
        ];
    }
}
