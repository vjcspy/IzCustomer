<?php

Route::group(
    ['middleware' => 'web', 'prefix' => 'izcustomer', 'namespace' => 'Modules\IzCustomer\Http\Controllers'],
    function () {
        Route::controller('/login', 'Login\LoginController');
        Route::controller('/facebook', 'Login\FacebookController');
    });