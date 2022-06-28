<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class DisbursementController extends Controller
{
    //
    function index()
    {
        DB::connection()->enableQueryLog();

        $agentList = DB::table('customers')
            ->select('customer_agent')
            ->distinct()
            ->where('is_active', 1)
            ->get(['customer_agent']);

        $disbursement = DB::table('ms_loans')
                    ->join('customers','ms_loans.customer_id', '=', 'customers.customer_id')
                    ->where('customers.is_active', 1)
                    ->where('ms_loans.is_active', 1)
                    ->whereMonth('ms_loans.create_at', '=', date('m'))
                    ->whereYear('ms_loans.create_at', '=', date('Y'))
                    ->select( DB::raw('DATE_FORMAT(ms_loans.create_at, "%d-%b-%Y") as transaction_date')
                            , 'ms_loans.loan_number'
                            , 'ms_loans.customer_id'
                            , 'customers.customer_name'
                            , 'customers.customer_proffesion'
                            , 'customers.customer_address'
                            , DB::raw('(CASE WHEN ms_loans.collateral_description IS NULL THEN IFNULL(ms_loans.collateral_category, "") ELSE ms_loans.collateral_description END) AS collateral')
                            , 'ms_loans.loan_amount'
                            , 'ms_loans.installment_amount'
                            , 'ms_loans.tenor'
                    )
                    // ->orderBy('trx_account_mgmt.id', 'desc')
                    ->paginate(10);
        
        $queries = DB::getQueryLog();;
        // dd($queries);

        return view('report/disbursement', [
            'disbursement' => $disbursement,
            'agentList' => $agentList
        ]);
    }

    function searchDisbursement(Request $request)
    {
        $sql = DB::table('ms_loans')
        ->join('customers','ms_loans.customer_id', '=', 'customers.customer_id');

        if ($request->searchAgent)
        {
            $sql->where("customers.customer_agent","=",$request->searchAgent);
        }
        if ($request->searchDateFrom)
        {
            $sql->whereRaw("DATE_FORMAT(ms_loans.create_at, '%Y-%m') >= ?", date($request->searchDateFrom));
        }
        if ($request->searchDateTo)
        {
            $sql->whereRaw("DATE_FORMAT(ms_loans.create_at, '%Y-%m') <= ?", date($request->searchDateTo));
        }

        DB::connection()->enableQueryLog();

        $disbursement = $sql
                    ->where('customers.is_active', 1)
                    ->where('ms_loans.is_active', 1)
                    ->select( DB::raw('DATE_FORMAT(ms_loans.create_at, "%d-%b-%Y") as transaction_date')
                            , 'ms_loans.loan_number'
                            , 'ms_loans.customer_id'
                            , 'customers.customer_name'
                            , 'customers.customer_proffesion'
                            , 'customers.customer_address'
                            , DB::raw('(CASE WHEN ms_loans.collateral_description IS NULL THEN IFNULL(ms_loans.collateral_category, "") ELSE ms_loans.collateral_description END) AS collateral')
                            , 'ms_loans.loan_amount'
                            , 'ms_loans.installment_amount'
                            , 'ms_loans.tenor'
                    )
                    ->paginate(10);
        
        $queries = DB::getQueryLog();;

        $agentList = DB::table('customers')
            ->select('customer_agent')
            ->distinct()
            ->where('is_active', 1)
            ->get(['customer_agent']);


        return view('report/disbursement', [
            'disbursement' => $disbursement,
            'agentList' => $agentList
        ]);
    }
}
