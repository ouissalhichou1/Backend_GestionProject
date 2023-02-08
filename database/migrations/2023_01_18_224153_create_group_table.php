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
            $table->increments('id_group');
            $table->integer('id_user1')->unsigned()->nullable();
            $table->foreign('id_user1')->references('id_user')->on('users');
            $table->integer('id_user2')->unsigned()->nullable();
            $table->foreign('id_user2')->references('id_user')->on('users');
            $table->integer('id_user3')->unsigned()->nullable();
            $table->foreign('id_user3')->references('id_user')->on('users');
            $table->integer('id_user4')->unsigned()->nullable();
            $table->foreign('id_user4')->references('id_user')->on('users');
            $table->integer('id_user5')->unsigned()->nullable();
            $table->foreign('id_user5')->references('id_user')->on('users');
            $table->integer('id_project')->unsigned();
            $table->foreign('id_project')->references('id_project')->on('projects');
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
