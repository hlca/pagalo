<?php
namespace Hlca\Pagalo;

use Illuminate\Support\ServiceProvider;

class PagaloServiceProvider extends ServiceProvider {
	protected $defer = false;

	public function boot() {
		$this->mergeConfigFrom(__DIR__ . '/../config/pagalo.php', 'pagalo');

		$this->publishes([__DIR__ . '/../config/pagalo.php' => config_path('pagalo.php')], 'config');
	}
}
