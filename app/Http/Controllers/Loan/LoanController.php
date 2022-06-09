<?php

namespace App\Http\Controllers\Loan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Loan;
use DB;
use Illuminate\Support\Str;
use Auth;
use Session;

// loan status
// 1 : Haven't due yet
// 2 : Due 
// 3 : Paid
// 4 : Overdue
// 5 : Partial Pay

class LoanController extends Controller
{
    //
    function index($id)
    {
        $customer = DB::table('customers')->where('customer_id', $id)
                                        ->where('is_active', 1)
                                        ->first();

        $loanList = DB::table('ms_loans')->where('customer_id', $id)
                                        ->where('is_active', 1)
                                        ->orderBy('customer_id', 'asc')
                                        ->paginate(10);

        if (sizeof($loanList) > 0)
        {
            $loanIds = [];
            foreach ($loanList as $loan)
            {
                array_push($loanIds,$loan->loan_id);

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
                        AND l.is_active = 1
                        AND i.is_active = 1
                ")[0]->CountOverdue;

                if ($checkCollectLoan> 0 && $checkCollectLoan < 5)
                {
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
    
            $checkIncoming = DB::table('ms_incomings')->whereIn('loan_id', $loanIds)
                                                    ->where('is_active', 1)
                                                    ->where('incoming_category', 'Installment')
                                                    ->where('loan_status', 'Due')
                                                    ->whereDate(DB::raw("(DATE_FORMAT(DATE_ADD(loan_due_date, INTERVAL 7 DAY),'%Y-%m-%d'))"), '<', date('Y-m-d'))
                                                    ->update(
                                                        [
                                                            'loan_status' => 'Overdue',
                                                            'update_at' => date('Y-m-d H:i:s')
                                                        ]
                                                    );

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
                    AND c.is_active = 1
                    AND l.is_active = 1
                    AND i.is_active = 1
            ")[0]->countOverdue;

            // update customer collect
            if ($checkCollectCustomer > 0 && $checkCollectCustomer < 5)
            {
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
                                                    
            $incoming = DB::table('ms_incomings')->whereIn('loan_id', $loanIds)
                                                ->where('is_active', 1)
                                                ->get();

            return view('loan/loan', [
                'customer' => $customer,
                'loanList' => $loanList,
                'incomings' => $incoming
            ]);
        }
        else{
            $incoming = $loanList;
            return view('loan/loan', [
                'customer' => $customer,
                'loanList' => $loanList,
                'incomings' => $incoming
            ]);
        }
    }

    function addLoan(Request $request)
    {
        $rules = [
            'custId'                => 'required',
            'loanAmount'            => 'required',
            'interestRate'          => 'required',
            'tenor'                 => 'required',
            'installmentAmount'     => 'required',
            'provisionFee'          => 'required',
            'disbursementAmount'    => 'required',
            'collateralFiles'       => 'mimetypes:image/jpeg,application/pdf,image/png'
        ];
        // dd($request->custId);

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
            if ($results[0]->loan_number == null)
            {
                $loanNumber = 'L00001';
            }
            else{
                // $results[0]->max_id
                $loanNumber = 'L' . str_pad((int)Str::substr($results[0]->loan_number,1)+1, 5, '0', STR_PAD_LEFT);
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
                $tenor = $request->tenor;
                $date = date('Y-m-d');
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
                return redirect()->route('customer')->with(['error' => 'File must be png, pdf or jpeg']); 
            }

            $fileFolder = 'collateral_file';
            $fileName = 'Collateral-' . '-' . date('Y-m-d') . '.' . $extendsion;
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

        if ($result)
        {
            DB::commit();
            return redirect()->route('loan',[$request->custId])->with(['success' => 'Data Berhasil Disimpan!']);
        }
        else
        {
            DB::rollback();
            return redirect()->route('loan',[$request->custId])->with(['errors' => 'Input Data Failed!!']);
        }
    }

    function payLoan(Request $request)
    {
        $row = json_decode($request->input('data'));
        $outstanding = 0;

        for ($i = 0; $i < sizeof($row); $i++)
        {
            $payData = json_decode($row[$i]);
            // dd($payData);

            $installmentAmount = DB::select("
                SELECT
                    l.installment_amount,
                    CASE
                        WHEN i.incoming_amount > 0 THEN (l.installment_amount - i.incoming_amount)
                        ELSE l.installment_amount
                    END as outstanding
                FROM
                    ms_loans l,
                    ms_incomings i
                WHERE
                    l.loan_id = '$request->loanId'
                    AND l.customer_id = '$request->customerId'
                    AND l.is_active = 1
                    AND i.loan_id = l.loan_id
                    AND i.incoming_id = '$payData->incomingId'
                    AND i.is_active = 1
                    AND i.loan_status NOT IN ('Paid')
            ");

            $outstanding = $payData->amount - $installmentAmount[0]->outstanding;
            if($outstanding < 0)
            {
                // partial pay
                DB::statement("
                    update ms_incomings
                    set
                        incoming_amount = incoming_amount + '$payData->amount'
                        , incoming_date = NOW()
                        , update_at = NOW()
                        , loan_status = 'Partial Pay'
                    where
                        incoming_id = '$payData->incomingId'
                        AND loan_id = '$request->loanId'
                        AND is_active = 1
                ");
            }
            else if ($outstanding == 0)
            {
                // paid
                DB::statement("
                    update ms_incomings
                    set
                        incoming_amount = incoming_amount + '$payData->amount'
                        , incoming_date = NOW()
                        , update_at = NOW()
                        , loan_status = 'Paid'
                    where
                        incoming_id = '$payData->incomingId'
                        AND loan_id = '$request->loanId'
                        AND is_active = 1
                ");
            }
            else if ($outstanding  > 0)
            {
                DB::statement("
                    update ms_incomings
                    set
                        incoming_amount = incoming_amount + '" . $installmentAmount[0]->outstanding . "'
                        , incoming_date = NOW()
                        , update_at = NOW()
                        , loan_status = 'Paid'
                    where
                        incoming_id = '$payData->incomingId'
                        AND loan_id = '$request->loanId'
                        AND is_active = 1
                ");

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
                        AND incoming_id NOT IN ('$payData->incomingId')
                        AND is_active = 1
                    ORDER BY
                        loan_due_date ASC
                ");

                $countIncoming = sizeof($incoming);

                for ($j = 0; $j < sizeof($incoming); $j++)
                {
                    $status = '';
                    $pay = 0;
                    $incomingId = '';
                    // dd($outstanding);
                    
                    if ($outstanding < 0)
                    {
                        break;
                    }
                    else if ($outstanding - $installmentAmount[0]->installment_amount > 0)
                    {
                        if ($j == ($countIncoming-1))
                        {  
                            $pay = $outstanding;
                            $status = 'Paid';
                        }
                        else{
                            $pay = $installmentAmount[0]->installment_amount;
                            $status = 'Paid';
                        }
                    }
                    else if ($outstanding == $installmentAmount[0]->installment_amount)
                    {
                        $status = 'Paid';
                        $pay = $outstanding;
                    }
                    else if ($outstanding < $installmentAmount[0]->installment_amount)
                    {
                        if ($incoming[$j]->loan_status == 'Haven\'t due yet' || $incoming[$j]->loan_status == 'Due')
                        {
                            $status = 'Partial Pay';
                            $pay = $outstanding;
                        }
                        else if ($incoming[$j]->loan_status == 'Overdue')
                        {
                            $status = 'Overdue';
                            $pay = $outstanding;
                        }
                    }

                    $outstanding = $outstanding - $installmentAmount[0]->installment_amount;

                    if ($status != '' && $pay != 0)
                    {
                        $incomingId = $incoming[$j]->incoming_id;
                        
                        DB::statement("
                            update ms_incomings
                            set
                                incoming_amount = incoming_amount + '$pay'
                                , incoming_date = NOW()
                                , update_at = NOW()
                                , loan_status = '$status'
                            where
                                incoming_id = '$incomingId'
                                AND loan_id = '$request->loanId'
                                AND is_active = 1
                        ");
                    }
                }
            }

        }

        DB::commit();
        return response()->json([
            'errNum' => 0,
            'errStr' => 'Payment Success',
            'redirect' => 'loan',
            'custId' => $request->customerId
        ]);
    }

    function searchByLoanId(Request $request)
    {
        $customer = DB::table('customers')->where('customer_id', $request->custId)
                                        ->where('is_active', 1)
                                        ->first();

        $loanList = DB::table('ms_loans')->where('loan_id', $request->search)
                                        ->where('is_active', 1)
                                        ->paginate(10);

        $incoming = DB::table('ms_incomings')->where('loan_id', $request->search)
                                        ->where('is_active', 1)
                                        ->get();

        return view('loan/loan', [
            'customer' => $customer,
            'loanList' => $loanList,
            'incomings' => $incoming
        ]);
    }
}
