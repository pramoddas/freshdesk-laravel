<?php namespace Freshdesk\Freshdesk;

use Illuminate\Support\ServiceProvider;

use Freshdesk\Freshdesk;

class FreshdeskServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$app = $this->app ?: app();
		$laravel_version = substr($app::VERSION, 0, strpos($app::VERSION, '.'));

		if ($laravel_version == 5)
		{
			$this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'Freshdesk');

			$this->publishes([
				__DIR__.'/../../config/config.php' => config_path('Freshdesk.php'),
			]);
		}
		else if ($laravel_version == 4)
		{
			$this->package('Freshdesk/Freshdesk', 'Freshdesk', __DIR__.'/../..');
		}

		$this->app['Freshdesk'] = $this->app->share(function($app)
		{
			return new Twitter($app['config'], $app['session.store']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['Freshdesk'];
	}

}