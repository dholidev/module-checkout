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

namespace Dholi\Checkout\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

	const XML_PATH_UNIQUE_TAXVAT = 'dholi_checkout/general/unique_taxvat';

	const XML_PATH_ADDRESS_PROVIDER = 'dholi_checkout/general/address_provider';

	protected $scopeConfig;

	public function __construct(\Magento\Framework\App\Helper\Context $context, ScopeConfigInterface $scopeConfig) {
		$this->scopeConfig = $scopeConfig;
		parent::__construct($context);
	}

	public function validateUniqueTaxvat($storeId = 0) {
		return (boolean) $this->scopeConfig->getValue(self::XML_PATH_UNIQUE_TAXVAT, ScopeInterface::SCOPE_STORE, $storeId);
	}

	public function addressProvider($storeId = 0): string {
		return $this->scopeConfig->getValue(self::XML_PATH_ADDRESS_PROVIDER, ScopeInterface::SCOPE_STORE, $storeId);
	}
}