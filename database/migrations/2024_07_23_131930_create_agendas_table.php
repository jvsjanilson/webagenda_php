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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->date('dt_agenda');

            $table->string('numero_pedido')->nullable();
            $table->boolean('entregue')->default(false);
            $table->string('tipo')->default('E');
            $table->text('obs')->nullable();
            $table->foreignId('empresa_id')->constrained();
            $table->boolean('ligar_antes')->default(false); //sim, nao
            $table->smallInteger('no_minimo')->default(0); //15min, 30min, 1h, 2h
            $table->smallInteger('periodo')->default(0);// 0-manha, 1-tarde
            $table->smallInteger('domicilio')->default(0);// 0-casa, 1-condominio
            $table->boolean('pagamento_entrega')->default(false); //sim, nao
            $table->smallInteger('pagamento')->default(0); //credito, debito, dinheiro, pix, link
            $table->decimal('valor', 15,2)->default(0);//valor pagamento se pagamento_entrega=sim

            $table->boolean('frete')->default(false); //sim, nao
            $table->decimal('valor_frete', 15,2)->default(0); //valor frete


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
