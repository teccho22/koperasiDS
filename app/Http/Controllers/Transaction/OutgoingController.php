<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;

class OutgoingController extends Controller
{
    //
    function index(Request $request)
    {
        $paginate = 10;
        if ($request->paginate)
        {
            $paginate = $request->paginate;
        }

        $outgoing = DB::table('ms_outgoings')
                    ->where('is_active', 1)
                    ->orderBy('outgoing_id', 'asc')
                    ->orderBy('outgoing_date', 'asc')
                    ->paginate($paginate);

        return view('transaction/outgoing', [
            'outgoing' => $outgoing,
            'paginate' => $paginate
        ]);
    }

    function addOutgoing(Request $request)
    {
        $rules = [
            'transactionDate'  => 'required',
            'category'         => 'required',
            'amount'           => 'required'
        ];

        if ($this->validate($request, $rules))
        {
            $outgoing = DB::table('ms_outgoings')->insertGetId(
                [
                    'outgoing_category' => $request->category,
                    'outgoing_amount'   => $request->amount,
                    'outgoing_date'     => $request->transactionDate,
                    'notes'             => ($request->notes ? $request->notes:''),
                    'update_at'         => date('Y-m-d H:i:s'),
                    'create_at'         => date('Y-m-d H:i:s'),
                    'is_active'         => 1,
                    'create_by'         => 3,
                    'update_by'         => 3
                ]
            );

            $cashAccount = DB::select("
                SELECT 
                    cash_account,
                    bank_account                     
                FROM 
                    trx_account_mgmt 
                WHERE 
                    id = (SELECT max(id) from trx_account_mgmt where is_active=1)
                    and is_active=1    
            ");

            if (sizeof($cashAccount) > 0)
            {
                $total = $cashAccount[0]->cash_account - $request->amount;
                $bank_account = $cashAccount[0]->bank_account;
            }
            else
            {
                $total=0;
                $bank_account = 0;
            }
                
            $transaction = DB::table('trx_account_mgmt')->insert([
                'trx_category'      => 'Outgoing',
                'trx_amount'        => $request->amount,
                'incoming_id'       => $outgoing,
                'cash_account'      => $total,
                'bank_account'      => $bank_account,
                'is_active'         => 1,
                'create_by'         => 3,
                'create_at'        => date('Y-m-d H:i:s'),
                'update_by'        => 3,
                'update_at'        => date('Y-m-d H:i:s')
            ]);

            if ($outgoing)
            {
                DB::commit();
                return redirect()->route('outgoing')->with(['success' => 'Data Berhasil Disimpan!']);
            }
            else
            {
                DB::rollback();
                return redirect()->route('outgoing')->with(['error' => 'Input Data Failed!!']);
            }
        }
    }

    function editOutgoing(Request $request)
    {
        $rules = [
            'outgoingId'       => 'required',
            'transactionDate'  => 'required',
            'category'         => 'required',
            'amount'           => 'required',
            'notes'            => 'required'
        ];

        if ($this->validate($request, $rules))
        {
            $outgoing = DB::table('ms_outgoings')
                ->where('outgoing_id', $request->outgoingId)
                ->where('is_active', 1)
                ->update([
                        'outgoing_category' =>$request->category,
                        'outgoing_amount'=>$request->amount,
                        'outgoing_date'=>$request->transactionDate,
                        'notes'=>$request->notes
                ]);

            if ($outgoing)
            {
                DB::commit();
                return redirect()->route('outgoing')->with(['success' => 'Data Berhasil Disimpan!']);
            }
            else
            {
                DB::rollback();
                return redirect()->route('outgoing')->with(['error' => 'Input Data Failed!!']);
            }
        }

    }

    function deleteOutgoing(Request $request)
    {

        if ($request->outgoingId)
        {
            $outgoing = DB::table('ms_outgoings')
                ->where('outgoing_id', $request->outgoingId)
                ->where('is_active', 1)
                ->update([
                        'is_active' => 0
                ]);
            
            $transaction = DB::table('trx_account_mgmt')
                ->where('outgoing_id', $request->outgoingId)
                ->where('is_active', 1)
                ->update([
                        'is_active' => 0
                ]);

            DB::commit();
            return response()->json([
                'errNum' => 0,
                'errStr' => 'Delete Success',
                'redirect' => 'outgoing'
            ]);
        }
        else
        {
            return response()->json([
                'errNum' => 1,
                'errStr' => 'Delete Failed'
            ]);
        }

    }
}
