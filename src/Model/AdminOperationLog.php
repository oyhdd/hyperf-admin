<?php declare (strict_types=1);

namespace Oyhdd\Admin\Model;

/**
 * @property int $id 
 * @property int $user_id 
 * @property string $path 
 * @property string $method 
 * @property string $ip 
 * @property string $input 
 * @property \Carbon\Carbon $create_time 
 * @property \Carbon\Carbon $update_time 
 */
class AdminOperationLog extends BaseModel
{
    public static $methodColors = [
        'GET'    => 'success',
        'POST'   => 'warning',
        'PUT'    => 'primary',
        'DELETE' => 'danger',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_operation_log';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'path', 'method', 'ip', 'input', 'create_time', 'update_time'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'create_time' => 'datetime', 'update_time' => 'datetime'];

    public function user()
    {
        return $this->hasOne(AdminUser::class, 'id', 'user_id');
    }
}