<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Excel;
use App\Exports\DisbursemetExport;
use Illuminate\Support\Facades\Input;

class DisbursementController extends Controller
{
    //
    function index(Request $request)
    {
        if ($request->session()->get('username') == null)
        {
            return redirect()->intended('/');
        }

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

        $disbursement = DB::table('ms_loans')
                    ->join('customers','ms_loans.customer_id', '=', 'customers.customer_id')
                    ->join('ms_outgoings','ms_loans.loan_id', '=', 'ms_outgoings.loan_id')
                    ->where('customers.is_active', 1)
                    ->where('ms_loans.is_active', 1)
                    ->where('ms_outgoings.is_active', 1)
                    ->whereMonth('ms_loans.create_at', '=', date('m'))
                    ->whereYear('ms_loans.create_at', '=', date('Y'))
                    ->select( DB::raw('DATE_FORMAT(ms_outgoings.outgoing_date, "%d-%b-%Y") as transaction_date')
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
                    ->paginate($paginate);

        return view('report/disbursement', [
            'disbursement' => $disbursement,
            'agentList' => $agentList,
            'paginate' => $paginate
        ]);
    }

    function searchDisbursement(Request $request)
    {
        if($request->type == 'Display')
        {
            $paginate = 10;
            if ($request->paginate)
            {
                $paginate = $request->paginate;
            }

            $sql = DB::table('ms_loans')
            ->join('ms_outgoings','ms_loans.loan_id', '=', 'ms_outgoings.loan_id')
            ->join('customers','ms_loans.customer_id', '=', 'customers.customer_id');

            if ($request->searchAgent)
            {
                $sql->where("customers.customer_agent","=",$request->searchAgent);
            }
            if ($request->searchDateFrom)
            {
                $sql->whereRaw("DATE_FORMAT(ms_outgoings.outgoing_date, '%Y-%m') >= ?", date($request->searchDateFrom));
            }
            if ($request->searchDateTo)
            {
                $sql->whereRaw("DATE_FORMAT(ms_outgoings.outgoing_date, '%Y-%m') <= ?", date($request->searchDateTo));
            }

            if (!$request->searchDateFrom && !$request->searchDateTo)
            {
                $sql->whereMonth('ms_outgoings.outgoing_date', '=', date('m'))
                ->whereYear('ms_outgoings.outgoing_date', '=', date('Y'));
            }

            // DB::connection()->enableQueryLog();

            $disbursement = $sql
                        ->where('customers.is_active', 1)
                        ->where('ms_loans.is_active', 1)
                        ->where('ms_outgoings.is_active', 1)
                        ->select( DB::raw('DATE_FORMAT(ms_outgoings.outgoing_date, "%d-%b-%Y") as transaction_date')
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
                        ->paginate($paginate);

            $queries = DB::getQueryLog();

            $agentList = DB::table('customers')
                ->select('customer_agent')
                ->distinct()
                ->where('is_active', 1)
                ->get(['customer_agent']);

            return view('report/disbursement', [
                'disbursement' => $disbursement,
                'agentList' => $agentList,
                'paginate' => $paginate
            ]);
        }
        else if($request->type == 'Excel')
        {
            return $this->generateDisbursementExcel($request);
        }
    }

    function generateDisbursementExcel(Request $request)
    {
        $fileName = '';

        if (!$request->searchDateFrom && !$request->searchDateTo)
        {
            $fileName = 'DisbursemetExcel_' . date('m') .'-'.date('Y').'_'.date('d-m-Y H:i:s').'.xlsx';
        }
        else if ($request->searchDateTo && $request->searchDateFrom )
        {
            $fileName = 'DisbursemetExcel_' . date($request->searchDateTo) .'_'.date($request->searchDateFrom).'_'.date('d-m-Y H:i:s').'.xlsx';
        }
        else
        {
            $fileName = 'DisbursemetExcel_' . date($request->searchDateTo).date($request->searchDateFrom).'_'.date('d-m-Y H:i:s').'.xlsx';
        }

        return Excel::download(new DisbursemetExport($request->searchAgent, $request->searchDateFrom, $request->searchDateTo), $fileName);
    }
}
