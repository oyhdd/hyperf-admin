<?php declare (strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use Illuminate\Hashing\BcryptHasher;
use Oyhdd\Admin\Model\{AdminUser, AdminRole, AdminRoleMenu, AdminRolePermission, AdminPermission, AdminMenu, AdminRoleUser};

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hash = new BcryptHasher();

        // create a user.
        AdminUser::truncate();
        AdminUser::create([
            'username' => 'admin',
            'password' => $hash->make('123456'),
            'name'     => 'Admin',
        ]);

        // create a role.
        AdminRole::truncate();
        AdminRole::insert([
            [
                'name' => '超级管理员',
                'slug' => 'root',
            ],
            [
                'name' => '普通用户',
                'slug' => 'user',
            ],
        ]);

        // add role to user.
        AdminRoleUser::truncate();
        AdminRoleUser::insert([
            [
                'role_id' => 1,
                'user_id' => 1,
            ],
            [
                'role_id' => 2,
                'user_id' => 2,
            ],
        ]);

        //create a permission
        AdminPermission::truncate();
        AdminPermission::insert([
            [
                'name'        => '所有',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => '系统管理',
                'slug'        => 'system.setting',
                'http_method' => '',
                'http_path'   => "admin/user*\r\nadmin/role*\r\nadmin/permission*\r\nadmin/menu*\r\nadmin/log*",
            ],
            [
                'name'        => '个人设置',
                'slug'        => 'user.setting',
                'http_method' => '',
                'http_path'   => "admin/user/login\r\nadmin/user/setting*",
            ],
            [
                'name'        => '首页',
                'slug'        => 'home',
                'http_method' => 'GET',
                'http_path'   => 'admin',
            ],
        ]);

        //add permission to role
        AdminRolePermission::truncate();
        AdminRolePermission::insert([
            [
                'role_id' => 1,
                'permission_id' => 1,
            ],
            [
                'role_id' => 2,
                'permission_id' => 3,
            ],
            [
                'role_id' => 2,
                'permission_id' => 4,
            ],
        ]);

        // add default menus.
        AdminMenu::truncate();
        AdminMenu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => '首页',
                'icon'      => 'fa-tachometer-alt',
                'uri'       => '/admin',
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => '系统管理',
                'icon'      => 'fa-tasks',
                'uri'       => '',
            ],
            [
                'parent_id' => 2,
                'order'     => 3,
                'title'     => '用户管理',
                'icon'      => 'fa-users',
                'uri'       => '/admin/user',
            ],
            [
                'parent_id' => 2,
                'order'     => 4,
                'title'     => '角色管理',
                'icon'      => 'fa-user',
                'uri'       => '/admin/role',
            ],
            [
                'parent_id' => 2,
                'order'     => 5,
                'title'     => '权限管理',
                'icon'      => 'fa-ban',
                'uri'       => '/admin/permission',
            ],
            [
                'parent_id' => 2,
                'order'     => 6,
                'title'     => '菜单管理',
                'icon'      => 'fa-bars',
                'uri'       => '/admin/menu',
            ],
            [
                'parent_id' => 2,
                'order'     => 7,
                'title'     => '操作记录',
                'icon'      => 'fa-history',
                'uri'       => '/admin/log',
            ],
        ]);

        // add role to menu.
        AdminRoleMenu::truncate();
        AdminMenu::find(2)->roles()->save(AdminRole::first());
    }
}
