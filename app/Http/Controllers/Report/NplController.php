<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class NplController extends Controller
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

        $npl = DB::table('ms_loans')
                    ->join('customers','ms_loans.customer_id', '=', 'customers.customer_id')
                    ->where('customers.is_active', 1)
                    ->where('ms_loans.is_active', 1)
                    ->where('ms_loans.loan_collect', '>=', 3)
                    ->whereMonth('ms_loans.create_at', '=', date('m'))
                    ->whereYear('ms_loans.create_at', '=', date('Y'))
                    ->select( 'ms_loans.customer_id',
                            'customers.customer_name',
                            'ms_loans.loan_number',
                            'ms_loans.loan_amount',
                            'ms_loans.installment_amount',
                            'ms_loans.create_at as disbursement_date',
                            'ms_loans.tenor',
                            DB::raw('IFNULL(ms_loans.loan_amount - (
                                SELECT SUM(incoming_amount)
                                 FROM ms_incomings 
                                 WHERE loan_status in ("Paid","Not Fully Paid")
                                and ms_incomings.loan_id = ms_loans.loan_id
                            ), ms_loans.loan_amount) AS outstanding'),
                            DB::raw('IFNULL(ms_loans.loan_amount - (
                                SELECT SUM(incoming_amount)
                                 FROM ms_incomings
                                 WHERE loan_status in ("Paid","Not Fully Paid")
                                and ms_incomings.loan_id = ms_loans.loan_id
                             ), ms_loans.loan_amount) AS npl_at')
                    )
                    // ->orderBy('trx_account_mgmt.id', 'desc')
                    ->paginate(10);
        
        $queries = DB::getQueryLog();;
        // dd($queries);

        return view('report/npl', [
            'npl' => $npl,
            'agentList' => $agentList
        ]);
    }

    function searchNpl(Request $request)
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
                    ->where('ms_loans.loan_collect', '>=', 3)
                    ->select( 'ms_loans.customer_id',
                            'customers.customer_name',
                            'ms_loans.loan_number',
                            'ms_loans.loan_amount',
                            'ms_loans.installment_amount',
                            'ms_loans.create_at as disbursement_date',
                            'ms_loans.tenor',
                            DB::raw('IFNULL(ms_loans.loan_amount - (
                                SELECT SUM(incoming_amount)
                                FROM ms_incomings 
                                WHERE loan_status in ("Paid","Not Fully Paid")
                                and ms_incomings.loan_id = ms_loans.loan_id
                            ), ms_loans.loan_amount) AS outstanding'),
                            DB::raw('IFNULL(ms_loans.loan_amount - (
                                SELECT SUM(incoming_amount)
                                FROM ms_incomings
                                WHERE loan_status in ("Paid","Not Fully Paid")
                                and ms_incomings.loan_id = ms_loans.loan_id
                            ), ms_loans.loan_amount) AS npl_at')
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
