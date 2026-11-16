@extends('layouts.app')

@section('page_css')
    <link href="{{ asset('assets/plugins/morrisjs/morris.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body pb-0 d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">Fuel Consumption</h4>
                                <p>Total Monthly Consumption of year 2019</p>
                                <h3 class="m-0">{{\App\FuelRequest::totalLitersFuelRequest()}} L</h3>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <div id="dept_chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card">
                        <div class="card-body pb-0 d-flex justify-content-between">
                            <div>
                                <h4 class="mb-1">Department Monthly Budget vs Consumption</h4>
                                <p>Top 10 Department based on Budget vs Consumption</p>
                                <h3 class="m-0">&#8369; {{\App\FuelRequest::totalFuelCost()}}</h3>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <div id="dept_budget" style="min-width: 310; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-body px-0">
                                <h4 class="card-title px-4 mb-3">Vehicles</h4>
                                <div class="todo-list">
                                    <div class="tdl-holder">
                                        <div class="tdl-content">
                                            <ul id="todo_list">
                                                @foreach($data as $d)
                                                    <li><label><p>{{ $d->name }} <small><span class="badge badge-primary">{{ $d->plateno }}</span></small> <small><span class="badge badge-info">{{ $d->dept }}</span></small> <a target="_blank" href='/vehicle/details/{{$d->id}}' class="fa fa-link"></a></p></label></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="px-4">
                                            <form id="home_vehicles_search">
                                                @csrf
                                                <div class="form-row">
                                                    <div class="input-group mb-12">
                                                    <input type="text" class="form-control" name="v_search" placeholder="Search vehicle here . . .">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="col-lg-12 col-md-3 col-sm-3 col-xs-12">
                        <div class="card gradient-4">
                            <div class="card-body pb-0 d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-1 text-white">Top 10 Vehicle based on Average KM/L</h5>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-xs mb-0">
                                    <thead>
                                        <tr>
                                            <th>Vehicle</th>
                                            <th>Average KM/L</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_avg">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="col-lg-12 col-md-3 col-sm-3 col-xs-12">
                        <div class="card gradient-2">
                            <div class="card-body pb-0 d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-1 text-white">Top 10 Vehicle with Irregular Fuel Consumption</h5>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-xs mb-0">
                                    <thead>
                                        <tr>
                                            <th>Vehicle</th>
                                            <th>Consumption</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_diff">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="col-lg-12 col-md-3 col-sm-3 col-xs-12">
                        <div class="white-box analytics-info-cs mg-b-10 res-mg-t-30">
                            <a href="/fuel/issuances" target="_blank">
                                <div class="card gradient-4">
                                    <div class="card-body">
                                        <h3 class="card-title text-white">Total Fuel Issuance</h3>
                                        <div class="d-inline-block">
                                            <h2 class="text-white">{{\App\FuelRequest::totalFuelIssuance()}}</h2>
                                            <p class="text-white mb-0">{{ date('F Y',strtotime(\App\FuelRequest::start_rq_dt())) }} - {{ date('F Y',strtotime(\App\FuelRequest::last_rq_dt())) }}</p>
                                        </div>
                                        <span class="float-right display-5 opacity-5"><i class="fa fa-cloud-upload"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="white-box analytics-info-cs mg-b-10">
                            <a href="/fuel/issuances" target="_blank">
                                <div class="card gradient-2">
                                    <div class="card-body">
                                        <h3 class="card-title text-white">Total Unissued Requests</h3>
                                        <div class="d-inline-block">
                                            <h2 class="text-white">{{\App\FuelRequest::totalFuelRequest()}}</h2>
                                        </div>
                                        <span class="float-right display-5 opacity-5"><i class="fa fa-cloud-upload"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('assets/js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('assets/js/highcharts/export-data.js') }}"></script>
    <script src="{{ asset('assets/js/highcharts/drilldown.js') }}"></script>
    <script src="{{ asset('assets/scripts/home.js') }}"></script>

    <script>
        (function($) {
            "use strict"

            $('#todo_list').slimscroll({
                position: "right",
                size: "5px",
                height: "250px",
                color: "transparent"
            });

        })(jQuery);

        $('#home_vehicles_search').keyup(function(e){
            e.preventDefault();
            var s_vehicle  = $('input[name=s_vehicle]').val();

            $.ajax({
                type: "GET",
                url: "/ajax/home_search_vehicle",
                data: $('#home_vehicles_search').serialize(),
                success: function( response ) {
                    $('#todo_list').html(response);     
                }
            });
        });
    </script>
@endsection