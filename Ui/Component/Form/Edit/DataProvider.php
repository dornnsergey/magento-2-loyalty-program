<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Ui\Component\Form\Edit;

use Dorn\Loyalty\Helper\Data;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as UiComponentDataProvider;

class DataProvider extends UiComponentDataProvider
{
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        private Data $helper,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    public function getData(): array
    {
        $transaction = $this->helper->getCoinsTransaction();
        if ($transaction === null) {
            return [];
        }

        return [$transaction->getId() => ['general' => $transaction->getData()]];
    }
}
