<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolingPadPressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::CONNECTION('sqlsrv')->create('tooling___pad_press', function (Blueprint $table) {
            $table->id();
            $table->string('pembuatan_pad_press')->default('-');
            $table->string('model')->default('-');
            $table->string('gender')->default('-');
            $table->string('version')->default('-');
            $table->string('remark')->default('-');
            $table->string('size')->default('-');
            $table->string('side')->default('-');
            $table->string('id_barcode')->unique();
            $table->string('location')->default('-');
            $table->string('no_rack')->default('-');
            $table->string('status')->default('-');
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
        Schema::CONNECTION('sqlsrv')->dropIfExists('tooling___pad_press');
    }
}
