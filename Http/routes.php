<?php

Route::group(['middleware' => 'web', 'prefix' => 'izcustomer', 'namespace' => 'Modules\IzCustomer\Http\Controllers'], function()
{
	Route::get('/', 'IzCustomerController@index');
});