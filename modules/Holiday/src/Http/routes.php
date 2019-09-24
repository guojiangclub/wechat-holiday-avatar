<?php
$router->group(['prefix' => 'holiday'], function () use ($router) {
    Route::post('quickLogin', 'AuthController@quickLogin')->name('api.holiday.quickLogin');


	$router->group(['middleware'=>['auth:api']], function ($router) {
		$router->post('createAvatar', 'AvatarController@createAvatar')->name('api.holiday.createAvatar');

	});

});