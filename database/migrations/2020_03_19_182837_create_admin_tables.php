<?php declare(strict_types=1);

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

/**
 * Create rbac tables
 */
class CreateAdminTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        /**
         * admin_users
         */
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 190)->default('')->comment('账号');
            $table->string('password', 60)->default('')->comment('密码');
            $table->string('name')->default('')->comment('昵称');
            $table->string('avatar')->default('')->comment('头像');
            $table->string('remember_token', 100)->default('')->comment('记住我');
            $table->text('customize_style', 100)->comment('网站自定义样式');
            $table->tinyInteger('status')->default(1)->comment('状态: -1删除 0禁用 1正常');
            $table->timestamp('create_time')->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('update_time')->default(Db::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新时间');

            $table->unique('username');
        });

        /**
         * admin_roles
         */
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('')->comment('名称');
            $table->string('slug', 50)->default('')->comment('标识');
            $table->timestamp('create_time')->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('update_time')->default(Db::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新时间');

            $table->unique('name');
            $table->unique('slug');
        });

        /**
         * admin_permissions
         */
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('')->comment('名称');
            $table->string('slug', 50)->default('')->comment('标识');
            $table->string('http_method')->default('')->comment('方法');
            $table->text('http_path')->comment('路径');
            $table->timestamp('create_time')->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('update_time')->default(Db::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新时间');

            $table->unique('name');
            $table->unique('slug');
        });

        /**
         * admin_menu
         */
        Schema::create('admin_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0)->comment('父级菜单id');
            $table->integer('order')->default(0)->comment('排序，值越小越靠前');
            $table->string('title', 50)->default('')->comment('名称');
            $table->string('icon', 50)->default('')->comment('图标');
            $table->string('uri', 50)->default('')->comment('路由');
            $table->string('permission')->default('')->comment('权限');
            $table->timestamp('create_time')->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('update_time')->default(Db::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新时间');
        });

        /**
         * admin_role_users
         */
        Schema::create('admin_role_users', function (Blueprint $table) {
            $table->integer('role_id')->default(0)->comment('角色id');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->timestamp('create_time')->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('update_time')->default(Db::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新时间');

            $table->index(['role_id', 'user_id']);
        });

        /**
         * admin_role_permissions
         */
        Schema::create('admin_role_permissions', function (Blueprint $table) {
            $table->integer('role_id')->default(0)->comment('角色id');
            $table->integer('permission_id')->default(0)->comment('权限id');
            $table->timestamp('create_time')->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('update_time')->default(Db::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新时间');

            $table->index(['role_id', 'permission_id']);
        });

        /**
         * admin_user_permissions
         */
        Schema::create('admin_user_permissions', function (Blueprint $table) {
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->integer('permission_id')->default(0)->comment('权限id');
            $table->timestamp('create_time')->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('update_time')->default(Db::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新时间');

            $table->index(['user_id', 'permission_id']);
        });

        /**
         * admin_role_menu
         */
        Schema::create('admin_role_menu', function (Blueprint $table) {
            $table->integer('role_id')->default(0)->comment('角色id');
            $table->integer('menu_id')->default(0)->comment('菜单id');
            $table->timestamp('create_time')->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('update_time')->default(Db::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新时间');

            $table->index(['role_id', 'menu_id']);
        });

        /**
         * admin_operation_log
         */
        Schema::create('admin_operation_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->string('path', 191)->default('')->comment('访问路径');
            $table->string('method', 10)->default('')->comment('方法');
            $table->string('ip')->default('')->comment('ip');
            $table->text('input')->comment('参数');
            $table->timestamp('create_time')->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('update_time')->default(Db::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新时间');

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
        Schema::dropIfExists('admin_roles');
        Schema::dropIfExists('admin_permissions');
        Schema::dropIfExists('admin_menu');
        Schema::dropIfExists('admin_user_permissions');
        Schema::dropIfExists('admin_role_users');
        Schema::dropIfExists('admin_role_permissions');
        Schema::dropIfExists('admin_role_menu');
        Schema::dropIfExists('admin_operation_log');
    }
}
