@extends('layouts.mainLayout')
 
@section('title', 'Loan')
 
@section('sidebar')
@stop

@section('content')
    {{-- breadcrump --}}
    @if (count($errors) > 0)
        <script>
            var text = "<ul>";
            var errors = {!! json_encode($errors->toArray()) !!};
            console.log(errors);
                                    
            $.each(errors, function(key, value){
                for (var i=0; i<value.length; i++)
                {
                    text += "<li>" + value[i] + "</li>";
                }
            });
            text += "</ul>";

            $.confirm({
                title: 'Add / Edit Loan Failed',
                content: text,
                type: 'red',
                typeAnimated: true,
                icon: 'fa fa-warning',
                buttons: {
                    close: function () {
                    }
                }
            });
        </script>
    @endif

    @if (!empty($success))
        <script>

            $.confirm({
                title: 'Add / Edit Loan Failed',
                content: 'Data Berhasil Disimpan!',
                type: 'green',
                typeAnimated: true,
                icon: 'fa fa-success',
                buttons: {
                    close: function () {
                    }
                }
            });
        </script>
    @endif

    {{-- modal --}}
    <div class="modal fade" id="modalLoan" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle" style="color: #0877DE !important;">Add Loan</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <form method="post" action="{{ url('/addLoan') }}" style="font-family: 'Roboto', cursive; color:black; font-size: 20px" id="addCustomerForm" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="custId" id="editCustomerId" class="form-control" value="{{ $customer->customer_id }}" hidden/>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Loan Date</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="inputLoanDate"  name="loanDate" onkeypress="return dateKeypress(event)" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Loan Amount</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="loanAmount" name="loanAmount" class="form-control" placeholder="Rp 10.000.000,00" onkeypress="decimalKeypress(event)" onkeydown="calculateLoan(event)"/>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Interest Rate</span>
                                </div>
                                <div class="col-sm-8 required">
                                    <input type="text" id="interestRate" name="interestRate" class="form-control" placeholder="2.5%" value="2.5" onkeypress="decimalKeypress(event)" onkeydown="calculateInterest(event)"/> 
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Tenor</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="tenor" name="tenor" class="form-control" placeholder="6" onkeypress="numericKeypress(event)" onkeydown="calculateTenor(event)"/>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Installment Amount</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="installmentAmount" name="installmentAmount" class="form-control" placeholder="Rp 10.000.000,00" onkeypress="decimalKeypress(event)" disabled/>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Provision Fee</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="provisionFee" name="provisionFee" class="form-control" placeholder="Rp 10.000.000,00" onkeypress="decimalKeypress(event)" disabled/>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Disbursement Amount</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="disbursementAmount" name="disbursementAmount" class="form-control" placeholder="Rp 10.000.000,00" onkeypress="decimalKeypress(event)" disabled/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Collateral Category</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="collateralCategory" class="form-control" placeholder="Collateral Category" list="collateralList"/>
                                    <datalist id="collateralList">
                                        @foreach ($collateralList as $collateral)
                                        <option>{{ $collateral->collateral_category }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Collateral File</span>
                                </div>
                                <div class="col-sm-8 upload-file">
                                    <i class="fa fa-camera file" style="margin-left: 0px"></i><span class="name">No file selected</span>
                                    <input type="file" name="collateralFiles" id="collateralFile" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Collateral Description</span>
                                </div>
                                <div class="col-sm-8">
                                    <textarea name="collateralDescription" class="form-control" placeholder="Description" rows="3"></textarea>
                                </div>
                            </div>
                        </form>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalLoan')">
                       <i class="fa fa-close"></i> Cancel
                   </button>
                    <button type="button" class="btn btn-primary" onclick="validate(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditLoan" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle" style="color: #0877DE !important;">Edit Loan</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <form method="post" action="{{ url('/editLoan') }}" style="font-family: 'Roboto', cursive; color:black; font-size: 20px" id="editLoanForm" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="custId" id="editCustomerId" class="form-control" value="{{ $customer->customer_id }}" hidden/>
                            <input type="hidden" name="loanId" id="editLoanId" class="form-control" value="" hidden/>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Loan Date</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="editLoanDate"  name="editLoanDate" onkeypress="return dateKeypress(event)" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Collateral Category</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="editCollateralCategory" id="editCollateralCategory" class="form-control" placeholder="Collateral Category" list="collateralList"/>
                                    <datalist id="collateralList">
                                        
                                    </datalist>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Collateral File</span>
                                </div>
                                <div class="col-sm-8 upload-file">
                                    <i class="fa fa-camera file" style="margin-left: 0px"></i><span class="name">No file selected</span>
                                    <input type="file" name="editCollateralFiles" id="editCollateralFiles" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Collateral Description</span>
                                </div>
                                <div class="col-sm-8">
                                    <textarea name="editCollateralDescription" id="editCollateralDescription" class="form-control" placeholder="Description" rows="3"></textarea>
                                </div>
                            </div>
                        </form>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalEditLoan')">
                       <i class="fa fa-close"></i> Cancel
                   </button>
                    <button type="button" class="btn btn-primary" onclick="validateEdit(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalPayLoan" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle" style="color: #0877DE !important;">Payment</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <input type="hidden" name="custId" id="payCustomerId" class="form-control" value="{{ $customer->customer_id }}" hidden/>
                        <input type="hidden" name="loanId" id="payLoanId" class="form-control" value="" hidden/>
                        <div class="form-horizontal">
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label" style="font-size: 18px">Payment Date</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="date" class="form-control" id="inputPaymentDate"  name="paymentDate" onkeypress="return dateKeypress(event)" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                            <div class="form-group row payData">
                                <div class="col-sm-4">
                                    <span for="" class="control-label" style="font-size: 18px">Pay Amount</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control amount-to-pay" id="payAmount" placeholder="amount" onkeypress="return decimalWithMinusKeypress(event)">
                                </div>
                            </div>
                        </div>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalPayLoan')">
                       <i class="fa fa-close"></i> Cancel
                   </button>
                    <button type="button" class="btn btn-primary" onclick="validatePay(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- customer detail --}}
    <div id="custDetail" class="container custDetail">
        <div class="row breadcrumbs" style="font-family: 'Righteous', cursive;">
            <a href="{{ route('customer') }}"><img src="{{ URL::asset('assets/images/back.png') }}"> <b>Customer/ Loan</b></a>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    <span class="col-sm-6">ID</span>
                    <span class="col-sm-6" style="color:#959797;" id="isBlacklist">: {{ $customer->customer_id }}</span>
                </div>
                <div class="row">
                    <span class="col-sm-6">Name</span>
                    <span class="col-sm-6" style="color:#959797;">: {{ $customer->customer_name }}</span>
                </div>
                <div class="row">
                    <span class="col-sm-6">Address</span>
                    <span class="col-sm-6" style="color:#959797;">: {{ $customer->customer_address }}</span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <span class="col-sm-6">KTP No</span>
                    <span class="col-sm-6" style="color:#959797;">: {{ $customer->customer_id_number }}</span>
                </div>
                <div class="row">
                    <span class="col-sm-6">Agent</span>
                    <span class="col-sm-6" style="color:#959797;">: {{ $customer->customer_agent }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-bottom: 25px">
        <button id="btnBlacklist" type="button" class="btn btn-danger mr-4" title="Add" style="float: right; margin: 5px;width:100px" onclick="blacklist('{{ $customer->customer_id }}')">Blacklist</button>
        <button id="btnUnblacklist" type="button" class="btn btn-danger mr-4" title="Add" style="float: right; margin: 5px;width:100px" onclick="unblacklist('{{ $customer->customer_id }}')">Unblacklist</button>
        <button id="btnPrint" type="button" class="btn btn-primary mr-4" title="Add" style="float: right; margin: 5px;width:100px">Print</button>
    </div>
    <div class="container custDetail">
        <div class="row">
            <div class="col-sm-4">
                <form method="post" action="{{ url('/searchByLoanId') }}" style="font-family: 'Roboto', cursive; color:black" class="form-inline">
                    {{ csrf_field() }}
                    <input type="hidden" name="custId" class="form-control" value="{{ $customer->customer_id }}" hidden/>
                    <input type="text" name="search" class="form-control" placeholder="search by loan id"/>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Search
                    </button>
                </form>
            </div>
            <div class="col-sm-4" class="form-inline" style="display: flex; align-content: center; justify-content: center; flex-wrap: wrap; flex-direction: row; align-items: center;">
                <span for="" class="control-label" style="margin-right: 10px;">Show Result</span>
                <select class="form-control selectpicker" id="pagination" class="pagination" name="pagination" data-live-search="true" style="" onchange="showPagination()">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-sm-4">
                <button id="addLoan" type="button" class="btn btn-primary mr-2" title="Add" style="float: right; width: 100px" onclick="showAddLoanModal();"><i class="fa fa-plus"></i> Add Loan</button>
            </div>
        </div>
    </div>
    <div class="container">
        <table class="table table-responsive" style="border-radius: 11px;">
            <thead style="background-color: #0877DE !important; color:#FFFFFF !important; letter-spacing: 1px;">
                <tr>
                    <th></th>
                    <th>Loan No</th>
                    <th>Loan Date</th>
                    <th>Loan Amount</th>
                    <th>Installment</th>
                    <th>Tenor</th>
                    <th>Collateral Description</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loanList as $data)
                <tr id="loan{{ $loop->index }}" class="inactive"
                    onclick="showHideRow('{{ $loop->index }}');"
                >
                    <td id="icon{{ $loop->index }}"><i class="fa fa-angle-down"></i></td>
                    <td>{{ $data->loan_number}}</td>
                    <td>{{ date("d/m/Y", strtotime($data->loan_date))}}</td>
                    <td>{{ number_format($data->loan_amount, 2, ',', '.')}}</td>
                    <td>{{ number_format($data->installment_amount, 2, ',', '.')}}</td>
                    <td>{{ $data->tenor}}</td>
                    <td style="word-wrap: break-word; max-width: 250px; min-width: 250px;">
                        @if($data->collateral_description != null)
                            {{ $data->collateral_description}}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <button id="payInstallment" type="button" class="btn btn-primary" title="Pay" onclick="showPayLoanModal('{{ $data->loan_id }}', '{{ $data->loan_number}}');">Pay</button>
                        <button id="editLoan" type="button" class="btn btn-primary" title="Edit" onclick="showEditLoanModal({{ $data->loan_id }});"><i class="fa fa-pen"></i> Edit</button>
                    </td>
                </tr>
                <tr id="hiddenLoan{{ $loop->index }}" class="hidden_row">
                    <td colspan=8>
                        <p style="color:#0877DE">Payment Schedule</p>
                        <table class="table table-responsive" style="border-radius: 11px; width: 80%; margin-left:15px;">
                            <thead style="background-color: #0877DE !important; color:#FFFFFF !important; letter-spacing: 1px;">
                                <tr>
                                    <th>Installment No</th>
                                    <th>Installment Date</th>
                                    <th>Amount</th>
                                    <th>Outstanding</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($incomings != null)
                                    @php
                                        $count = 1;
                                    @endphp
                                    @foreach ($incomings as $installment)
                                        @if ($installment->loan_id == $data->loan_id)
                                            <tr>
                                                <td>{{ $data->loan_number}}-{{ $count }}</td>
                                                <td>{{ date("d/m/Y", strtotime($installment->loan_due_date))}}</td>
                                                <td>{{ number_format($data->installment_amount, 2, ',', '.')}}</td>
                                                <td>{{ number_format(($installment->incoming_amount - $data->installment_amount) * -1, 2, ',', '.')}}</td>
                                                <td>{{ $installment->loan_status}}</td>
                                            </tr>
                                            @php
                                                $count++;
                                            @endphp
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="font-size: 15px">Showing {{($loanList->currentpage()-1)*$loanList->perpage()+1}} to {{ $loanList->currentpage()*(($loanList->perpage() < $loanList->total()) ? $loanList->perpage(): $loanList->total())}} of {{ $loanList->total()}} entries</p>
        <div class="contentSeg" style="text-align: center;">
            {{ $loanList->links() }}
        </div>
    </div>
@stop

@section('javascript')
    <script>
        var loanList = {!! json_encode($loanList->toArray()) !!}.data;
        var incomingList = {!! json_encode($incomings->toArray()) !!};
        var index = 1;
        var customer = {!! json_encode($customer) !!};
        // console.log({!! json_encode($incomings->toArray()) !!});
        $('#pagination').val({!! json_encode($paginate) !!});
        function showPagination()
        {
            console.log(customer);
            console.log("{{ url('/loan/') }}"+customer.customer_id+"?paginate=" + $('#pagination').val());
            window.location = "{{ url('/loan/') }}/"+customer.customer_id+"?paginate=" + $('#pagination').val();
        }

        $(function()
        {
            console.log(customer);
            if(customer.is_blacklist == 1)
            {
                $('#addLoan').hide();
                $('#isBlacklist').append(' <span style="color:red;border: 1px solid red; border-radius: 20px;background-color:#ffcaca;padding:5px;">BLACKLIST</span>');

                $('#btnBlacklist').hide();
                $('#btnUnblacklist').show();
            }
            else
            {
                $('#btnBlacklist').show();
                $('#btnUnblacklist').hide();
            }
        });

        $("i.upload-file").click(function () {
            $("input[type='file']").trigger('click');
        });

        $('input[type="file"]').on('change', function() {
            var val = $(this).val();
            $(this).siblings('span').text(val);
        })

        function showAddLoanModal()
        {
            $('#modalLoan').modal('show');
        }

        function showEditLoanModal(id)
        {
            // assign value
            var loan = $.grep(loanList, function(v) {
                return v.loan_id == id;
            });
            console.log(loan);

            $('#editLoanId').val(id);
            $('#editCollateralCategory').val(loan[0]['collateral_category']);
            $('#editCollateralFiles').val(loan[0]['collateral_file_path']);
            $('#editCollateralDescription').val(loan[0]['collateral_description']);
            
            $('#modalEditLoan').modal('show');
        }

        function showPayLoanModal(id, loanNumber)
        {
            $('#payLoanId').val(id);

            var incoming = $.grep(incomingList, function(v) {
                return v.loan_id == id  && v.loan_status != 'Paid';
                // && v.loan_status != 'Haven\'t due yet' && v.loan_status != 'Paid'
            });

            if (incoming.length > 0)
            {
                var loan = $.grep(loanList, function(v) {
                    return v.loan_id == id;
                });
                
                var outstanding = loan[0]['installment_amount'] - incoming[0]['incoming_amount']
    
                $('#payAmount').val(outstanding.toFixed(2));
    
                $('#modalPayLoan').modal('show');
            }
            else
            {
                $.confirm({
                    title: 'Alert',
                    content: 'Loan Already Paid',
                    type: 'red',
                    typeAnimated: true,
                    icon: 'fa fa-warning',
                    buttons: {
                        close: function () {
                        }
                    }
                });
            }
        }

        function calculateLoan(event)
        {
            var loan = 0;
            var tenor = 0;
            if (event.key != 'Backspace')
            {
                loan = parseInt($('#loanAmount').val() + event.key);
            }
            else
            {
                loan = parseInt($('#loanAmount').val().substring(0, $('#loanAmount').val().length - 1));
            }

            // provisionFee default 3%
            var provisionFee = (3/100) * loan;
            if (isNaN(provisionFee))
            {
                $('#provisionFee').val(0);
            }
            else
            {
                $('#provisionFee').val(provisionFee);
            }

            var disbursement = loan - provisionFee;
            if (isNaN(disbursement))
            {
                $('#disbursementAmount').val(0);
            }
            else
            {
                $('#disbursementAmount').val(disbursement);
            }

            // interest default 2,5%
            var interestRate = parseFloat($('#interestRate').val());
            var installmentAmount = 0;
            if ($('#tenor').val() != '')
            {
                tenor = parseInt($('#tenor').val());
                installmentAmount = loan * (1 + ((interestRate/100 * tenor)))/tenor;
                
                $('#installmentAmount').val(installmentAmount.toFixed(2));
            }

        }

        function calculateInterest(event)
        {
            var interestRate = 0;
            if (event.key != 'Backspace')
            {
                interestRate = parseInt($('#interestRate').val() + event.key);
            }
            else
            {
                interestRate = parseInt($('#interestRate').val().substring(0, $('#interestRate').val().length - 1));
            }

            var installmentAmount = 0;
            var loan = parseInt($('#loanAmount').val());
            if ($('#tenor').val() != '' && $('#loanAmount').val() != '')
            {
                tenor = $('#tenor').val();
                installmentAmount = loan * (1 + ((interestRate/100 * tenor)))/tenor;

                $('#installmentAmount').val(installmentAmount.toFixed(2));
            }
        }

        function calculateTenor(event)
        {
            var tenor = 0;
            if (event.key != 'Backspace')
            {
                tenor = parseInt($('#tenor').val() + event.key);
            }
            else
            {
                tenor = parseInt($('#tenor').val().substring(0, $('#tenor').val().length - 1));
            }
            
            var interestRate = parseFloat($('#interestRate').val());
            var loan = parseInt($('#loanAmount').val());
            var installmentAmount = 0;
            if (tenor != 0 && $('#loanAmount').val() != '')
            {
                installmentAmount = loan * (1 + (((interestRate/100) * tenor)))/tenor;

                $('#installmentAmount').val(installmentAmount.toFixed(2));
            }
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
                            // console.log($('#installmentAmount').val());
                            $("<input />").attr("type", "hidden")
                            .attr("name", "installmentAmount")
                            .attr("value", $('#installmentAmount').val())
                            .appendTo("#addCustomerForm");
                            
                            $("<input />").attr("type", "hidden")
                            .attr("name", "provisionFee")
                            .attr("value", $('#provisionFee').val())
                            .appendTo("#addCustomerForm");

                            $("<input />").attr("type", "hidden")
                            .attr("name", "disbursementAmount")
                            .attr("value", $('#disbursementAmount').val())
                            .appendTo("#addCustomerForm");
                            
                            $('#addCustomerForm').submit();
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
                content: 'Are you sure you want to edit this data?',
                buttons: {   
                    ok: {
                        btnClass: 'btn-primary',
                        action: function(){                            
                            $('#editLoanForm').submit();
                            return true;
                        }
                    },
                    cancel: function(){
                        // return false;
                    }
                }
            });
        }

        function showHideRow(row) {
            
            if ($("#hiddenLoan" + row).css('display') == 'none')
            {
                $('.hidden_row').css('display', 'none');
                $("#hiddenLoan" + row).toggle();
            }
            else{
                $("#hiddenLoan" + row).toggle();
            }

            if ($('#loan' + row).attr('class') == 'inactive')
            {
                $('#icon' + row).html('<i class="fa fa-angle-up"></i>');
                $('#loan' + row).removeClass('inactive');
                $('#loan' + row).addClass('active');
            }
            else
            {
                $('#icon' + row).html('<i class="fa fa-angle-down"></i>');
                $('#loan' + row).removeClass('active');
                $('#loan' + row).addClass('inactive');
            }
        }

        function generatePay(id,selectId)
        {
            var incoming = $.grep(incomingList, function(v) {
                return v.loan_id == id;
                // && v.loan_status != 'Haven\'t due yet' && v.loan_status != 'Paid'
            });

            var loan = $.grep(loanList, function(v) {
                return v.loan_id == id;
            });

            $('#' + selectId).html('');
            $('#' + selectId).selectpicker('destroy');
            
            if (incoming.length == 0)
            {
                $('#' + selectId).prop('disabled', true);
                $('#' + selectId).prop('title', 'No available incoming exists');
            }
            else
            {
                for (var i = 0; i < incoming.length; i++)
                {
                    $('#' + selectId).append('<option value="' + incoming[i]['incoming_id'] + '">' + loan[0]['loan_number'] + '-' + (i+1) + ' (' + incoming[i]['loan_status'] + ')' + '</option>');
                }
                $('#' + selectId).selectpicker();
                $('#' + selectId).selectpicker('val', '');
            }
        }

        function validatePay(e)
        {
            $.confirm({
                title: 'Please Confirm',
                content: 'Are you sure you want to pay?',
                buttons: {   
                    ok: {
                        btnClass: 'btn-primary',
                        action: function(){
                            var data = [];

                            for (var i = 0; i < index; i++)
                            {
                                var payData = {incomingId: $('#pay'+i).val(), amount: $('#payAmount'+i).val()};

                                // payData['incomingId'] = $('#pay'+i).val();
                                // payData['amount'] = $('#payAmount'+i).val();

                                data.push(JSON.stringify(payData));
                            }

                            $.ajax
                            ({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                dataType : 'json',
                                type: 'POST',
                                url: "{{ url('/payLoan') }}",
                                data: {
                                    payAmount : $('#payAmount').val(),
                                    loanId : $('#payLoanId').val(),
                                    customerId : $('#payCustomerId').val(),
                                    paymentDate : $('#inputPaymentDate').val()
                                },
                                success : function(result)
                                {
                                    if (result.errNum == 1)
                                    {
                                        var text = "<ul>";
                                    
                                        $.each(result.errors, function(key, value){
                                            for (var i=0; i<value.length; i++)
                                            {
                                                text += "<li>" + value[i] + "</li>";
                                            }
                                        });
                                        text += "</ul>";

                                        $.confirm({
                                            title: 'Payment Failed',
                                            content: text,
                                            type: 'red',
                                            typeAnimated: true,
                                            icon: 'fa fa-warning',
                                            buttons: {
                                                close: function () {
                                                }
                                            }
                                        });
                                    }
                                    else{
                                        location.reload();
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

        function blacklist(id)
        {
            $.confirm({
                title: 'Please Confirm',
                content: 'Are you sure you want to blacklist this user?',
                buttons: {   
                    ok: {
                        btnClass: 'btn-primary',
                        action: function(){

                            $.ajax
                            ({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                dataType : 'json',
                                type: 'POST',
                                url: "{{ url('/blacklist') }}",
                                data: {
                                    customerId : id
                                },
                                success : function(result)
                                {
                                    if (result.errNum == 0)
                                    {
                                        $.confirm ({
                                            title: 'Alert',
                                            content: 'Blacklist success',
                                            buttons: { 
                                                ok: {
                                                    btnClass: 'btn-primary',
                                                    action: function(){
                                                        var url = '/' + result.redirect + '/' + result.custId;
                                                        location.reload();
                                                    }
                                                }  
                                            }
                                        })
                                    }
                                    else{
                                        $.confirm ({
                                            title: 'Alert',
                                            content: 'Blacklist Failed',
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

        function unblacklist(id)
        {
            $.confirm({
                title: 'Please Confirm',
                content: 'Are you sure you want to unblacklist this user?',
                buttons: {   
                    ok: {
                        btnClass: 'btn-primary',
                        action: function(){

                            $.ajax
                            ({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                dataType : 'json',
                                type: 'POST',
                                url: "{{ url('/unblacklist') }}",
                                data: {
                                    customerId : id
                                },
                                success : function(result)
                                {
                                    if (result.errNum == 0)
                                    {
                                        $.confirm ({
                                            title: 'Alert',
                                            content: 'Unblacklist success',
                                            buttons: { 
                                                ok: {
                                                    btnClass: 'btn-primary',
                                                    action: function(){
                                                        var url = '/' + result.redirect + '/' + result.custId;
                                                        location.reload();
                                                    }
                                                }  
                                            }
                                        })
                                    }
                                    else{
                                        $.confirm ({
                                            title: 'Alert',
                                            content: 'Unblacklist Failed',
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
                            $('#addCustomerForm')[0].reset();
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
    .custDetail{
        font-family: 'Roboto', cursive;
        font-size: 18px;
    }

    table .hidden_row{
        display: none;
    }

    #collateralFile {
        display: none;
    }

    #editCollateralFiles {
        display: none !important;
    }

    .file.fa-camera {
        margin: 10px;
        cursor: pointer;
        font-size: 30px;
    }
    .file:hover {
        opacity: 0.6;
    }
    .breadcrumbs {
        margin-top: -25px;
        margin-bottom: 25px;
        margin-left: -50px !important;
        cursor: pointer;
        font-size: 30px;
        color: #337ab7;
    }
    .dropdown.bootstrap-select.form-control.bs3{
        width: 75px !important;
    }
</style>
