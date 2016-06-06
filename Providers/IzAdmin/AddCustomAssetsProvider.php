<?php
/**
 * Created by PhpStorm.
 * User: vjcspy
 * Date: 06/06/2016
 * Time: 16:58
 */

namespace Modules\IzCustomer\Providers\IzAdmin;


use Illuminate\Support\ServiceProvider;
use Modules\IzCore\Repositories\Theme\Asset;

class AddCustomAssetsProvider extends ServiceProvider {

   
    public function boot() {
        app()['izAsset']->addCustomAssets(
            'izadmin',
            [
                'IzCustomerSentinel'               =>
                    [
                        'source'     => 'scripts/services/core/izcustomer/authentication/izsentinel.js',
                        'dependency' => [],
                        'theme_name' => 'admin.default'
                    ],
                'IzCustomerFacebookApi'            =>
                    [
                        'source'     => 'scripts/services/core/izcustomer/facebook/api.js',
                        'dependency' => [],
                        'theme_name' => 'admin.default'
                    ],
                'IzCustomerFacebookAuthentication' =>
                    [
                        'source'     => 'scripts/services/core/izcustomer/facebook/authentication.js',
                        'dependency' => [],
                        'theme_name' => 'admin.default'
                    ],
            ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        // TODO: Implement register() method.
    }
}