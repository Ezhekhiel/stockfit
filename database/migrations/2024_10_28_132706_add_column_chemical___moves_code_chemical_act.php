<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnChemicalMovesCodeChemicalAct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chemical___moves', function (Blueprint $table) {
            $table->string('code_chemical_act');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chemical___moves', function (Blueprint $table) {
            $table->dropColumn('code_chemical_act');
        });
    }
}
