<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutConfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rout_confs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('router_id')->unsigned();
            $table->integer('configuration_id')->unsigned();
        });

        Schema::table('rout_confs', function (Blueprint $table) {
            $table->foreign('router_id')->references('id')->on('routers');
            $table->foreign('configuration_id')->references('id')->on('configurations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rout_confs');
    }
}
