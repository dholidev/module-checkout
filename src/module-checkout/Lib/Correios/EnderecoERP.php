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

class EnderecoERP {
	
	public $bairro;
	public $cep;
	public $cidade;
	public $complemento2;
	public $end;
	public $uf;
	
	public function __construct($bairro = null, $cep = null, $cidade = null, $complemento2 = null, $end = null, $uf = null) {
		$this
			->setBairro($bairro)
			->setCep($cep)
			->setCidade($cidade)
			->setComplemento2($complemento2)
			->setEnd($end)
			->setUf($uf);
	}
	
	public function getBairro() {
		return $this->bairro;
	}
	
	public function setBairro($bairro = null) {
		// validation for constraint: string
		if (!is_null($bairro) && !is_string($bairro)) {
			throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($bairro, true), gettype($bairro)), __LINE__);
		}
		$this->bairro = $bairro;
		return $this;
	}
	
	public function getCep() {
		return $this->cep;
	}
	
	public function setCep($cep = null) {
		// validation for constraint: string
		if (!is_null($cep) && !is_string($cep)) {
			throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($cep, true), gettype($cep)), __LINE__);
		}
		$this->cep = $cep;
		return $this;
	}
	
	public function getCidade() {
		return $this->cidade;
	}
	
	public function setCidade($cidade = null) {
		// validation for constraint: string
		if (!is_null($cidade) && !is_string($cidade)) {
			throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($cidade, true), gettype($cidade)), __LINE__);
		}
		$this->cidade = $cidade;
		return $this;
	}
	
	public function getComplemento2() {
		return $this->complemento2;
	}
	
	public function setComplemento2($complemento2 = null) {
		// validation for constraint: string
		if (!is_null($complemento2) && !is_string($complemento2)) {
			throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($complemento2, true), gettype($complemento2)), __LINE__);
		}
		$this->complemento2 = $complemento2;
		return $this;
	}
	
	public function getEnd() {
		return $this->end;
	}
	
	public function setEnd($end = null) {
		if (!is_null($end) && !is_string($end)) {
			throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($end, true), gettype($end)), __LINE__);
		}
		$this->end = $end;
		return $this;
	}
	
	public function getUf() {
		return $this->uf;
	}
	
	public function setUf($uf = null) {
		// validation for constraint: string
		if (!is_null($uf) && !is_string($uf)) {
			throw new InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($uf, true), gettype($uf)), __LINE__);
		}
		$this->uf = $uf;
		return $this;
	}
}
