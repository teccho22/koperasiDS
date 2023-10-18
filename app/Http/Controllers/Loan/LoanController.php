<?php

namespace App\Http\Controllers\Loan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Loan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use Session;
use Illuminate\Support\Facades\Validator;

// loan status
// 1 : Haven't due yet
// 2 : Due
// 3 : Paid
// 4 : Overdue
// 5 : Not Fully Paid

class LoanController extends Controller
{
    //
    function index($id, Request $request)
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

        $customer = DB::table('customers')->where('customer_id', $id)
                                        ->where('is_active', 1)
                                        ->first();

        $loanList = DB::table('ms_loans')
                ->join('ms_outgoings','ms_loans.loan_id', '=', 'ms_outgoings.loan_id')
                ->where('ms_loans.customer_id', $id)
                ->where('ms_loans.is_active', 1)
                ->where('ms_outgoings.is_active', 1)
                ->select(
                    'ms_loans.loan_number',
                    'ms_loans.loan_amount',
                    'ms_loans.installment_amount',
                    'ms_loans.tenor',
                    'ms_loans.collateral_description',
                    'ms_loans.loan_id',
                    'ms_loans.customer_id',
                    'ms_loans.collateral_file_path',
                    DB::raw('DATE_FORMAT(ms_outgoings.outgoing_date, "%Y-%b-%d") as loan_date')
                )
                ->orderBy('customer_id', 'asc')
                ->get();

        $loans = DB::table('ms_loans')
                ->join('ms_outgoings','ms_loans.loan_id', '=', 'ms_outgoings.loan_id')
                ->where('ms_loans.customer_id', $id)
                ->where('ms_loans.is_active', 1)
                ->where('ms_outgoings.is_active', 1)
                ->select(
                    'ms_loans.loan_number',
                    'ms_loans.loan_amount',
                    'ms_loans.installment_amount',
                    'ms_loans.tenor',
                    'ms_loans.collateral_description',
                    'ms_loans.loan_id',
                    'ms_loans.customer_id',
                    'ms_loans.collateral_file_path',
                    DB::raw('DATE_FORMAT(ms_outgoings.outgoing_date, "%Y-%b-%d") as loan_date')
                )
                ->orderBy('customer_id', 'asc')
                ->paginate($paginate);

        $collateralList = DB::table('ms_loans')
                        ->select('collateral_category')
                        ->distinct()
                        ->where('is_active', 1)
                        ->get(['collateral_category']);

