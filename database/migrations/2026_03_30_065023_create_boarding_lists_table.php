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
        Schema::create('boarding_lists', function (Blueprint $table) {
            $table->id();

            // Relasi ke barangs
            $table->foreignId('barang_id')
                ->constrained('barangs')
                ->cascadeOnDelete();

            // Code boarding dari input
            $table->string('code_boarding')->nullable();

            // Quantity
            $table->integer('qty')->nullable();
            $table->integer('koli');
            // Relasi ke users
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Boarding start time
            $table->timestamp('boarding_start')->nullable();

            // Boarding end time
            $table->timestamp('boarding_end')->nullable();

            // Relasi ke outlets
            $table->foreignId('outlet_id')
                ->constrained('outlets')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boarding_lists');
    }
};
