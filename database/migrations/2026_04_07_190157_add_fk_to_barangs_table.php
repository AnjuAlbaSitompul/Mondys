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
        Schema::table('barangs', function (Blueprint $table) {
            // foreign key ke jenis_barangs
            $table->foreign('jenis_barang_id')
                ->references('id')
                ->on('jenis_barangs')
                ->nullOnDelete();

            // optional: sekalian rapihin relasi lain
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropForeign(['jenis_barang_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['updated_by']);
        });
    }
};
