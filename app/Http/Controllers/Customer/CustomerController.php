<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use DB;
use Illuminate\Support\Str;
use Auth;

class CustomerController extends Controller
{
    //
    public function __construct(Request $request)
    {
        // dd(Auth::check() );
    }

    function index(Request $request)
    {
        $paginate = 10;
        if ($request->paginate)
        {
            $paginate = $request->paginate;
        }
        $customer = DB::table('customers')->where('is_active', 1)->orderBy('customer_id', 'asc')->paginate($paginate);
        // dd($customer);
        // print_r($customer);
        $agentList = DB::table('customers')
                        ->select('customer_agent')
                        ->distinct()
                        ->where('is_active', 1)
                        ->get(['customer_agent']);

        $jobList = DB::table('customers')
                        ->select('customer_proffesion')
                        ->distinct()
                        ->where('is_active', 1)
                        ->get(['customer_proffesion']);
        
        $collateralList = DB::table('ms_loans')
                        ->select('collateral_category')
                        ->distinct()
                        ->where('is_active', 1)
                        ->get(['collateral_category']);

        return view('customer/customer', [
            'customer' => $customer,
            'agentList' => $agentList,
            'jobList' => $jobList,
            'collateralList' => $collateralList,
            'paginate' => $paginate
        ]);
    }

    function addCustomer(Request $request)
    {
        $rules = [
            'customerName'          => 'required',
            'customerKtp'           => 'required',
            'customerJob'           => 'required',
            'customerAddress'       => 'required',
            'customerAgent'         => 'required',
            'loanAmount'            => 'required',
            'interestRate'          => 'required',
            'tenor'                 => 'required',
            'installmentAmount'     => 'required',
            'provisionFee'          => 'required',
            'disbursementAmount'    => 'required',
            'collateralFiles'       => 'mimetypes:image/jpeg,application/pdf,image/png'
        ];

        if ($this->validate($request, $rules))
        {
            $firstStringCharacter = strtoupper(substr($request->customerName, 0, 1));
            
            $results = DB::select("
                SELECT 
                    max(customer_id) as max_id
                FROM 
                    customers 
                WHERE 
                    customer_id like '". $firstStringCharacter . "%'
                    and is_active = 1    
                "
            );
            // dd($results);
            
            if ($results[0]->max_id == null)
            {
                $custId = $firstStringCharacter . '00001';
            }
            else{
                // $results[0]->max_id
                $custId = $firstStringCharacter . str_pad((int)Str::substr($results[0]->max_id,1)+1, 5, '0', STR_PAD_LEFT);
            }
            
            DB::beginTransaction();
            
            $customer = DB::table('customers')->insert(
                [
                    'customer_id' => strtoupper($custId),
                    'customer_name' => $request->customerName,
                    'customer_id_number' => $request->customerKtp,
                    'customer_address' => $request->customerAddress,
                    'customer_proffesion' => $request->customerJob,
                    'customer_phone' => $request->customerPhone,
                    'customer_agent' => $request->customerAgent,
                    'customer_collect' => 1,
                    'is_active' => 1,
                    'create_by' => 3,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_by' => 3,
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );
            
            if ($customer)
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
                        customer_id = '$custId'
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
                        'customer_id' => $custId,
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
                        'outgoing_date'     => date('Y-m-d H:i:s'),
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
                    // loan status
                    // 1 : Haven't due yet
                    // 2 : Due 
                    // 3 : Paid
                    // 4 : Overdue
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
                            return redirect()->route('customer')->with(['error' => 'Input Data Failed!!']); 
                            break;
                        }
                    };

                    DB::commit();
                    return redirect()->route('customer')->with(['success' => 'Data Berhasil Disimpan!']);
                }
                else
                {
                    DB::rollback();
                    return redirect()->route('customer')->with(['error' => 'Input Data Failed!!']); 
                }
            }
            else
            {
                DB::rollback();
                return redirect()->route('customer')->with(['errors' => 'Input Data Failed!!']);
            }
        }
    }

    function getEditData(Request $request)
    {
        $results = DB::select("
            SELECT 
                customer_id,
                customer_name,
                customer_id_number,
                customer_address,
                customer_proffesion,
                customer_phone,
                customer_agent
            FROM 
                customers 
            WHERE 
                customer_id like '". $request->id . "%'
                AND is_active = 1
            "    
        );

        return response()->json(['result' => $results]);
    }

    function editCustomer(Request $request)
    {
        $rules = [
            'editCustomerName'          => 'required',
            'editCustomerKtp'           => 'required',
            'editCustomerJob'           => 'required',
            'editCustomerAddress'       => 'required',
            'editCustomerAgent'         => 'required'
        ];

        if ($this->validate($request, $rules))
        {
            $customer = Customer::where('customer_id',$request->editCustomerId)->take(1)->get();

            $result = Customer::where('customer_id',$request->editCustomerId)
                                ->where('is_active', 1)
                                ->update(['customer_name' =>$request->editCustomerName,
                                        'customer_id_number'=>$request->editCustomerKtp,
                                        'customer_address'=>$request->editCustomerAddress,
                                        'customer_proffesion'=>$request->editCustomerJob,
                                        'customer_phone'=>$request->editCustomerPhone,
                                        'customer_agent'=>$request->editCustomerAgent]);

            if ($result)
            {
                DB::commit();
                return redirect()->route('customer')->with(['success' => 'Data Berhasil Disimpan!']);
            }
            else
            {
                DB::rollback();
                return redirect()->route('customer')->with(['errors' => 'Input Data Failed!!']);
            }
        }
    }

    function searchCustomer(Request $request)
    {
        $customer = DB::table('customers')->where('customer_id', 'LIKE', '%'.$request->search.'%')
                                        ->orWhere('customer_name', 'LIKE', '%'.$request->search.'%')
                                        ->orWhere('customer_id_number', 'LIKE', '%'.$request->search.'%')
                                        ->where('is_active', 1)
                                        ->paginate(10);


        $agentList = DB::table('customers')
                        ->select('customer_agent')
                        ->distinct()
                        ->where('is_active', 1)
                        ->get(['customer_agent']);

        $jobList = DB::table('customers')
                        ->select('customer_proffesion')
                        ->distinct()
                        ->where('is_active', 1)
                        ->get(['customer_proffesion']);

        $collateralList = DB::table('ms_loans')
                        ->select('collateral_category')
                        ->distinct()
                        ->where('is_active', 1)
                        ->get(['collateral_category']);

        return view('customer/customer', [
            'customer' => $customer,
            'agentList' => $agentList,
            'jobList' => $jobList,
            'collateralList' => $collateralList,
            'paginate' => 10
        ]);
    }
}
