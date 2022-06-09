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

    <div id="divIncoming">
        <div class="toolbar">
            <form method="post" action="{{ url('/customer/search') }}" style="font-family: Roboto; color:black" class="form-inline">
                {{ csrf_field() }}
                <input type="text" name="search" class="form-control" placeholder="Name/KTP"/>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search"></i> Search
                </button>
                <div style="float: right">
                    <button id="addCustomer" type="button" class="btn btn-primary mr-2" title="Add" onclick="showAddCustomerModal()"><i class="fa fa-plus"></i> Add Incoming</button>
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incoming as $data)
                <tr>
                    <td>{{ ($incoming->currentPage()-1) * $incoming->perPage() + $loop->index + 1}}</td>
                    <td>{{ $data->incoming_id}}</td>
                    <td>{{ $data->incoming_date}}</td>
                    <td>{{ $data->incoming_category}}</td>
                    <td>{{ $data->incoming_amount}}</td>
                    <td>
                        <button id="editCustomer" type="button" class="btn btn-primary" title="Edit" onclick="showEditModal('{{ $data->incoming_id}}')"><i class="fa fa-pen"></i></button>
                        <a id="viewDetail" type="button" class="btn btn-primary" title="View Detail" href="{{ route('loan', ['id' => $data->incoming_id]) }}"><i class="fa fa-eye"></i></a>
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

<style>
    #divIncoming {
        font-family: Roboto;
        font-size: 18px;
    }
</style>