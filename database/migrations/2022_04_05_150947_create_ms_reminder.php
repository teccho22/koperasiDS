<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsReminder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ms_reminders', function (Blueprint $table) {
            $table->increments('id')->autoIncrement();
            $table->string('customer_id', 32);
            $table->string('reminder_file_name', 128);
            $table->dateTime('reminder_generated_date');
            $table->text('reminder_file_path');
            // mandatory
            $table->boolean('is_active')->default(1);
            $table->integer('created_by');
            $table->dateTime('created_at')->useCurrent();
            $table->integer('updated_by');
            $table->dateTime('updated_at')->useCurrentOnUpdate();

            $table->foreign('customer_id')->references('customer_id')->on('ms_customers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