        if (sizeof($loanList) > 0)
        {
            $loanIds = [];
            foreach ($loanList as $loan)
            {
                array_push($loanIds,$loan->loan_id);

                $checkIncoming = DB::table('ms_incomings')->where('loan_id', $loan->loan_id)
                    ->where('is_active', 1)
                    ->where('incoming_category', 'Installment')
                    ->where('loan_status', 'not like', 'Paid')
                    ->whereDate(DB::raw("(DATE_FORMAT(DATE_ADD(loan_due_date, INTERVAL 7 DAY),'%Y-%m-%d'))"), '<', date('Y-m-d'))
                    ->update(
                        [
                            'loan_status' => 'Overdue',
                            'update_at' => date('Y-m-d H:i:s')
                        ]
                    );

                $checkCollectLoan = DB::select("
                    SELECT
                        count(i.loan_due_date) as CountOverdue
                    FROM
                        ms_loans l,
                        ms_incomings i
                    WHERE
                        l.customer_id = '$id'
                        AND l.loan_id = '$loan->loan_id'
                        AND l.loan_id = i.loan_id
                        AND DATE_FORMAT(DATE_ADD(i.loan_due_date, INTERVAL 7 DAY),'%Y-%m-%d') < DATE_FORMAT(NOW(),'%Y-%m-%d')
                        AND i.incoming_category = 'Installment'
                        AND i.loan_status = 'Overdue'
                        AND l.is_active = 1
                        AND i.is_active = 1
                ")[0]->CountOverdue;

                if ($checkCollectLoan > 0)
                {
                    if ($checkCollectLoan > 4)
                    {
                        $checkCollectLoan = 4;
                    }

                    $loanDpd = DB::select("
                        SELECT
                            min(loan_due_date),
                            DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'), DATE_FORMAT(loan_due_date,'%Y-%m-%d')) dpd
                        FROM
                            ms_incomings
                        WHERE
                            loan_id = '$loan->loan_id'
                            AND is_active = 1
                            AND DATE_FORMAT(DATE_ADD(loan_due_date, INTERVAL 7 DAY),'%Y-%m-%d') < DATE_FORMAT(NOW(),'%Y-%m-%d')
                            AND incoming_category = 'Installment'
                        GROUP BY
                            loan_due_date
                    ")[0]->dpd;
                    // dd($loanDpd);

                    // update dpd
                    DB::statement("update ms_loans set loan_dpd = '$loanDpd', update_at = NOW() WHERE loan_id='$loan->loan_id' AND is_active=1");

                    // update loan colect
                    DB::statement("
                        update ms_loans
                        set
                            loan_collect = '$checkCollectLoan' + 1
                            , update_at = NOW()
                        where
                            customer_id = '$id'
                            AND loan_id='$loan->loan_id'
                            AND is_active = 1
                    ");
                }
                else
                {
                    DB::statement("update ms_loans set loan_dpd = 0, update_at = NOW() WHERE loan_id='$loan->loan_id' AND is_active=1");

                    DB::statement("
                        update ms_loans
                        set
                            loan_collect = 1
                            , update_at = NOW()
                        where
                            customer_id = '$id'
                            AND loan_id='$loan->loan_id'
                            AND is_active = 1
                    ");
                }
            }
            $ids= implode(',', $loanIds);

            $checkDue = DB::statement("
                update ms_incomings
                set
                    loan_status = 'Due'
                    , update_at = NOW()
                where
                    is_active=1
                    AND incoming_category = 'Installment'
                    AND loan_id IN ($ids) AND DATE_FORMAT(NOW(),'%Y-%m-%d') BETWEEN DATE_FORMAT(loan_due_date,'%Y-%m-%d') AND DATE_FORMAT(DATE_ADD(loan_due_date, INTERVAL 7 DAY),'%Y-%m-%d')
            ");

            $checkCollectCustomer = DB::select("
                SELECT
                    count(i.loan_due_date) as countOverdue
                FROM
                    customers c,
                    ms_loans l,
                    ms_incomings i
                WHERE
                    c.customer_id = '$id'
                    AND l.customer_id = c.customer_id
                    AND l.loan_id = i.loan_id
                    AND DATE_FORMAT(DATE_ADD(i.loan_due_date, INTERVAL 7 DAY),'%Y-%m-%d') < DATE_FORMAT(NOW(),'%Y-%m-%d')
                    AND i.incoming_category = 'Installment'
                    AND i.loan_status = 'Overdue'
                    AND c.is_active = 1
                    AND l.is_active = 1
                    AND i.is_active = 1
            ")[0]->countOverdue;

            $checkCustomerActiveLoan = DB::select("
                SELECT
                    count(l.loan_id) as countLoan
                FROM
                    customers c,
                    ms_loans l
                WHERE
                    c.customer_id = '$id'
                    AND l.customer_id = c.customer_id
                    AND c.is_active = 1
                    AND l.is_active = 1
                    AND l.loan_id IN (
                        SELECT DISTINCT
                            l.loan_id
                        FROM
                            ms_loans l,
                            ms_incomings i
                        WHERE
                            l.loan_id = i.loan_id
                            AND i.incoming_category = 'Installment'
                            AND i.loan_status != 'Paid'
                            AND l.is_active = 1
                            AND i.is_active = 1
                    )
            ")[0]->countLoan;

            DB::statement("
                update customers
                set
                    customer_active_loan = '$checkCustomerActiveLoan'
                    , updated_at = NOW()
                where
                    customer_id = '$id'
                    AND is_active = 1
            ");

            // update customer collect
            if ($checkCollectCustomer > 0)
            {
                if ($checkCollectCustomer > 4)
                {
                    $checkCollectCustomer = 4;
                }

                DB::statement("
                    update customers
                    set
                        customer_collect = '$checkCollectCustomer' + 1
                        , updated_at = NOW()
                    where
                        customer_id = '$id'
                        AND is_active = 1
                ");
            }
            else
            {
                DB::statement("
                    update customers
                    set
                        customer_collect = 1
                        , updated_at = NOW()
                    where
                        customer_id = '$id'
                        AND is_active = 1
                ");
            }

            $checkBlacklist = DB::statement("
                update customers
                set
                    is_blacklist = 1
                    , updated_at = NOW()
                where
                    customer_id = '$id'
                    AND customer_collect = 5
                    AND is_active = 1
            ");

            $incoming = DB::table('ms_incomings')->whereIn('loan_id', $loanIds)
                                                ->where('is_active', 1)
                                                ->get();

            $maxCollect = DB::select("
                SELECT
                    max(loan_collect) as collect
                FROM
                    ms_loans
                WHERE
                    customer_id = '$id'
                    AND loan_id='$loan->loan_id'
                    AND is_active = 1
            ")[0]->collect;

            return view('loan/loan', [
                'customer' => $customer,
                'loanList' => $loans,
                'incomings' => $incoming,
                'collateralList' => $collateralList,
                'paginate' => $paginate,
                'collect' => $maxCollect
            ]);
        }
        else{
            $incoming = $loanList;
            return view('loan/loan', [
                'customer' => $customer,
                'loanList' => $loans,
                'incomings' => $incoming,
                'collateralList' => $collateralList,
                'paginate' => $paginate,
                'collect' => 0
            ]);
        }
    }

    function addLoan(Request $request)
    {
        $rules = [
            'loanId'                => 'required',
            'loanDate'              => 'required',
            'custId'                => 'required',
            'loanAmount'            => 'required',
            'interestRate'          => 'required',
            'tenor'                 => 'required',
            'installmentAmount'     => 'required',
            'provisionFee'          => 'required',
            'disbursementAmount'    => 'required',
            'collateralFiles'       => 'mimetypes:image/jpeg,application/pdf,image/png'
        ];
        // dd($request->loanDate);

        if ($this->validate($request, $rules))
        {
            $file = $request->file('collateralFiles');
            $fileName = '';
            $filePath = '';
            $extendsion = '';
            if ($file)
            {
                if ($file->getMimeType() == 'image/jpeg')
                {
                    $extendsion = 'jpeg';
                }
                elseif ($file->getMimeType() == 'image/png')
                {
                    $extendsion = 'png';
                }
                elseif ($file->getMimeType() == 'application/pdf')
                {
                    $extendsion = 'pdf';
                }
                else{
                    return redirect()->route('customer')->with(['error' => 'File must be png, pdf or jpeg']);
                }

                $fileFolder = 'collateral_file';
                $fileName = 'Collateral-' .$request->customerName . '-' . date('Y-m-d') . '.' . $extendsion;
                // dd($fileName);
                $file->move($fileFolder,$fileName);
                $filePath = $fileFolder . '/' . $fileName;
            }

            $results = DB::select("
                SELECT
                    max(loan_number) as loan_number
                FROM
                    ms_loans
                WHERE
                    customer_id = '$request->custId'
                    and is_active=1
                "
            );

            $loanNumber = '';
            if ($request->loanId != null)
            {
                $loanNumber = $request->loanId;
            }
            else
            {
                if ($results[0]->loan_number == null)
                {
                    $loanNumber = 'L00001';
                }
                else{
                    // $results[0]->max_id
                    $loanNumber = 'L' . str_pad((int)Str::substr($results[0]->loan_number,1)+1, 5, '0', STR_PAD_LEFT);
                }
            }

            $loan = DB::table('ms_loans')->insertGetId(
                [
                    'customer_id' => $request->custId,
                    'loan_number' => $loanNumber,
                    'loan_amount' => $request->loanAmount,
                    'interest_rate' => $request->interestRate,
                    'provision_fee' => $request->provisionFee,
                    'disbursement_amount' => $request->disbursementAmount,
                    'tenor' => $request->tenor,
                    'installment_amount' => $request->installmentAmount,
                    'collateral_category' => $request->collateralCategory,
                    'collateral_file_name' => $fileName,
                    'collateral_file_path' => $filePath,
                    'collateral_description' => $request->collateralDescription,
                    'loan_collect' => 1,
                    'loan_dpd' => 0,
                    'update_at' => date('Y-m-d H:i:s'),
                    'create_at' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'create_by' => 3,
                    'update_by' => 3
                ]
            );

            if ($loan)
            {
                $outgoing = DB::table('ms_outgoings')->insertGetId([
                    'loan_id'           => $loan,
                    'outgoing_category' => 'New Loan',
                    'outgoing_date'     => date('Y-m-d H:i:s', strtotime($request->loanDate)),
                    'outgoing_amount'   => $request->loanAmount,
                    'update_at' => date('Y-m-d H:i:s'),
                    'create_at' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'create_by' => 3,
                    'update_by' => 3
                ]);

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
                    $total = $cashAccount[0]->cash_account - $request->loanAmount;
                    $bank_account = $cashAccount[0]->bank_account;
                }
                else
                {
                    $total=0;
                    $bank_account = 0;
                }

                $transaction = DB::table('trx_account_mgmt')->insertGetId([
                    'outgoing_id'       => $outgoing,
                    'trx_category'      => 'Outgoing',
                    'trx_amount'        => $request->loanAmount,
                    'cash_account'      => $total,
                    'bank_account'      => $bank_account,
                    'update_at'         => date('Y-m-d H:i:s'),
                    'create_at'         => date('Y-m-d H:i:s'),
                    'is_active'         => 1,
                    'create_by'         => 3,
                    'update_by'         => 3
                ]);

                $tenor = $request->tenor;
                $date = date('Y-m-d', strtotime($request->loanDate));
                for ($i=0; $i < $tenor; $i++) {
                    $date = date("Y-m-d", strtotime( $date . "+1 month"));

                    $incoming = DB::table('ms_incomings')->insertGetId(
                        [
                            'loan_id' => $loan,
                            'incoming_category' => 'Installment',
                            'loan_due_date' => $date,
                            'loan_status' => 'Haven\'t due yet',
                            'update_at' => date('Y-m-d H:i:s'),
                            'create_at' => date('Y-m-d H:i:s'),
                            'is_active' => 1,
                            'create_by' => 3,
                            'update_by' => 3
                        ]
                    );

                    if (!$incoming)
                    {
                        DB::rollback();
                        return redirect()->route('loan', [$request->custId])->with(['error' => 'Input Data Failed!!']);
                        break;
                    }
                };

                DB::commit();
                return redirect()->route('loan', [$request->custId])->with(['success' => 'Data Berhasil Disimpan!']);
            }
            else
            {
                DB::rollback();
                return redirect()->route('loan', [$request->custId])->with(['error' => 'Input Data Failed!!']);
            }
        }
    }

    function editLoan(Request $request)
    {
        $rules = [
            'editCollateralFiles'         => 'mimetypes:image/jpeg,application/pdf,image/png'
        ];

        $file = $request->file('editCollateralFiles');
        $fileName = '';
        $filePath = '';
        $extendsion = '';
        if ($file)
        {
            if ($file->getMimeType() == 'image/jpeg')
            {
                $extendsion = 'jpeg';
            }
            elseif ($file->getMimeType() == 'image/png')
            {
                $extendsion = 'png';
            }
            elseif ($file->getMimeType() == 'application/pdf')
            {
                $extendsion = 'pdf';
            }
            else{
                return redirect()->route('customer')->withErrors(['files' => 'File must be png, pdf or jpeg']);
            }

            $fileFolder = 'collateral_file';
            $fileName = 'Collateral-' . date('Y-m-d H:i:s') . '.' . $extendsion;
            // dd($fileName);
            $file->move($fileFolder,$fileName);
            $filePath = $fileFolder . '/' . $fileName;
        }
        // dd($request);

        $loan = DB::table('ms_loans')->where('loan_id',$request->loanId)->take(1)->get();

        $result = DB::table('ms_loans')->where('loan_id',$request->loanId)
                    ->where('is_active', 1)
                    ->update(['collateral_category' =>$request->editCollateralCategory,
                            'collateral_file_name'=>$fileName,
                            'collateral_file_path'=>$filePath,
                            'collateral_description'=>$request->editCollateralDescription]);

        if ($request->editLoanDate)
        {
            DB::table('ms_outgoings')
                ->where('loan_id',$request->loanId)
                ->where('is_active', 1)
                ->update(['outgoing_date' => date('Y-m-d H:i:s', strtotime($request->editLoanDate))]);

            $incomings = DB::select("
                SELECT
                    incoming_id
                FROM
                    ms_incomings
                WHERE
                    loan_id = $request->loanId
                    and incoming_category = 'Installment'
                    and is_active=1
            ");

            $date = date('Y-m-d', strtotime($request->editLoanDate));
            for ($i=0; $i < sizeof($incomings); $i++) {
                $date = date("Y-m-d", strtotime( $date . "+1 month"));

                DB::table('ms_incomings')
                    ->where('incoming_id', $incomings[$i]->incoming_id)
                    ->where('is_active',1)
                    ->update(['loan_due_date' => date('Y-m-d H:i:s', strtotime($date))]);

            };

            DB::commit();
            return redirect()->route('loan',[$request->custId])->withSuccess(['editLoanDate' => 'Success']);
        }
        else{
            DB::rollback();
            return redirect()->route('loan',[$request->custId])->withErrors(['editLoanDate' => 'The loan date field is required']);
        }
    }

    function payLoan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'paymentDate'       => 'required',
            'payAmount'         => 'required',
            'loanId'            => 'required',
            'customerId'        => 'required'
        ]);

        $paymentAmount = 0;

        if (!$validator->fails())
        {
            $installmentAmount = DB::select("
                SELECT
                    l.installment_amount,
                    CASE
                        WHEN i.incoming_amount > 0 THEN (l.installment_amount - i.incoming_amount)
                        ELSE l.installment_amount
                    END as outstanding,
                    i.incoming_id
                FROM
                    ms_loans l,
                    ms_incomings i
                WHERE
                    l.loan_id = '$request->loanId'
                    AND l.customer_id = '$request->customerId'
                    AND l.is_active = 1
                    AND i.loan_id = l.loan_id
                    AND i.is_active = 1
                    AND i.loan_status NOT IN ('Paid')
                ORDER BY i.incoming_id
            ");

            $installment = (sizeof($installmentAmount) > 0 ? $installmentAmount[0]->installment_amount:0);
            $remainingOutstanding = (sizeof($installmentAmount) > 0 ? $installmentAmount[0]->outstanding:0);
            $outstanding = floatval(floatval($request->payAmount) - (sizeof($installmentAmount) > 0 ? $installmentAmount[0]->outstanding:0));
            $incomingId = $installmentAmount[0]->incoming_id;

            if(intval($outstanding) < 0)
            {
                // Not Fully Paid
                DB::statement("
                    update ms_incomings
                    set
                        incoming_amount = incoming_amount + '$request->payAmount'
                        , incoming_date = STR_TO_DATE('$request->paymentDate','%Y-%c-%d %H:%i:%s')
                        , update_at = NOW()
                        , loan_status = 'Not Fully Paid'
                    where
                        incoming_id = '$incomingId'
                        AND loan_id = '$request->loanId'
                        AND is_active = 1
                ");

                $paymentAmount = $request->payAmount;
            }
            else if (intval($outstanding) == 0)
            {
                // paid
                DB::statement("
                    update ms_incomings
                    set
                        incoming_amount = incoming_amount + '$request->payAmount'
                        , incoming_date = STR_TO_DATE('$request->paymentDate','%Y-%c-%d %H:%i:%s')
                        , update_at = NOW()
                        , loan_status = 'Paid'
                    where
                        incoming_id = '$incomingId'
                        AND loan_id = '$request->loanId'
                        AND is_active = 1
                ");

                $paymentAmount = $request->payAmount;
            }
            else if (intval($outstanding)  > 0)
            {
                DB::statement("
                    update ms_incomings
                    set
                        incoming_amount = incoming_amount + '" . $remainingOutstanding . "'
                        , incoming_date = STR_TO_DATE('$request->paymentDate','%Y-%c-%d %H:%i:%s')
                        , update_at = NOW()
                        , loan_status = 'Paid'
                    where
                        incoming_id = '$incomingId'
                        AND loan_id = '$request->loanId'
                        AND is_active = 1
                ");

                $paymentAmount = $remainingOutstanding;

                $incoming = DB::select("
                    SELECT
                        incoming_id,
                        loan_due_date,
                        loan_status
                    FROM
                        ms_incomings
                    WHERE
                        loan_status NOT IN ('Paid')
                        AND loan_id = '$request->loanId'
                        AND incoming_id NOT IN ('$incomingId')
                        AND is_active = 1
                    ORDER BY
                        loan_due_date ASC
                ");

                $countIncoming = sizeof($incoming);

                for ($j = 0; $j < sizeof($incoming); $j++)
                {
                    $status = '';
                    $pay = 0;
                    $incomingIds = '';

                    if ($outstanding <= 0)
                    {
                        break;
                    }
                    else if ($outstanding - $installment > 0)
                    {
                        if ($j == ($countIncoming-1))
                        {
                            $pay = $outstanding;
                            $status = 'Paid';
                        }
                        else{
                            $pay = $installment;
                            $status = 'Paid';
                        }
                    }
                    else if ($outstanding == $installment)
                    {
                        $status = 'Paid';
                        $pay = $outstanding;
                    }
                    else if ($outstanding < $installment)
                    {
                        if ($incoming[$j]->loan_status == 'Haven\'t due yet' || $incoming[$j]->loan_status == 'Due')
                        {
                            $status = 'Not Fully Paid';
                            $pay = $outstanding;
                        }
                        else if ($incoming[$j]->loan_status == 'Overdue')
                        {
                            $status = 'Overdue';
                            $pay = $outstanding;
                        }
                    }

                    $outstanding = $outstanding - $installment;

                    if ($status != '' && $pay != 0)
                    {
                        $incomingIds = $incoming[$j]->incoming_id;

                        DB::statement("
                            update ms_incomings
                            set
                                incoming_amount = incoming_amount + '$pay'
                                , incoming_date = STR_TO_DATE('$request->paymentDate','%Y-%c-%d %H:%i:%s')
                                , update_at = NOW()
                                , loan_status = '$status'
                            where
                                incoming_id = '$incomingIds'
                                AND loan_id = '$request->loanId'
                                AND is_active = 1
                        ");

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

                        $incomingStatus = DB::select("
                            SELECT
                                loan_status,
                                incoming_amount
                            FROM
                                ms_incomings
                            WHERE
                                incoming_id = '$incomingIds'
                                AND loan_id = '$request->loanId'
                                AND is_active = 1
                        ");

                        $total = $cashAccount[0]->cash_account + $pay;

                        $transaction = DB::table('trx_account_mgmt')->insert([
                            'trx_category'      => 'Incoming',
                            'trx_amount'        => $pay,
                            'incoming_id'       => $incomingIds,
                            'cash_account'      => $total,
                            'bank_account'      => $cashAccount[0]->bank_account,
                            'is_active'         => 1,
                            'create_by'         => 3,
                            'create_at'        => date('Y-m-d H:i:s'),
                            'update_by'        => 3,
                            'update_at'        => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }

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

            $incomingStatus = DB::select("
                SELECT
                    loan_status,
                    incoming_amount
                FROM
                    ms_incomings
                WHERE
                    incoming_id = '$incomingId'
                    AND loan_id = '$request->loanId'
                    AND is_active = 1
            ");

            $total = $cashAccount[0]->cash_account + $paymentAmount;

            $transaction = DB::table('trx_account_mgmt')->insert([
                'trx_category'      => 'Incoming',
                'trx_amount'        => $paymentAmount,
                'incoming_id'       => $incomingId,
                'cash_account'      => $total,
                'bank_account'      => $cashAccount[0]->bank_account,
                'is_active'         => 1,
                'create_by'         => 3,
                'create_at'        => date('Y-m-d H:i:s'),
                'update_by'        => 3,
                'update_at'        => date('Y-m-d H:i:s')
            ]);

            DB::commit();

            return response()->json([
                'errNum' => 0,
                'errStr' => 'Payment Success',
                'redirect' => 'loan',
                'custId' => $request->customerId
            ]);
        }
        else
        {
            // dd($validator->errors());
            return response()->json([
                'errNum' => 1,
                'errStr' => 'Payment Failed',
                'redirect' => 'loan',
                'custId' => $request->customerId,
                'errors' => $validator->errors()
            ]);
        }

    }

    function searchByLoanId(Request $request)
    {
        $customer = DB::table('customers')->where('customer_id', $request->custId)
                                        ->where('is_active', 1)
                                        ->first();

        $loanList = DB::table('ms_loans')
                ->join('ms_outgoings','ms_loans.loan_id', '=', 'ms_outgoings.loan_id')
                ->where('ms_loans.customer_id',  $request->custId)
                ->where('loan_number', 'like', '%'.$request->search.'%')
                ->where('ms_loans.is_active', 1)
                ->where('ms_outgoings.is_active', 1)
                ->select(
                    'ms_loans.loan_number',
                    'ms_loans.loan_amount',
                    'ms_loans.installment_amount',
                    'ms_loans.tenor',
                    'ms_loans.collateral_description',
                    'ms_loans.loan_id',
                    'ms_loans.customer_id',
                    'ms_loans.collateral_file_path',
                    DB::raw('DATE_FORMAT(ms_outgoings.outgoing_date, "%d-%b-%Y") as loan_date')
                )
                ->paginate(10);

        $loanIds = [];
        foreach($loanList as $loan)
        {
            array_push($loanIds,$loan->loan_id);
        }

        $incoming = DB::table('ms_incomings')->whereIn('loan_id', $loanIds)
                                        ->where('is_active', 1)
                                        ->get();

        $collateralList = DB::table('ms_loans')
                                        ->select('collateral_category')
                                        ->distinct()
                                        ->where('is_active', 1)
                                        ->get(['collateral_category']);

        $maxCollect = DB::select("
            SELECT
                max(loan_collect) as collect
            FROM
                ms_loans
            WHERE
                customer_id = '$request->custId'
                AND is_active = 1
        ")[0]->collect;

        return view('loan/loan', [
            'customer' => $customer,
            'loanList' => $loanList,
            'incomings' => $incoming,
            'collateralList' => $collateralList,
            'paginate' => 10,
            'collect' => $maxCollect
        ]);
    }

    function blacklist(Request $request)
    {
        $rules = [
            'customerId'         => 'required'
        ];

        if ($this->validate($request, $rules))
        {
            $blacklist = DB::table('customers')
                        ->where('customer_id', $request->customerId)
                        ->where('is_active', 1)
                        ->where('is_blacklist', 0)
                        ->orWhere('is_blacklist', null)
                        ->update([
                            'is_blacklist' => 1,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

            if($blacklist)
            {
                return response()->json([
                    'errNum' => 0,
                    'errStr' => 'Blacklist Success',
                    'redirect' => 'loan',
                    'custId' => $request->customerId
                ]);
            }
            else{
                return response()->json([
                    'errNum' => 1,
                    'errStr' => 'Blacklist Failed'
                ]);
            }
        }
    }

    function unblacklist(Request $request)
    {
        $rules = [
            'customerId'         => 'required'
        ];

        if ($this->validate($request, $rules))
        {
            $blacklist = DB::table('customers')
                        ->where('customer_id', $request->customerId)
                        ->where('is_active', 1)
                        ->where('is_blacklist', 1)
                        ->orWhere('is_blacklist', null)
                        ->update([
                            'is_blacklist' => 0,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

            if($blacklist)
            {
                return response()->json([
                    'errNum' => 0,
                    'errStr' => 'Unblacklist Success',
                    'redirect' => 'loan',
                    'custId' => $request->customerId
                ]);
            }
            else{
                return response()->json([
                    'errNum' => 1,
                    'errStr' => 'Unblacklist Failed'
                ]);
            }
        }
    }

    function generateSp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'spNo'              => 'required',
            'customerId'        => 'required',
            'loanId'            => 'required'
        ]);

        if (!$validator->fails())
        {
            $limit = $request->spNo + 1;

            // get customer detail
            $customerDetails = DB::select("
                SELECT
                    c.customer_name,
                    c.customer_address,
                    l.loan_amount,
                    l.installment_amount,
                    l.tenor,
                    l.collateral_description
                FROM
                    customers c,
                    ms_loans l
                WHERE
                    c.customer_id='$request->customerId'
                    AND c.is_active=1
                    AND c.customer_id = l.customer_id
                    AND l.is_active=1
                    AND l.loan_id='$request->loanId'
            ")[0];


            // get installment
            if ($limit > 3)
            {
                $installmentDetail = DB::select("
                    SELECT
                        GROUP_CONCAT(DATE_FORMAT(i.loan_due_date,'%M %Y') SEPARATOR ', ') as month
                    FROM
                        ms_incomings i
                    WHERE
                        i.loan_id = '$request->loanId'
                        AND i.is_active = 1
                        AND i.loan_status='Overdue'
                ")[0];

                $selectIncoming = DB::select("
                    SELECT
                        GROUP_CONCAT(incoming_id SEPARATOR ', ') ids
                    FROM ms_incomings
                    WHERE loan_id = '$request->loanId' and loan_status='Overdue' and is_active = 1
                ")[0];
            }
            else
            {
                $installmentDetail = DB::select ("
                    SELECT
                        SUBSTRING_INDEX(GROUP_CONCAT(DATE_FORMAT(i.loan_due_date,'%M %Y') SEPARATOR ', '),', ',$limit) as month
                    FROM
                        ms_incomings i
                    WHERE
                        i.loan_id = '$request->loanId'
                        AND i.is_active = 1
                        AND i.loan_status='Overdue'
                ")[0];

                $selectIncoming = DB::select("
                    SELECT
                        SUBSTRING_INDEX(GROUP_CONCAT(incoming_id SEPARATOR ', '),', ',$limit) ids
                    FROM ms_incomings
                    WHERE loan_id = '$request->loanId' and loan_status='Overdue' and is_active = 1
                ")[0];
            }

            $installmentPaid = DB::select ("
                SELECT
                    IFNULL(SUM(i.incoming_amount), 0) total_paid,
                    count(i.loan_status) count_paid
                FROM
                    ms_incomings i
                WHERE
                    i.loan_id = '$request->loanId'
                    AND i.is_active = 1
                    AND i.loan_status IN ('Paid', 'Not Fully Paid')
            ");

            $installmentPaidDetail = DB::select("
                SELECT
                    GROUP_CONCAT(DATE_FORMAT(i.loan_due_date,'%M %Y')) as month
                FROM
                    ms_incomings i
                WHERE
                    i.loan_id = '$request->loanId'
                    AND i.is_active = 1
                    AND i.loan_status='Paid'
            ")[0];

            $installmentUnpaid = DB::select ("
                SELECT
                    count(i.loan_status) count_unpaid,
                    (l.installment_amount * count(i.loan_status)) total_unpaid
                FROM
                    ms_incomings i,
                    ms_loans l
                WHERE
                    l.loan_id = '$request->loanId'
                    AND i.loan_id = l.loan_id
                    AND i.is_active = 1
                    AND i.loan_status='Overdue'
                    AND i.incoming_id IN ($selectIncoming->ids)
                GROUP BY l.installment_amount
            ");

            return response()->json([
                'errNum' => 0,
                'errStr' => 'Success',
                'redirect' => 'sp',
                'custDetail' => $customerDetails,
                'installmentDetail' => $installmentDetail,
                'installmentPaid'   => $installmentPaid,
                'installmentUnpaid'   => $installmentUnpaid,
                'installmentPaidDetail' => $installmentPaidDetail
            ]);
        }
        else
        {
            return response()->json([
                'errNum' => 1,
                'errStr' => 'Generate Sp Failed',
                'redirect' => 'loan',
                'custId' => $request->customerId,
                'errors' => $validator->errors()
            ]);
        }
    }

    function generateAggrementLetter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loanId'     => 'required',
            'customerId' => 'required'
        ]);

        if (!$validator->fails())
        {
            $loanDetail = DB::select("
                SELECT
                    l.loan_id,
                    l.loan_number,
                    l.loan_amount,
                    l.interest_rate,
                    l.provision_fee,
                    l.disbursement_amount,
                    l.installment_amount,
                    l.tenor,
                    l.collateral_description,
                    DATE_FORMAT(l.create_at, '%d %M %Y') as loan_date,
                    DATE_FORMAT(l.create_at, '%Y') as loan_year
                FROM
                    ms_loans l
                WHERE
                    l.customer_id='$request->customerId'
                    AND l.loan_id='$request->loanId'
                    AND l.is_active=1
            ")[0];

            $customerDetails = DB::select("
                SELECT
                    customer_id,
                    customer_name,
                    customer_address,
                    customer_phone,
                    customer_id_number,
                    customer_proffesion
                FROM
                    customers
                WHERE
                    customer_id='$request->customerId'
                    AND is_active=1
            ")[0];

            return response()->json([
                'errNum' => 0,
                'errStr' => 'Success',
                'redirect' => 'agreement',
                'custDetail' => $customerDetails,
                'loanDetail' => $loanDetail,
            ]);
        }
        else
        {
            return response()->json([
                'errNum' => 1,
                'errStr' => 'Generate Agreement Letter Failed',
                'redirect' => 'loan',
                'custId' => $request->customerId,
                'errors' => $validator->errors()
            ]);
        }
    }

    function deleteLoan(Request $request)
    {
        if ($request->id)
        {
            $incomingIds = DB::table('ms_incomings')
                            ->select('incoming_id')
                            ->where('is_active', 1)
                            ->where('loan_id', $request->id)
                            ->get()->pluck('incoming_id')->all();

            $outgoingIds = DB::table('ms_outgoings')
                            ->select('outgoing_id')
                            ->where('is_active', 1)
                            ->where('loan_id', $request->id)
                            ->get()->pluck('outgoing_id')->all();

            DB::table('trx_account_mgmt')
            ->whereIn('incoming_id', $incomingIds)
            ->orWhereIn('outgoing_id', $outgoingIds)
            ->where('is_active', 1)
            ->update([
                    'is_active' => 0
            ]);

            DB::table('ms_incomings')
            ->where('loan_id', $request->id)
            ->where('is_active', 1)
            ->update([
                    'is_active' => 0
            ]);

            DB::table('ms_outgoings')
            ->where('loan_id', $request->id)
            ->where('is_active', 1)
            ->update([
                    'is_active' => 0
            ]);

            DB::table('ms_loans')
            ->where('loan_id', $request->id)
            ->where('is_active', 1)
            ->update([
                    'is_active' => 0
            ]);

            DB::commit();
            return redirect()->route('loan',[$request->custId])->with(['success' => 'Data Berhasil Dihapus!']);
        }
        else
        {
            return redirect()->route('loan',[$request->custId])->with(['failed' => 'Data gagal Dihapus!']);
        }
    }
}
