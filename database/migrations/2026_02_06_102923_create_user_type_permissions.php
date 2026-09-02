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
        Schema::create('user_type_permissions', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_type_id');
            $table->uuid('permission_id');
            $table->uuid('department_id');

            $table->foreign('department_id')->references('id')->on('departments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_type_id')->references('id')->on('user_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['user_type_id', 'department_id', 'permission_id'], 'unique_user_type_department_permission');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_type_permissions', function (Blueprint $table) {
            //
        });
    }
};
