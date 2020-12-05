<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorpoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corpo', function (Blueprint $table) {
            $table->string('id');
            $table->string('remetente_name')->comment('Remitente')->nullable();
            $table->string('remetente_doc_type')->comment('Tipo de documento')->nullable();
            $table->string('remetente_doc_number')->comment('# de documento')->nullable();
            $table->string('remetente_email')->comment('Email')->nullable();
            $table->string('subject_to')->comment('Asunto')->nullable();
            $table->string('code_in')->comment('Consecutivo de Entrada')->nullable();
            $table->string('request_at')->comment('Fecha y hora de creación')->nullable();
            $table->string('status')->comment('Estado')->nullable();
            $table->string('response_date')->comment('Fecha respuesta')->nullable();
            $table->string('response_hour')->comment('Hora respuesta')->nullable();
            $table->string('code_out')->comment('Código consecutivo de Salida')->nullable();
            $table->string('serie')->comment('Serie documental')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('corpo');
    }
}
