<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incomings extends Model
{
    //
    protected $fillable = [
        'incoming_id','loan_id','incoming_category','incoming_date','incoming_amount','notes','loan_due_date','loan_status','update_at','create_at','is_active','created_by','updated_by'
    ];
}
