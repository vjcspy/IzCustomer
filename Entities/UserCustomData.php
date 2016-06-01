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

    /**
     * @param                                   $data
     * @param \Modules\IzCustomer\Entities\User $user
     *
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function updateOrCreateCustomDataByUser($data, User $user) {
        if (is_array($data['value']))
            $data['value'] = json_encode($data['value']);

        if (isset($data['id'])) {
            $customData = $this->query()->firstOrFail(['id' => $data['id']]);
            $customData->update($data);
        }
        else {
            /* Updating "Belongs To" Relationships */
            $customData = $user->userCustomData()->create($data);
        }

        return $customData;
    }

    public function removeCustomDataById($id, User $user) {
        return $this->query()->where('id', $id)->delete();
    }
}