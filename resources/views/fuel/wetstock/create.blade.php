@extends('layouts.app')

@section('pagecss')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css' ) }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css' ) }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css' ) }}">

    <style>
        input.transparent-input{
           background-color:transparent !important;
           border:none !important;
        }

        textarea.transparent-input{
           background-color:transparent !important;
           border:none !important;
        }

        .input_highlight{
            border:2px solid red;
        }

        .select2-container--default .select2-selection--single {
            border:2px solid red;
        }
    </style>
@endsection

@section('content')

    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('fuel-deliveries.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Create Wetstock Report</li>
                </ol>
            </div>
        </div>
    </div>

    <section class="content">
        
        <div class="row">
            
            <div class="col-12">

                <div class="card card-outline">

                    <div class="card-header">
                        
                        <h3 class="card-title text-center">
                            Wetstock Report
                        </h3>

                    </div>

                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="table-responsive">

                            <table class="table table-bordered" id="delivery-table">

                                <tr>
                                    <td class="text-center">Tank</td>
                                    <td class="text-center">Day</td>
                                    <td class="text-center">Opening Stock <br> (previous day`s end dip reading)</td>
                                    <td class="text-center">Actual Delivered</td>
                                    <td class="text-center">Issuance</td>
                                    <td class="text-center">Book Stock Remaining</td>
                                    <td class="text-center">Ending Dipstick Measurement</td>
                                    <td class="text-center">Daily Variance</td>
                                    <td class="text-center">Cumulative Variance</td>
                                    <td class="text-center">Cumulative Issuance</td>
                                    <td class="text-center">Cumulative % Variance</td>
                                </tr>

                                @foreach( $tanks as $tank )

                                    @php

                                        $comm_variance = 0;                                                
                                        $comm_issuance = 0;
                                        $issuances = 0;
                                        $comm_variance_percentage = 0;

                                        $opening_dp_sum = \App\DipstickMeasurement::whereDate('date_delivered', "!=", \Carbon\Carbon::now())
                                            ->where('tank_name', $tank->name)
                                            ->where('transaction_type', 'wetstock')
                                            ->latest()
                                            ->take(3)
                                            ->get();

                                        $ending_dp_sum = \App\DipstickMeasurement::whereDate('date_delivered', \Carbon\Carbon::now())
                                            ->where('tank_name', $tank->name)
                                            ->where('transaction_type', 'wetstock')
                                            ->latest()
                                            ->take(3)
                                            ->get();

                                        if(count($opening_dp_sum)) {
                                            $opening_dp_ave = number_format($opening_dp_sum->pluck('to_liter')->avg() , 2);
                                        } else {
                                            $opening_dp_ave = 0;
                                        }

                                        if(count($ending_dp_sum)) {
                                            $ending_dp_ave = number_format($ending_dp_sum->pluck('to_liter')->avg() , 2);
                                        } else {
                                            $ending_dp_ave = 0;
                                        }

                                        $delivered = \App\FuelDeliveryDetail::whereDate('created_at', \Carbon\Carbon::now())
                                            ->where('tank_name', $tank->name)
                                            ->sum('actual_delivered_in_liters');

                                        $fuel_dispense = \App\DailyFuelDispense::where('tank_id', $tank->id)
                                            ->whereDate('created_at', \Carbon\Carbon::now())
                                            ->first();

                                        if($fuel_dispense) {
                                            $dispense_issuances = \App\DailyDispenseIssuance::where('daily_fuel_dispense_id', $fuel_dispense->id)->get();
                                            $issuances = \App\IssuanceRequest::whereIn('id', $dispense_issuances->pluck('issuance_id')->toArray())->sum('actual_qty');
                                        } 

                                        $opening_dp_ave = filter_var($opening_dp_ave, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
                                        $ending_dp_ave = filter_var($ending_dp_ave, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);
                                        $issuances = filter_var($issuances, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);

                                        $remaining_stock = ($opening_dp_ave + $delivered ) - $issuances;
                                       
                                        $daily_variance = $ending_dp_ave - $remaining_stock;
                                        $wetstock_yesterday = \App\Wetstock::where('tank_id', $tank->id)
                                            ->whereDate('created_at', "!=", \Carbon\Carbon::now())
                                            ->latest()
                                            ->first();
                                        
                                        if($wetstock_yesterday) {
                                            $comm_variance = $wetstock_yesterday->cumulative_variance;
                                            $comm_issuance = $wetstock_yesterday->cumulative_issuance;
                                            if($comm_variance != 0 && $comm_issuance != 0 ) {
                                                $comm_variance_percentage = $comm_variance / $comm_issuance;
                                            } else {
                                                $comm_variance_percentage = 0;
                                            }
                                        }

                                    @endphp

                                    <tr id="copy-row" class="wetstock-input-row" data-tankref="{{ strtolower(str_replace(' ', '-', $tank->name)) }}">
                                        <td> {{ $tank->name }} <input type="hidden" data-name="tank" id="tank-{{ str_replace(' ', '-', $tank->name) }}" value="{{ $tank->id }}"/> </td>
                                        <td> {{ date('l') }} </td>
                                        <td><input type="text" readonly class="form-control" data-name="opening_dp_ave" id="opening-dipstick-{{ str_replace(' ', '-', $tank->name) }}"
                                            value="{{ $opening_dp_ave }}" /></td>
                                        <td><input type="text" class="form-control actual-delivered-input" data-name="actual_delivered" data-ref="{{ str_replace(' ', '-', $tank->name) }}" id="actual-delivered-{{ str_replace(' ', '-', $tank->name) }}"
                                            value="{{ number_format($delivered,2) }}" readonly/></td>
                                        <td><input type="text" class="form-control issuance-input" data-name="issuance" data-ref="{{ str_replace(' ', '-', $tank->name) }}" id="issuance-{{ str_replace(' ', '-', $tank->name) }}"
                                            value="{{ number_format($issuances,2) }}" readonly /></td>
                                        <td><input type="text" readonly class="form-control" data-name="remaining_stock" id="stock-remaining-{{ str_replace(' ', '-', $tank->name) }}" value="{{ number_format($remaining_stock,2) }}" /></td>
                                        <td><input type="text" readonly class="form-control" data-name="ending_dp_ave" id="ending-dipstick-{{ str_replace(' ', '-', $tank->name) }}" value="{{ $ending_dp_ave }}" /></td>
                                        <td><input type="text" readonly class="form-control" data-name="variance" id="variance-{{ str_replace(' ', '-', $tank->name) }}" value="{{ number_format($daily_variance,2) }}"/></td>
                                        <td><input type="text" readonly class="form-control" data-name="cumulative_variance" id="cumulative-variance-{{ str_replace(' ', '-', $tank->name) }}" value="{{ number_format($comm_variance,2) }}" /></td>
                                        <td><input type="text" readonly class="form-control" data-name="cumulative_issuance" id="cumulative-issuance-{{ str_replace(' ', '-', $tank->name) }}" value="{{ number_format($comm_issuance,2) }}" /></td>
                                        <td><input type="text" readonly class="form-control" data-name="cumulative_variance_percentage" id="cumulative-variance-percentage-{{ str_replace(' ', '-', $tank->name) }}" value="{{ number_format($comm_variance_percentage,2) }}" /></td>                                            
                                    </tr>
                                @endforeach

                            </table>

                            <div class="col-12 mb-10">
                                <button class="btn btn-primary btn-md float-right" id="submit-wetstock-report"> Submit </button>
                                <div class="clearfix"></div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection

@section('pagejs')
    
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>

        $(function() {
            $('#timepicker').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour') ,
                locale: {
                    format: 'Y-MM-DD hh:mm A'
                }, 
                singleDatePicker: true
            });
        });

        $(document).on('click', '#submit-wetstock-report', function() {

            let _data = {};

            $('.wetstock-input-row').each(function(i,o){

                let _ref = $(this).data('tankref');

                _data[_ref] = {
                    'tank_id' : $(this).find('input[data-name="tank"]').val() ,
                    'opening_dp_ave' : $(this).find('input[data-name="opening_dp_ave"]').val() ,
                    'actual_delivered' : $(this).find('input[data-name="actual_delivered"]').val() ,
                    'issuance' : $(this).find('input[data-name="issuance"]').val() ,
                    'remaining_stock' : $(this).find('input[data-name="remaining_stock"]').val() ,
                    'ending_dp_ave' : $(this).find('input[data-name="ending_dp_ave"]').val() ,
                    'daily_variance' : $(this).find('input[data-name="variance"]').val() ,
                    'cumulative_issuance' : $(this).find('input[data-name="cumulative_issuance"]').val() ,
                    'cumulative_variance' : $(this).find('input[data-name="cumulative_variance"]').val() ,
                    'cumulative_variance_percentage' : $(this).find('input[data-name="cumulative_variance_percentage"]').val()
                }
            
            });

            let _url = '{!! route("daily-wetstock.store") !!}';
            $.post(_url, {
                _data , 
                '_token' : '{!! csrf_token() !!}',
            }, function(data) {
                
                if(data.hasOwnProperty('success')) {
                    console.log('yes');
                    window.location = '{!! route("daily-wetstock.index") !!}';
                }

            });

        });

        // $(document).on('change', '.dipstick-input', function() {
            
        //     let _that = $(this);
        //     let _ref = $(this).data('ref');
        //     let _fer = $(this).data('fer');
        //     let _tank = $(this).data('tank')
        //     let _total = 0;
        //     let _get_ave = 'input-'+_fer+'-'+_ref+'-'+_tank+'-ave';

        //     $('.input-'+_fer+'-'+_ref+'-'+_tank).each(function(i, e) {
        //         if(e.value == '') {
        //             _total = parseFloat(_total) + parseFloat(0);
        //         } else {
        //             _total = parseFloat(_total) + parseFloat(e.value);
        //         }
        //     });

        //     _total = _total/3;

        //     let _to_liter = _total / 1000;

        //     $('#'+_get_ave).val(_total.toFixed(2));
            
        //     $("#ending-dipstick-"+_tank.replace("_","-", _tank)).val(_total.toFixed(2));         

        //     // for wetstock
        //     let _variance = _total - parseFloat($('#stock-remaining-'+_tank).val());

        //     $('#variance-'+_tank).val(_variance.toFixed(2));   

        // });

        // $(document).on('keyup', '.actual-delivered-input', function() {

        //     let _tank = $(this).data('ref');
        //     let _ref = $(this).data('ref');

        //     let _opening_stock = $("#opening-dipstick-"+_tank).val();
        //     let _issuance = $("#issuance-"+_tank).val();

        //     let _stock_remaining = ( parseFloat(_opening_stock) + parseFloat($(this).val()) ) - parseFloat(_issuance);

        //     $("#stock-remaining-"+_tank).val(_stock_remaining.toFixed(2));

        // });

        // $(document).on('keyup', '.issuance-input', function(){

        //     let _tank = $(this).data('ref');
        //     let _ref = $(this).data('ref');

        //     let _opening_stock = $("#opening-dipstick-"+_tank).val();
        //     let _actual_delivered = $("#actual-delivered-"+_tank).val();

        //     let _stock_remaining = ( parseFloat(_opening_stock) + parseFloat(_actual_delivered) ) - parseFloat($(this).val());

        //     $("#stock-remaining-"+_tank).val(_stock_remaining.toFixed(2));

        // });

    </script>

@endsection