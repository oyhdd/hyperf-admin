<?php declare (strict_types=1);

namespace Oyhdd\Admin\Model;

use Hyperf\Database\Model\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $avatar
 * @property string $remember_token
 * @property \Carbon\Carbon $create_time
 * @property \Carbon\Carbon $update_time
 */
class AdminUser extends BaseModel
{
    use HasPermissions;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'password', 'name', 'avatar', 'remember_token'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'create_time' => 'datetime', 'update_time' => 'datetime'];

    protected $hidden = ['remember_token'];

    /**
     * A user has and belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(AdminRole::class, 'admin_role_users', 'user_id', 'role_id');
    }

    /**
     * A User has and belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions() : BelongsToMany
    {
        return $this->belongsToMany(AdminPermission::class, 'admin_user_permissions', 'user_id', 'permission_id');
    }
}