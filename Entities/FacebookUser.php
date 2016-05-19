<?php namespace Modules\IzCustomer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;

class FacebookUser extends Model {

    /*
     * Automatic create or update data to database
     * Use Trait in another module
     * */
    use SyncableGraphNodeTrait;

    /**
     * Configuration in trait SyncableGraphNodeTrait
     * Field Mapping
     *
     * The keys of the array are the names of the fields on the Graph node.
     * The values of the array are the names of the columns in the local database.
     *
     * @var array
     */
    protected static $graph_node_field_aliases
        = [
            'id'         => 'facebook_id',
            'first_name' => 'first_name',
            'last_name'  => 'last_name',
            'email'      => 'email'
        ];

    /**
     * Configuration in trait SyncableGraphNodeTrait
     *
     * By default the createOrUpdateGraphNode() method will try to insert all the fields of a node into the database
     * But sometimes the Graph API will return fields that you didn't specifically ask for and don't exist in your database
     * In those cases we can white list specific fields with the $graph_node_fillable_fields property.
     *
     * @var array
     */
    protected static $graph_node_fillable_fields = ['id', 'first_name', 'last_name', 'email'];

    protected $table = 'izcustomer_facebook_users';
    protected $fillable = ['email', 'facebook_id', 'first_name', 'last_name', 'user_id'];

    /**
     * Relationship function
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('Modules\IzCustomer\Entities\User');
    }

    /**
     * Kiểm tra xem facebook user đã có trong sentinel-user chưa
     * Nếu có trả về user model
     * Chưa có trả về false
     *
     * @param \Illuminate\Database\Eloquent\Model $facebookUser
     *
     * @return bool | Model
     */
    public function isFacebookUserExisted(Model $facebookUser) {
        if (!!$facebookUser->getAttribute('user_id'))
            return $facebookUser->user;
        else
            return false;
    }
}