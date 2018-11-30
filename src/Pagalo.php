<?php
namespace Hlca\Pagalo;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Hlca\Pagalo\Customer;

class Pagalo {
	static function charge(Customer $customer, $items, $cardToken, $totalCharge) {
		$client = new HttpClient();
		$url = config('pagalo.url') . '/api/v1/boveda/transaccion/' . config('pagalo.business_key');
		$business = self::getBusiness();

		$customer->total = $totalCharge;

		$itemDetails = array_map(function ($item) {
			return $item->pagaditoArray();
		}, $items);

		$requestJSON = [
			'empresa' => $business,
			'cliente' => $customer->pagaditoJSONString(),
			'detalle' => json_encode($itemDetails),
			'tarjetaPagalo' => json_encode([
				'tokenTarjeta' => $cardToken,
			]),
		];

		try {
			$response = $client->request('POST', $url, [
				'json' => $requestJSON,
				'headers' => [
					'Accept' => 'application/json',
					'Content-Type' => 'application/json',
				],
			]);

			$responseBody = json_decode($response->getBody()->read(1024), true);
		} catch (ClientException $e) {
			$responseBody = json_decode($e->getResponse()->getBody()->read(1024), true);
		} catch (ServerException $e) {
			$responseBody = json_decode($e->getResponse()->getBody()->read(1024), true);
		} catch (Exception $e) {
			$responseBody = [
				'transaccion' => 0,
				'requestID' => '',
				'decision' => 'REJECT',
				'estado' => 2,
			];
		}

		$customResponse = [
			'transaction_number' => $responseBody['transaccion'],
			'valid' => $responseBody['decision'] == 'ACCEPT',
			'request_id' => $responseBody['requestID'],
			'estado' => $responseBody['estado'],
		];

		return $customResponse;
	}

	static function getBusiness() {
		return json_encode([
			'key_secret' => config('pagalo.key_secret'),
			'key_public' => config('pagalo.key_public'),
			'idenEmpresa' => config('pagalo.business_id'),
		]);
	}

	static function register(Customer $customer, Card $card) {
		$client = new HttpClient();
		$url = config('pagalo.url') . '/api/v1/boveda/nuevo/' . config('pagalo.business_key');

		$business = self::getBusiness();

		$requestJSON = [
			'empresa' => $business,
			'cliente' => $customer->pagaditoJSONString(),
			'detalle' => '',
			'tarjetaPagalo' => $card->pagaditoJSONString(),
		];

		try {
			$response = $client->request('POST', $url, [
				'json' => $requestJSON,
				'headers' => [
					'Accept' => 'application/json',
					'Content-Type' => 'application/json',
				],
			]);

			$responseBody = json_decode($response->getBody()->read(1024), true);
		} catch (ClientException $e) {
			$responseBody = json_decode($e->getResponse()->getBody()->read(1024), true);
		} catch (ServerException $e) {
			$responseBody = json_decode($e->getResponse()->getBody()->read(1024), true);
		} catch (Exception $e) {
			$responseBody = [
				'descripcion' => 'Error desconocido, revise su configuración.',
				'decision' => 'REJECT',
				'estado' => 2,
			];
		}

		$customResponse = [
			'description' => $responseBody['descripcion'],
			'valid' => $responseBody['decision'] == 'ACCEPT',
			'token' => array_key_exists('token', $responseBody) ? $responseBody['token'] : null,
		];

		return (object) $customResponse;
	}
}