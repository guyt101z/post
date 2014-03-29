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
	}
}