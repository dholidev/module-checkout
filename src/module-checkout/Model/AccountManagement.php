<?php
/**
* 
* Checkout para Magento 2
* 
* @category     Dholi
* @package      Modulo Checkout
* @copyright    Copyright (c) 2020 dholi (https://www.dholi.dev)
* @version      1.0.0
* @license      https://opensource.org/licenses/OSL-3.0
* @license      https://opensource.org/licenses/AFL-3.0
*
*/
declare(strict_types=1);

namespace Dholi\Checkout\Model;

use Dholi\Checkout\Api\AccountManagementInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Store\Model\StoreManagerInterface;
use Dholi\Checkout\Helper\Data;

class AccountManagement implements AccountManagementInterface {

	private $customerFactory;

	private $storeManager;

	private $helper;

	public function __construct(CustomerFactory $customerFactory, StoreManagerInterface $storeManager, Data $helper) {
		$this->customerFactory = $customerFactory;
		$this->storeManager = $storeManager;
		$this->helper = $helper;
	}

	/**
	 * @inheritDoc
	 */
	public function isTaxvatAvailable($taxvat, $websiteId = null) {
		if ($websiteId === null) {
			$websiteId = $this->storeManager->getStore()->getWebsiteId();
		}
		if (!$this->helper->validateUniqueTaxvat($this->storeManager->getStore()->getId())) {
			return true;
		}

		$taxVat = preg_replace('/\D/', '', $taxvat);

		$customer = $this->customerFactory->create()->getCollection()
			->addAttributeToSelect("*")
			->addAttributeToFilter("taxvat", array("eq" => $taxVat))
			->addAttributeToFilter("website_id", array("eq" => $websiteId))
			->load();

		return (count($customer) == 0);
	}
}