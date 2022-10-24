<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Outgoings extends Model
{
    //
    protected $fillable = [
        'outgoing_id','loan_id','outgoing_category','outgoin_amount','outgoing_date','notes','update_at','create_at','is_active','created_by','updated_by'
    ];
}
