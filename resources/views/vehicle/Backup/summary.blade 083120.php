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
                            <div class="col-md-8">
                                <div class="chart">
                                    <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p class="text-center">
                                    <strong>Vehicle Details</strong>
                                </p>

                                <div class="progress-group">
                                    Name : {{ $details->name }}
                                </div>

                                <div class="progress-group">
                                    Plate No: {{ $details->plateno }}
                                </div>

                                <div class="progress-group">
                                    Cost Code: {{ $details->vehicle_code }}
                                </div>

                                <div class="progress-group">
                                    Department: {{ $details->dept }}
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header">{{$total_actual_qty}}</h5>
                                    <span class="description-text">TOTAL ACTUAL QTY</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header">8.00</h5>
                                    <span class="description-text">TOTAL COST PER DAY</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block border-right">
                                    <h5 class="description-header">4,813.53</h5>
                                    <span class="description-text">DATA 1</span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-6">
                                <div class="description-block">
                                    <h5 class="description-header">1200</h5>
                                    <span class="description-text">DATA 2</span>
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
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Details</a>
                    </li>
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
                                            <th>Cost Code</th>
                                            <th>Requested Qty</th>
                                            <th>Actual Qty</th>
                                            <th>Odometer</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($issuances as $issuance)
                                        <tr>
                                            <td><a href="{{ route('view.issuance',$issuance->id) }}">{{ $issuance->uniq_code }}</a></td>
                                            <td>{{ $issuance->rq }}</td>
                                            <td>{{ $issuance->cost_code }}</td>
                                            <td class="text-right">{{ $issuance->requested_qty }}</td>
                                            <td class="text-right">{{ $issuance->actual_qty }}</td>
                                            <td>{{ $issuance->odo }}</td>
                                            <td>{{ $issuance->status }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="7"><center>NO FUEL ISSUANCES FOUND</center></td></tr>
                                        @endforelse
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

    <script>
        $(function () {

            'use strict'

            /* ChartJS
            * -------
            * Here we will create a few charts using ChartJS
            */

            //-----------------------
            //- MONTHLY SALES CHART -
            //-----------------------

            // Get context with jQuery - using jQuery's .get() method.
            var salesChartCanvas = $('#salesChart').get(0).getContext('2d')

            var salesChartData = {
                labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [
                {
                    label               : 'Digital Goods',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius          : false,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label               : 'Electronics',
                    backgroundColor     : 'rgba(210, 214, 222, 1)',
                    borderColor         : 'rgba(210, 214, 222, 1)',
                    pointRadius         : false,
                    pointColor          : 'rgba(210, 214, 222, 1)',
                    pointStrokeColor    : '#c1c7d1',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data                : [65, 59, 80, 81, 56, 55, 40]
                },
                ]
            }

            var salesChartOptions = {
                maintainAspectRatio : false,
                responsive : true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines : {
                            display : false,
                        }
                    }],
                    yAxes: [{
                        gridLines : {
                            display : false,
                        }
                    }]
                }
            }

            // This will get the first returned node in the jQuery collection.
            var salesChart = new Chart(salesChartCanvas, { 
                type: 'line', 
                data: salesChartData, 
                options: salesChartOptions
            })
        })

    </script>
@endsection