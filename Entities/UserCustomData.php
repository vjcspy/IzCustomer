<?php namespace Modules\IzCustomer\Entities;

use Illuminate\Database\Eloquent\Model;

class UserCustomData extends Model {

    protected $table = 'izcustomer_user_custom_data';
    protected $fillable = ['name', 'value'];

    /**
     * Relationship function
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('Modules\IzCustomer\Entities\User');
    }

    /**
     * Retrieve custom data by name and user id
     *
     * @param $name
     * @param $userId
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCustomDataCustomerByName($name, $userId) {
        return $this->query()
                    ->where(
                        [
                            'name'    => $name,
                            'user_id' => $userId
                        ])
                    ->get();
    }
}