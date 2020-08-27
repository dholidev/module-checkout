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

namespace Dholi\Checkout\Block;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

class LayoutProcessor implements LayoutProcessorInterface {

	public function process($jsLayout) {
		if(isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step'])) {
			$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
			['shippingAddress']['children']['shipping-address-fieldset']['children']['street'] = [
				'component' => 'Magento_Ui/js/form/components/group',
				'label' => __('Address'),
				'required' => false,
				'dataScope' => 'shippingAddress.street',
				'provider' => 'checkoutProvider',
				'sortOrder' => 40,
				'type' => 'group',
				'additionalClasses' => 'street',
				'children' => [
					[
						'label' => __('Street Address 1'),
						'component' => 'Magento_Ui/js/form/element/abstract',
						'config' => [
							'customScope' => 'shippingAddress',
							'template' => 'ui/form/field',
							'elementTmpl' => 'ui/form/element/input'
						],
						'dataScope' => '0',
						'provider' => 'checkoutProvider',
						'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255],
					],
					[
						'label' => __('Street Address 2'),
						'component' => 'Magento_Ui/js/form/element/abstract',
						'config' => [
							'customScope' => 'shippingAddress',
							'template' => 'ui/form/field',
							'elementTmpl' => 'ui/form/element/input'
						],
						'dataScope' => '1',
						'provider' => 'checkoutProvider',
						'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255],
					],
					[
						'label' => __('Street Address 3'),
						'component' => 'Magento_Ui/js/form/element/abstract',
						'config' => [
							'customScope' => 'shippingAddress',
							'template' => 'ui/form/field',
							'elementTmpl' => 'ui/form/element/input'
						],
						'dataScope' => '2',
						'provider' => 'checkoutProvider',
						'validation' => ['required-entry' => false, "min_text_len‌​gth" => 1, "max_text_length" => 255],
					],
					[
						'label' => __('Street Address 4'),
						'component' => 'Magento_Ui/js/form/element/abstract',
						'config' => [
							'customScope' => 'shippingAddress',
							'template' => 'ui/form/field',
							'elementTmpl' => 'ui/form/element/input'
						],
						'dataScope' => '3',
						'provider' => 'checkoutProvider',
						'validation' => ['required-entry' => true, "min_text_len‌​gth" => 1, "max_text_length" => 255],
					],
				]
			];
		}

		/**
		 * VAT ID
		 */
		if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['vat_id'])) {
			$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['vat_id']['validation'] = [
				'required-entry' => true
			];
		}

		$configuration = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'];
		foreach ($configuration as $paymentGroup => $groupConfig) {
			if (isset($groupConfig['component']) AND $groupConfig['component'] === 'Magento_Checkout/js/view/billing-address') {
				if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']['children']['vat_id'])) {
					$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']['children']['vat_id']['validation'] = [
						'required-entry' => true
					];
				}

				if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']['children']['street'])) {
						$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']['children']['street']['children'][0]['label'] = __('Street Address 1');
						$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']['children']['street']['children'][1]['label'] = __('Street Address 2');
						$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']['children']['street']['children'][2]['label'] = __('Street Address 3');
						$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']['children']['street']['children'][3]['label'] = __('Street Address 4');
				}
			}
		}

		$this->sortBillingAddressComponent($jsLayout);

		return $jsLayout;
	}

	/**
	 * Re-order Billing Address for each payment address
	 *
	 * @param $jsLayout
	 */
	private function sortBillingAddressComponent(&$jsLayout) {
		$paymentsList = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
		['children']['payment']['children']['payments-list']['children'];
		$attributes = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
		['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
		foreach ($paymentsList as $formCode => $paymentList) {
			if (isset($paymentList['children']['form-fields'])) {
				foreach ($attributes as $key => $value) {
					if (isset($paymentList['children']['form-fields']['children'][$key]['sortOrder'])
						&& isset($attributes[$key]['sortOrder'])
					) {
						$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
						['payment']['children']['payments-list']['children'][$formCode]['children']['form-fields']['children'][$key]['sortOrder'] = $attributes[$key]['sortOrder'];
					}
				}
			}
		}
	}
}