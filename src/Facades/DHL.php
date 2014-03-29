<?php namespace Sairiz\Post\Facades;

use Illuminate\Support\Facades\Facade;

class DHL extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return  string
	 */
	protected static function getFacadeAccessor() { return 'dhl'; }
}