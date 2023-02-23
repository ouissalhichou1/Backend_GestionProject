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
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_group_admin')->unsigned();
            $table->foreign('id_group_admin')->references('id')->on('users');
            $table->integer('id_user2')->unsigned()->nullable();
            $table->foreign('id_user2')->references('id')->on('users');
            $table->integer('id_user3')->unsigned()->nullable();
            $table->foreign('id_user3')->references('id')->on('users');
            $table->integer('id_user4')->unsigned()->nullable();
            $table->foreign('id_user4')->references('id')->on('users');
            $table->integer('id_user5')->unsigned()->nullable();
            $table->foreign('id_user5')->references('id')->on('users');
            $table->integer('id_project')->unsigned()->nullable();
            $table->foreign('id_project')->references('id')->on('projects');
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
        Schema::dropIfExists('groups');
    }
};
