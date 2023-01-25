<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user1')->unsigned();
            $table->foreign('id_user1')->references('id')->on('users');
            $table->integer('id_user2')->unsigned();
            $table->foreign('id_user2')->references('id')->on('users');
            $table->integer('id_user3')->unsigned();
            $table->foreign('id_user3')->references('id')->on('users');
            $table->integer('id_user4')->unsigned();
            $table->foreign('id_user4')->references('id')->on('users');
            $table->integer('id_user5')->unsigned();
            $table->foreign('id_user5')->references('id')->on('users');
            $table->integer('id_projet')->unsigned();
            $table->foreign('id_projet')->references('id')->on('users');
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
        Schema::dropIfExists('group');
    }
};
