<?php
namespace Hlca\Pagalo;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Hlca\Pagalo\Customer;

class Pagalo {
	static function register(Customer $customer, Card $card) {
		$client = new HttpClient();
		$url = config('pagalo.url') . '/api/v1/boveda/nuevo/' . config('pagalo.business_key');

		$business = [
			'key_secret' => config('pagalo.key_secret'),
			'key_public' => config('pagalo.key_public'),
			'idenEmpresa' => config('pagalo.business_id'),
		];

		$requestJSON = [
			'empresa' => json_encode($business),
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