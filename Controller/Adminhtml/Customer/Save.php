<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Controller\Adminhtml\Customer;

use Dorn\Loyalty\Api\CoinsTransactionRepositoryInterface;
use Dorn\Loyalty\Helper\Data;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;

class Save extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Magento_Backend::system';

    public function __construct(
        Context $context,
        private CoinsTransactionRepositoryInterface $coinsTransactionRepository,
        private ManagerInterface $message,
        private CustomerRepositoryInterface $customerRepository,
        private Data $helper
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $transactionId = (int) $this->getRequest()->getParam('general')['id'];
        $customerId = (int) $this->getRequest()->getParam('general')['customer_id'];
        try {
            $transaction = $this->coinsTransactionRepository->getById($transactionId);
            $customer = $this->customerRepository->getById($customerId);
        } catch (NoSuchEntityException|LocalizedException) {
            throw new NotFoundException(__('Page Not Found.'));
        }

        try {
            $this->helper->updateCoinsReceivedValue($transaction, $customer, $this->getRequest()->getParam('general'));
            $this->message->addSuccessMessage(__('Value has been successfully updated.'));
        } catch (CouldNotSaveException|LocalizedException $e) {
            $this->message->addErrorMessage($e->getMessage());
        } catch (\Exception) {
            $this->message->addErrorMessage(__('Something went wrong.'));
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)
                                   ->setPath('*/*/edit', ['id' => $transactionId]);
    }
}
