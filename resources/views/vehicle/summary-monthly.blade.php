@extends('layouts.app')

@section('pagecss')
<link rel="stylesheet" href="{{ asset('assets/plugins/jquery-ui/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Vehicle</a></li>
                    <li class="breadcrumb-item active">Issuances</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h5 class="card-title">Vehicle Issuances Report</h5>
                        <br><br>
                        <form class="form-inline">
                            <div class="form-group col-lg-4">
                                <label for="report_basis" class="col-lg-4"> Report Basis</label>
                                <select name="report_basis" id="report_basis" class="form-control col-lg-8">
                                    <option value="date" {{ request()->report_basis == 'date' ? 'selected' : '' }}>Daily</option>
                                    <option value="week" {{ request()->report_basis == 'week' ? 'selected' : '' }}>Weekly</option>
                                    <option value="month" {{ request()->report_basis == 'month' ? 'selected' : '' }}>Monthly</option>
                                    <option value="text" {{ request()->report_basis == 'text' ? 'selected' : '' }}>Semi-annual</option>
                                    <option value="number" {{ request()->report_basis == 'number' ? 'selected' : '' }}>Annual</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 ordinary_forms">
                                <label for="date_from" class="col-lg-3">From</label>
                                <input type="{{ request()->has('report_basis') ? request()->report_basis : 'date' }}" value="{{ request()->has('date_from') ? request()->date_from : '' }}" name="date_from" class="form-control col-lg-9 date_select">
                            </div>
                            <div class="form-group col-lg-3 ordinary_forms">
                                <label for="date_from" class="col-lg-3">To</label>
                                <input type="{{ request()->has('report_basis') ? request()->report_basis : 'date' }}" value="{{ request()->has('date_to') ? request()->date_to : '' }}" name="date_to" class="form-control col-lg-9 date_select">
                            </div>


                            <div class="form-group col-lg-3 semi_annual_forms" style="display: none;">
                                <label for="date_from" class="col-lg-3">Months</label>
                                <select name="semi_annual" class="form-control col-lg-9">
                                    <option value="['01', '02', '03', '04', '05', '06']" {{ request()->semi_annual == "['01', '02', '03', '04', '05', '06']" ? 'selected' : '' }}>January to June</option>
                                    <option value="['07', '08', '09', '10', '11', '12']" {{ request()->semi_annual == "['07', '08', '09', '10', '11', '12']" ? 'selected' : '' }}>July to December</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 semi_annual_forms" style="display: none;">
                                <label for="date_from" class="col-lg-3">Year</label>
                                <input type="number" value="{{ request()->has('semi_year') ? request()->semi_year : '' }}" placeholder="YYYY" name="semi_year" class="form-control col-lg-9">
                            </div>

                            <button class="btn btn-success"> Plot </button>&nbsp;&nbsp;
                            <a href="{{ route('fms.vehicle-summary-monthly',$details->id) }}" class="btn btn-info">Reset</a>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-center">
                                    <strong>Vehicle Information</strong>
                                </p>
                                <p class="text-center">
                                    <h4 class="text-center">{{$details->name}} ({{$details->plateno}})</h4>
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">Cost Code: {{$details->vehicle_code}}</div>
                            <div class="col-md-4">Type: {{$details->type}}</div>
                            <div class="col-md-4">Dept: {{$details->dept}}</div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="fuel_consu" height="380"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="fuel_ec"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="fuel_con"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="distance_travelled" height="380"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 col-4">
                                <div class="description-block border-right">
                                    <h5 class="description-header">{{$oth['fuel_consumption'] ?? 'No Record'}}</h5>
                                    <span class="description-text">FUEL CONSUMPTION</span>
                                </div>
                            </div>
                            <div class="col-sm-4 col-4">
                                <div class="description-block border-right">
                                    <h5 class="description-header">{{$oth['fuel_economy'] ?? 'No Record'}}</h5>
                                    <span class="description-text">FUEL ECONOMY</span>
                                </div>
                            </div>
                            <div class="col-sm-4 col-4">
                                <div class="description-block border-right">
                                    <h5 class="description-header">{{$oth['last_odo'] ?? 'No Record'}}</h5>
                                    <span class="description-text">LAST ODOMETER</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tachometer-alt"></i>
                    Summary Table
                </h3>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Fuel Issuances</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Update Profile</a>
                    </li> --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Update Profile</a>
                    </li> --}}
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div class="card-body">
                            <div class="tab-content">
                                <table class="table table-bordered table-striped">
                                    
                                    <thead>                  
                                        <tr>
                                            <th>Month</th>
                                            <th>Starting Km</th>
                                            <th>Final Km</th>
                                            <th>Total Distance (Km)</th>
                                            <th>Fuel Usage/Issued (L)</th>
                                            <th>Fuel Economy (km/L)</th>
                                        </tr>
                                    </thead>
                                 
                                    
                                    <tbody>
                                        @if($raw)
                                            {!!$raw!!}
                                        @else
                                            <tr><td colspan="6"><center>NO FUEL ISSUANCES/REQUESTS FOUND</center></td></tr>
                                        @endif
                                    </tbody>
                                </table>

                                <div class="row clearfix">
                                    <div class="col-md-6">
                                        <p>Showing {{$paginated_monthly_summary->firstItem()}} to {{$paginated_monthly_summary->lastItem()}} of {{$paginated_monthly_summary->total()}} items</p>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            {{ $paginated_monthly_summary->links() }}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                        <form role="form" method="post" action="{{ route('vehicle-details-update') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Name <i class="text-danger">*</i></label>
                                            <input type="hidden" name="vid" value="{{ $details->id }}">
                                            <input type="text" name="name" class="form-control" value="{{ $details->name }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Plate No. <i class="text-danger">*</i></label>
                                            <input type="text" name="plateno" class="form-control" value="{{ $details->plateno }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Cost Code <i class="text-danger">*</i></label>
                                            <input type="text" name="ccode" class="form-control" value="{{ $details->vehicle_code }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Type <i class="text-danger">*</i></label>
                                            <input type="text" name="type" class="form-control" value="{{ $details->type }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Department <i class="text-danger">*</i></label>
                                            <input type="text" name="dept" class="form-control" value="{{ $details->dept }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FOR MODAL DAILY TRANSACTIONS --}}
    <div class="modal fade" id="monthTransactionsModal" tabindex="-1" aria-labelledby="monthTransactionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="monthTransactionsModalLabel">Daily Transactions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="month-transactions-content">
                    <!-- Content will be injected here -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@php
    $report_basis = "";
    if(request()->has('report_basis')){
        $report_basis = request()->report_basis;
    }

