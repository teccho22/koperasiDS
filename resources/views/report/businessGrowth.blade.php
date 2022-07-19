@extends('layouts.mainLayout')
 
@section('title', 'Business Growth Report')
 
@section('sidebar')
@stop

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js" charset="utf-8"></script>
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
            <form method="get" action="{{ url('/generateChart') }}" style="font-family: 'Roboto', cursive; color:black" class="">
                {{ csrf_field() }}
                <div class="row">
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
                    <button type="submit" class="btn btn-primary" style="float: right">
                        <i class="fa fa-search"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div style="width: 100%;margin-top: 50px; border: 1px black solid; border-radius: 20px">
        <div id="mychart" style="width: 60%;margin: 0px auto;">
            <canvas id="chart" style="width: 100%;margin: 20px auto;" ></canvas>
        </div>
    </div>
</div>
@stop

@section('javascript')
    <script>
        var label = {!! json_encode($label) !!};
        var data = {!! json_encode($data) !!};
        label.splice(0, 0, "");
        data.splice(0, 0, 0);

        function generateChart(label, data)
        {
            var ctx = document.getElementById("chart").getContext('2d');
            var myChart = new Chart(ctx, {
                // type: 'bar',
                // data: {
                //     labels: label,
                //     datasets: [{
                //         label: 'Total Account',
                //         data: data,
                //         backgroundColor: [
                //             'rgba(255, 99, 132, 0.2)',
                //             'rgba(54, 162, 235, 0.2)',
                //             'rgba(255, 206, 86, 0.2)',
                //             'rgba(75, 192, 192, 0.2)',
                //             'rgba(153, 102, 255, 0.2)',
                //             'rgba(255, 159, 64, 0.2)',
                //             'rgba(255, 99, 132, 0.2)',
                //             'rgba(54, 162, 235, 0.2)',
                //             'rgba(255, 206, 86, 0.2)',
                //             'rgba(75, 192, 192, 0.2)',
                //             'rgba(153, 102, 255, 0.2)',
                //             'rgba(255, 159, 64, 0.2)'
                //         ],
                //         borderColor: [
                //             'rgba(255,99,132,1)',
                //             'rgba(54, 162, 235, 1)',
                //             'rgba(255, 206, 86, 1)',
                //             'rgba(75, 192, 192, 1)',
                //             'rgba(153, 102, 255, 1)',
                //             'rgba(255, 159, 64, 1)',
                //             'rgba(255,99,132,1)',
                //             'rgba(54, 162, 235, 1)',
                //             'rgba(255, 206, 86, 1)',
                //             'rgba(75, 192, 192, 1)',
                //             'rgba(153, 102, 255, 1)',
                //             'rgba(255, 159, 64, 1)'
                //         ],
                //         borderWidth: 2
                //     }]
                // },
                type: 'line',
                data: {
                    labels: label,
                    datasets: [{
                        label: 'Total Account',
                        data: data,
                        backgroundColor: 
                            'rgba(255, 99, 132, 0.2)'
                        ,
                        borderColor: 
                            'rgba(255,99,132,1)'
                        ,
                        borderWidth: 3,
                        fill: false
                    }]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Business Growth Report'
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true,
                                userCallback: function(value, index, values) {
                                    value = value.toString();
                                    value = value.split(/(?=(?:...)*$)/);
                                    value = value.join(',');
                                    return 'Rp'+value;
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, chart){
                                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                return datasetLabel + ': Rp' + thousandSeparator(tooltipItem.yLabel);
                            }
                        }
                    }, 
                }
            });
        }

        $(function()
        {
            $('#searchAgent').selectpicker('val','');

            generateChart(label, data)
        });
    </script>
@stop

<style>
    #divOutgoing {
        font-family: 'Roboto', cursive;
        font-size: 18px;
    }
</style>