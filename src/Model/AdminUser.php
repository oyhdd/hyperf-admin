<?php

declare(strict_types=1);
namespace Oyhdd\Admin\Model;

use Hyperf\Database\Model\Relations\BelongsToMany;

/**
 * @property int $id 
 * @property string $username 
 * @property string $password 
 * @property string $name 
 * @property string $avatar 
 * @property string $remember_token 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdminUser extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'password', 'name', 'avatar'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    protected $hidden = ['password'];

    protected $lock = false;

    protected function init()
    {
        $this->setTable(config('admin.database.user_table'));
    }

    public function roles(): BelongsToMany
    {
        $relatedModel = config('admin.database.role_model');
        $pivotTable = config('admin.database.role_user_table');

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'role_id')->withTimestamps();
    }

    public function getLockAttribute()
    {
        return $this->lock;
    }

    /**
     * lock
     */
    public function lock(): AdminUser
    {
        $this->lock = true;

        return $this;
    }

    /**
     * unlock
     */
    public function unlock(): AdminUser
    {
        $this->lock = false;

        return $this;
    }

    /**
     * Remember password
     * @param bool $remember
     */
    public function remember(): AdminUser
    {
        $this->remember_token = generate_num($this->id);
        $this->save();

        return $this;
    }

    /**
     * @return AdminUser
     */
    public function findByUsername(string $username)
    {
        return self::query()->where(['username' => $username])->first();
    }

    /**
     * @return AdminUser
     */
    public function findById(int $id)
    {
        return self::query()->find($id);
    }

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator(): bool
    {
        $roleModel = config('admin.database.role_model');

        return $this->isRole($roleModel::ADMINISTRATOR);
    }

    /**
     * Check if user is $role.
     *
     * @param string $role
     *
     * @return mixed
     */
    public function isRole(string $role): bool
    {
        $roles = $this->roles;

        return $roles->pluck('slug')->contains($role);
    }

}
