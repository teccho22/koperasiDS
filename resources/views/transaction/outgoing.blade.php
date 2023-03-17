@extends('layouts.mainLayout')

@section('title', 'Outgoing')

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

    <div class="modal fade" id="modalOutgoing" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle" style="color: #0877DE !important;">Add Outgoing</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <form method="post" action="{{ url('/addOutgoing') }}" style="font-family: 'Roboto', cursive; color:black; font-size: 20px" id="addOutgoingForm" enctype="multipart/form-data">
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
                                        <option value="Salary">Salary</option>
                                        <option value="Stationary">Stationary</option>
                                        <option value="Adjustment">Adjustment</option>
                                        <option value="Devident">Devident</option>
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
                   <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalOutgoing')">
                       <i class="fa fa-close"></i> Cancel
                   </button>
                    <button type="button" class="btn btn-primary" onclick="validate(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditOutgoing" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i></button>
                    <h4 class="modal-title bold-header" id="modalApproveTitle" style="color: #0877DE !important;">Edit Outgoing</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <form method="post" action="{{ url('/editOutgoing') }}" style="font-family: 'Roboto', cursive; color:black; font-size: 20px" id="editOutgoingForm" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="outgoingId" id="editOutgoingId" class="form-control" value="" hidden/>
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
                                        <option value="Salary">Salary</option>
                                        <option value="Stationary">Stationary</option>
                                        <option value="Adjustment">Adjustment</option>
                                        <option value="Devident">Devident</option>
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
                   <button type="reset" class="btn btn-danger" onclick="confirmCancel('modalEditOutgoing')">
                       <i class="fa fa-close"></i> Cancel
                   </button>
                    <button type="button" class="btn btn-primary" onclick="validateEdit(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="divOutgoing">
        <div class="toolbar Row">
            <form method="post" action="{{ url('/searchOutgoing') }}" style="font-family: 'Roboto', cursive; color:black" class="form-inline">
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
                    <button id="addCustomer" type="button" class="btn btn-primary mr-2" title="Add" onclick="showAddOutgoingModal()"><i class="fa fa-plus"></i> Add Outgoing</button>
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
                @foreach ($outgoing as $data)
                <tr>
                    <td>{{ ($outgoing->currentPage()-1) * $outgoing->perPage() + $loop->index + 1}}</td>
                    <td>
                        {{ date("d-M-Y", strtotime($data->outgoing_date))}}
                    </td>
                    <td>{{ $data->outgoing_category}}</td>
                    <td>Rp{{ number_format($data->outgoing_amount, 2, ',', '.')}}</td>
                    <td style="word-wrap: break-word; max-width: 250px; min-width: 250px;">
                        {{ $data->notes}}
                    </td>
                    <td>
                        @if ($data->outgoing_category != 'New Loan')
                            <button type="button" class="btn btn-primary" title="Edit" onclick="showEditOutgoingModal('{{ $data->outgoing_id}}')"><i class="fa fa-pen"></i></button>
                            <button type="button" class="btn btn-danger" title="Edit" onclick="validateDelete('{{ $data->outgoing_id}}')"><i class="fa fa-trash"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="font-size: 15px">Showing {{($outgoing->currentpage()-1)*$outgoing->perpage()+1}} to {{ $outgoing->currentpage()*(($outgoing->perpage() < $outgoing->total()) ? $outgoing->perpage(): $outgoing->total())}} of {{ $outgoing->total()}} entries</p>
        <div class="contentSeg" style="text-align: center;">
            {{ $outgoing->links() }}
        </div>
    </div>
</div>
@stop

@section('javascript')
    <script>
        var outgoing = {!! json_encode($outgoing->toArray()) !!}.data;
        $('#pagination').val({!! json_encode($paginate) !!});
        function showPagination()
        {
            window.location = "{{ url('/outgoing') }}?paginate=" + $('#pagination').val();
        }

        $(function()
        {

        });

        function showAddOutgoingModal()
        {
            $('#modalOutgoing').modal('show');
        }

        function showEditOutgoingModal(id)
        {
            var value = $.grep(outgoing, function(v) {
                return v.outgoing_id == id;
            });

            // assign value
            var date = value[0].outgoing_date.split(' ');

            $('#editOutgoingId').val(value[0].outgoing_id);
            $('#editTransactionDate').val(date[0]);
            $('#editAmount').val(formatNumber(value[0].outgoing_amount.toString()));
            $('#editNotes').val(value[0].notes);

            $('#editCategory').val(value[0].outgoing_category);
            $('.selectpicker').selectpicker('refresh');

            $('#modalEditOutgoing').modal('show');
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
            console.log(input);

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
                allowEnterKey: true,
                buttons: {
                    ok: {
                        btnClass: 'btn-primary',
                        action: function(){
                            var amount = Number($('#amount').val().replace(/[^0-9.-]+/g,""));
                            $('#amount').val(amount);

                            $("<input />").attr("type", "hidden")
                            .attr("name", "amount")
                            .attr("value", $('#amount').val())
                            .appendTo("#addOutgoingForm");

                            $('#addOutgoingForm').submit();
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
                allowEnterKey: true,
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

                            $('#editOutgoingForm').submit();
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
                allowEnterKey: true,
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
                                    outgoingId : id
                                },
                                success : function(result)
                                {
                                    if (result.errNum == 0)
                                    {
                                        $.confirm ({
                                            title: 'Alert',
                                            content: 'Delete outgoing success',
                                            allowEnterKey: true,
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
                                            content: 'Delete outgoing Failed',
                                            allowEnterKey: true,
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
                            // $('#' + modalId)[0].reset();
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
    #divOutgoing {
        font-family: 'Roboto', cursive;
        font-size: 18px;
    }
</style>
