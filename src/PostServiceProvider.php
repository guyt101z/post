<?php namespace Sairiz\Post;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Sairiz\Post\Courier\PosLaju;
use Sairiz\Post\Courier\DHL;

class PostServiceProvider extends ServiceProvider {

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
		$this->package('sairiz/post', null, __DIR__);

		include __DIR__.'/routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['poslaju'] = $this->app->share(function()
		{
			return new PosLaju;
		});

		$this->app['dhl'] = $this->app->share(function()
		{
			return new DHL;
		});

		$this->app['post'] = $this->app->share(function()
		{
			return new Post;
		});		

		$this->app->booting(function()
		{
			$loader = AliasLoader::getInstance();
			$loader->alias('PosLaju','Sairiz\Post\Facades\PosLaju');
			$loader->alias('DHL','Sairiz\Post\Facades\DHL');
			$loader->alias('Post','Sairiz\Post\Facades\Post');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('post','poslaju','dhl');
	}

}