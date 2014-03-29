<?php namespace Sairiz\Post\Controllers;

use Controller;
use Response;
use Sairiz\Post\Post;

class PostController extends Controller {

	public function cost($courier,$weight,$zone,$cc = false)
	{
		$post = (new Post)->{$courier}();

		$cost = $post->cost($weight,$zone,$cc);

		return Response::json([
			'cost' => $cost
		]);
	}
}