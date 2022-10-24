<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id')->autoIncrement()->unique();
            $table->string('username', 128);
            $table->string('password');
            // mandatory
            $table->boolean('is_active')->default(1);
            $table->integer('created_by')->default(0);
            $table->dateTime('created_at')->useCurrent();
            $table->integer('updated_by')->default(0);
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
