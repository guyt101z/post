<?php namespace Sairiz\Post\Courier;

abstract class BasePos {

	//protected $realCC;

	//protected $cc;

	public function cost($weight, $zone)
	{
		return floatval ( number_format ( $this->{$zone}($weight), 2 ) );
	}

	/*
	public function getCC()
	{
		return $this->cc;
	}

	public function getRealCC()
	{
		return $this->realCC;
	}

	public function cc($cost)
	{
		$this->cc = round((($cost * 100 / 97) - $cost),2);

		$total = $this->cc + $cost;

		$this->realCC = $total - round((($total * .966) - 2),2);

		return $this->cc;
	}
	*/
}