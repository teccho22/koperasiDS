<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMsLoan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('ms_loans', function (Blueprint $table) {
            $table->increments('loan_id')->autoIncrement();
            $table->string('customer_id', 64);
            $table->string('loan_number', 32);
            $table->float('loan_amount', 16, 2);
            $table->float('interest_rate', 16, 2)->default('2.50');
            $table->float('provision_fee', 16, 2);
            $table->float('disbursement_amount', 16, 2);
            $table->integer('tenor');
            $table->float('installment_amount', 16, 2);
            $table->text('collateral_category');
            $table->text('collateral_file_name');
            $table->text('collateral_file_path');
            $table->text('collateral_description');
            $table->integer('loan_collect');
            $table->integer('loan_dpd');
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
