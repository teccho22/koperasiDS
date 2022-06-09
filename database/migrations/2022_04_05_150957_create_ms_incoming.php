<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsIncoming extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ms_incomings', function (Blueprint $table) {
            $table->increments('incoming_id')->autoIncrement();
            $table->integer('loan_id', 10);
            $table->string('incoming_category', 64);
            $table->dateTime('incoming_date');
            $table->float('incoming_amount', 16, 2)->default(0);
            $table->text('notes');  
            $table->datetime('loan_due_date');
            $table->string('loan_status', 64);
            // mandatory
            $table->boolean('is_active')->default(1);
            $table->integer('created_by');
            $table->dateTime('created_at')->useCurrent();
            $table->integer('updated_by');
            $table->dateTime('updated_at')->useCurrentOnUpdate();
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
