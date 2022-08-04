@extends('layouts.mainLayout')
 
@section('title', 'Incoming')
 
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

    <div class="modal fade" id="modalIncoming" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle" style="color: #0877DE !important;">Add Incoming</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <form method="post" action="{{ url('/addIncoming') }}" style="font-family: 'Roboto', cursive; color:black; font-size: 20px" id="addIncomingForm" enctype="multipart/form-data">
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
                                    <select class="form-control selectpicker" id="category" name="category" data-size="5" data-live-search="true">
                                        <option value="Capital">Capital</option>
                                        <option value="Adjustment">Adjustment</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Amount</span>
                                </div>
                                <div class="col-sm-8 required">
                                    <input type="text" id="amount" name="amount" class="form-control" placeholder="Rp2.390.000" onkeypress="decimalKeypress(event)" data-type="currency"/> 
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Notes</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="notes" name="notes" class="form-control" placeholder="notes"/>
                                </div>
                            </div>
                        </form>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalIncoming')">
                       <i class="fa fa-close"></i> Cancel
                   </button>
                    <button type="button" class="btn btn-primary" onclick="validate(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditIncoming" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle" style="color: #0877DE !important;">Edit Incoming</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <form method="post" action="{{ url('/editIncoming') }}" style="font-family: 'Roboto', cursive; color:black; font-size: 20px" id="editIncomingForm" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="incomingId" id="editIncomingId" class="form-control" value="" hidden/>
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
                                        <option value="Capital">Capital</option>
                                        <option value="Adjustment">Adjustment</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row required">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Amount</span>
                                </div>
                                <div class="col-sm-8 required">
                                    <input type="text" id="editAmount" name="amount" class="form-control" placeholder="Rp2.390.000" onkeypress="return numericKeypress(event)" data-type="currency"/> 
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <span for="" class="control-label">Notes</span>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="editNotes" name="notes" class="form-control" placeholder="notes"/>
                                </div>
                            </div>
                        </form>
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalEditIncoming')">
                       <i class="fa fa-close"></i> Cancel
                   </button>
                    <button type="button" class="btn btn-primary" onclick="validateEdit(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="divIncoming">
        <div class="toolbar Row">
            <form method="post" action="{{ url('/searchIncoming') }}" style="font-family: 'Roboto', cursive; color:black" class="form-inline">
                {{ csrf_field() }}
                <div class="Column">
                    <input type="text" name="search" class="form-control" placeholder="ID/ Date/ Category"/>
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
                    <button id="addCustomer" type="button" class="btn btn-primary mr-2" title="Add" onclick="showAddIncomingModal()"><i class="fa fa-plus"></i> Add Incoming</button>
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
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incoming as $data)
                <tr>
                    <td>{{ ($incoming->currentPage()-1) * $incoming->perPage() + $loop->index + 1}}</td>
                    <td>
                        @if($data->incoming_date != null)
                            {{ date("d-M-Y", strtotime($data->incoming_date))}}
                        @else
                            Due at {{ date("d-M-Y", strtotime($data->loan_due_date))}}
                        @endif
                    </td>
                    <td>{{ $data->incoming_category}}</td>
                    <td>Rp{{ number_format($data->incoming_amount, 2, ',', '.')}}</td>
                    <td style="word-wrap: break-word; max-width: 250px; min-width: 250px;">
                        {{ $data->notes}}
                    </td>
                    <td>
                        @if($data->incoming_category != 'Installment')
                        <button id="editCustomer" type="button" class="btn btn-primary" title="Edit" onclick="showEditIncomingModal('{{ $data->incoming_id}}')"><i class="fa fa-pen"></i></button>
                        <button type="button" class="btn btn-danger" title="Edit" onclick="validateDelete('{{ $data->incoming_id}}')"><i class="fa fa-trash"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="font-size: 15px">Showing {{($incoming->currentpage()-1)*$incoming->perpage()+1}} to {{ $incoming->currentpage()*(($incoming->perpage() < $incoming->total()) ? $incoming->perpage(): $incoming->total())}} of {{ $incoming->total()}} entries</p>
        <div class="contentSeg" style="text-align: center;">
            {{ $incoming->links() }}
        </div>
    </div>
</div>
@stop

@section('javascript')
    <script>
        var incoming = {!! json_encode($incoming->toArray()) !!}.data;

        $('#pagination').val({!! json_encode($paginate) !!});
        function showPagination()
        {
            window.location = "{{ url('/incoming') }}?paginate=" + $('#pagination').val();
        }

        $(function()
        {
            
        });

        function showAddIncomingModal()
        {
            $('#modalIncoming').modal('show');
        }

        function showEditIncomingModal(id)
        {
            var value = $.grep(incoming, function(v) {
                return v.incoming_id == id;
            });

            // assign value
            var date = value[0].incoming_date.split(' ');
            
            $('#editIncomingId').val(value[0].incoming_id);
            $('#editTransactionDate').val(date[0]);
            $('#editAmount').val(formatNumber(value[0].incoming_amount.toString()));
            $('#editNotes').val(value[0].notes);
            
            $('#editCategory').val(value[0].incoming_category);
            $('.selectpicker').selectpicker('refresh');

            $('#modalEditIncoming').modal('show');
        }

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

        function validate(e)
        {
            $.confirm({
                title: 'Please Confirm',
                content: 'Are you sure you want to save this data?',
                buttons: {   
                    ok: {
                        btnClass: 'btn-primary',
                        action: function(){
                            var amount = Number($('#amount').val().replace(/[^0-9.-]+/g,""));
                            $('#amount').val(amount);

                            $("<input />").attr("type", "hidden")
                            .attr("name", "amount")
                            .attr("value", $('#amount').val())
                            .appendTo("#addIncomingForm");

                            $('#addIncomingForm').submit();
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
                            var amount = Number($('#editAmount').val().replace(/[^0-9.-]+/g,""));
                            $('#editAmount').val(amount);

                            $("<input />").attr("type", "hidden")
                            .attr("name", "editAmount")
                            .attr("value", $('#editAmount').val())
                            .appendTo("#editOutgoingForm");

                            $('#editIncomingForm').submit();
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
                                url: "{{ url('/deleteIncoming') }}",
                                data: {
                                    incomingId : id
                                },
                                success : function(result)
                                {
                                    if (result.errNum == 0)
                                    {
                                        $.confirm ({
                                            title: 'Alert',
                                            content: 'Delete incoming success',
                                            buttons: { 
                                                ok: {
                                                    btnClass: 'btn-primary',
                                                    action: function(){
                                                        location.reload();
                                                    }
                                                }  
                                            }
                                        })
                                    }
                                    else
                                    {
                                        $.confirm ({
                                            title: 'Alert',
                                            content: 'Delete incoming Failed',
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
    #divIncoming {
        font-family: 'Roboto', cursive;
        font-size: 18px;
    }
</style>