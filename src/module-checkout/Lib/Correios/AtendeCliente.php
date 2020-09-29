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

namespace Dholi\Checkout\Lib\Correios;

use InvalidArgumentException;
use SoapClient;

class AtendeCliente extends SoapClient {
	
	const TIMEOUT = '30';
	
	private static $classmap = [
		'consultaCEP' => '\Dholi\Checkout\Lib\Correios\ConsultaCEP',
		'consultaCEPResponse' => '\Dholi\Checkout\Lib\Correios\ConsultaCEPResponse'];
	
	public function __construct(array $options = array(), $wsdl = 'https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl') {
		foreach (self::$classmap as $key => $value) {
			if (!isset($options['classmap'][$key])) {
				$options['classmap'][$key] = $value;
			}
		}
		ini_set('default_socket_timeout', self::TIMEOUT);
		parent::__construct($wsdl, $options);
	}
	
	public function consultaCEP(ConsultaCEP $parameters) {
		return $this->__soapCall('consultaCEP', array($parameters));
	}
}
