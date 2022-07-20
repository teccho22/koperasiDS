@extends('layouts.mainLayout')
 
@section('title', 'Transaction Management')
 
@section('sidebar')
@stop

@section('content')
<div class="container">
    @if (count($errors) > 0)
        <script>
            var errors = {!! json_encode($errors->toArray()) !!};
            swal({
                title: "Alert",
                text: 'Add Customer Failed. Please check your data!!',
                icon: "error",
                type: "error",
                buttons: {
                    confirm: true
                }
            });
        </script>
    @endif

    @if(session()->has('message'))
        <script>
            swal({
                title: "Alert",
                text: '{{ session()->get("message") }}',
                icon: "error",
                type: "error",
                buttons: {
                    confirm: true
                }
            });
        </script>
    @endif

    <div class="modal fade" id="modalTransaction" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle" style="color: #0877DE !important;">Add Transaction</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <form method="post" action="{{ url('/addTransaction') }}" style="font-family: 'Roboto', cursive; color:black; font-size: 20px" id="addTransactionForm" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Transaction Date</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="inputTransactionDate"  name="transactionDate" onkeypress="return dateKeypress(event)" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Category</span>
                                </div>
                                <div class="col-sm-8">
                                    <select class="form-control selectpicker" id="category" name="category" data-live-search="true">
                                        <option value="Cash2Bank">Cash to Bank</option>
                                        <option value="Bank2Cash">Bank to Cash</option>
                                        <option value="BankInterest">Bank Interest</option>
                                        <option value="BankTax">Bank Tax</option>
                                        <option value="BankTrf">Bank Transfer</option>
                                        <option value="Cash">Input Cash</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Amount</span>
                                </div>
                                <div class="col-sm-8 required">
                                    <input type="text" id="amount" name="amount" class="form-control" placeholder="Rp2.390.000" onkeypress="decimalKeypress(event)"/> 
                                </div>
                            </div>
                        </form>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalTransaction')">
                       <i class="fa fa-close"></i> Cancel
                   </button>
                    <button type="button" class="btn btn-primary" onclick="validate(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditTransaction" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle" style="color: #0877DE !important;">Edit Transaction</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <form method="post" action="{{ url('/editTransaction') }}" style="font-family: 'Roboto', cursive; color:black; font-size: 20px" id="modalEditTransaction" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="transactionId" id="editTransactionId" class="form-control" value="" hidden/>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Transaction Date</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="editTransactionDate"  name="transactionDate" onkeypress="return dateKeypress(event)" placeholder="dd-mm-yyyy" pattern="\d{2}-\d{2}-\d{4}">
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Category</span>
                                </div>
                                <div class="col-sm-8">
                                    <select class="form-control selectpicker" id="editCategory" name="category" data-size="5" data-live-search="true">
                                        <option value="Cash2Bank">Cash to Bank</option>
                                        <option value="Bank2Cash">Bank to Cash</option>
                                        <option value="InterestTax">Bank Interest and Tax</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Amount</span>
                                </div>
                                <div class="col-sm-8 required">
                                    <input type="text" id="editAmount" name="amount" class="form-control" placeholder="Rp2.390.000" onkeypress="return numericKeypress(event)"/> 
                                </div>
                            </div>
                        </form>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalEditTransaction')">
                       <i class="fa fa-close"></i> Cancel
                   </button>
                    <button type="button" class="btn btn-primary" onclick="validateEdit(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="divTransaction">
        <div class="toolbar Row">
            <form method="post" action="{{ url('/searchTransaction') }}" style="font-family: 'Roboto', cursive; color:black" class="form-inline">
                {{ csrf_field() }}
                <div class="Column">
                    <input type="text" name="search" class="form-control" placeholder="Id/Date/Category"/>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
                <div class="Column">
                    <span for="" class="control-label" style="margin-right: 10px;">Show Result</span>
                    <select class="form-control selectpicker" id="pagination" name="pagination" data-live-search="true" style="margin-right: auto;" onchange="showPagination()">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="Column">
                    <button id="addCustomer" type="button" class="btn btn-primary mr-2" title="Add" onclick="showAddTransactionModal()"><i class="fa fa-plus"></i> Add Transaction</button>
                    {{-- <button id="addLoan" type="button" class="btn btn-primary" title="Add" onclick="showAddLoanModal()"><i class="fa fa-plus"></i> Add Loan</button> --}}
                </div>
            </form>
        </div>
        <table class="table table-responsive">
            <thead style="background-color: #0877DE !important; color:#FFFFFF !important; letter-spacing: 1px;">
                <tr>
                    <th>No.</th>
                    <th>Transaction Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Cash Account</th>
                    <th>Bank Account</th>
                    {{-- <th>Action</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction as $data)
                <tr>
                    <td>{{ ($transaction->currentPage()-1) * $transaction->perPage() + $loop->index + 1}}</td>
                    <td>
                        {{ date("d-M-Y", strtotime($data->transaction_date))}}
                    </td>
                    <td>
                        @if ($data->trx_category == 'Cash2Bank')
                            Cash to Bank
                        @elseif ($data->trx_category == 'Bank2Cash')
                            Bank to Cash
                        @elseif ($data->trx_category == 'BankTrf')
                            Bank Transfer
                        @elseif ($data->trx_category == 'BankTax')
                            Bank Tax
                        @elseif ($data->trx_category == 'BankInterest')
                            Bank Interest
                        @else
                            {{ $data->trx_category }}
                        @endif
                    </td>
                    <td>Rp{{ number_format($data->trx_amount, 2, ',', '.')}}</td>
                    <td>Rp{{ number_format($data->cash_account, 2, ',', '.')}}</td>
                    <td>Rp{{ number_format($data->bank_account, 2, ',', '.')}}</td>
                    {{-- <td>
                        @if ($data->trx_category != 'New Loan')
                            <button type="button" class="btn btn-primary" title="Edit" onclick="showEditTransactionModal('{{ $data->id}}')"><i class="fa fa-pen"></i></button>
                            <button type="button" class="btn btn-danger" title="Edit" onclick="validateDelete('{{ $data->id}}')"><i class="fa fa-trash"></i></button>
                        @endif
                    </td> --}}
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="font-size: 15px">Showing {{($transaction->currentpage()-1)*$transaction->perpage()+1}} to {{ $transaction->currentpage()*(($transaction->perpage() < $transaction->total()) ? $transaction->perpage(): $transaction->total())}} of {{ $transaction->total()}} entries</p>
        <div class="contentSeg" style="text-align: center;">
            {{ $transaction->links() }}
        </div>
    </div>
</div>
@stop

@section('javascript')
    <script>
        var transaction = {!! json_encode($transaction->toArray()) !!}.data;
        // console.log(transaction);
        $('#pagination').val({!! json_encode($paginate) !!});
        function showPagination()
        {
            window.location = "{{ url('/transaction') }}?paginate=" + $('#pagination').val();
        }

        $(function()
        {
            
        });

        function showAddTransactionModal()
        {
            $('#modalTransaction').modal('show');
        }

        function showEditTransactionModal(id)
        {
            var value = $.grep(transaction, function(v) {
                return v.id == id;
            });

            $('#modalEditTransaction').modal('show');
        }

        function validate(e)
        {
            $.confirm({
                title: 'Please Confirm',
                content: 'Are you sure you want to save this data?',
                buttons: {   
                    ok: {
                        btnClass: 'btn-primary',
                        action: function(){
                            $('#addTransactionForm').submit();
                            return true;
                        }
                    },
                    cancel: function(){
                        // return false;
                    }
                }
            });
        }

        function validateEdit(e)
        {
            $.confirm({
                title: 'Please Confirm',
                content: 'Are you sure you want to save this data?',
                buttons: {   
                    ok: {
                        btnClass: 'btn-primary',
                        action: function(){
                            $('#modalEditTransaction').submit();
                            return true;
                        }
                    },
                    cancel: function(){
                        // return false;
                    }
                }
            });
        }

        function validateDelete(id)
        {
            $.confirm({
                title: 'Please Confirm',
                content: 'Are you sure you want to delete this data?',
                buttons: {   
                    ok: {
                        btnClass: 'btn-primary',
                        action: function(){
                            $.ajax
                            ({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                dataType : 'json',
                                type: 'POST',
                                url: "{{ url('/deleteOutgoing') }}",
                                data: {
                                    transactionId : id
                                },
                                success : function(result)
                                {
                                    if (result.errNum == 0)
                                    {
                                        $.confirm ({
                                            title: 'Alert',
                                            content: 'Delete transaction success',
                                            buttons: { 
                                                ok: {
                                                    btnClass: 'btn-primary',
                                                    action: function(){
                                                        var url = '/' + result.redirect;
                                                        window.location = url;
                                                    }
                                                }  
                                            }
                                        })
                                    }
                                    else
                                    {
                                        $.confirm ({
                                            title: 'Alert',
                                            content: 'Delete transaction Failed',
                                            buttons: { 
                                                ok: {
                                                    btnClass: 'btn-primary',
                                                    action: function(){
                                                        
                                                    }
                                                }  
                                            }
                                        })
                                    }
                                }
                            });
                        }
                    },
                    cancel: function(){
                        // return false;
                    }
                }
            });
        }

        function confirmCancel(modalId)
        {
            // $('#addCustomerForm')[0].reset();

            $.confirm({
                title: 'Please Confirm',
                content: 'Are you sure you want to leave and discard changes?',
                buttons: {   
                    ok: {
                        btnClass: 'btn-primary',
                        action: function(){
                            $('#' + modalId)[0].reset();
                            $('#' + modalId).modal('hide');
                        }
                    },
                    cancel: function(){
                    }
                }
            });
        }
    </script>
@stop

<style>
    #divTransaction {
        font-family: 'Roboto', cursive;
        font-size: 18px;
    }
</style>