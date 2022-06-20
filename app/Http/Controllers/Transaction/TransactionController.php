<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class TransactionController extends Controller
{
    //
    function index()
    {
        // DB::connection()->enableQueryLog();
        $transaction = DB::table('trx_account_mgmt')
                    // ->leftJoin('ms_incomings', 'trx_account_mgmt.incoming_id', '=', 'ms_incomings.incoming_id')
                    ->leftJoin('ms_incomings', function($join){
                        $join->on('trx_account_mgmt.incoming_id', '=', 'ms_incomings.incoming_id');
                    })
                    ->leftJoin('ms_outgoings', 'trx_account_mgmt.outgoing_id', '=','ms_outgoings.outgoing_id')
                    ->where('trx_account_mgmt.is_active', 1)
                    ->orderBy('trx_account_mgmt.id', 'desc')
                    ->paginate(10);
        
        // $queries = DB::getQueryLog();
        // dd($queries);

        return view('transaction/transaction', [
            'transaction' => $transaction
        ]);
    }

    function addTransaction(Request $request)
    {
        $rules = [
            'transactionDate'   => 'required',
            'category'          => 'required',
            'amount'            => 'required'
        ];

        if ($this->validate($request, $rules))
        {
            $balance = DB::select("
                SELECT
                    cash_account,
                    bank_account
                FROM 
                    trx_account_mgmt 
                WHERE 
                    id = (SELECT max(id) from trx_account_mgmt where is_active=1)
                    and is_active=1
            ");

            if ($request->category == 'Cash2Bank')
            {
                // cash acc - amount, bank acc + amount
                $cashAccount = $balance[0]->cash_account - $request->amount;
                $bankAccount = $balance[0]->bank_account + $request->amount;

                if ($cashAccount < 0)
                {
                    return redirect()->back()->with('message', 'Amount cannot be greater than cash account');
                }
            }
            else if ($request->category == 'Bank2Cash')
            {
                // cash acc + amount, bank acc - amount
                $cashAccount = $balance[0]->cash_account + $request->amount;
                $bankAccount = $balance[0]->bank_account - $request->amount;

                if ($bankAccount < 0)
                {
                    return redirect()->back()->with('message', 'Amount cannot be greater than bank account');
                }
            }
            else if ($request->category == 'BankInterest' || $request->category == 'BankTrf')
            {
                // bank acc + amount
                $bankAccount = $balance[0]->bank_account + $request->amount;
                $cashAccount = $balance[0]->cash_account;
            }
            else if ($request->category == 'BankTax')
            {
                // bank acc - amount
                $bankAccount = $balance[0]->bank_account - $request->amount;
                $cashAccount = $balance[0]->cash_account;

                if ($bankAccount < 0)
                {
                    return redirect()->back()->with('message', 'Amount cannot be greater than bank account');
                }
            }
            else if ($request->category == 'Cash')
            {
                // cash acc + amount
                $cashAccount = $balance[0]->cash_account + $request->amount;
                $bankAccount = $balance[0]->bank_account;
            }

            $transaction = DB::table('trx_account_mgmt')->insert([
                'trx_category'      => $request->category,
                'trx_amount'        => $request->amount,
                'cash_account'      => $cashAccount,
                'bank_account'      => $bankAccount,
                'is_active'         => 1,
                'create_by'         => 3,
                'create_at'        => date('Y-m-d H:i:s'),
                'update_by'        => 3,
                'update_at'        => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return redirect()->route('transaction')->with(['success' => 'Data Berhasil Disimpan!']);
        }
    }
}
