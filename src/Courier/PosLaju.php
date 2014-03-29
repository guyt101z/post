<?php namespace Sairiz\Post\Courier;

class PosLaju extends BasePos {

	protected $tax;

	const FUEL = 0.15;

	const HANDLING = 0.1;

	const GST = 0.06;

	public function __construct()
	{
		$this->tax = (1 + self::FUEL + self::HANDLING) * (1 + self::GST);
	}

	protected function calc($rate, $add, $weight)
	{
		if ($weight <= 500)
			return $rate * $this->tax;
		elseif ($weight <= 2000) 
		{
			$var = (int)(($weight - 500) / 250) + 1;
			return ($rate + ($var * $add)) * $this->tax;
		}
		elseif ($weight <= 2500)
			return $rate * $this->tax;
		elseif ($weight <= 30000)
		{
			$var = (int)(($weight - 500) / 500) + 1;
			return ($rate + ($var * $add)) * $this->tax;
		}

	}

	protected function semenanjung($weight)
	{
		if ($weight <= 2000)
			return $this->calc(4.5, 1.0, $weight);
		elseif ($weight <= 30000)
			return $this->calc(16.0, 2.0, $weight);
	}	

	protected function sarawak($weight)
	{
		if ($weight <= 2000)
			return $this->calc(6.5, 1.5, $weight);
		elseif ($weight <= 30000)
			return $this->calc(26.0, 3.5, $weight);
	}

	protected function sabah($weight)
	{
		if ($weight <= 2000)
			return $this->calc(7.0, 2.0, $weight);
		elseif ($weight <= 30000)
			return $this->calc(31.0, 4.0, $weight);
	}

	public function getTax()
	{
		return $this->tax;
	}
}