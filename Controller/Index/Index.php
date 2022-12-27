<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Controller\Index;

use Dorn\Loyalty\Helper\Data;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\UrlInterface;

class Index implements HttpGetActionInterface
{
    public function __construct(
        private ResultFactory $resultFactory,
        private CurrentCustomer $currentCustomer,
        private Data $helper,
        private UrlInterface $urlBuilder
    ) {
    }

    public function execute()
    {
        if (! $this->helper->isModuleEnabled()) {
            throw new NotFoundException(__('Page Not Found.'));
        }

        if (! $this->currentCustomer->getCustomerId()) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                                       ->setPath(
                                           'customer/account/login',
                                           ['referer' => base64_encode($this->urlBuilder->getCurrentUrl())]
                                       );
        }

        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}