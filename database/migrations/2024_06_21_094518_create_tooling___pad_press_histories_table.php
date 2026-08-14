<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToolingPadPressHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tooling___pad_press_history', function (Blueprint $table) {
            $table->id();
            $table->string('id_barcode');
            $table->string('pic');
            $table->string('location');
            $table->string('status');
            $table->string('reason');
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
        Schema::dropIfExists('tooling___pad_press_history');
    }
}
