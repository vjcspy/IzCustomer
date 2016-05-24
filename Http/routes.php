<?php

Route::group(
    ['middleware' => 'web', 'prefix' => 'izcustomer', 'namespace' => 'Modules\IzCustomer\Http\Controllers'],
    function () {
        Route::controller('/account', 'Authentication\AccountController');
        Route::controller('/facebook', 'Authentication\FacebookController');
        Route::controller('/', 'IzCustomerController');
    });