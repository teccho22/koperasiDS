@extends('layouts.mainLayout')
 
@section('title', 'Disbursement Report')
 
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

    <div id="divOutgoing">
        <div class="toolbar">
            <form method="get" action="{{ url('/searchDisbursement') }}" style="font-family: 'Roboto', cursive; color:black" class="">
                {{ csrf_field() }}
                <div class="row">
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <span for="" class="control-label">Agent</span>
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control selectpicker" id="searchAgent" name="searchAgent" data-size="5" data-live-search="true" data-live-search-placeholder="Search">
                                <option value="" selected>Please Choose One</option>
                                @foreach ($agentList as $agent)
                                    <option value="{{ $agent->customer_agent }}">{{ $agent->customer_agent }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <span for="" class="control-label">Date</span>
                        </div>
                        <div class="col-sm-3">
                            <input type="month" class="form-control" id="searchDateFrom"  name="searchDateFrom" onkeypress="return dateKeypress(event)" placeholder="mm-yyyy" pattern="\d{2}-\d{4}">
                        </div>
                        <div class="col-sm-1">
                            <span for="" class="control-label">To</span>
                        </div>
                        <div class="col-sm-3">
                            <input type="month" class="form-control" id="searchDateTo"  name="searchDateTo" onkeypress="return dateKeypress(event)" placeholder="mm-yyyy" pattern="\d{2}-\d{4}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <span for="" class="control-label">Report Type</span>
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control selectpicker" name="type">
                                <option value="Display">Display</option>
                                <option value="Excel">Excel</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-primary" name="submit" value="submit" style="float: right">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6" style="">
                        <span for="" class="control-label" style="margin-right: 10px;">Show Result</span>
                        <select class="form-control selectpicker paginateSelector" id="pagination" name="paginate" data-live-search="true" style="margin-right: auto;" onchange="showPagination()">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-responsive">
            <thead style="background-color: #0877DE !important; color:#FFFFFF !important; letter-spacing: 1px;">
                <tr style="text-align: center">
                    <th>No.</th>
                    <th>Outgoing Date</th>
                    <th>Loan No.</th>
                    <th>Customer ID</th>
                    <th>Customer Name</th>
                    <th>Customer Proffesion</th>
                    <th style="width: 150px; word-wrap: break-word;">Customer Address</th>
                    <th style="width: 150px; word-wrap: break-word;">Collateral</th>
                    <th>Loan Amount</th>
                    <th>Installment Amount</th>
                    <th>Tenor</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $toalLoanAmount = 0;
                @endphp
                @foreach ($disbursement as $data)
                <tr>
                    @php
                        $toalLoanAmount += $data->loan_amount;
                    @endphp
                    <td>{{ ($disbursement->currentPage()-1) * $disbursement->perPage() + $loop->index + 1}}</td>
                    <td>{{ $data->transaction_date }}</td>
                    <td>{{ $data->loan_number }}</td>
                    <td>{{ $data->customer_id }}</td>
                    <td>{{ $data->customer_name }}</td>
                    <td>{{ $data->customer_proffesion }}</td>
                    <td style="width: 150px; word-wrap: break-word;">{{ $data->customer_address }}</td>
                    <td style="width: 150px; word-wrap: break-word;">{{ $data->collateral }}</td>
                    <td>Rp{{ number_format($data->loan_amount, 2, ',', '.') }} </td>
                    <td>Rp{{ number_format($data->installment_amount, 2, ',', '.') }} </td>
                    <td>{{ $data->tenor }}</td>
                </tr>
                @endforeach
                <tr style="">
                    <td colspan="7"></td>
                    <td><strong>Total Loan</strong></td>
                    <td><strong>Rp{{ number_format($toalLoanAmount, 2, ',', '.') }}</strong></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
        <p style="font-size: 15px">Showing {{($disbursement->currentpage()-1)*$disbursement->perpage()+1}} to {{ $disbursement->currentpage()*(($disbursement->perpage() < $disbursement->total()) ? $disbursement->perpage(): $disbursement->total())}} of {{ $disbursement->total()}} entries</p>
        <div class="contentSeg" style="text-align: center;">
            {{ $disbursement->links() }}
        </div>
    </div>
</div>
@stop

@section('javascript')
    <script>
        var disbursement = {!! json_encode($disbursement->toArray()) !!}.data;
        $('#pagination').val({!! json_encode($paginate) !!});
        function showPagination()
        {
            console.log($(location).attr("href").indexOf("searchDisbursement"));
            if ($(location).attr("href").indexOf("searchDisbursement") != -1)
            {
                var url = $(location).attr("href");
                url = url.replace("paginate=" + {!! json_encode($paginate) !!}, "paginate=" + $('#pagination').val());
                window.location = url;
            }
            else
            {
                window.location = "{{ url('/disbursement') }}?paginate=" + $('#pagination').val();
            }
        }

        $(function()
        {
            $('#searchAgent').selectpicker('val','');
        });
    </script>
@stop

<style>
    #divOutgoing {
        font-family: 'Roboto', cursive;
        font-size: 18px;
    }
    th,td{
        vertical-align: middle !important;
    }
    .dropdown.bootstrap-select.form-control.bs3.paginateSelector{
        width: 75px !important;
    }
</style>