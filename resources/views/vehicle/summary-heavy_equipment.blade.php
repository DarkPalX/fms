@extends('layouts.app')

@section('pagecss')
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Vehicle Issuance Report</h5>
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
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 col-4">
                                <div class="description-block border-right">
                                    <h5 class="description-header">
                                        @if($oth['total_working_hour'] > 0)
                                    {{number_format(($oth['total_fuel_issued'] / $oth['total_working_hour']),2)}}
                                    @else
                                    0.00
                                    @endif</h5>
                                    <span class="description-text">FUEL CONSUMPTION</span>
                                </div>
                            </div>
                            <div class="col-sm-4 col-4">
                                <div class="description-block border-right">
                                    <h5 class="description-header">{{$oth['total_working_hour'] ?? 'No Record'}}</h5>
                                    <span class="description-text">TOTAL WORKING HR</span>
                                </div>
                            </div>
                            <div class="col-sm-4 col-4">
                                <div class="description-block border-right">
                                    <h5 class="description-header">{{$oth['total_fuel_issued'] ?? 'No Record'}}</h5>
                                    <span class="description-text">TOTAL FUEL ISSUED</span>
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
                </ul>
                <div class="tab-content" id="custom-content-below-tabContent">
                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                        <div class="card-body">
                            <div class="tab-content">
                                <table class="table table-bordered table-striped">                                   
                                    <thead>                  
                                        <tr>
                                            <th>Reference #</th>
                                            <th>RQ #</th>
                                            <th>Date</th>
                                            <th>Starting Hr</th>
                                            <th>Final Hr</th>
                                            <th>Total Working Hr</th>
                                            <th>Fuel Issued</th>
                                            <th>Avg Fuel Consumption (L/H)</th>
                                            <th>Tank Status</th>
                                            <th>Location</th>
                                        </tr>
                                    </thead>                                   
                                    
                                    <tbody>
                                        @if($raw)
                                          {!!$raw!!}
                                        @else
                                        <tr><td colspan="9"><center>NO FUEL ISSUANCES/REQUESTS FOUND</center></td></tr>
                                        @endif
                                    </tbody>
                                </table>

                                <div class="row clearfix">
                                    <div class="col-md-6">
                                        <p>Showing {{$issuances->firstItem()}} to {{$issuances->lastItem()}} of {{$issuances->total()}} items</p>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            {{ $issuances->links() }}
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
</section>



@endsection

@section('pagejs')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>

    <script src="{{ asset('assets/js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('assets/js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('assets/js/highcharts/export-data.js') }}"></script>

    <script>
        Highcharts.chart('fuel_consu', {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Fuel Consumption'
            },
            xAxis: [{
                categories: <?php echo json_encode($arrayTransaction);?>,
                crosshair: true
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value} L/H',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                title: {
                    text: 'Fuel Consumption',
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
                name: 'Fuel Consumption',
                type: 'spline',
                data: <?php echo json_encode($arrayConsuData);?>,
                tooltip: {
                    valueSuffix: ' H/L'
                }
            }]
        });

    </script>
@endsection