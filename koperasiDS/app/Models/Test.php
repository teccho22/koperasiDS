<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Test extends Model
{
    public function checkDb()
    {
        if(DB::connection()->getDatabaseName())
        {
        echo "Yes! successfully connected to the DB: " . DB::connection()->getDatabaseName();
        }
    }
}
