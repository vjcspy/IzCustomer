<?php
/**
 * Created by PhpStorm.
 * User: vjcspy
 * Date: 18/05/2016
 * Time: 17:39
 */

namespace Modules\IzCustomer\Http\Controllers\Authentication;


use Cartalyst\Sentinel\Sentinel;
use Facebook\Exceptions\FacebookSDKException;
use Modules\IzCore\Http\Controllers\Api\BasicController;
use Modules\IzCustomer\Entities\FacebookUser;
use Modules\IzCustomer\Repositories\Social\Facebook\Helper as FacebookHelper;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookController extends BasicController {

    /**
     * @var \SammyK\LaravelFacebookSdk\LaravelFacebookSdk
     */
    protected $laravelFacebookSdk;
    /**
     * @var Helper
     */
    protected $facebookHelper;
    /**
     * @var \Modules\IzCustomer\Entities\FacebookUser
     */
    protected $facebookUser;
    /**
     * @var \Cartalyst\Sentinel\Sentinel
     */
    protected $sentinel;

    /**
     * FacebookController constructor.
     *
     * @param \SammyK\LaravelFacebookSdk\LaravelFacebookSdk           $laravelFacebookSdk
     * @param \Modules\IzCustomer\Repositories\Social\Facebook\Helper $facebookHelper
     * @param \Modules\IzCustomer\Entities\FacebookUser               $facebookUser
     * @param Sentinel                                                $sentinel
     */
    public function __construct(
        LaravelFacebookSdk $laravelFacebookSdk,
        FacebookHelper $facebookHelper,
        FacebookUser $facebookUser,
        Sentinel $sentinel
    ) {
        $this->laravelFacebookSdk = $laravelFacebookSdk;
        $this->facebookHelper     = $facebookHelper;
        $this->facebookUser       = $facebookUser;
        $this->sentinel           = $sentinel;
    }

    /**
     * @return mixed
     */
    public function postLogin() {
        /*
         * Login Facebook
         * Người dùng login bằng javascriptSDK sau đó sẽ gửi short token-key lên server.
         * Server sẽ dùng short token-key này để lấy long-lived token-key
         * Tiếp tục dùng long-lived token key để lấy thông tin của người dùng: id-facebook + email + name....
         * Kiểm tra người dùng trên hệ thống. Lưu ý đây là người dùng facebook. Sẽ link sang user-sentinel bằng user_id.
         * Nếu người dùng chưa tồn tại trên hệ thống thì sẽ tạo 1 user mới để đăng nhập. User này do sentinel quản lý. Email/pass word do mình tự generate ra.
         * Nếu người dùng tồn tại trên hệ thống rồi => lấy user-sentinel => login-sentinel
        */
        try {
            $token = $this->laravelFacebookSdk->getJavaScriptHelper()->getAccessToken();
            if (!$token) {
                // User hasn't logged in using the JS SDK yet
                $this->setErrorData('not_authorized');
            }
            else {
                // Get long-lived access token
                $longLiveAccessToken = $this->facebookHelper->extendToken($token);
                // Use long-lived access token above to get UserData
                $facebookUser = $this->facebookHelper->getSocialUserByLongLivedAccessToken($longLiveAccessToken);
                // Check current facebook user existed in user database
                $userSentinel = $this->facebookUser->isFacebookUserExisted($facebookUser);
                if (!$userSentinel) {
                    $credentials             = $facebookUser->toArray();
                    $credentials['password'] = md5(microtime());
                    /** @var \Modules\IzCustomer\Entities\User $userSentinel */
                    $userSentinel = $this->sentinel->registerAndActivate($credentials);

                    // update relationship
                    $userSentinel->facebook()->save($facebookUser);
                }
                // Login and remember to sentinel
                $this->sentinel->loginAndRemember($userSentinel);

                // set output
                $this->setResponseData($userSentinel->toArray());
            }
        } catch (FacebookSDKException $e) {
            $this->setResponseCode(400);
            $this->setErrorData($e->getMessage());
        }

        return $this->responseJson();
    }
}