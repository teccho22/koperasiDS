<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    // use HasFactory;
 
    protected $fillable = [
        'customer_id','customer_name','customer_id_number','customer_address','customer_proffesion','customer_phone','customer_agent','update_at','create_at','is_active','created_by','updated_by'
    ];
}
