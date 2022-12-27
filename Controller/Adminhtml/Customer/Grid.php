<?php

declare(strict_types=1);

namespace Dorn\Loyalty\Controller\Adminhtml\Customer;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Grid extends Action
{
	public const ADMIN_RESOURCE = 'Magento_Backend::system';

	public function execute()
	{
		return $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
	}
}
