<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Model;

use Hyperf\Database\Model\Relations\BelongsToMany;

/**
 * @property int $id 
 * @property string $name 
 * @property string $slug 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdminRole extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    protected function init()
    {
        $this->setTable(config('admin.database.role_table'));
    }

    const ADMINISTRATOR = 'administrator';

    /**
     * A role belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions() : BelongsToMany
    {
        $relatedModel = config('admin.database.permission_model');
        $pivotTable = config('admin.database.role_permission_table');

        return $this->belongsToMany($relatedModel, $pivotTable, 'role_id', 'permission_id')->withTimestamps();
    }

}