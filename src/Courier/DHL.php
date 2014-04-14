<?php namespace Sairiz\Post\Courier;

class DHL extends BasePos {
	
	public function singapore($weight)
	{
		if ($weight <= 500)
			return 110;
		elseif ($weight <= 1000)
			return 130;
		elseif ($weight <= 2000)
			return 160;
		elseif ($weight <= 5000)
			return 180;
		elseif ($weight <= 10000)
			return 190;	
		elseif ($weight <= 15000)
			return 220;
		elseif ($weight <= 20000)
			return 245;						
	}
}