<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Model\Gateway\Config;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigProvider implements ConfigProviderInterface
{
    public const PAYMENT_CODE = 'coins';

    public const XML_PATH_PAYMENT_IS_ACTIVE = 'payment/coins/active';

    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        private CurrentCustomer $currentCustomer
    ) {
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        if (! $this->isActive()) {
            return [];
        }

        return [
            'payment' => [
                self::PAYMENT_CODE => [
                    'enabled' => $this->isActive() && $this->currentCustomer->getCustomerId()
                ]
            ]
        ];
    }

    public function isActive(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_PAYMENT_IS_ACTIVE);
    }
}