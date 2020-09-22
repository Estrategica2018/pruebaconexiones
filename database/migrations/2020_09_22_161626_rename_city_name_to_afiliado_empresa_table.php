<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCityNameToAfiliadoEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('afiliado_empresas', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE afiliado_empresas CHANGE city city_name varchar(250)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('afiliado_empresas', function (Blueprint $table) {
            //
        });
    }
}
