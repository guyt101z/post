<?php namespace Sairiz\Post;

use Sairiz\Post\Courier\PosLaju;
use Sairiz\Post\Courier\DHL;

class Post {
	
	public function poslaju()
	{
		return new PosLaju;
	}

	public function dhl()
	{
		return new DHL;
	}

	public function __get($property)
	{
		if (method_exists($this, $property))
			return $this->{$property}();
	}
}