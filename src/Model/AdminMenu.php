<?php declare (strict_types=1);

namespace Oyhdd\Admin\Model;

use Hyperf\Database\Model\Relations\BelongsToMany;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property int $parent_id
 * @property int $order
 * @property string $title
 * @property string $icon
 * @property string $uri
 * @property string $permission
 * @property \Carbon\Carbon $create_time
 * @property \Carbon\Carbon $update_time
 */
class AdminMenu extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'parent_id', 'order', 'title', 'icon', 'uri', 'permission', 'create_time', 'update_time'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'parent_id' => 'integer', 'order' => 'integer', 'create_time' => 'datetime', 'update_time' => 'datetime'];


    /**
     * A Menu belongs to many roles.
     *
     * @return HasMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(AdminRole::class, 'admin_role_menu', 'menu_id', 'role_id');
    }

    /**
     * Left sider-bar menu.
     *
     * @param  string   $uri
     * @return array
     */
    public static function getMenuTree(string $uri = '', $user = null): array
    {
        $tree = [];
        $items = AdminMenu::orderBy('order')->get();
        if (empty($items->toArray())) {
            return $tree;
        }

        if (!empty($user)) {
            $userPermissions = $user->allPermissions()->toArray();
            $urlPatterns = array_column($userPermissions, 'http_path');
            $urlPatterns = array_map(function ($http_path) {
                return explode("\n", str_replace(["\r\n", " ", ","], "\n", $http_path));
            }, $urlPatterns);
            $slugPatterns = array_column($userPermissions, 'slug');

            // 菜单权限校验
            foreach ($items as $key => $item) {
                $item->active = false; //默认不激活
                $item->has_permission = true; // 默认有权限
                $menuPermissions = $item->allPermissions();
                // 菜单未设置权限
                if (empty($menuPermissions) || in_array('*', $slugPatterns)) {
                    continue;
                }
                // 用户无菜单权限
                if (empty(array_intersect($menuPermissions, $slugPatterns))) {
                    $item->has_permission = false;
                }
            }
        }
        $items = $items->toArray();
        $items = array_column($items, null, 'id');
        foreach ($items as $id => $item) {
            if ($uri == $item['uri']) {
                $items[$id]['active'] = true;
            }
            if (isset($items[$item['parent_id']])) {
                if ($items[$id]['active']) {
                    $items[$item['parent_id']]['active'] = true;
                }
                $items[$item['parent_id']]['children'][] = &$items[$id];
            } else {
                $tree[] = &$items[$id];
            }
        }

        return $tree;
    }

    /**
     * Build options of select field in form.
     *
     * @param array  $nodes
     * @param int    $parentId
     * @param string $prefix
     * @param string $space
     *
     * @return array
     */
    public static  function buildSelectOptions(array $menus = [], $level = 0): array
    {
        $options = [];
        if (empty($menus)) {
            $menus = self::getMenuTree();
        }
        $space = '&nbsp;&nbsp;';

        foreach ($menus as $menu) {
            $options[] = ['id' => $menu['id'], 'title' => str_repeat($space, $level*4).'┝'.$space.$menu['title']];
            if (!empty($menu['children'])) {
                $options = array_merge($options, self::buildSelectOptions($menu['children'], $level + 1));
            }
        }

        return $options;
    }

    /**
     * Get all permissions of user.
     *
     * @return mixed
     */
    public function allPermissions()
    {
        $permissions = $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->toArray();
        $rolesPermission = array_column($permissions, 'slug');
        $permission = !empty($this->permission) ? [$this->permission] : [];
        if (empty($permission) && empty($rolesPermission)) {
            return [];
        }
        return array_merge($permission, $rolesPermission);
    }

}