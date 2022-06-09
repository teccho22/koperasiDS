<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsOutgoing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ms_outgoings', function (Blueprint $table) {
            $table->increments('outgoing_id')->autoIncrement();
            $table->integer('loan_id', 10);
            $table->string('outgoing_category', 64);
            $table->dateTime('outgoing_date');
            $table->float('outgoing_amount', 16, 2);
            $table->text('notes');
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
