<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $slug 
 * @property string $http_method 
 * @property string $http_path 
 * @property int $order 
 * @property int $parent_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdminPermission extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'http_method', 'http_path', 'parent_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'order' => 'integer', 'parent_id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * @var array
     */
    public static $httpMethods = [
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD',
    ];

    protected function init()
    {
        $this->setTable(config('admin.database.permission_table'));
    }
}