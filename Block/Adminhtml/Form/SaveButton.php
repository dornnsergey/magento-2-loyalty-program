<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Block\Adminhtml\Form;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{
    public function getButtonData(): array
    {
        return [
            'label'          => __('Save'),
            'class'          => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'saveAndContinueEdit']
                ]
            ]
        ];
    }
}
