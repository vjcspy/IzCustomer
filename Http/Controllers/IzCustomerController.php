<?php namespace Modules\IzCustomer\Http\Controllers;

use Cartalyst\Sentinel\Sentinel;
use Modules\IzCore\Http\Controllers\Api\BasicController;
use Illuminate\Http\Request;
use Modules\IzCustomer\Entities\User;
use Modules\IzCustomer\Entities\UserCustomData;


class IzCustomerController extends BasicController {

    /**
     * @var UserCustomData
     */
    protected $userCustomerDataModel;
    /**
     * @var \Cartalyst\Sentinel\Sentinel
     */
    protected $sentinel;
    /**
     * @var \Modules\IzCustomer\Entities\User
     */
    protected $userModel;

    /**
     * IzCustomerController constructor.
     *
     * @param \Modules\IzCustomer\Entities\UserCustomData $userCustomData
     * @param \Cartalyst\Sentinel\Sentinel                $sentinel
     * @param \Modules\IzCustomer\Entities\User           $user
     */
    public function __construct(
        UserCustomData $userCustomData,
        Sentinel $sentinel,
        User $user
    ) {
        $this->sentinel              = $sentinel;
        $this->userCustomerDataModel = $userCustomData;
        $this->userModel             = $user;
    }

    /**
     * Retrieve Custom data of current customer by name
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function getCustomDataByName(Request $request) {
        try {
            $requestData = $this->getRequestData($request);
            if (isset($requestData['name']) && !!$requestData['name'] && !!($user = $this->sentinel->check()))
                $this->setResponseData($this->userCustomerDataModel->getCustomDataCustomerByName($requestData['name'], $user->getUserId()));
            else
                throw new \Exception("Can't get custom data");

        } catch (\Exception $e) {
            $this->setErrorData($e->getMessage());
        }

        return $this->responseJson();
    }

    /**
     * Update or create custom data by current user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function postUpdateCustomData(Request $request) {
        try {
            $data = $this->getRequestData($request);

            if (!!($user = $this->sentinel->check()))
                $this->setResponseData($this->userCustomerDataModel->updateOrCreateCustomDataByUser($data, $user));
            else
                throw new \Exception("Can't get current user");
        } catch (\Exception $e) {
            $this->setErrorData($e->getMessage());
        }

        return $this->responseJson();
    }
}