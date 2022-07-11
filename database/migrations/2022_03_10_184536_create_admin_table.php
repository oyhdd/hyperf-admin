<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    public function getConnection()
    {
        return $this->config('database.connection') ?: config('database.default');
    }

    public function config($key)
    {
        return config('admin.' . $key);
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create($this->config('database.user_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 120)->unique();
            $table->string('password', 80);
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('remember_token', 64)->nullable();
            $table->timestamps();
        });

        Schema::create($this->config('database.role_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });

        Schema::create($this->config('database.permission_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50);
            $table->string('slug', 50)->unique();
            $table->string('http_method')->nullable();
            $table->text('http_path')->nullable();
            $table->bigInteger('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create($this->config('database.menu_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50)->default('')->unique();
            $table->string('icon', 50)->nullable();
            $table->string('uri', 50)->nullable();
            $table->timestamps();
        });

        Schema::create($this->config('database.role_user_table'), function (Blueprint $table) {
            $table->bigInteger('role_id');
            $table->bigInteger('user_id');
            $table->unique(['role_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create($this->config('database.role_permission_table'), function (Blueprint $table) {
            $table->bigInteger('role_id');
            $table->bigInteger('permission_id');
            $table->unique(['role_id', 'permission_id']);
            $table->timestamps();
        });

        Schema::create($this->config('database.menu_role_table'), function (Blueprint $table) {
            $table->bigInteger('menu_id');
            $table->bigInteger('role_id');
            $table->unique(['menu_id', 'role_id']);
            $table->timestamps();
        });

        Schema::create($this->config('database.operation_log_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('path', 50);
            $table->string('method', 10);
            $table->string('ip', 50);
            $table->text('input');
            $table->timestamps();

            $table->index('user_id');
            $table->index('path');
        });

        Schema::create($this->config('database.site_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 100);
            $table->longText('value');
            $table->timestamps();

            $table->unique('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->config('database.user_table'));
        Schema::dropIfExists($this->config('database.role_table'));
        Schema::dropIfExists($this->config('database.permission_table'));
        Schema::dropIfExists($this->config('database.menu_table'));
        Schema::dropIfExists($this->config('database.role_user_table'));
        Schema::dropIfExists($this->config('database.role_permission_table'));
        Schema::dropIfExists($this->config('database.menu_role_table'));
        Schema::dropIfExists($this->config('database.operation_log_table'));
        Schema::dropIfExists($this->config('database.site_table'));
    }
}
