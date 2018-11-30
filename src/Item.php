<?php

namespace Hlca\Pagalo;

class Item {
	public $productId = '';
	public $quantity = 0;
	public $type = 'producto';
	public $name = '';
	public $price = 0;
	public $subTotal = 0;

	public function __construct($productId, $quantity, $name, $price) {
		$this->productId = $productId;
		$this->quantity = $quantity;
		$this->subTotal = $price * $quantity;
	}

	public function pagaditoArray() {
		$array = [
			'id_producto' => $this->productId,
			'cantidad' => $this->quantity,
			'tipo' => $this->type,
			'nombre' => $this->name,
			'precio' => $this->price,
			'Subtotal' => $this->subTotal,
		];

		return $array;
	}

}