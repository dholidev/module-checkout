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

namespace Dholi\Checkout\Block\Adminhtml\Config\Source;

class ZipCodeProvider implements \Magento\Framework\Option\ArrayInterface {

	const VIACEP = 'viacep';
	const CORREIOS = 'correios';
	/**
	 * {@inheritdoc}
	 */
	public function toOptionArray() {
		return [
			['value' => self::VIACEP, 'label' => __('ViaCEP')],
			['value' => self::CORREIOS, 'label' => __('Correios')]
		];
	}
}