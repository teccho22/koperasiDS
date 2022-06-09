@extends('layouts.mainLayout')
 
@section('title', 'Customer & Loan')
 
@section('sidebar')
@stop

@section('content')
<div class="container">
    @if (count($errors) > 0)
        <script>
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
    <div class="modal fade" id="modalCustomer" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle">Add Customer</h4>
               </div>
               <form method="post" action="{{ url('/customer/addCustomer') }}" style="font-family: Roboto; color:black" id="addCustomerForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="container-fluid">
                            {{ csrf_field() }}
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Customer Name</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="customerName" class="form-control" placeholder="Name"/>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">KTP No.</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="customerKtp" class="form-control" placeholder="KTP"/>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Job</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="customerJob" class="form-control" placeholder="Job" list="jobList"/>
                                    <datalist id="jobList">
                                        @foreach ($jobList as $job)
                                        <option>{{ $job->customer_proffesion }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Address</span>
                                </div>
                                <div class="col-sm-8">
                                    <textarea name="customerAddress" class="form-control" placeholder="Address" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Customer Phone Number</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="customerPhone" class="form-control" placeholder="Name" onkeypress="numericKeypress(event)"/>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Agent</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="customerAgent" class="form-control" placeholder="Agent" list="agentList"/>
                                    <datalist id="agentList">
                                        @foreach ($agentList as $agent)
                                        <option>{{ $agent->customer_agent }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <span for="" class="" style="font-size: 20px; color:#0877DE;">Disbursement</span>
                            <div class="divDisbursement">
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
                                        <span for="" class="control-label">Interst Rate</span>
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
                                            
                                        </datalist>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <span for="" class="control-label">Collateral File</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <i class="fa fa-camera file"></i><span class="name">No file selected</span>
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
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="validate(this)">
                            <i class="fa fa-share"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-danger" onclick="confirmCancel()">
                            <i class="fa fa-close"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEditCustomer" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle">Edit Customer</h4>
               </div>
               <form method="post" action="{{ url('/customer/editCustomer') }}" style="font-family: Roboto; color:black" id="editCustomerForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="container-fluid">
                            {{ csrf_field() }}
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                            <input type="hidden" name="editCustomerId" id="editCustomerId" class="form-control" hidden/>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Customer Name</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="editCustomerName" id="editCustomerName" class="form-control" placeholder="Name"/>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">KTP No.</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="editCustomerKtp" id="editCustomerKtp" class="form-control" placeholder="KTP"/>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Job</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="editCustomerJob" id="editCustomerJob" class="form-control" placeholder="Job" list="jobList"/>
                                    <datalist id="jobList">
                                        @foreach ($jobList as $job)
                                        <option>{{ $job->customer_proffesion }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Address</span>
                                </div>
                                <div class="col-sm-8">
                                    <textarea name="editCustomerAddress" id="editCustomerAddress" class="form-control" placeholder="Address" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Customer Phone Number</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="editCustomerPhone" id="editCustomerPhone" class="form-control" placeholder="Name" onkeypress="numericKeypress(event)"/>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Agent</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" name="editCustomerAgent" id="editCustomerAgent" class="form-control" placeholder="Agent" list="agentList"/>
                                    <datalist id="agentList">
                                        @foreach ($agentList as $agent)
                                        <option>{{ $agent->customer_agent }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="validateEdit(this)">
                            <i class="fa fa-share"></i> Submit
                        </button>
                        <button type="reset" class="btn btn-danger" onclick="confirmCancel()">
                            <i class="fa fa-close"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="divCustomer">
        <div class="toolbar">
            <form method="post" action="{{ url('/customer/search') }}" style="font-family: Roboto; color:black" class="form-inline">
                {{ csrf_field() }}
                <input type="text" name="search" class="form-control" placeholder="Name/KTP"/>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i> Search
                </button>
                <div style="float: right">
                    <button id="addCustomer" type="button" class="btn btn-primary mr-2" title="Add" onclick="showAddCustomerModal()"><i class="fa fa-plus"></i> Add Customer</button>
                    {{-- <button id="addLoan" type="button" class="btn btn-primary" title="Add" onclick="showAddLoanModal()"><i class="fa fa-plus"></i> Add Loan</button> --}}
                </div>
            </form>
        </div>
        <table class="table table-responsive" style="border-radius: 11px">
            <thead style="background-color: #0877DE !important; color:#FFFFFF !important">
                <tr>
                    <th>No.</th>
                    <th>Customer Id</th>
                    <th>Customer Name</th>
                    <th>KTP No.</th>
                    <th>Job</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Agent</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customer as $data)
                <tr>
                    <td>{{ ($customer->currentPage()-1) * $customer->perPage() + $loop->index + 1}}</td>
                    <td>{{ $data->customer_id}}</td>
                    <td>{{ $data->customer_name}}</td>
                    <td>{{ $data->customer_id_number}}</td>
                    <td>{{ $data->customer_proffesion}}</td>
                    <td>{{ $data->customer_address}}</td>
                    <td>{{ $data->customer_phone}}</td>
                    <td>{{ $data->customer_agent}}</td>
                    <td>
                        <button id="editCustomer" type="button" class="btn btn-primary" title="Edit" onclick="showEditModal('{{ $data->customer_id}}')"><i class="fa fa-pen"></i></button>
                        <a id="viewDetail" type="button" class="btn btn-primary" title="View Detail" href="{{ route('loan', ['id' => $data->customer_id]) }}"><i class="fa fa-eye"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="font-size: 15px">Showing {{($customer->currentpage()-1)*$customer->perpage()+1}} to {{ $customer->currentpage()*(($customer->perpage() < $customer->total()) ? $customer->perpage(): $customer->total())}} of {{ $customer->total()}} entries</p>
        <div class="contentSeg" style="text-align: center;">
            {{ $customer->links() }}
        </div>
    </div>
</div>
@stop

@section('javascript')
    <script type="text/javascript">

        $("i").click(function () {
            $("input[type='file']").trigger('click');
        });

        $('input[type="file"]').on('change', function() {
            var val = $(this).val();
            $(this).siblings('span').text(val);
        })

        function showAddCustomerModal()
        {
            $('#modalCustomer').modal('show');
        }
        
        function confirmCancel()
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
                            $('#modalCustomer').modal('hide');
                        }
                    },
                    cancel: function(){
                    }
                }
            });
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

        function calculateLoan(event)
        {
            var loan = 0;
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
            var interest = $('#interestRate').val();
            var installmentAmount = 0;
            if ($('#tenor').val() != '')
            {
                installmentAmount = loan + (((interest * loan)/100)/parseInt($('#tenor').val()));
                
                $('#installmentAmount').val(installmentAmount.toFixed(2));
            }

        }

        function calculateInterest(event)
        {
            var interest = 0;
            if (event.key != 'Backspace')
            {
                interest = parseInt($('#interestRate').val() + event.key);
            }
            else
            {
                interest = parseInt($('#interestRate').val().substring(0, $('#interestRate').val().length - 1));
            }

            var installmentAmount = 0;
            var loan = parseInt($('#loanAmount').val());
            if ($('#tenor').val() != '' && $('#loanAmount').val() != '')
            {
                installmentAmount = loan + (((interest * loan)/100)/parseInt($('#tenor').val()));

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

            var interest = $('#interestRate').val();
            var loan = parseInt($('#loanAmount').val());
            var installmentAmount = 0;
            if ($('#loanAmount').val() != '')
            {
                installmentAmount = (loan + ((interest * loan)/100))/tenor;

                $('#installmentAmount').val(installmentAmount.toFixed(2));
            }
        }

        function showEditModal(id)
        {
            $.ajax
            ({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType : 'json',
                type: 'POST',
                url: '/customer/getEditData',
                data: {
                    id : id
                },
                success: function(result)
                {
                    console.log(result.result[0]['customer_name']);

                    //fill edit field
                    $('#editCustomerId').val(result.result[0]['customer_id']);
                    $('#editCustomerName').val(result.result[0]['customer_name']);
                    $('#editCustomerKtp').val(result.result[0]['customer_id_number']);
                    $('#editCustomerJob').val(result.result[0]['customer_proffesion']);
                    $('#editCustomerAddress').val(result.result[0]['customer_address']);
                    $('#editCustomerPhone').val(result.result[0]['customer_phone']);
                    $('#editCustomerAgent').val(result.result[0]['customer_agent']);

                    $('#modalEditCustomer').modal('show');
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
                            $('#editCustomerForm').submit();
                            return true;
                        }
                    },
                    cancel: function(){
                        // return false;
                    }
                }
            });
        }
    </script>
@stop

<style>
    #divCustomer {
        font-family: Roboto;
        font-size: 18px;
    }

    .modal {
        font-size: 15px;
    }

    .divDisbursement {
        border-style: solid;
        border-width: 1.5;
        border-radius: 8px;
        border-color: #c2c4c5;
        padding: 15px;
    }

    .file.fa-camera {
        margin: 10px;
        cursor: pointer;
        font-size: 30px;
    }
    .file:hover {
        opacity: 0.6;
    }
    #collateralFile {
        display: none;
    }
</style>