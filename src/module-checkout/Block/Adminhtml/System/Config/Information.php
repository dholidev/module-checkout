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

namespace Dholi\Checkout\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;

class Information extends \Magento\Config\Block\System\Config\Form\Fieldset {

	private $userGuide = 'https://docs.dholi.dev/checkout/';

	protected $fieldRenderer;

	public function render(AbstractElement $element) {
		$html = $this->_getHeaderHtml($element);
		$html .= $this->getUserGuide($element);
		$html .= $this->_getFooterHtml($element);
		$html = preg_replace('(onclick=\"Fieldset.toggleCollapse.*?\")', '', $html);

		return $html;
	}

	private function getUserGuide($fieldset) {
		return $this->getFieldHtml($fieldset, 'magento_path', __('User Guide'), $this->userGuide);
	}

	protected function getFieldHtml($fieldset, $fieldName, $label = '', $value = '') {
		$field = $fieldset->addField($fieldName, 'label', [
			'name' => 'dummy',
			'label' => $label,
			'after_element_html' => $value,
		])->setRenderer($this->getFieldRenderer());

		return $field->toHtml();
	}

	private function getFieldRenderer() {
		if (empty($this->fieldRenderer)) {
			$this->fieldRenderer = $this->_layout->createBlock(\Magento\Config\Block\System\Config\Form\Field::class);
		}

		return $this->fieldRenderer;
	}

}
