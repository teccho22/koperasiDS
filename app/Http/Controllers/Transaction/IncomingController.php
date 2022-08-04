<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class IncomingController extends Controller
{
    //
    function index(Request $request)
    {
        $paginate = 10;
        if ($request->paginate)
        {
            $paginate = $request->paginate;
        }

        $incoming = DB::table('ms_incomings')
                    ->where('is_active', 1)
                    ->orderBy('loan_due_date', 'asc')
                    ->orderBy('incoming_date', 'asc')
                    ->paginate($paginate);

        return view('transaction/incoming', [
            'incoming' => $incoming,
            'paginate' => $paginate
        ]);
    }

    function addIncoming(Request $request)
    {
        $rules = [
            'transactionDate'  => 'required',
            'category'         => 'required',
            'amount'           => 'required'
        ];

        if ($this->validate($request, $rules))
        {
            $incoming = DB::table('ms_incomings')->insertGetId(
                [
                    'incoming_category' => $request->category,
                    'incoming_amount'   => $request->amount,
                    'incoming_date'     => $request->transactionDate,
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
                $total = $cashAccount[0]->cash_account + $request->amount;
                $bank_account = $cashAccount[0]->bank_account;
            }
            else
            {
                $total=$request->amount;
                $bank_account = 0;
            }
                
            $transaction = DB::table('trx_account_mgmt')->insert([
                'trx_category'      => 'Incoming',
                'trx_amount'        => $request->amount,
                'incoming_id'       => $incoming,
                'cash_account'      => $total,
                'bank_account'      => $bank_account,
                'is_active'         => 1,
                'create_by'         => 3,
                'create_at'        => date('Y-m-d H:i:s'),
                'update_by'        => 3,
                'update_at'        => date('Y-m-d H:i:s')
            ]);

            if ($incoming)
            {
                DB::commit();
                return redirect()->route('incoming')->with(['success' => 'Data Berhasil Disimpan!']);
            }
            else
            {
                DB::rollback();
                return redirect()->route('incoming')->with(['error' => 'Input Data Failed!!']);
            }
        }

    }

    function editIncoming(Request $request)
    {
        $rules = [
            'incomingId'       => 'required',
            'transactionDate'  => 'required',
            'category'         => 'required',
            'amount'           => 'required'
        ];

        if ($this->validate($request, $rules))
        {
            $incoming = DB::table('ms_incomings')
                ->where('incoming_id', $request->incomingId)
                ->where('is_active', 1)
                ->update([
                        'incoming_category' =>$request->category,
                        'incoming_amount'=>$request->amount,
                        'incoming_date'=>$request->transactionDate,
                        'notes'=>$request->notes
                ]);

            if ($incoming)
            {
                DB::commit();
                return redirect()->route('incoming')->with(['success' => 'Data Berhasil Disimpan!']);
            }
            else
            {
                DB::rollback();
                return redirect()->route('incoming')->with(['error' => 'Input Data Failed!!']);
            }
        }

    }

    function deleteIncoming(Request $request)
    {

        if ($request->incomingId)
        {
            $incoming = DB::table('ms_incomings')
                ->where('incoming_id', $request->incomingId)
                ->where('is_active', 1)
                ->update([
                        'is_active' => 0
                ]);

            $transaction = DB::table('trx_account_mgmt')
                ->where('incoming_id', $request->incomingId)
                ->where('is_active', 1)
                ->update([
                        'is_active' => 0
                ]);

            DB::commit();
            return response()->json([
                'errNum' => 0,
                'errStr' => 'Delete Success',
                'redirect' => 'incoming'
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

    function searchIncoming(Request $request)
    {
        DB::connection()->enableQueryLog();
        $paginate = 10;
        if ($request->paginate)
        {
            $paginate = $request->paginate;
        }

        $incoming = DB::table('ms_incomings')
                    ->where('is_active', 1)
                    ->where('incoming_category', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('incoming_id', 'LIKE', '%'.$request->search.'%')
                    ->orderBy('loan_due_date', 'asc')
                    ->orderBy('incoming_date', 'asc')
                    ->paginate($paginate);
        // dd(DB::getQueryLog());
        // dd($incoming);

        return view('transaction/incoming', [
            'incoming' => $incoming,
            'paginate' => $paginate
        ]);
    }
}
