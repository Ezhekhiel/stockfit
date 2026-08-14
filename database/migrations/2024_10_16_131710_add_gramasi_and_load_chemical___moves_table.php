<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGramasiAndLoadChemicalMovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chemical___moves', function (Blueprint $table) {
            $table->string('gram')->default('-');
            $table->string('lot_number')->default('-');
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
            $table->dropColumn('gram');
            $table->dropColumn('lot_number');
        });
    }
}
