<?php
/**
 * Created by PhpStorm.
 * User: vjcspy
 * Date: 19/05/2016
 * Time: 11:45
 */

namespace Modules\IzCustomer\Repositories\Social;


interface SocialHelper {

    /**
     * Get long-live access token from short token
     *
     * @param $shortToken
     *
     * @return \Facebook\Authentication\AccessToken
     */
    function extendToken($shortToken);

    /**
     * Lấy data của người dùng facebook dựa vào longlive access token
     *
     * @param $longLiveAccessToken
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    function getSocialUserByLongLivedAccessToken($longLiveAccessToken);
}