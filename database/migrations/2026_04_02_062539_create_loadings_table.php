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
        Schema::create('loadings', function (Blueprint $table) {
            $table->id();

            $table->string('surat_jalan')->unique();

            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('co_driver_id')->nullable();

            $table->timestamp('loading_start')->nullable();
            $table->timestamp('loading_end')->nullable();

            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreignId('outlet_id')
                ->nullable()
                ->constrained('outlets')
                ->nullOnDelete();

            $table->timestamps();

            // optional foreign key (recommended)
            $table->foreign('driver_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('co_driver_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loadings');
    }
};
