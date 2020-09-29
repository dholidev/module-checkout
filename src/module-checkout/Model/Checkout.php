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

use Dholi\Checkout\Api\CheckoutInterface;
use Dholi\Checkout\Helper\Data;
use GuzzleHttp\Client;
use Magento\Customer\Model\CustomerFactory;
use Magento\Directory\Model\RegionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Dholi\Checkout\Lib\Correios\AtendeCliente;
use Dholi\Checkout\Lib\Correios\ConsultaCEP;

class Checkout implements CheckoutInterface {

	private $customerFactory;

	private $regionFactory;

	private $storeManager;

	private $helper;

	/**
	 * @var \Magento\Framework\Serialize\Serializer\Json
	 */
	private $serializer;

	public function __construct(CustomerFactory $customerFactory,
	                            RegionFactory $regionFactory,
	                            StoreManagerInterface $storeManager,
	                            Data $helper,
	                            \Magento\Framework\Serialize\Serializer\Json $serializer = null) {
		$this->customerFactory = $customerFactory;
		$this->regionFactory = $regionFactory;
		$this->storeManager = $storeManager;
		$this->helper = $helper;
		$this->serializer = $serializer ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Framework\Serialize\Serializer\Json::class);
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
		$customer = $this->customerFactory->create()->getCollection()
			->addAttributeToSelect("taxvat")
			->addFieldToFilter("website_id", ["eq" => $websiteId])
			->addFieldToFilter("taxvat", [
				["eq" => $taxvat],
				["eq" => preg_replace('/\D/', '', $taxvat)]
			])
			->load();

		return (count($customer) == 0);
	}

	/**
	 * @inheritDoc
	 */
	public function getAddressByZipCode($zipcode) {
		$provider = $this->helper->addressProvider($this->storeManager->getStore()->getId());
		$address = null;

		if($provider == 'viacep') {
			$client = new Client();
			$response = $client->get("https://viacep.com.br/ws/${zipcode}/json/unicode/", [
				'headers' => array_merge(array('Content-Type' => 'application/json')),
				'query' => null
			]);

			$content = json_decode($response->getBody()->getContents());
			if(isset($content->cep)) {
				$address = [
					'zipcode' 			=> $content->cep,
					'street' 				=> $content->logradouro,
					'city' 					=> $content->localidade,
					'state' 			 	=> $this->regionFactory->create()->loadByCode($content->uf, 'BR')->getRegionId(),
					'neighborhood' 	=> $content->bairro
				];
			}
		} else if ($provider == 'correios') {
			$client = new AtendeCliente();
			$consultaCEP = new ConsultaCEP(preg_replace('/\D/', '', $zipcode));
			$content = $client->consultaCEP($consultaCEP);
			
			if(isset($content->return)) {
				$address = [
					'zipcode' 			=> $content->return->cep,
					'street' 				=> $content->return->end,
					'city' 					=> $content->return->cidade,
					'state' 			 	=> $this->regionFactory->create()->loadByCode($content->return->uf, 'BR')->getRegionId(),
					'neighborhood' 	=> $content->return->bairro
				];
			}
		}

		return $this->serializer->serialize($address);
	}
}