<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\NplExport;
use Illuminate\Support\Facades\DB;
use Excel;

class NplController extends Controller
{
    //
    function index(Request $request)
    {
        if ($request->session()->get('username') == null)
        {
            return redirect()->intended('/');
        }
        
        // DB::connection()->enableQueryLog();
        $paginate = 10;
        if ($request->paginate)
        {
            $paginate = $request->paginate;
        }

        $agentList = DB::table('customers')
            ->select('customer_agent')
            ->distinct()
            ->where('is_active', 1)
            ->get(['customer_agent']);

        $npl = DB::table('ms_loans')
                    ->join('customers','ms_loans.customer_id', '=', 'customers.customer_id')
                    ->join('ms_outgoings','ms_loans.loan_id', '=', 'ms_outgoings.loan_id')
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
                            DB::raw('DATE_FORMAT(ms_outgoings.outgoing_date, "%d-%b-%Y") as disbursement_date'),
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
                    ->paginate($paginate);
        
        // $queries = DB::getQueryLog();;
        // dd($queries);

        return view('report/npl', [
            'npl' => $npl,
            'agentList' => $agentList,
            'paginate' => $paginate
        ]);
    }

    function searchNpl(Request $request)
    {
        if($request->type == 'Display') 
        {
            $paginate = 10;
            if ($request->paginate)
            {
                $paginate = $request->paginate;
            }

            $sql = DB::table('ms_loans')
                ->join('customers','ms_loans.customer_id', '=', 'customers.customer_id')
                ->join('ms_outgoings','ms_loans.loan_id', '=', 'ms_outgoings.loan_id');

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
            if (!$request->searchDateFrom && !$request->searchDateTo)
            {
                $sql->whereMonth('ms_loans.create_at', '=', date('m'))
                ->whereYear('ms_loans.create_at', '=', date('Y'));
            }

            // DB::connection()->enableQueryLog();

            $npl = $sql
                        ->where('customers.is_active', 1)
                        ->where('ms_loans.is_active', 1)
                        ->where('ms_loans.loan_collect', '>=', 3)
                        ->select( 'ms_loans.customer_id',
                                'customers.customer_name',
                                'ms_loans.loan_number',
                                'ms_loans.loan_amount',
                                'ms_loans.installment_amount',
                                DB::raw('DATE_FORMAT(ms_outgoings.outgoing_date, "%d-%b-%Y") as disbursement_date'),
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
                        ->paginate($paginate);
            
            // $queries = DB::getQueryLog();;

            $agentList = DB::table('customers')
                ->select('customer_agent')
                ->distinct()
                ->where('is_active', 1)
                ->get(['customer_agent']);


            return view('report/npl', [
                'npl' => $npl,
                'agentList' => $agentList,
                'paginate' => $paginate
            ]);
        }
        else if($request->type == 'Excel')
        {
            return $this->generateNplExcel($request);
        }
    }

    function generateNplExcel(Request $request)
    {
        $fileName = '';
        
        if (!$request->searchDateFrom && !$request->searchDateTo)
        {
            $fileName = 'NplExcel_' . date('m') .'-'.date('Y').'_'.date('d-m-Y H:i:s').'.xlsx';
        }
        else if ($request->searchDateTo && $request->searchDateFrom )
        {
            $fileName = 'NplExcel_' . date($request->searchDateTo) .'_'.date($request->searchDateFrom).'_'.date('d-m-Y H:i:s').'.xlsx';
        }
        else
        {
            $fileName = 'NplExcel_' . date($request->searchDateTo).date($request->searchDateFrom).'_'.date('d-m-Y H:i:s').'.xlsx';   
        }

        return Excel::download(new NplExport($request->searchAgent, $request->searchDateFrom, $request->searchDateTo), $fileName);
    }
}
