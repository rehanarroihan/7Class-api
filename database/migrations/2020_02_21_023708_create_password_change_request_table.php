<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordChangeRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_change_request', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('id_user');
            $table->string('token');
            $table->boolean('is_changed');
            $table->timestamps();
        });

        Schema::table('password_change_request', function (Blueprint $table) {
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_change_request');
    }
}
