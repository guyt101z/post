<?php

Route::get('cuba', function()
{
	$data = new Sairiz\Post\Post;

	return Post::dhl()->cost(400,'singapore',true);
});

Route::group(array('prefix' => 'api'), function()
{
	Route::get('post/cost/{courier}/{weight}/{zone}', 'Sairiz\Post\Controllers\PostController@cost');
});
