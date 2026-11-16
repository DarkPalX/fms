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
                    <li class="breadcrumb-item active">Fuel Dipstick Measurement</li>
                </ol>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                
                <div class="card card-danger card-outline">

                    <div class="card-header">
                        
                        <h3 class="card-title">
                            <i class="fas fa-oil-can"></i>
                            Fuel Dipstick Measurements
                        </h3>

                        <a href="#" data-toggle="modal" data-target="#dipstick-measurement" class="btn btn-primary btn-sm float-right">New Dipstick Measurement</a>
                        <br><br>
                        <form class="form-inline">

                            <div class="form-group col-lg-2">
                                <label for="tank" class="col-lg-4"> Tank </label>
                                <select name="tank_name" id="tank_name" class="form-control col-lg-8">
                                    <option value="">Select Tank</option>
                                    @foreach( $tanks as $tank)
                                        <option value="{{ $tank->name }}" @if(request()->has('tank_name') && request()->tank_name == $tank->name) selected @endif>{{ $tank->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-lg-2">
                                <label for="date_from" class="col-lg-4">From</label>
                                <input type="date" value="{{ request()->has('date_from') ? request()->date_from : \Carbon\Carbon::yesterday()->format('Y-m-d') }}" name="date_from" class="form-control col-lg-8">
                            </div>

                            <div class="form-group col-lg-2">
                                <label for="date_from" class="col-lg-4">To</label>
                                <input type="date" value="{{ request()->has('date_to') ? request()->date_to : date('Y-m-d') }}" name="date_to" class="form-control col-lg-8">
                            </div>

                            <button class="btn btn-primary"> Go </button>

                        </form>

                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-striped table-bordered">

                                <thead>
                                    <th> Fuel Type </th>
                                    <th> Tank Name </th>
                                    <th> Date Created / Delivery Date </th>
                                    <th> Type </th>
                                    <th> Average Dipstick Measurement (cm) </th>
                                    <th> Average Dipstick Measurement (L) </th>
                                    <td> Action </td>
                                </thead>

                                <tbody>
                                    @php $data = []; $ctr = 0; @endphp
                                    @foreach( $dipsticks as $key => $dipstick )
                                        @php
                                            $data[$key] = [];                                          
                                        @endphp

                                        @foreach($dipstick as $dipstick_tank )

                                            @php
                                                $ave_cm = 0;
                                                $ave_l = 0;       
                                            @endphp

                                            @foreach($dipstick_tank as $dipstick)

                                                @php 
                                                    $ave_cm+=$dipstick->cm;
                                                    $ave_l+= $dipstick->to_liter;
                                                @endphp

                                            @endforeach

                                            @php
                                                $data[$key] = $dipstick_tank[0];
                                                $data[$key]['ave_cm'] = $ave_cm / 3;
                                                $data[$key]['ave_l'] = $ave_l / 3;
                                                $data[$key]['data'] = $dipstick_tank;
                                            @endphp

                                        @endforeach
                                    @endforeach

                                    @forelse($data as $key => $dipstick)
                                        @php $ctr++ @endphp
                                        <tr>
                                            <td> {{ $dipstick->fuel_type }} </td>
                                            <td> {{ $dipstick->tank_name }} </td>
                                            <td> {{ $dipstick->date_delivered }} </td>
                                            <td> Pre Post Transaction </td>
                                            <td> {{ number_format($dipstick->ave_cm, 2) }} </td>
                                            <td> {{ number_format($dipstick->ave_l, 2) }} </td>    
                                            <td> 
                                                <button class="btn btn-primary" data-toggle="collapse" href="#collapseExample{{$ctr}}" role="button" aria-expanded="false" aria-controls="collapseExample"> show details </button> 
                                            </td>                                 
                                        </tr>
                                        <tr>
                                            <td colspan="7">
                                                <table class="table table-bordered collapse" id="collapseExample{{$ctr}}">
                                             
                                                    <thead>
                                                        <th> Dipstick No. </th>
                                                        <th> Dipstick Measurement (cm) </th>
                                                        <th> Dipstick Measurement (l) </th>                                                        
                                                    </thead>

                                                    <tbody>
                                                        @foreach( $dipstick->data as $dips )
                                                            <tr>
                                                                <td> {{ $dips->dip_no }} </td>
                                                                <td> {{ number_format($dips->cm, 2) }} </td>
                                                                <td> {{ number_format($dips->to_liter, 2) }} </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>

                                                </table>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="7"> No Dipstick Measurement Found </td>
                                        </tr>
                                    @endforelse

                                </tbody>

                            </table>

                        </div>                      

                    </div>            

                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="dipstick-measurement">
        <div class="modal-dialog  modal-lg">
            <div class="modal-content">
            
                <div class="modal-header">
                    <h4 class="modal-title text-center">Pre Post Dipsticking Measurement</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">

                    <form action="{{ route('dipstick-measurements.store') }}" method="POST">
                        @csrf                        
                    
                        <div class="table-responsive">
                            
                            <div class="form-group">

                                <label for="date created"> Date and Time Created </label>
                                <input type="text" class="form-control" id="timepicker" name="date_created"  />

                            </div>

                            <div class="form-group">

                                <label for="fuel type"> Fuel Type </label>
                                <select name="fuel_type" class="form-control" id="fuel_type">
                                    <option value="">Select Fuel Type</option>
                                    @foreach( $fuel_types as $type )
                                        <option value="{{ $type->fuel_type }}">{{ $type->fuel_type }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="form-group">

                                <label for="tank name"> Tank name </label>
                                <select name="tank_name" id="tank_name" class="form-control" required>
                                    <option value="">Select Tank</option>
                                    @foreach($tanks as $tank)
                                        <option value="{{ $tank->name }}"> {{ $tank->name }} </option>
                                    @endforeach
                                </select>

                            </div>

                            <table class="table table-bordered" id="modal-dipstick-table">                                                                

                                <tr>
                                    <td align="center">Dip No</td>
                                    <td align="center">CM</td>
                                </tr>

                                <tr class="dipstick-input-wrap">
                                    <td>1</td>
                                    <td><input type="number" step="any" min="0" class="form-control dipstick-input"  
                                        data-refraf="before-cm" name="cm1" required data-ref="cm" data-fer="before"></td>
                                </tr>

                                <tr class="dipstick-input-wrap">
                                    <td>2</td>
                                    <td><input type="number" step="any" min="0" class="form-control dipstick-input"  
                                        data-refraf="before-cm" name="cm2" required data-ref="cm" data-fer="before"></td>
                                </tr>

                                <tr class="dipstick-input-wrap">
                                    <td>3</td>
                                    <td><input type="number" step="any" min="0" class="form-control dipstick-input" 
                                        data-refraf="before-cm" name="cm3" required data-ref="cm" data-fer="before"></td>
                                </tr>

                                <tr>
                                    <td>4</td>
                                    <td><input type="number" step="any" class="form-control dipstick-input-average"></td>
                                </tr>

                            </table>

                            <div class="form-group">
                                <button class="btn btn-primary float-right"> Submit Dipstick Measurement </button>
                            </div>

                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection

@section('pagejs')
    
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>

        $(function() {
            $('#timepicker').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'Y-MM-DD hh:mm A'
                }, 
                singleDatePicker: true
            });
        });


        $(document).on('keyup', '.dipstick-input', function() {

            let _total = 0;

            $('.dipstick-input').each(function() {
                if( $(this).val() == '' ) {
                    _total = _total + 0;
                } else {
                    _total = _total + parseFloat($(this).val());
                }
            });

            _total = _total / 3;

            $('.dipstick-input-average').val(_total.toFixed(2));

        });

    </script>

@endsection

        