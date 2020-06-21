<?php declare (strict_types=1);

namespace Oyhdd\Admin\Model;

/**
 * @property int $role_id
 * @property int $user_id
 * @property \Carbon\Carbon $create_time
 * @property \Carbon\Carbon $update_time
 */
class AdminUserPermission extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user_permissions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['role_id' => 'integer', 'user_id' => 'integer', 'create_time' => 'datetime', 'update_time' => 'datetime'];
}