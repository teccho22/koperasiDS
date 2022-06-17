@extends('layouts.mainLayout')
 
@section('title', 'Transaction/incoming')
 
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
                    <h4 class="modal-title bold-header" id="modalApproveTitle">Add Incoming</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <form method="post" action="{{ url('/addIncoming') }}" style="font-family: Roboto; color:black; font-size: 20px" id="addIncomingForm" enctype="multipart/form-data">
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
                                    <input type="text" id="amount" name="amount" class="form-control" placeholder="Rp2.390.000" onkeypress="decimalKeypress(event)"/> 
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
                    <button type="button" class="btn btn-primary" onclick="validate(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                    <button type="reset" class="btn btn-danger" onclick="confirmCancel()">
                        <i class="fa fa-close"></i> Cancel
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
                    <h4 class="modal-title bold-header" id="modalApproveTitle">Edit Incoming</h4>
               </div>
               <div class="modal-body">
                   <div class="container-fluid">
                        <form method="post" action="{{ url('/editIncoming') }}" style="font-family: Roboto; color:black; font-size: 20px" id="editIncomingForm" enctype="multipart/form-data">
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
                                    <input type="text" id="editAmount" name="amount" class="form-control" placeholder="Rp2.390.000" onkeypress="return numericKeypress(event)"/> 
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
                    <button type="button" class="btn btn-primary" onclick="validateEdit(this)">
                        <i class="fa fa-share"></i> Submit
                    </button>
                    <button type="reset" class="btn btn-danger" onclick="confirmCancel()">
                        <i class="fa fa-close"></i> Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="divIncoming">
        <div class="toolbar">
            <form method="post" action="{{ url('/searchIncoming') }}" style="font-family: Roboto; color:black" class="form-inline">
                {{ csrf_field() }}
                <input type="text" name="search" class="form-control" placeholder="Id/Date/Category"/>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i> Search
                </button>
                <div style="float: right">
                    <button id="addCustomer" type="button" class="btn btn-primary mr-2" title="Add" onclick="showAddIncomingModal()"><i class="fa fa-plus"></i> Add Incoming</button>
                    {{-- <button id="addLoan" type="button" class="btn btn-primary" title="Add" onclick="showAddLoanModal()"><i class="fa fa-plus"></i> Add Loan</button> --}}
                </div>
            </form>
        </div>
        <table class="table table-responsive">
            <thead style="background-color: #0877DE !important; color:#FFFFFF !important">
                <tr>
                    <th>No.</th>
                    <th>Id</th>
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
                    <td>{{ str_pad($data->incoming_id, 4, '0', STR_PAD_LEFT)}}</td>
                    <td>
                        @if($data->incoming_date != null)
                            {{ date("d-M-Y", strtotime($data->incoming_date))}}
                        @else
                            Due at {{ date("d-M-Y", strtotime($data->loan_due_date))}}
                        @endif
                    </td>
                    <td>{{ $data->incoming_category}}</td>
                    <td>{{ number_format($data->incoming_amount, 2, ',', '.')}}</td>
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
            $('#editAmount').val(value[0].incoming_amount.toString());
            $('#editNotes').val(value[0].notes);
            
            $('#editCategory').val(value[0].incoming_category);
            $('.selectpicker').selectpicker('refresh');

            $('#modalEditIncoming').modal('show');
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
    </script>
@stop

<style>
    #divIncoming {
        font-family: Roboto;
        font-size: 18px;
    }
</style>