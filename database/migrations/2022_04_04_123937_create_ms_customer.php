<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ms_customers', function (Blueprint $table) {
            $table->increments('id')->autoIncrement();
            $table->string('customer_id', 64)->unique();
            $table->string('customer_name', 128);
            $table->string('customer_id_number', 16);
            $table->text('customer_address');
            $table->string('customer_proffesion', 128);
            $table->string('customer_phone', 32);
            $table->integer('customer_collect');
            $table->integer('customer_dpd');
            $table->integer('customer_active_loan');
            $table->integer('is_blacklist')->default(0);
            $table->integer('is_alert');
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
