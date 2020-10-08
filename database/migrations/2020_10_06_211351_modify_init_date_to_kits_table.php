<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyInitDateToKitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kits', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE kits CHANGE init_date end_date date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kits', function (Blueprint $table) {
            //
        });
    }
}
