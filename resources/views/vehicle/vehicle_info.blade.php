@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/chosen/css/bootstrap-chosen.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-xl-3">
            <div class="card">
                <div class="card-body">
                <br><br>
                    <form id="form_search_vehicle">
                        <div>
                            <select id="vhcl" name="vhcl" class="chosen-select">
                                <option selected value="">Choose Vehicle</option>
                                @foreach($vhcl as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <br>
                        <div>
                            <button class="btn btn-primary pull-right" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="media align-items-center mb-4">
                        <img class="mr-3" src="{{ asset('assets/images/vehicles/Yamaha_Sniper_135MX_L_1.jpg') }}" width="80" height="80" alt="">
                        <div class="media-body">
                            <h3 class="mb-0">{{ $data->name }}</h3>
                            <p class="text-muted mb-0">{{ $data->plateno }}</p>
                            <input type="hidden" name="v_id" value="{{ $data->id }}">
                        </div>
                    </div>

                    <h4>About Me</h4>
                    <ul class="card-profile__info">
                        <li class="mb-1"><strong class="text-dark mr-4">Age</strong>
                            <span class="pull-right">{{ $no_of_days }} days</span>
                        </li>
                        <li class="mb-1"><strong class="text-dark mr-4">Distance Travelled</strong> 
                            <span class="pull-right">{{ \App\FuelRequest::totalKM($data->id) }} KM</span>
                        </li>
                        <li class="mb-1"><strong class="text-dark mr-4">Department</strong> 
                            <span class="pull-right">{{ $data->dept }}</span>
                        </li>
                        <li class="mb-1"><strong class="text-dark mr-4">Purchased Date</strong> 
                            <span class="pull-right">{{ $data->yr_purchased }}</span>
                        </li>
                        <li><strong class="text-dark mr-4">Model</strong> 
                            <span class="pull-right">{{ $data->model }}</span>
                        </li>
                        <li><strong class="text-dark mr-4">Category</strong> 
                            <span class="pull-right">{{ ucwords($data->type) }}</span>
                        </li>
                        <li><strong class="text-dark mr-4">Brand</strong> 
                            <span class="pull-right">{{ $data->brand }}</span>
                        </li>
                    </ul>
                </div>
            </div>  
        </div>
        <div class="col-lg-8 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <div class="card-footer border-0 bg-transparent">
                    <span>as of {{ date('F Y',strtotime(\App\FuelRequest::first_rq_dt($data->id))) }} - {{ date('F Y',strtotime(\App\FuelRequest::latest_rq_dt($data->id))) }}</span>
                        <div class="row">
                            <div class="col-2 border-right-1 pt-3">
                                <a class="text-center d-block text-muted" href="javascript:void()">
                                    <i class="gradient-1-text" aria-hidden="true"><b>{{ \App\FuelRequest::totalRequestedLiter($data->id)}}</b></i>
                                    <p class="">Total Liters</p>
                                </a>
                            </div>
                            <div class="col-2 border-right-1 pt-3"><a class="text-center d-block text-muted" href="javascript:void()">
                                <i class="gradient-1-text"><b>{{ number_format(\App\FuelRequest::totalCost($data->id),2)}}</b></i>
                                    <p class="">Total Fuel Cost</p>
                                </a>
                            </div>
                            <div class="col-2 pt-3"><a class="text-center d-block text-muted" href="javascript:void()">

                            @if($cost_per_day != 0)
                                @foreach($cost_per_day as $c)
                                    <i class="gradient-1-text"><b>{{ number_format($c->total,2) }}</b></i>
                                @endforeach
                            @else
                                <i class="gradient-1-text"><b>0</b></i>
                            @endif
                                    <p class="">Cost Per Day</p>
                                </a>
                            </div>
                            <div class="col-2 border-right-1 pt-3">
                                <a class="text-center d-block text-muted" href="javascript:void()">
                                    <i class="gradient-3-text" aria-hidden="true"><b>{{ number_format(\App\FuelRequest::avgCost($data->id),2) }}</b></i>
                                    <p class="">Average Cost</p>
                                </a>
                            </div>
                            <div class="col-2 pt-3"><a class="text-center d-block text-muted" href="javascript:void()">
                                    <i class="gradient-3-text"><b>{{ number_format(\App\ViewVehicles::avgKML($data->id),2) }} KM/L</b></i>
                                    <p class="">Average KM/L</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div id="fuel_consu" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div>

                <div class="card-body">
                    <div id="consu_chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div>

                <div class="card-body">
                    <div id="fuel_monthly_issuance_chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>RQ #</th>
                                    <th>Request Date</th>
                                    <th>Fuel Type</th>
                                    <th>Cost</th>
                                    <th>Total Cost</th>
                                    <th>Liter</th>
                                    <th>Odometer</th>
                                    <th>Distance</th>
                                    <th>Avg. Fuel Consumption <small>(KM/L)</small></th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach($requests as $r)
                                    <tr>
                                        <td>{{ $r->rq }}</td>
                                        <td>{{ $r->request_dt }}</td>
                                        <td>{{ $r->fuel_type }}</td>
                                        <td>{{ $r->cost }}</td>
                                        <td>{{ $r->total_cost }}</td>
                                        <td>{{ $r->liter }}</td>
                                        <td>{{ $r->odo }}</td>
                                        <td>{{ $r->distance_travelled }}</td>
                                        <td>{{ number_format($r->avg_fuel_consu,2) }}<small>KM/L</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
    <script src="{{ asset('assets/scripts/v_info.js') }}"></script>
    <script src="{{ asset('assets/js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('assets/js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('assets/js/highcharts/export-data.js') }}"></script>

    <script src="{{ asset('assets/plugins/chosen/js/chosen.jquery.js') }}"></script>
    <script src="{{ asset('assets/plugins/chosen/js/chosen-active.js') }}"></script>
@endsection
