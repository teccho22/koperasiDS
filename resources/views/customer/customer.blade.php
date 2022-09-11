@extends('layouts.mainLayout')
 
@section('title', 'Customer')
 
@section('sidebar')
@stop

@section('content')
<div class="container">
    @if (count($errors) > 0)
        <script>
            var text = "<ul>";
            var errors = {!! json_encode($errors->toArray()) !!};
                                    
            $.each(errors, function(key, value){
                for (var i=0; i<value.length; i++)
                {
                    text += "<li>" + value[i] + "</li>";
                }
            });
            text += "</ul>";

            $.confirm({
                title: 'Add / Edit Customer Failed',
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
    <div class="modal fade" id="modalCustomer" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle" style="color: #0877DE !important;">Add Customer</h4>
               </div>
               <form method="post" action="{{ url('/customer/addCustomer') }}" style="font-family: 'Roboto', cursive; color:black" id="addCustomerForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="container-fluid">
                            {{ csrf_field() }}
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
                                        <option value="{{ $job->customer_proffesion }}">{{ $job->customer_proffesion }}</option>
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
                                    <input type="text" name="customerPhone" class="form-control" placeholder="0856xxxxxx" onkeypress="numericKeypress(event)"/>
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
                            <span for="" class="" style="font-size: 20px; color:#0877DE;"><b>Disbursement</b></span>
                            <div class="divDisbursement">
                                <div class="form-group row required">
                                    <div class="col-sm-4">
                                        <span for="" class="control-label">Loan Amount</span>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" id="loanAmount" name="loanAmount" class="form-control" placeholder="Rp 10.000.000,00" onkeypress="decimalKeypress(event)" onkeydown="calculateLoan(event)" data-type="currency"/>
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
                                        <i class="fa fa-camera file upload-file" style="margin-left: 0px"></i><span class="name">No file selected</span>
                                        <input type="file" name="collateralFiles" id="collateralFile" class="form-control upload-file"/>
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
                        <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalCustomer')">
                            <i class="fa fa-close"></i> Cancel
                        </button>
                        <button type="button" class="btn btn-primary" onclick="validate(this)">
                            <i class="fa fa-share"></i> Submit
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
               <form method="post" action="{{ url('/customer/editCustomer') }}" style="font-family: 'Roboto', cursive; color:black" id="editCustomerForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="container-fluid">
                            {{ csrf_field() }}
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
                        <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalEditCustomer')">
                            <i class="fa fa-close"></i> Cancel
                        </button>
                        <button type="button" class="btn btn-primary" onclick="validateEdit(this)">
                            <i class="fa fa-share"></i> Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="divCustomer">
        <div class="toolbar Row">
            <form method="post" action="{{ url('/searchCustomer') }}" style="font-family: 'Roboto', cursive; color:black" class="form-inline">
                {{ csrf_field() }}
                <div class="Column">
                    <input type="text" name="search" class="form-control" placeholder="Name/ KTP/ Customer ID"/>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
                <div class="Column">
                    <span for="" class="control-label" style="margin-left: auto;">Show Result</span>
                    <select class="form-control selectpicker" id="pagination" name="pagination" data-live-search="true" style="margin-right: auto;" onchange="showPagination()">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="Column">
                    <button id="addCustomer" type="button" class="btn btn-primary mr-2" title="Add" onclick="showAddCustomerModal()"><i class="fa fa-plus"></i> Add Customer</button>
                    {{-- <button id="addLoan" type="button" class="btn btn-primary" title="Add" onclick="showAddLoanModal()"><i class="fa fa-plus"></i> Add Loan</button> --}}
                </div>
            </form>
        </div>
        <table class="table table-responsive" style="border-radius: 11px">
            <thead style="background-color: #0877DE !important; color:#FFFFFF !important; letter-spacing: 1px;">
                <tr>
                    <th>No.</th>
                    <th>Customer ID</th>
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
        $('#pagination').val({!! json_encode($paginate) !!});
        function showPagination()
        {
            window.location = "{{ url('/customer') }}?paginate=" + $('#pagination').val();
        }

        $("i.upload-file").click(function () {
            $("input[type='file']").trigger('click');
        });

        $('input[type="file"]').on('change', function() {
            var val = $(this).val();
            $(this).siblings('span').text(val);
        })

        $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() { 
                formatCurrency($(this), "blur");
            }
        });

        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }

        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.
            
            // get input value
            var input_val = input.val();
            
            // don't validate empty input
            if (input_val === "") { return; }
            
            // original length
            var original_len = input_val.length;

            // initial caret position 
            var caret_pos = input.prop("selectionStart");
                
            // check for decimal
            if (input_val.indexOf(".") >= 0) {
                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);
                
                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                right_side += "00";
                }
                
                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = left_side + "." + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = input_val;
                
                // final formatting
                if (blur === "blur") {
                input_val += ".00";
                }
            }
            
            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }

        function calculateLoan(event)
        {
            var loan = 0;
            var tenor = 0;
            if (event.key != 'Backspace')
            {
                loan = parseInt(Number($('#loanAmount').val().replace(/[^0-9.-]+/g,"")) + event.key);
            }
            else
            {
                loan = parseInt($('#loanAmount').val().replace(/[^0-9.-]+/g,"").substring(0, $('#loanAmount').val().replace(/[^0-9.-]+/g,"").length - 1));
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
                formatCurrency($('#provisionFee'));
            }

            var disbursement = loan - provisionFee;
            if (isNaN(disbursement))
            {
                $('#disbursementAmount').val(0);
            }
            else
            {
                $('#disbursementAmount').val(disbursement);
                formatCurrency($('#disbursementAmount'));
            }

            // interest default 2,5%
            var interestRate = parseFloat($('#interestRate').val());
            var installmentAmount = 0;
            if ($('#tenor').val() != '')
            {
                tenor = $('#tenor').val();

                installmentAmount = loan * (1 + ((interestRate/100 * tenor)))/tenor;
                
                $('#installmentAmount').val(installmentAmount.toFixed(2));
                formatCurrency($('#installmentAmount'));
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
            var loan = Number($('#loanAmount').val().replace(/[^0-9.-]+/g,""));
            if ($('#tenor').val() != '' && $('#loanAmount').val() != '')
            {
                tenor = $('#tenor').val();

                installmentAmount = loan * (1 + ((interestRate/100 * tenor)))/tenor;

                $('#installmentAmount').val(installmentAmount.toFixed(2));
                formatCurrency($('#installmentAmount'));
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
            var loan = Number($('#loanAmount').val().replace(/[^0-9.-]+/g,""));
            var installmentAmount = 0;
            if ($('#loanAmount').val() != '')
            {
                installmentAmount = loan * (1 + ((interestRate/100 * tenor)))/tenor;

                $('#installmentAmount').val(installmentAmount.toFixed(2));
                formatCurrency($('#installmentAmount'));
            }
        }

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
                            var loan = Number($('#loanAmount').val().replace(/[^0-9.-]+/g,""));
                            $('#loanAmount').val(loan);

                            var installmentAmount = Number($('#installmentAmount').val().replace(/[^0-9.-]+/g,""));
                            $('#installmentAmount').val(loan);
                            var provisionFee = Number($('#provisionFee').val().replace(/[^0-9.-]+/g,""));
                            $('#provisionFee').val(loan);
                            var disbursementAmount = Number($('#disbursementAmount').val().replace(/[^0-9.-]+/g,""));
                            $('#disbursementAmount').val(loan);

                            $("<input />").attr("type", "hidden")
                            .attr("name", "loanAmount")
                            .attr("value", $('#loanAmount').val())
                            .appendTo("#addCustomerForm");

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

        function showEditModal(id)
        {
            $.ajax
            ({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType : 'json',
                type: 'POST',
                url: "{{ url('/customer/getEditData') }}",
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
    #divCustomer {
        font-family: 'Roboto', cursive;
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
    .Row {
        display: table;
        width: 100%; /*Optional*/
        table-layout: fixed; /*Optional*/
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .Column {
        display: table-cell;
        width: 46%;
    }
</style>