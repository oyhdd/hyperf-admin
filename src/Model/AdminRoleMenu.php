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
     * @param  int    $menu_id
     * @param  array  $roleIds
     * @return bool
     */
    public static function batchInsert(int $menu_id, array $roleIds): bool
    {
        $data = [];
        foreach ($roleIds as $role_id) {
            if (empty($role_id)) {
                continue;
            }
            $data[] = [
                'menu_id' => $menu_id,
                'role_id' => $role_id,
            ];
        }
        if (empty($data)) {
            return false;
        }

        self::query()->where('menu_id', $menu_id)->delete();
        return Db::table('admin_role_menu')->insert($data);
    }
}