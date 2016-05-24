<?php namespace Modules\IzCustomer\Entities;

use Cartalyst\Sentinel\Users\EloquentUser;

/**
 * Override lại class User của Sentinel
 * Thay thế class sentinel ở config
 * Class User
 *
 * @package Modules\Izcustomer\Entities
 */
class User extends EloquentUser {

    /**
     * Relationship function
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function facebook() {
        return $this->hasOne('Modules\IzCustomer\Entities\FacebookUser');
    }

    /**
     * Relationship function
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCustomData() {
        return $this->hasMany('Modules\IzCustomer\Entities\UserCustomData');
    }
}