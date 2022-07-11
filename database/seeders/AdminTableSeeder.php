<?php

use Hyperf\Database\Seeders\Seeder;
use Hyperf\DbConnection\Db;
use HyperfExt\Hashing\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Create a user.
        $adminUserModel = config('admin.database.user_model');
        $adminUserModel::truncate();
        $adminUserModel::create([
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'name'     => 'Administrator',
        ]);

        // Create a role.
        $adminRoleModel = config('admin.database.role_model');
        $adminRoleModel::truncate();
        $adminRoleModel::insert([
            [
                'name' => 'Administrator',
                'slug' => 'administrator',
            ],
            [
                'name' => 'Develop Tool',
                'slug' => 'develop_tool',
            ]
        ]);

        // add role to user.
        $adminRoles = $adminUserModel::first()->roles();
        if ($adminRoles->count() == 0) {
            $adminRoles->save($adminRoleModel::first());
        }

        // Create a permission
        $adminPermissionModel = config('admin.database.permission_model');
        $adminPermissionModel::truncate();
        $adminPermissionModel::insert([
            [
                'name'        => 'Administrator',
                'slug'        => 'administrator',
                'http_method' => '',
                'http_path'   => '*',
                'parent_id'   => 0,
                'order'       => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Auth Manage',
                'slug'        => 'auth-manage',
                'http_method' => '',
                'http_path'   => '',
                'parent_id'   => 0,
                'order'       => 2,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'User',
                'slug'        => 'user',
                'http_method' => '',
                'http_path'   => '/auth/user*',
                'parent_id'   => 2,
                'order'       => 1,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Role',
                'slug'        => 'role',
                'http_method' => '',
                'http_path'   => '/auth/role*',
                'parent_id'   => 2,
                'order'       => 2,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Permission',
                'slug'        => 'permission',
                'http_method' => '',
                'http_path'   => '/auth/permission*',
                'parent_id'   => 2,
                'order'       => 3,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Menu',
                'slug'        => 'menu',
                'http_method' => '',
                'http_path'   => '/auth/menu*',
                'parent_id'   => 2,
                'order'       => 4,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Develop Tool',
                'slug'        => 'develop_tool',
                'http_method' => '',
                'http_path'   => '/auth/site/*',
                'parent_id'   => 0,
                'order'       => 3,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]
        ]);

        // add role to permission.
        Db::table(config('admin.database.role_permission_table'))->truncate();
        Db::table(config('admin.database.role_permission_table'))->insert([
            'role_id' => 2,
            'permission_id' => 7,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // Add default menus.
        $adminMenuModel = config('admin.database.menu_model');
        $adminMenuModel::truncate();
        $adminMenuModel::insert([
            [
                'parent_id'  => 0,
                'order'      => 1,
                'title'      => 'home',
                'icon'       => 'fa-dashboard',
                'uri'        => '/',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 0,
                'order'      => 2,
                'title'      => 'system',
                'icon'       => 'fa-tasks',
                'uri'        => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 2,
                'order'      => 1,
                'title'      => 'user',
                'icon'       => 'fa-users',
                'uri'        => 'auth/user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 2,
                'order'      => 2,
                'title'      => 'role',
                'icon'       => 'fa-user',
                'uri'        => 'auth/role',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 2,
                'order'      => 3,
                'title'      => 'permission',
                'icon'       => 'fa-ban',
                'uri'        => 'auth/permission',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 2,
                'order'      => 4,
                'title'      => 'menu',
                'icon'       => 'fa-bars',
                'uri'        => 'auth/menu',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 2,
                'order'      => 5,
                'title'      => 'operation_log',
                'icon'       => 'fa-bars',
                'uri'        => 'auth/operation',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 0,
                'order'      => 3,
                'title'      => 'develop_tool',
                'icon'       => 'fa-keyboard-o',
                'uri'        => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 8,
                'order'      => 1,
                'title'      => 'website_setting',
                'icon'       => 'fa-cog',
                'uri'        => 'auth/site/edit',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 8,
                'order'      => 2,
                'title'      => 'scaffold',
                'icon'       => 'fa-code',
                'uri'        => 'auth/generate/new',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // add menu to role.
        Db::table(config('admin.database.menu_role_table'))->truncate();
        Db::table(config('admin.database.menu_role_table'))->insert([
            [
                'menu_id' => 8,
                'role_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'menu_id' => 9,
                'role_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'menu_id' => 10,
                'role_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // Add default setting.
        $adminSiteModel = config('admin.database.site_model');
        $adminSiteModel::truncate();
        $adminSiteModel::insert([
            [
                'key'        => 'color_scheme',
                'value'      => 'skin-black',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'animation_type',
                'value'      => 'fadeIn',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'animation_duration',
                'value'      => '0.3',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'animation_delay',
                'value'      => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'operation_log_off',
                'value'      => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'allow_del_operation_log',
                'value'      => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'toastr_timeout',
                'value'      => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
