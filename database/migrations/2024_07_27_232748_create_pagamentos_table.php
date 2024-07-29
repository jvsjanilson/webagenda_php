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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->string('qrcode',1000)->nullable()->default('');
            $table->timestamp('data_pagamento')->nullable();
            $table->decimal('valor')->default(0);
            $table->string('documento')->nullable();
            
            $table->string('status')->nullable()->default('ABERTO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
