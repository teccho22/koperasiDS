@extends('layouts.mainLayout')
 
@section('title', 'Transaction/outgoing')
 
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
            <form method="get" action="{{ url('/searchNpl') }}" style="font-family: Roboto; color:black" class="">
                {{ csrf_field() }}
                <div class="row">
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <span for="" class="control-label">Agent</span>
                        </div>
                        <div class="col-sm-7">
                            <select class="form-control selectpicker" id="searchAgent" name="searchAgent" data-size="5" data-live-search="true">
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
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
        <table class="table table-responsive">
            <thead style="background-color: #0877DE !important; color:#FFFFFF !important; letter-spacing: 1px;">
                <tr>
                    <th>No.</th>
                    <th>Customer Id</th>
                    <th>Customer Name</th>
                    <th>Loan Amount</th>
                    <th>Installment Amount</th>
                    <th>Disbursement Date</th>
                    <th>Tenor</th>
                    <th>Outstanding</th>
                    <th>NPL At</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $toalLoanAmount = 0;
                    $totalOutstanding = 0;
                    $totalNpl = 0;
                @endphp
                @foreach ($npl as $data)
                <tr>
                    @php
                        $toalLoanAmount += $data->loan_amount;
                        $totalOutstanding += $data->outstanding;
                        $totalNpl += $data->npl_at;
                    @endphp
                    <td>{{ ($npl->currentPage()-1) * $npl->perPage() + $loop->index + 1}}</td>
                    <td>{{ $data->customer_id }}</td>
                    <td>{{ $data->customer_name }}</td>
                    <td>Rp. {{ number_format($data->loan_amount, 2, ',', '.') }} </td>
                    <td>Rp. {{ number_format($data->installment_amount, 2, ',', '.') }} </td>
                    <td>{{ $data->disbursement_date }}</td>
                    <td>{{ $data->tenor }}</td>
                    <td>Rp. {{ number_format($data->outstanding, 2, ',', '.') }} </td>
                    <td>Rp. {{ number_format($data->npl_at, 2, ',', '.') }} </td>
                </tr>
                @endforeach
                <tr style="">
                    <td colspan="6"></td>
                    <td colspan="2" style="text-align: center;"><strong>Total Loan</strong></td>
                    <td colspan="3"><strong>Rp. {{ number_format($toalLoanAmount, 2, ',', '.') }}</strong></td>
                </tr>
                <tr style="">
                    <td colspan="6"></td>
                    <td colspan="2" style="text-align: center;"><strong>Total Outstanding</strong></td>
                    <td colspan="3"><strong>Rp. {{ number_format($toalLoanAmount, 2, ',', '.') }}</strong></td>
                </tr>
                <tr style="">
                    <td colspan="6"></td>
                    <td colspan="2" style="text-align: center;"><strong>Total NPL</strong></td>
                    <td colspan="3"><strong>Rp. {{ number_format($toalLoanAmount, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
        <p style="font-size: 15px">Showing {{($npl->currentpage()-1)*$npl->perpage()+1}} to {{ $npl->currentpage()*(($npl->perpage() < $npl->total()) ? $npl->perpage(): $npl->total())}} of {{ $npl->total()}} entries</p>
        <div class="contentSeg" style="text-align: center;">
            {{ $npl->links() }}
        </div>
    </div>
</div>
@stop

@section('javascript')
    <script>
        var npl = {!! json_encode($npl->toArray()) !!}.data;

        $(function()
        {
            $('#searchAgent').selectpicker('val','');
        });
    </script>
@stop

<style>
    #divOutgoing {
        font-family: Roboto;
        font-size: 18px;
    }
</style>