<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Model;

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

}