<?php
/**
 * Created by PhpStorm.
 * User: vjcspy
 * Date: 18/05/2016
 * Time: 20:07
 */

namespace Modules\IzCustomer\Repositories\Social\Facebook;


use Modules\IzCustomer\Entities\FacebookUser;
use Modules\IzCustomer\Repositories\Social\SocialHelper;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class Helper implements SocialHelper {

    /**
     * @var \SammyK\LaravelFacebookSdk\LaravelFacebookSdk
     */
    protected $laravelSocialSdk;
    /**
     * @var \Modules\IzCustomer\Entities\FacebookUser
     */
    protected $socialUser;

    public function __construct(
        LaravelFacebookSdk $laravelFacebookSdk,
        FacebookUser $facebookUser
    ) {
        $this->laravelSocialSdk = $laravelFacebookSdk;
        $this->socialUser       = $facebookUser;
    }

    /**
     * Get long-live access token from short token
     *
     * @param $shortToken
     *
     * @return \Facebook\Authentication\AccessToken
     */
    public function extendToken($shortToken) {
        // OAuth 2.0 client handler
        $oAuth2Client = $this->laravelSocialSdk->getOAuth2Client();

        // Exchanges a short-lived access token for a long-lived one
        return $oAuth2Client->getLongLivedAccessToken($shortToken);
    }

    /**
     * Lấy data của người dùng facebook dựa vào longlive access token
     *
     * @param $longLiveAccessToken
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getSocialUserByLongLivedAccessToken($longLiveAccessToken) {
        // set access token to work with API
        $this->laravelSocialSdk->setDefaultAccessToken($longLiveAccessToken);

        // Set endpoint API
        $response = $this->laravelSocialSdk->get('/me?fields=id,name,last_name,first_name,email');

        // get data from API
        $facebookUserData = $response->getGraphUser();

        // update social data from api to database
        $facebookUser = $this->socialUser->createOrUpdateGraphNode($facebookUserData);

        // update long-lived access token
        $facebookUser->setAttribute('access_token', $longLiveAccessToken)->save();

        return $facebookUser;
    }
}