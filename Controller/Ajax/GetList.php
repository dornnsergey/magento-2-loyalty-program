<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Controller\Ajax;

use Dorn\Loyalty\Helper\Data;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class GetList implements HttpGetActionInterface
{
    public function __construct(
        private Data $helper,
        private ResultFactory $resultFactory
    ) {
    }

    public function execute()
    {
        try {
            $response['data'] = array_values($this->helper->getList());
            $response['totalCoins'] = $this->helper->getCurrentCustomerTotalCoins();
            $response['success'] = true;
        } catch (\Exception) {
            $response['success'] = false;
            $response['errorMsg'] = __('Something went wrong. Data have not been obtained.');
        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($response);
    }
}
