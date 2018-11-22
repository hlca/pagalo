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
			$responseBody = $response->getBody()->read(1024);
		} catch (ClientException $e) {
			$responseBody = $e->getResponse()->getBody()->read(1024);
		} catch (ServerException $e) {
			$responseBody = $e->getResponse()->getBody()->read(1024);
		} catch (Exception $e) {
			$responseBody = [
				'descripcion' => 'Error desconocido, revise su configuración.',
				'decision' => 'REJECT',
				'estado' => 2,
			];
		}

		return $responseBody;
	}
}