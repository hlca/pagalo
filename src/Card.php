<?php

namespace Hlca\Pagalo;

class Card {
	public $cardHolderName = '';
	public $cardNumber = '';
	public $expirationMonth = '';
	public $expirationYear = '';
	public $cvvNumber = '';

	public function __construct($cardHolderName, $cardNumber, $expirationMonth, $expirationYear, $cvvNumber) {
		$this->cardHolderName = $cardHolderName;
		$this->cardNumber = $cardNumber;
		$this->expirationYear = $expirationYear;
		$this->expirationMonth = $expirationMonth;
		$this->cvvNumber = $cvvNumber;
	}

	public function pagaditoJSONString() {
		$array = [
			'nameCard' => $this->cardHolderName,
			'accountNumber' => $this->cardNumber,
			'expirationMonth' => $this->expirationMonth,
			'expirationYear' => $this->expirationYear,
			'CVVCard' => $this->cvvNumber,
		];

		return json_encode($array);
	}
}