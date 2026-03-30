<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedBigInteger('module_id')->nullable()->comment('關聯模組ID');
            $table->string('description')->nullable();
            $table->string('slug')->unique()->comment('唯一識別碼');
            $table->timestamps();
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('display_name')->unique();
            $table->string('description')->nullable();
            $table->string('slug')->unique()->comment('唯一識別碼');
            $table->unsignedTinyInteger('scope')->default(1)->comment('角色作用域:0=system 1=tenant, 2=platform')->index();
            $table->boolean('is_protected')->default(false)->comment('是否為系統保護角色');
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->foreignId('role_id');
            $table->primary(['user_id', 'role_id']);
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id');
            $table->foreignId('role_id');
            $table->foreignUuid('tenant_id')->nullable()->comment('多租戶ID');
            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
