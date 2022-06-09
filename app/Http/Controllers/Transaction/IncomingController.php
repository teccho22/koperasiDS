<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class IncomingController extends Controller
{
    //
    function index()
    {
        $incoming = DB::table('ms_incomings')->where('is_active', 1)->orderBy('incoming_id', 'asc')->paginate(10);

        return view('transaction/incoming', [
            'incoming' => $incoming
        ]);
    }
}
