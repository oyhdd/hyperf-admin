<?php declare (strict_types=1);

namespace Oyhdd\Admin\Model;

use Hyperf\DbConnection\Db;

/**
 * @property int $role_id 
 * @property int $menu_id 
 * @property \Carbon\Carbon $create_time 
 * @property \Carbon\Carbon $update_time 
 */
class AdminRoleMenu extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_role_menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'menu_id', 'create_time', 'update_time'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['role_id' => 'integer', 'menu_id' => 'integer', 'create_time' => 'datetime', 'update_time' => 'datetime'];

    /**
     * batch insert
     * @author Eric
     * @param  int    $menu_id
     * @param  array  $roleIds
     * @return bool
     */
    public static function batchInsert(int $menu_id, array $roleIds): bool
    {
        self::query()->where('menu_id', $menu_id)->delete();
        $data = [];
        foreach ($roleIds as $role_id) {
            $data[] = [
                'menu_id' => $menu_id,
                'role_id' => $role_id,
            ];
        }
        if (empty($data)) {
            return false;
        }
        return Db::table('admin_role_menu')->insert($data);
    }
}