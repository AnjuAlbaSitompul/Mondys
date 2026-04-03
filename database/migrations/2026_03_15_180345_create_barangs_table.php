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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('user_id')->nullable()->nullOnDelete();
            $table->string('nama_barang')->nullable();

            $table->enum('status', [
                'PICKED',
                'PICK END',
                'BOARDING',
                'LOADING',
                'DEPARTURE',
                'FINISHED'
            ]);
            $table->enum('type', [
                'REGULER',
                'TITIP'
            ]);
            $table->foreignId('updated_by')->nullable()->nullOnDelete();
            $table->foreignId('jenis_barang_id')->nullable()->nullOnDelete();
            $table->string('id_outlet')->nullable();
            $table->string('sjcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
