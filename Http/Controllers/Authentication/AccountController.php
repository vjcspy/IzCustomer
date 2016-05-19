<?php
/**
 * Created by PhpStorm.
 * User: vjcspy
 * Date: 18/05/2016
 * Time: 17:39
 */

namespace Modules\IzCustomer\Http\Controllers\Authentication;


use Cartalyst\Sentinel\Sentinel;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\IzCore\Http\Controllers\Api\BasicController;
use Modules\IzCustomer\Http\Requests\CredentialsRequest;
use Response;

class AccountController extends BasicController {

    use ValidatesRequests;

    /**
     * @var \Cartalyst\Sentinel\Sentinel
     */
    protected $sentinel;

    /**
     * LoginController constructor.
     *
     * @param \Cartalyst\Sentinel\Sentinel $sentinel
     */
    public function __construct(
        Sentinel $sentinel
    ) {
        $this->sentinel = $sentinel;
    }

    /**
     * Check Login
     */
    public function getIsLogged() {
        if ($user = $this->sentinel->check()) {
            $this->setResponseData(
                [
                    'logged'    => true,
                    'user_data' => $user->toArray()
                ]);
        }
        else
            $this->setResponseData(
                [
                    'logged' => false
                ]);

        return $this->responseJson();
    }

    public function postLogin(CredentialsRequest $request) {
        if ($user = $this->sentinel->authenticateAndRemember($this->getRequestData($request))) {
            $this->setResponseData(
                [
                    'logged'    => true,
                    'user_data' => $user->toArray()
                ]);
        }
        else
            $this->setResponseData(
                [
                    'logged' => false
                ]);

        return $this->responseJson();
    }

    public function postRegister(CredentialsRequest $request) {
        if ($user = $this->sentinel->registerAndActivate($this->getRequestData($request))) {
            $this->sentinel->loginAndRemember($user);
            $this->setResponseData($user->toArray());
        }
        else {
            $this->setErrorData("Can't create user");
        }

        return $this->responseJson();
    }
}