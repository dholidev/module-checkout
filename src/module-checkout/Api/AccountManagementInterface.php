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

namespace Dholi\Checkout\Api;

/**
 * Interface for managing customers accounts.
 * @api
 * @since 100.0.2
 */
interface AccountManagementInterface {

	/**
	 * Check if given taxvat is associated with a customer account in given website.
	 *
	 * @param string $taxvat
	 * @param int $websiteId If not set, will use the current websiteId
	 * @return bool
	 * @throws \Magento\Framework\Exception\LocalizedException
	 */
	public function isTaxvatAvailable($taxvat, $websiteId = null);
}