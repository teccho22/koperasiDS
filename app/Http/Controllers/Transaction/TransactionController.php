<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Response;

class TransactionController extends Controller
{
    //
    function index(Request $request)
    {
        $paginate = 10;
        if ($request->paginate)
        {
            $paginate = $request->paginate;
        }

        // DB::connection()->enableQueryLog();
        $transaction = DB::table('trx_account_mgmt')
                    // ->leftJoin('ms_incomings', 'trx_account_mgmt.incoming_id', '=', 'ms_incomings.incoming_id')
                    ->leftJoin('ms_incomings', function($join){
                        $join->on('trx_account_mgmt.incoming_id', '=', 'ms_incomings.incoming_id');
                    })
                    ->leftJoin('ms_outgoings', 'trx_account_mgmt.outgoing_id', '=','ms_outgoings.outgoing_id')
                    ->where('trx_account_mgmt.is_active', 1)
                    ->select(
                        'trx_account_mgmt.id',
                        DB::raw('(CASE 
                            WHEN trx_account_mgmt.trx_category ="Outgoing" THEN ms_outgoings.outgoing_date
                            WHEN trx_account_mgmt.trx_category ="Incoming" THEN ms_incomings.incoming_date
                            ELSE trx_account_mgmt.update_at
                            END) AS transaction_date'),
                        'trx_account_mgmt.trx_category',
                        'trx_account_mgmt.trx_amount',
                        'trx_account_mgmt.cash_account',
                        'trx_account_mgmt.bank_account'
                    )
                    ->orderBy('trx_account_mgmt.id', 'desc')
                    ->paginate($paginate);
        
        // $queries = DB::getQueryLog();
        // dd($queries);

        return view('transaction/transaction', [
            'transaction' => $transaction,
            'paginate' => $paginate
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

    function searchTransaction(Request $request)
    {
        $transaction = DB::table('trx_account_mgmt')
                    // ->leftJoin('ms_incomings', 'trx_account_mgmt.incoming_id', '=', 'ms_incomings.incoming_id')
                    ->leftJoin('ms_incomings', function($join){
                        $join->on('trx_account_mgmt.incoming_id', '=', 'ms_incomings.incoming_id');
                    })
                    ->leftJoin('ms_outgoings', 'trx_account_mgmt.outgoing_id', '=','ms_outgoings.outgoing_id')
                    ->where('trx_account_mgmt.is_active', 1)
                    ->where('trx_account_mgmt.trx_category', 'LIKE', '%'.$request->search.'%')
                    ->orWhere('trx_account_mgmt.id', 'LIKE', '%'.$request->search.'%')
                    ->orWhere(DB::raw('DATE_FORMAT(trx_account_mgmt.update_at, "%Y-%b-%d") = STR_TO_DATE("'.$request->search.'","%Y-%b-%d")'))
                    ->select(
                        'trx_account_mgmt.id',
                        DB::raw('(CASE 
                            WHEN trx_account_mgmt.trx_category ="Outgoing" THEN ms_outgoings.outgoing_date
                            WHEN trx_account_mgmt.trx_category ="Incoming" THEN ms_incomings.incoming_date
                            ELSE trx_account_mgmt.update_at
                            END) AS transaction_date'),
                        'trx_account_mgmt.trx_category',
                        'trx_account_mgmt.trx_amount',
                        'trx_account_mgmt.cash_account',
                        'trx_account_mgmt.bank_account'
                    )
                    ->orderBy('trx_account_mgmt.id', 'desc')
                    ->paginate(10);

        return view('transaction/transaction', [
            'transaction' => $transaction,
            'paginate' => 10
        ]);
    }
}
