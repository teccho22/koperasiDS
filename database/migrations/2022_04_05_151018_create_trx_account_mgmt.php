<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrxAccountMgmt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('trx_account_mgmt', function (Blueprint $table) {
            $table->increments('id')->autoIncrement();
            $table->integer('incoming_id', 10);
            $table->integer('outgoing_id', 10);
            $table->string('trx_category', 64);
            $table->float('trx_amount', 16, 2);
            $table->text('notes');
            // mandatory
            $table->boolean('is_active')->default(1);
            $table->integer('created_by');
            $table->dateTime('created_at')->useCurrent();
            $table->integer('updated_by');
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
        //
    }
}
