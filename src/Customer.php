<?php
namespace Hlca\Pagalo;

use Carbon\Carbon;

class Customer {
	public $code = '';
	public $firstName = '';
	public $lastName = '';
	public $address = '';
	public $phone = '';
	public $countryCode = 'GT';
	public $city = 'Guatemala';
	public $stateCode = 'GT';
	public $postalCode = '01001';
	public $email = '';
	public $ipAddress = '';
	public $total = 0;
	public $currency = 'GTQ';

	public function __construct($code, $firstName, $lastName, $address, $phone, $email, $ipAddress) {
		$this->code = $code;
		$this->firstName = $firstName;
		$this->lastName = $lastName;
		$this->address = $address;
		$this->phone = $phone;
		$this->email = $email;
		$this->ipAddress = $ipAddress;
	}

	public function pagaditoJSONString() {
		$array = [
			'codigo' => 'C' . $this->code,
			'firstName' => $this->firstName,
			'lastName' => $this->lastName,
			'street1' => $this->address,
			'phone' => $this->phone,
			'country' => $this->countryCode,
			'city' => $this->city,
			'state' => $this->stateCode,
			'postalCode' => $this->postalCode,
			'email' => $this->email,
			'ipAddress' => $this->ipAddress,
			'Total' => (string) $this->total,
			'fecha_transaccion' => Carbon::now()->format('Y-m-d H:i:s'),
			'currency' => $this->currency,
		];

		return json_encode($array);
	}
}