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

	protected $scopeConfig;

	public function __construct(\Magento\Framework\App\Helper\Context $context, ScopeConfigInterface $scopeConfig) {
		$this->scopeConfig = $scopeConfig;
		parent::__construct($context);
	}

	public function validateUniqueTaxvat($storeId = 0) {
		return (boolean) $this->scopeConfig->getValue(self::XML_PATH_UNIQUE_TAXVAT, ScopeInterface::SCOPE_STORE, $storeId = 0);
	}
}