@endphp

@section('pagejs')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <script src="{{ asset('assets/js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('assets/js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('assets/js/highcharts/export-data.js') }}"></script>

    {{-- FOR THE MODAL --}}
    <script>
        $(document).ready(function() {
            $('.clickable-row').on('click', function() {
                const month = $(this).data('month'); // e.g. "2024-03"
                const vehicleId = {{ $details->id }};

                $.ajax({
                    url: '{{ route("fms.vehicle-monthly-transactions") }}',
                    type: 'GET',
                    data: {
                        id: vehicleId,
                        month: month
                    },
                    success: function(response) {
                        $('#month-transactions-content').html(response);
                        $('#monthTransactionsModal').modal('show');
                    }
                });
            });
        });
    </script>

    <script>
        var report_basis = "{{ $report_basis }}";
            
        //FOR MONTHLY SHOWING
        $('#report_basis').val('month');
        
        $(document).ready(function() {

            if (['week', 'month', 'number', ''].includes(report_basis)) {
                $(".semi_annual_forms").hide();
                $(".ordinary_forms").show();
            } else {
                $(".semi_annual_forms").show();
                $(".ordinary_forms").hide();
            }

            $('#report_basis').change(function() {
                var selectedOption = $(this).val();
                var dateInput = $('.date_select');

                if(selectedOption === 'week' || selectedOption === 'month' || selectedOption === 'number' || selectedOption === ''){
                    $(".semi_annual_forms").hide();
                    $(".ordinary_forms").show();
                }

                if (selectedOption === 'week') {
                    dateInput.attr('type', 'week');
                }else if (selectedOption === 'month') {
                    dateInput.attr('type', 'month');
                }else if (selectedOption === 'number') {
                    dateInput.attr('type', 'number');
                    dateInput.attr('min', 2010);
                    dateInput.attr('max', 2100);
                    dateInput.attr('placeholder', 'YYYY');
                    dateInput.on('input', function() {
                        var year = $(this).val();
                        if (year.length > 4) {
                            $(this).val("");
                        }
                    });
                }else if (selectedOption === 'text'){
                    $(".semi_annual_forms").show();
                    $(".ordinary_forms").hide();
                }else{
                    dateInput.attr('type', 'date');
                }
            });
        });



        // CHARTS GENERATION SECTION

        Highcharts.chart('fuel_consu', {
            chart: {
                plotBorderWidth: 1,
                zoomType: 'xy'
            },
            title: {
                text:  $("#report_basis option:selected").text()+' Fuel Economy vs Fuel Consumption'
            },
            xAxis: [{
                categories: <?php echo json_encode($arrayTransaction);?>,
                crosshair: true
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value} km/L',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                title: {
                    text: 'Fuel Economy',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                }
            }, { // Secondary yAxis
                title: {
                    text: 'Fuel Consumption',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                labels: {
                    format: '{value} L/100Km',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 50,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255,255,255,0.25)'
            },
            series: [{
                name: 'Fuel Economy',
                type: 'spline',
                data: <?php echo json_encode($arrayEconomyData);?>,
                tooltip: {
                    valueSuffix: ' km/L'
                }

            }, {
                name: 'Fuel Consumption',
                type: 'spline',
                yAxis:1,
                data: <?php echo json_encode($arrayConsuData);?>,
                tooltip: {
                    valueSuffix: ' L/100Km'
                }
            }]
        });

        // FUEL ECONOMY
        Highcharts.chart('fuel_ec', {
            chart: {
                plotBorderWidth: 1,
                type: 'column'
            },
            title: {
                text: $("#report_basis option:selected").text()+' Fuel Economy',
                align: 'left'
            },
            xAxis: {
                categories: <?php echo json_encode($arrayTransaction);?>,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Fuel Economy (km/L)'
                }
            },
            legend: {
                enabled: false // Hide the legend
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} km/L</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'km/L',
                data: <?php echo json_encode($arrayEconomyData);?>,
                color: '#3946F6'
                }]
        });

        // FUEL CONSUMPTION
        Highcharts.chart('fuel_con', {
            chart: {
                plotBorderWidth: 1,
                type: 'column'
            },
            title: {
                text: $("#report_basis option:selected").text()+' Fuel Consumption',
                align: 'left'
            },
            xAxis: {
                categories: <?php echo json_encode($arrayTransaction);?>,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Fuel Consumption (L/100Km)'
                }
            },
            legend: {
                enabled: false // Hide the legend
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} L/100Km</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'L/100Km',
                data: <?php echo json_encode($arrayConsuData);?>,
                color: '#000000'
                }]
        });

        // DISTANCE TRAVELLED
        Highcharts.chart('distance_travelled', {
            chart: {
                plotBorderWidth: 1,
                zoomType: 'xy'
            },
            title: {
                text: $("#report_basis option:selected").text()+' Distance Travelled'
            },
            xAxis: [{
                categories: <?php echo json_encode($arrayDistanceTransactions);?>,
                crosshair: true
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value} KM',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Distance Travelled',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                }
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 100,
                floating: true,
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255,255,255,0.25)'
            },
            series: [ {
                name: 'Distance Travelled',
                data: <?php echo json_encode($arrayDistanceTravelled); ?>,
                tooltip: {
                    valueSuffix: ' KM'
                }
            }]
        });

    </script>
@endsection