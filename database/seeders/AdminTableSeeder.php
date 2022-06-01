<?php

use Hyperf\Database\Seeders\Seeder;
use Illuminate\Hashing\BcryptHasher;

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
        $now = date('Y-m-d H:i:s');

        // Create a user.
        $adminUserModel = config('admin.database.user_model');
        $adminUserModel::truncate();
        $adminUserModel::create([
            'username' => 'admin',
            'password' => $hash->make('123456'),
            'name'     => 'Administrator',
        ]);

        // Create a role.
        $adminRoleModel = config('admin.database.role_model');
        $adminRoleModel::truncate();
        $adminRoleModel::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
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
                'http_path'   => '/*',
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
                'order'       => 3,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Role',
                'slug'        => 'role',
                'http_method' => '',
                'http_path'   => '/auth/role*',
                'parent_id'   => 2,
                'order'       => 4,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Permission',
                'slug'        => 'permission',
                'http_method' => '',
                'http_path'   => '/auth/permission*',
                'parent_id'   => 2,
                'order'       => 5,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'name'        => 'Menu',
                'slug'        => 'menu',
                'http_method' => '',
                'http_path'   => '/auth/menu*',
                'parent_id'   => 2,
                'order'       => 6,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
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
                'order'      => 3,
                'title'      => 'user',
                'icon'       => 'fa-users',
                'uri'        => 'auth/user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 2,
                'order'      => 4,
                'title'      => 'role',
                'icon'       => 'fa-user',
                'uri'        => 'auth/role',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 2,
                'order'      => 5,
                'title'      => 'permission',
                'icon'       => 'fa-ban',
                'uri'        => 'auth/permission',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 2,
                'order'      => 6,
                'title'      => 'menu',
                'icon'       => 'fa-bars',
                'uri'        => 'auth/menu',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 2,
                'order'      => 7,
                'title'      => 'operation_log',
                'icon'       => 'fa-bars',
                'uri'        => 'auth/operation',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 0,
                'order'      => 8,
                'title'      => 'develop_tool',
                'icon'       => 'fa-keyboard-o',
                'uri'        => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 8,
                'order'      => 9,
                'title'      => 'website_setting',
                'icon'       => 'fa-cog',
                'uri'        => 'auth/site/edit',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_id'  => 8,
                'order'      => 10,
                'title'      => 'scaffold',
                'icon'       => 'fa-code',
                'uri'        => 'auth/generate/new',
                'created_at' => $now,
                'updated_at' => $now,
            ],
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
