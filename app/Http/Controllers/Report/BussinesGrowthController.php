<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class BussinesGrowthController extends Controller
{
    //
    public function index()
    {
        // DB::connection()->enableQueryLog();
        $result = DB::select("
            SELECT
                ROUND(SUM(cash_account) + SUM(bank_account) + 
                (
                    SELECT
                        SUM( 
                            CASE
                                WHEN i.loan_status = 'Not Fully Paid' THEN l.installment_amount - i.incoming_amount
                                ELSE l.installment_amount
                            END
                        )
                    FROM
                        ms_loans l,
                        ms_incomings i
                    WHERE
                        l.loan_id = i.loan_id
                        AND i.is_active = 1
                        AND i.loan_status <> 'PAID'
                )
                , 2) 
                as total_account,
                DATE_FORMAT(create_at, '%b-%Y') as month
            FROM trx_account_mgmt
            GROUP BY DATE_FORMAT(create_at, '%b-%Y') 
            LIMIT 12
        ");
        // dd(DB::getQueryLog());
        // dd($result);

        $totalAccount = [];
        $month = [];
        foreach ($result as $totalCash) {
            array_push($totalAccount, $totalCash->total_account);
            array_push($month, $totalCash->month);
        }

        return view('report/businessGrowth', ['label' => $month, 'data' => $totalAccount]);
    }

    public function generateChart(Request $request)
    {
        $sql = DB::table('trx_account_mgmt');

        if ($request->searchDateFrom)
        {
            $sql->whereRaw("DATE_FORMAT(trx_account_mgmt.create_at, '%Y-%m') >= ?", date($request->searchDateFrom));
        }
        if ($request->searchDateTo)
        {
            $sql->whereRaw("DATE_FORMAT(trx_account_mgmt.create_at, '%Y-%m') <= ?", date($request->searchDateTo));
        }

        $result = $sql
                ->where('trx_account_mgmt.is_active', 1)
                ->select(DB::raw("ROUND(SUM(trx_account_mgmt.cash_account) + SUM(trx_account_mgmt.bank_account) + 
                         (
                             SELECT
                                 SUM( 
                                     CASE
                                         WHEN i.loan_status = 'Not Fully Paid' THEN l.installment_amount - i.incoming_amount
                                         ELSE l.installment_amount
                                     END
                                 )
                             FROM
                                 ms_loans l,
                                 ms_incomings i
                             WHERE
                                 l.loan_id = i.loan_id
                                 AND i.is_active = 1
                                 AND i.loan_status <> 'PAID'
                         )
                         , 2) 
                         as total_account"),
                        DB::raw("DATE_FORMAT(trx_account_mgmt.create_at, '%b-%Y') as month"))
                ->groupBy(DB::raw("DATE_FORMAT(trx_account_mgmt.create_at, '%b-%Y')"))
                ->get();
        // dd(DB::getQueryLog());
        // dd($result);
        
        $totalAccount = [];
        $month = [];
        foreach ($result as $totalCash) {
            array_push($totalAccount, $totalCash->total_account);
            array_push($month, $totalCash->month);
        }

        return view('report/businessGrowth', ['label' => $month, 'data' => $totalAccount]);
    }
}
