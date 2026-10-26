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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')
                ->default('karyawan')
                ->index();

            $table->foreignUuid('branch_id')
                ->nullable()
                ->after('role')
                ->constrained('branches')
                ->nullOnDelete();

            $table->index(['role', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);

            $table->dropIndex(['role', 'branch_id']);

            $table->dropIndex(['role']);

            $table->dropColumn([
                'role',
                'branch_id',
            ]);
        });
    }
};
