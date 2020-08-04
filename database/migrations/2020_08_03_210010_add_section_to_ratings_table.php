<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSectionToRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `ratings` CHANGE `color` `section` VARCHAR(15)'); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         
    }
}
