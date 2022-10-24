<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ms_loans extends Model
{
    //
    protected $fillable = [
        'loan_id','customer_id','loan_number','loan_amount','interest_rate','provision_fee','disbursement_amount','tenor','installment_amount','collateral_category','collateral_file_name','collateral_file_path','collateral_description','loan_collect','loan_dpd','update_at','create_at','is_active','created_by','updated_by'
    ];
}