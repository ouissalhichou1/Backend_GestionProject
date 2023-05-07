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
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_group')->unsigned();
            $table->foreign('id_group')->references('id')->on('groups')->onDelete('cascade');;
            $table->integer('id_project')->unsigned();
            $table->foreign('id_project')->references('id')->on('projects')->onDelete('cascade');;
            $table->string('response')->nullable();
            $table->string('response_admin')->nullable();
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
        Schema::dropIfExists('applications');
    }
};
